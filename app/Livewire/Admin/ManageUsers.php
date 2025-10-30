<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->selected_id)],
            'role' => ['required', Rule::in(['admin', 'bendahara', 'ketua_dkm'])],
            // Password hanya wajib saat membuat user baru
            'password' => [$this->selected_id ? 'nullable' : 'required', 'string', 'min:8'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.manage-users', [
            'users' => User::latest()->paginate(10)
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = null; // Kosongkan password saat edit
        $this->isModalOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        // Hanya update password jika diisi
        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        // Set email_verified_at jika user baru
        if (!$this->selected_id) {
            $data['email_verified_at'] = now();
        }

        User::updateOrCreate(['id' => $this->selected_id], $data);

        session()->flash('success',
            $this->selected_id ? 'User berhasil diperbarui.' : 'User berhasil dibuat.');

        $this->closeModal();
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            session()->flash('error', 'Gagal! Anda tidak bisa menghapus akun Anda sendiri.');
            return;
        }

        $user = User::withCount('transactions')->find($id);

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

    private function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'selected_id']);
        $this->role = 'bendahara';
    }
}
