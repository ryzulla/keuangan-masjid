<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Resident;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
use Livewire\Attributes\Layout; // <-- Penting untuk layout

#[Layout('layouts.app')] // <-- Menentukan layout utama
class ManageUsers extends Component
{
    use WithPagination;

    // Properti Form
    public $name, $email, $role = 'bendahara', $password;

    // Properti State
    public $selected_id;
    public $isModalOpen = false;

    // Mode pembuatan: 'new' (akun baru) | 'resident' (dari penghuni)
    public $createMode = 'new';
    public $residentSearch = '';
    public $selectedResidentIds = [];

    // Saat edit: akun ini berasal dari penghuni (cukup ganti role, tanpa password).
    public $editResidentLinked = false;
    // Saat edit: target adalah akun Super Admin (role terkunci, tak bisa dihapus).
    public $editIsSuperAdmin = false;
    // Role asli saat edit — tetap boleh dipertahankan meski role-nya dinonaktifkan.
    public $editOriginalRole = null;

    /** Hanya Super Admin yang boleh menetapkan role super_admin. */
    private function canAssignSuper(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    /** Admin/Super Admin mengurus semua grup; role lain hanya grup role-nya sendiri. */
    private function manageableGroups(): ?array
    {
        $role = auth()->user()->role;
        if ($role === 'super_admin' || $role === 'admin') {
            return null; // null = semua grup
        }
        $group = Role::allCached()->firstWhere('key', $role)?->group;
        return $group ? [$group] : [];
    }

    /** Kunci role yang boleh diurus user saat ini (berdasarkan grup domainnya). */
    private function manageableRoleKeys(): array
    {
        $roles = Role::allCached();
        $groups = $this->manageableGroups();
        if ($groups !== null) {
            $roles = $roles->whereIn('group', $groups);
        }
        return $roles->pluck('key')->all();
    }

    private function canManageRole(?string $roleKey): bool
    {
        return $roleKey !== null && in_array($roleKey, $this->manageableRoleKeys(), true);
    }

    /**
     * Apakah user saat ini boleh mengurus (edit/hapus/nonaktifkan) $user tertentu.
     * Manajer berdomain (mis. Ketua RT) TIDAK bisa mengurus sesama pemegang role yang sama.
     */
    public function canManageUser(User $user): bool
    {
        if (! $this->canManageRole($user->role)) {
            return false;
        }
        // Proteksi sesama Ketua: role sama & bukan diri sendiri → tidak boleh.
        if ($this->manageableGroups() !== null
            && $user->role === auth()->user()->role
            && $user->id !== auth()->id()) {
            return false;
        }
        return true;
    }

    /** Role yang boleh DIPILIH (dalam domain, aktif, super_admin hanya untuk Super Admin). */
    private function assignableRoleKeys(): array
    {
        $keys = $this->manageableRoleKeys();

        // Hanya role aktif yang bisa ditugaskan...
        $active = Role::activeKeys();
        $keys = array_values(array_filter($keys, fn ($k) => in_array($k, $active, true)));

        // ...kecuali role asli akun yang sedang diedit (agar bisa dipertahankan).
        if ($this->editOriginalRole
            && ! in_array($this->editOriginalRole, $keys, true)
            && in_array($this->editOriginalRole, $this->manageableRoleKeys(), true)) {
            $keys[] = $this->editOriginalRole;
        }

        if (! $this->canAssignSuper()) {
            $keys = array_values(array_filter($keys, fn ($k) => $k !== 'super_admin'));
        }
        return $keys;
    }

    /** Role default yang valid untuk user saat ini. */
    private function defaultRole(): string
    {
        $keys = $this->assignableRoleKeys();
        foreach (['bendahara', 'pengurus_rt', 'perumahan', 'dkm'] as $pref) {
            if (in_array($pref, $keys, true)) return $pref;
        }
        return $keys[0] ?? 'bendahara';
    }

    protected function rules()
    {
        // Mode "dari penghuni": nama, email & password otomatis — cukup pilih penghuni + role.
        if (! $this->selected_id && $this->createMode === 'resident') {
            return [
                'selectedResidentIds'   => 'required|array|min:1',
                'selectedResidentIds.*' => 'exists:residents,id',
                'role'                  => ['required', Rule::in($this->assignableRoleKeys())],
            ];
        }

        return [
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->selected_id)],
            'role'     => ['required', Rule::in($this->assignableRoleKeys())],
            'password' => [$this->selected_id ? 'nullable' : 'required', 'string', 'min:6'],
        ];
    }

    protected function validationAttributes()
    {
        return ['selectedResidentIds' => 'penghuni'];
    }

    protected function messages()
    {
        return ['selectedResidentIds.required' => 'Pilih minimal satu penghuni.'];
    }

    public function render()
    {
        $residents = collect();
        if ($this->isModalOpen && $this->createMode === 'resident') {
            // Select2 melakukan pencarian di sisi klien, jadi muat semua penghuni yang eligible.
            $residents = Resident::query()
                ->whereDoesntHave('adminUser')
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        // Dropdown role: hanya role dalam domain yang boleh dipilih.
        $assignableKeys = $this->assignableRoleKeys();
        $rolesGrouped = Role::allCached()->whereIn('key', $assignableKeys)->groupBy('group');

        // Daftar pengguna: hanya yang rolenya dalam domain yang boleh diurus.
        $usersQuery = User::with('resident:id,name')->latest();
        $groups = $this->manageableGroups();
        if ($groups !== null) {
            $usersQuery->whereIn('role', $this->manageableRoleKeys());
        }
        $users = $usersQuery->paginate(10);

        // Peta boleh-diurus per baris (untuk proteksi sesama Ketua di UI).
        $manageableMap = [];
        foreach ($users as $u) {
            $manageableMap[$u->id] = $this->canManageUser($u);
        }

        return view('livewire.admin.manage-users', [
            'users'          => $users,
            'residents'      => $residents,
            'rolesGrouped'   => $rolesGrouped,
            'roleLabels'     => Role::labels(),
            'roleColors'     => Role::colors(),
            'canManageSuper' => $this->canAssignSuper(),
            'manageableMap'  => $manageableMap,
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->createMode = 'new';
        $this->isModalOpen = true;
    }

    // Ganti tab Akun Baru / Dari Penghuni di dalam modal.
    public function setMode($mode)
    {
        $this->createMode = $mode === 'resident' ? 'resident' : 'new';
        $this->resetErrorBag();
        $this->reset(['residentSearch', 'selectedResidentIds']);
        if ($this->createMode === 'resident') {
            $this->role = $this->defaultRole();
            $this->password = null;
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Akun Super Admin hanya bisa dikelola oleh Super Admin.
        if ($user->role === 'super_admin' && ! $this->canAssignSuper()) {
            session()->flash('error', 'Akun Super Admin hanya bisa dikelola oleh Super Admin.');
            return;
        }

        // Hanya boleh mengurus pengguna dalam domain, dan bukan sesama Ketua.
        if (! $this->canManageUser($user)) {
            session()->flash('error', 'Anda tidak berwenang mengurus pengguna ini.');
            return;
        }

        $this->createMode = 'new';
        $this->selected_id = $id;
        $this->editResidentLinked = (bool) $user->resident_id;
        $this->editIsSuperAdmin = $user->role === 'super_admin';
        $this->editOriginalRole = $user->role;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = null; // Kosongkan password saat edit
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        // ── Jadikan sejumlah penghuni sebagai admin (role sama) ──
        if (! $this->selected_id && $this->createMode === 'resident') {
            $residents = Resident::whereIn('id', $this->selectedResidentIds)
                ->whereDoesntHave('adminUser')
                ->get();

            if ($residents->isEmpty()) {
                session()->flash('error', 'Penghuni terpilih sudah memiliki akses admin.');
                return;
            }

            foreach ($residents as $resident) {
                User::create([
                    'resident_id'       => $resident->id,
                    'name'              => $resident->name,
                    'email'             => $this->makeAdminEmail($resident),
                    'password'          => Hash::make(Str::random(40)),
                    'role'              => $this->role,
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]);
            }

            session()->flash('success', $residents->count() . ' penghuni berhasil dijadikan admin.');
            $this->closeModal();
            return;
        }

        // ── Proteksi role Super Admin & domain ──
        if ($this->selected_id) {
            $target = User::find($this->selected_id);
            if ($target && ! $this->canManageUser($target)) {
                session()->flash('error', 'Anda tidak berwenang mengurus pengguna ini.');
                return;
            }
            if ($target && $target->role === 'super_admin') {
                if (! $this->canAssignSuper()) {
                    session()->flash('error', 'Akun Super Admin hanya bisa dikelola oleh Super Admin.');
                    return;
                }
                $this->role = 'super_admin'; // role Super Admin terkunci, tak bisa diubah
            }
        }
        // Hanya Super Admin yang boleh menetapkan role super_admin.
        if ($this->role === 'super_admin' && ! $this->canAssignSuper()) {
            session()->flash('error', 'Hanya Super Admin yang bisa menetapkan role Super Admin.');
            return;
        }

        // ── Buat / edit akun biasa ──
        $data = [
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
        ];

        if (! empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if (! $this->selected_id) {
            $data['email_verified_at'] = now();
        }

        User::updateOrCreate(['id' => $this->selected_id], $data);

        session()->flash('success',
            $this->selected_id ? 'User berhasil diperbarui.' : 'User berhasil dibuat.');

        $this->closeModal();
    }

    // Cabut / aktifkan kembali akses admin (dipakai terutama untuk penghuni-admin).
    public function toggleActive($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
            return;
        }

        $user = User::findOrFail($id);

        if (! $this->canManageUser($user)) {
            session()->flash('error', 'Anda tidak berwenang mengurus pengguna ini.');
            return;
        }

        $user->update(['is_active' => ! $user->is_active]);

        session()->flash('success', $user->is_active ? 'Akses admin diaktifkan.' : 'Akses admin dicabut.');
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', 'Gagal! Anda tidak bisa menghapus akun Anda sendiri.');
            return;
        }

        $user = User::withCount('transactions')->find($id);

        if ($user->role === 'super_admin') {
            session()->flash('error', 'Akun Super Admin tidak bisa dihapus.');
            return;
        }

        if (! $this->canManageUser($user)) {
            session()->flash('error', 'Anda tidak berwenang mengurus pengguna ini.');
            return;
        }

        if ($user->transactions_count > 0) {
            session()->flash('error', 'Gagal! User ini sudah memiliki transaksi terkait.');
            return;
        }

        $user->delete();
        session()->flash('success', 'User berhasil dihapus.');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    // Email identitas akun admin: pakai email penghuni bila ada & belum dipakai, selain itu digenerate.
    private function makeAdminEmail(Resident $resident): string
    {
        if ($resident->email && ! User::where('email', $resident->email)->exists()) {
            return $resident->email;
        }

        $email = 'penghuni' . $resident->id . '@warga.local';
        $i = 1;
        while (User::where('email', $email)->exists()) {
            $email = 'penghuni' . $resident->id . '-' . $i . '@warga.local';
            $i++;
        }

        return $email;
    }

    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'selected_id', 'residentSearch', 'selectedResidentIds']);
        $this->createMode = 'new';
        $this->editResidentLinked = false;
        $this->editIsSuperAdmin = false;
        $this->editOriginalRole = null;
        $this->role = $this->defaultRole();
        $this->resetErrorBag();
    }
}
