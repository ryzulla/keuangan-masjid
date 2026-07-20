<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class RoleAccessSettings extends Component
{
    public array $matrix = [];

    const GROUPS = ['Perumahan', 'DKM Masjid', 'Administrator', 'Lainnya'];

    // Panel atur akses per role
    public bool $isAccessModalOpen = false;
    public ?string $activeRole = null;

    // Modal tambah/edit role
    public bool $isRoleModalOpen = false;
    public $roleId = null;
    public string $roleKey = '';
    public string $roleLabel = '';
    public string $roleColor = '#164A40';
    public string $roleGroup = 'Lainnya';

    const GATES = [
        'manage-users'        => ['label' => 'Manajemen Pengguna',            'group' => 'Administrasi',   'icon' => 'users'],
        'manage-admin'        => ['label' => 'Pengaturan Admin (Role, Pengumuman, dll)', 'group' => 'Administrasi', 'icon' => 'shield'],
        'manage-transactions' => ['label' => 'Master Data (Akun & Kategori)', 'group' => 'Administrasi',   'icon' => 'cog'],
        'view-reports'        => ['label' => 'Laporan Keuangan',              'group' => 'Administrasi',   'icon' => 'chart'],
        'manage-dkm'                => ['label' => 'Transaksi DKM',              'group' => 'DKM Masjid',  'icon' => 'book'],
        'manage-programs-dkm'       => ['label' => 'Program DKM',               'group' => 'DKM Masjid',  'icon' => 'star'],
        'manage-residents'          => ['label' => 'Data Penghuni & Blok Rumah','group' => 'Perumahan',   'icon' => 'home'],
        'manage-ipl'                => ['label' => 'IPL & Pengaturan Tarif',    'group' => 'Perumahan',   'icon' => 'receipt'],
        'manage-perumahan'          => ['label' => 'Transaksi Perumahan',       'group' => 'Perumahan',   'icon' => 'cash'],
        'manage-programs-perumahan' => ['label' => 'Program Perumahan',         'group' => 'Perumahan',   'icon' => 'star'],
    ];

    /** Role yang bisa diatur di matrix (semua kecuali super_admin yang selalu penuh). */
    private function editableRoleKeys(): array
    {
        return Role::allCached()->where('key', '!=', 'super_admin')->pluck('key')->all();
    }

    public function mount(): void
    {
        $this->buildMatrix();
    }

    private function buildMatrix(): void
    {
        $existing = RolePermission::all();
        $this->matrix = [];

        foreach ($this->editableRoleKeys() as $role) {
            foreach (array_keys(self::GATES) as $gate) {
                $this->matrix[$role][$gate] = $existing
                    ->where('role', $role)->where('gate', $gate)->isNotEmpty();
            }
        }
    }

    private const DEFAULTS = [
        'admin'         => ['manage-users', 'manage-admin', 'manage-dkm', 'manage-perumahan', 'manage-programs-dkm', 'manage-programs-perumahan', 'manage-transactions', 'view-reports', 'manage-residents', 'manage-ipl'],
        'bendahara'     => ['manage-dkm', 'manage-programs-dkm', 'manage-transactions', 'view-reports'],
        'bendahara_rt'  => ['manage-perumahan', 'manage-programs-perumahan', 'manage-transactions', 'view-reports', 'manage-ipl'],
        'ketua_dkm'     => ['manage-users', 'view-reports'],
        'dkm'           => ['manage-dkm', 'manage-programs-dkm'],
        'perumahan'     => ['manage-users', 'manage-perumahan', 'manage-programs-perumahan', 'manage-residents', 'manage-ipl'],
        'pengurus_rt'   => ['manage-perumahan', 'manage-programs-perumahan', 'manage-transactions', 'view-reports', 'manage-residents', 'manage-ipl'],
    ];

    // ─── Atur Akses per role ───────────────────────────────────────────────
    public function openAccess(string $roleKey): void
    {
        if (! in_array($roleKey, $this->editableRoleKeys())) return;
        $this->activeRole = $roleKey;
        $this->isAccessModalOpen = true;
    }

    public function closeAccess(): void
    {
        $this->isAccessModalOpen = false;
        $this->activeRole = null;
    }

    public function toggleAll(bool $value): void
    {
        if (! $this->activeRole) return;
        foreach (array_keys(self::GATES) as $gate) {
            $this->matrix[$this->activeRole][$gate] = $value;
        }
    }

    public function resetActiveRole(): void
    {
        if (! $this->activeRole) return;
        foreach (array_keys(self::GATES) as $gate) {
            $this->matrix[$this->activeRole][$gate] = in_array($gate, self::DEFAULTS[$this->activeRole] ?? []);
        }
    }

    public function saveAccess(): void
    {
        $role = $this->activeRole;
        if (! $role || ! in_array($role, $this->editableRoleKeys())) {
            $this->closeAccess();
            return;
        }

        RolePermission::where('role', $role)->delete();
        foreach ($this->matrix[$role] ?? [] as $gate => $allowed) {
            if ($allowed && array_key_exists($gate, self::GATES)) {
                RolePermission::create(['role' => $role, 'gate' => $gate]);
            }
        }

        $this->flushGateCache();
        session()->flash('success', 'Akses role "' . Role::labelFor($role) . '" berhasil disimpan.');
        $this->closeAccess();
    }

    // ─── Kelola Role ────────────────────────────────────────────────────────
    public function addRole(): void
    {
        $this->reset(['roleId', 'roleKey', 'roleLabel']);
        $this->roleColor = '#164A40';
        $this->roleGroup = 'Lainnya';
        $this->resetErrorBag();
        $this->isRoleModalOpen = true;
    }

    public function editRole($id): void
    {
        $role = Role::findOrFail($id);
        $this->roleId = $role->id;
        $this->roleKey = $role->key;
        $this->roleLabel = $role->label;
        $this->roleColor = $role->color;
        $this->roleGroup = in_array($role->group, self::GROUPS) ? $role->group : 'Lainnya';
        $this->resetErrorBag();
        $this->isRoleModalOpen = true;
    }

    public function saveRole(): void
    {
        // Key hanya bisa diatur saat membuat role baru (mengubah key merusak data lama).
        $rules = [
            'roleLabel' => 'required|string|max:50',
            'roleColor' => ['required', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'roleGroup' => ['required', Rule::in(self::GROUPS)],
        ];
        if (! $this->roleId) {
            $rules['roleKey'] = ['required', 'string', 'max:40', 'regex:/^[a-z][a-z0-9_]*$/', Rule::unique('roles', 'key')];
        }
        $this->validate($rules, [
            'roleKey.regex' => 'Kode role hanya boleh huruf kecil, angka, dan garis bawah, diawali huruf.',
            'roleColor.regex' => 'Warna harus format hex, contoh #164A40.',
        ], [
            'roleLabel' => 'nama role', 'roleKey' => 'kode role', 'roleColor' => 'warna', 'roleGroup' => 'grup',
        ]);

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->update([
                'label' => $this->roleLabel,
                'color' => $this->roleColor,
                'group' => $this->roleGroup,
            ]);
        } else {
            Role::create([
                'key'       => $this->roleKey,
                'label'     => $this->roleLabel,
                'color'     => $this->roleColor,
                'group'     => $this->roleGroup,
                'is_system' => false,
                'sort'      => (int) (Role::max('sort') ?? 0) + 1,
            ]);
            $this->buildMatrix(); // agar kolom baru muncul di matrix
        }

        session()->flash('success', 'Role berhasil disimpan.');
        $this->closeRoleModal();
    }

    public function toggleRoleActive($id): void
    {
        $role = Role::findOrFail($id);

        // Role bawaan sistem yang sedang aktif tidak boleh dinonaktifkan —
        // menonaktifkannya akan mengunci akses pemegangnya (mis. admin/super_admin).
        if ($role->is_system && $role->is_active) {
            session()->flash('error', "Role bawaan \"{$role->label}\" tidak bisa dinonaktifkan.");
            return;
        }

        $role->update(['is_active' => ! $role->is_active]);
        $this->flushGateCache();

        session()->flash('success', $role->is_active
            ? "Role \"{$role->label}\" diaktifkan."
            : "Role \"{$role->label}\" dinonaktifkan — pemegangnya kehilangan akses.");
    }

    public function deleteRole($id): void
    {
        $role = Role::findOrFail($id);

        if ($role->is_system) {
            session()->flash('error', 'Role bawaan tidak bisa dihapus.');
            return;
        }

        $userCount = User::where('role', $role->key)->count();
        if ($userCount > 0) {
            session()->flash('error', "Role \"{$role->label}\" masih dipakai {$userCount} pengguna. Ganti role mereka dulu.");
            return;
        }

        RolePermission::where('role', $role->key)->delete();
        $role->delete();
        $this->flushGateCache();
        unset($this->matrix[$role->key]);

        session()->flash('success', 'Role berhasil dihapus.');
    }

    public function closeRoleModal(): void
    {
        $this->isRoleModalOpen = false;
        $this->reset(['roleId', 'roleKey', 'roleLabel']);
        $this->roleColor = '#164A40';
        $this->roleGroup = 'Lainnya';
        $this->resetErrorBag();
    }

    private function flushGateCache(): void
    {
        foreach (array_keys(self::GATES) as $gate) {
            Cache::forget("gate_roles_{$gate}");
        }
    }

    public function render()
    {
        $roles = Role::allCached()->where('key', '!=', 'super_admin')->values();

        $summary = [];
        foreach ($roles as $role) {
            $summary[$role->key] = collect($this->matrix[$role->key] ?? [])->filter()->count();
        }

        return view('livewire.admin.role-access-settings', [
            'gates'   => self::GATES,
            'roles'   => $roles,
            'summary' => $summary,
            'groups'  => self::GROUPS,
        ]);
    }
}
