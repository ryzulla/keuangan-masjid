<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.guest', ['bare' => true])]
class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;

    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required|string',
    ];

    public function login(): void
    {
        $this->validate();

        if (!Auth::guard('resident')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau password salah, atau akun belum diaktifkan.');
            return;
        }

        $resident = Auth::guard('resident')->user();
        if (!$resident->is_active) {
            Auth::guard('resident')->logout();
            $this->addError('email', 'Akun Anda tidak aktif. Hubungi pengurus perumahan.');
            return;
        }

        $this->redirect(route('penghuni.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.penghuni.login');
    }
}
