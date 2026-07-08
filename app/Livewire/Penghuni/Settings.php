<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Resident;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

#[Layout('layouts.penghuni')]
class Settings extends Component
{
    use WithFileUploads;

    public int $residentId;

    // ─── Profil ───
    public string $name     = '';
    public string $phone    = '';
    public string $whatsapp = '';
    public string $email    = '';

    // ─── Foto Profil ───
    public $photo = null;
    public ?string $existingPhoto = null;

    // ─── Ganti Password ───
    public string $currentPassword            = '';
    public string $newPassword                = '';
    public string $newPassword_confirmation   = '';

    // ─── Preferensi Notifikasi ───
    public array $notifications = [];

    public function mount(): void
    {
        $resident = auth('resident')->user();
        $this->residentId = $resident->id;

        $this->name          = $resident->name ?? '';
        $this->phone         = $resident->phone ?? '';
        $this->whatsapp      = $resident->whatsapp ?? '';
        $this->email         = $resident->email ?? '';
        $this->existingPhoto = $resident->photo;

        foreach (array_keys(Resident::NOTIFICATION_TYPES) as $key) {
            $this->notifications[$key] = $resident->wantsNotification($key);
        }
    }

    // ─── a. Edit profil ───
    public function saveProfile(): void
    {
        $validated = $this->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email'    => [
                'nullable', 'email', 'max:255',
                Rule::unique('residents', 'email')->ignore($this->residentId),
            ],
        ], [], [
            'name'     => 'nama',
            'phone'    => 'nomor telepon',
            'whatsapp' => 'nomor WhatsApp',
            'email'    => 'email',
        ]);

        try {
            $resident = Resident::findOrFail($this->residentId);
            $resident->update([
                'name'     => $validated['name'],
                'phone'    => ($validated['phone'] ?? '') ?: null,
                'whatsapp' => ($validated['whatsapp'] ?? '') ?: null,
                'email'    => ($validated['email'] ?? '') ?: null,
            ]);
            session()->flash('profile_success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Penghuni\Settings::saveProfile ' . $e->getMessage());
            session()->flash('profile_error', 'Gagal menyimpan profil.');
        }
    }

    // ─── b. Foto profil ───
    public function savePhoto(): void
    {
        $this->validate([
            'photo' => 'required|image|max:2048',
        ], [
            'photo.required' => 'Silakan pilih foto terlebih dahulu.',
            'photo.image'    => 'Berkas harus berupa gambar.',
            'photo.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        try {
            $resident = Resident::findOrFail($this->residentId);

            if ($resident->photo) {
                Storage::disk('public')->delete($resident->photo);
            }
            $path = $this->photo->store('residents', 'public');
            $resident->update(['photo' => $path]);

            $this->existingPhoto = $path;
            $this->photo = null;
            session()->flash('photo_success', 'Foto profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Penghuni\Settings::savePhoto ' . $e->getMessage());
            session()->flash('photo_error', 'Gagal menyimpan foto.');
        }
    }

    public function removePhoto(): void
    {
        // Batalkan pilihan yang belum disimpan lebih dulu
        if ($this->photo) {
            $this->photo = null;
            return;
        }

        try {
            $resident = Resident::findOrFail($this->residentId);
            if ($resident->photo) {
                Storage::disk('public')->delete($resident->photo);
                $resident->update(['photo' => null]);
            }
            $this->existingPhoto = null;
            session()->flash('photo_success', 'Foto profil berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Penghuni\Settings::removePhoto ' . $e->getMessage());
            session()->flash('photo_error', 'Gagal menghapus foto.');
        }
    }

    // ─── c. Ganti password ───
    public function savePassword(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword'     => 'required|min:6|confirmed',
        ], [
            'currentPassword.required' => 'Password saat ini wajib diisi.',
            'newPassword.required'     => 'Password baru wajib diisi.',
            'newPassword.min'          => 'Password baru minimal 6 karakter.',
            'newPassword.confirmed'    => 'Konfirmasi password baru tidak cocok.',
        ]);

        $resident = Resident::findOrFail($this->residentId);

        if (! Hash::check($this->currentPassword, $resident->password)) {
            $this->addError('currentPassword', 'Password saat ini salah.');
            return;
        }

        try {
            $resident->update(['password' => Hash::make($this->newPassword)]);
            $this->reset('currentPassword', 'newPassword', 'newPassword_confirmation');
            session()->flash('password_success', 'Password berhasil diubah.');
        } catch (\Exception $e) {
            Log::error('Penghuni\Settings::savePassword ' . $e->getMessage());
            session()->flash('password_error', 'Gagal mengubah password.');
        }
    }

    // ─── d. Preferensi notifikasi ───
    public function saveNotifications(): void
    {
        try {
            $prefs = [];
            foreach (array_keys(Resident::NOTIFICATION_TYPES) as $key) {
                $prefs[$key] = (bool) ($this->notifications[$key] ?? false);
            }

            $resident = Resident::findOrFail($this->residentId);
            $resident->update(['notification_preferences' => $prefs]);
            session()->flash('notif_success', 'Preferensi notifikasi berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Penghuni\Settings::saveNotifications ' . $e->getMessage());
            session()->flash('notif_error', 'Gagal menyimpan preferensi notifikasi.');
        }
    }

    public function render()
    {
        return view('livewire.penghuni.settings', [
            'notificationTypes' => Resident::NOTIFICATION_TYPES,
        ]);
    }
}
