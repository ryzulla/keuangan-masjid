<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\FamilyMember;

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

        // 1) Coba login sebagai penghuni utama (kepala keluarga).
        if (Auth::guard('resident')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $resident = Auth::guard('resident')->user();
            if (!$resident->is_active) {
                Auth::guard('resident')->logout();
                $this->addError('email', 'Akun Anda tidak aktif. Hubungi pengurus perumahan.');
                return;
            }

            // Login sebagai kepala keluarga sendiri — bersihkan penanda "masuk sebagai".
            session()->forget(['family_login_id', 'family_login_name']);
            $this->redirect(route('penghuni.dashboard'), navigate: true);
            return;
        }

        // 2) Coba login sebagai anggota keluarga yang punya akses sendiri.
        if ($this->loginAsFamilyMember()) {
            return;
        }

        $this->addError('email', 'Email atau password salah, atau akun belum diaktifkan.');
    }

    /**
     * Autentikasi anggota keluarga (mis. istri) memakai email + password miliknya,
     * lalu masuk ke sesi akun rumah tangga penghuni utama (akun berbagi penuh).
     */
    private function loginAsFamilyMember(): bool
    {
        $member = FamilyMember::whereNotNull('password')
            ->where('email', $this->email)
            ->with('resident')
            ->first();

        if (!$member || !Hash::check($this->password, $member->password)) {
            return false;
        }

        $resident = $member->resident;
        if (!$resident || !$resident->is_active) {
            $this->addError('email', 'Akun rumah tangga Anda tidak aktif. Hubungi pengurus perumahan.');
            return true;
        }

        Auth::guard('resident')->login($resident, $this->remember);

        // Tandai bahwa yang login adalah anggota keluarga, bukan kepala keluarga.
        session(['family_login_id' => $member->id, 'family_login_name' => $member->name]);

        $this->redirect(route('penghuni.dashboard'), navigate: true);
        return true;
    }

    public function render()
    {
        return view('livewire.penghuni.login');
    }
}
