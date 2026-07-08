<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use App\Models\Resident;

#[Layout('layouts.guest', ['bare' => true])]
class ForgotPassword extends Component
{
    public int $step = 1;

    // Step 1 — verifikasi identitas
    public string $email      = '';
    public string $method     = 'nik'; // 'nik' | 'phone'
    public string $identifier = '';

    // Step 2 — password baru
    public string $password              = '';
    public string $password_confirmation = '';

    // Server-side: id penghuni yang sudah terverifikasi (tidak dipercaya dari input step 2)
    public ?int $residentId = null;

    /**
     * Normalisasi nomor telepon: buang semua non-digit, samakan awalan
     * 62 / +62 / 0 sehingga bisa dibandingkan berdasarkan digit belakang.
     */
    private function normalizePhone(string $value): string
    {
        $digits = preg_replace('/\D+/', '', $value);
        if ($digits === '') {
            return '';
        }
        if (str_starts_with($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }
        return ltrim($digits, '0');
    }

    public function verify(): void
    {
        $this->validate([
            'email'      => 'required|email',
            'method'     => 'required|in:nik,phone',
            'identifier' => 'required|string',
        ], [], [
            'email'      => 'email',
            'identifier' => $this->method === 'nik' ? 'NIK' : 'No. WhatsApp/HP',
        ]);

        $email = trim($this->email);
        $input = trim($this->identifier);

        // Ambil semua kandidat berdasarkan email (bisa lebih dari satu) & harus aktif.
        $candidates = Resident::where('email', $email)
            ->where('is_active', true)
            ->get();

        $matched = null;

        foreach ($candidates as $resident) {
            // Abaikan resident tanpa email (jangan match email kosong).
            if (blank($resident->email)) {
                continue;
            }

            if ($this->method === 'nik') {
                // nik di-cast encrypted → accessor otomatis dekripsi, bandingkan di PHP.
                if (filled($resident->nik) && hash_equals((string) $resident->nik, $input)) {
                    $matched = $resident;
                    break;
                }
            } else {
                $inputPhone = $this->normalizePhone($input);
                if ($inputPhone === '') {
                    continue;
                }
                $phone    = $this->normalizePhone((string) $resident->phone);
                $whatsapp = $this->normalizePhone((string) $resident->whatsapp);
                if (($phone !== '' && $phone === $inputPhone) ||
                    ($whatsapp !== '' && $whatsapp === $inputPhone)) {
                    $matched = $resident;
                    break;
                }
            }
        }

        if (!$matched) {
            $this->addError('identifier', 'Data tidak cocok. Pastikan email dan NIK/No. WhatsApp sesuai data yang terdaftar.');
            return;
        }

        $this->residentId = (int) $matched->id;
        $this->identifier = '';       // jangan simpan NIK/telepon di memori komponen
        $this->step       = 2;
    }

    public function resetPassword()
    {
        // Wajib sudah terverifikasi di step 1.
        if (!$this->residentId) {
            $this->step = 1;
            $this->addError('identifier', 'Sesi verifikasi tidak valid. Silakan ulangi.');
            return;
        }

        $this->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $resident = Resident::findOrFail($this->residentId);

        if (!$resident->is_active) {
            $this->addError('password', 'Akun tidak aktif. Hubungi pengurus perumahan.');
            return;
        }

        $resident->update(['password' => Hash::make($this->password)]);

        session()->flash('status', 'Password berhasil diperbarui. Silakan masuk dengan password baru.');

        return $this->redirect(route('penghuni.login'), navigate: true);
    }

    public function render()
    {
        return view('livewire.penghuni.forgot-password');
    }
}
