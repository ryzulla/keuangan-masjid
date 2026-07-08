<?php
namespace App\Livewire\Residents;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Resident;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.app')]
class ResidentDetail extends Component
{
    public Resident $resident;

    public bool   $isPasswordModalOpen = false;
    public string $newPassword         = '';
    public string $confirmPassword     = '';

    public function mount(Resident $resident): void
    {
        $this->resident = $resident->load([
            'currentAssignments.houseBlock',
            'assignments.houseBlock',
            'familyMembers',
            'iplBillings.period',
            'iplBillings.houseBlock',
        ]);
    }

    public function render()
    {
        return view('livewire.residents.resident-detail');
    }

    public function toggleActive(): void
    {
        $active = !$this->resident->is_active;

        if (!$active) {
            $this->resident->currentAssignments()->update(['ended_at' => now()]);
        }

        $this->resident->update(['is_active' => $active]);
        $this->resident->refresh();
        session()->flash('success', 'Status penghuni berhasil diubah.');
    }

    public function openPasswordModal(): void
    {
        $this->newPassword     = '';
        $this->confirmPassword = '';
        $this->isPasswordModalOpen = true;
    }

    public function setPassword(): void
    {
        $this->validate([
            'newPassword'     => 'required|string|min:6',
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'confirmPassword.same' => 'Konfirmasi password tidak cocok.',
        ]);

        if (!$this->resident->email) {
            $this->addError('newPassword', 'Penghuni harus memiliki email terlebih dahulu untuk bisa login.');
            return;
        }

        $this->resident->update(['password' => Hash::make($this->newPassword)]);

        // Siapkan link WhatsApp berisi kredensial (email + password) — hanya tersedia
        // sekali ini karena password disimpan ter-hash.
        $waUrl = $this->buildWaUrl($this->newPassword);

        $this->isPasswordModalOpen = false;
        session()->flash('success', 'Password akses portal penghuni berhasil diatur.');
        if ($waUrl) {
            session()->flash('portal_wa_credentials_url', $waUrl);
        }
    }

    public function resetPortalAccess(): void
    {
        $this->resident->update(['password' => null]);
        session()->flash('success', 'Akses portal penghuni dicabut. Penghuni tidak dapat login lagi.');
    }

    /** Nomor WhatsApp penghuni dinormalkan ke format internasional (62...). */
    private function normalizeWaNumber(): ?string
    {
        $raw = $this->resident->whatsapp ?: $this->resident->phone;
        if (!$raw) return null;
        $d = preg_replace('/\D+/', '', (string) $raw);
        if ($d === '') return null;
        if (str_starts_with($d, '0'))  return '62' . substr($d, 1);
        if (str_starts_with($d, '62')) return $d;
        if (str_starts_with($d, '8'))  return '62' . $d;
        return $d;
    }

    /** Bangun URL wa.me berisi info akun portal. $password hanya disertakan bila diberikan. */
    private function buildWaUrl(?string $password = null): ?string
    {
        if (!$this->resident->email) return null;

        $login = route('penghuni.login');
        $lines = [
            "Assalamu'alaikum {$this->resident->name},",
            'Akun Portal Penghuni Anda sudah siap digunakan.',
            '',
            "Alamat login: {$login}",
            "Email: {$this->resident->email}",
        ];
        if ($password) {
            $lines[] = "Password: {$password}";
        }
        $lines[] = '';
        $lines[] = $password
            ? 'Setelah masuk, silakan ganti password di menu Pengaturan. Jika lupa, gunakan fitur "Lupa Password".'
            : 'Silakan login dengan password yang telah diberikan. Jika lupa, gunakan fitur "Lupa Password" di halaman login.';

        $msg  = implode("\n", $lines);
        $num  = $this->normalizeWaNumber();
        $base = $num ? "https://wa.me/{$num}" : 'https://wa.me/';

        return $base . '?text=' . rawurlencode($msg);
    }

    /** URL WhatsApp info login (tanpa password) — untuk kirim ulang kapan saja. */
    public function getWaLoginUrlProperty(): ?string
    {
        return $this->buildWaUrl(null);
    }
}
