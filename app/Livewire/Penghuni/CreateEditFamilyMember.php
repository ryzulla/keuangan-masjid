<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\FamilyMember;

#[Layout('layouts.penghuni')]
class CreateEditFamilyMember extends Component
{
    use WithFileUploads;

    public ?int $memberId = null;

    public string $name         = '';
    public string $relationship = 'anak';
    public string $gender       = 'laki-laki';
    public string $nik          = '';
    public string $birth_date   = '';
    public string $memberNotes  = '';
    public ?string $existingPhoto = null;
    public $photo = null;

    // Akses login anggota keluarga (opsional)
    public string $loginEmail    = '';
    public string $loginPassword = '';
    public bool   $hasLogin      = false;

    protected function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'relationship'  => 'required|in:istri,suami,anak,orang_tua,mertua,saudara,lainnya',
            'gender'        => 'required|in:laki-laki,perempuan',
            'nik'           => 'nullable|string|max:20',
            'birth_date'    => 'nullable|date',
            'memberNotes'   => 'nullable|string|max:500',
            'photo'         => 'nullable|image|max:2048',
            'loginEmail'    => 'nullable|email|max:255',
            'loginPassword' => 'nullable|string|min:6',
        ];
    }

    public function mount(?int $member = null): void
    {
        if ($member) {
            $fm = FamilyMember::where('resident_id', Auth::guard('resident')->id())
                ->findOrFail($member);

            $this->memberId     = $fm->id;
            $this->name         = $fm->name;
            $this->relationship = $fm->relationship;
            $this->gender       = $fm->gender;
            $this->nik          = $fm->nik ?? '';
            $this->birth_date   = $fm->birth_date?->format('Y-m-d') ?? '';
            $this->memberNotes  = $fm->notes ?? '';
            $this->existingPhoto = $fm->photo;
            $this->loginEmail   = $fm->email ?? '';
            $this->hasLogin     = $fm->canLogin();
        }
    }

    /** Cabut akses login anggota keluarga ini. */
    public function revokeLogin(): void
    {
        if (!$this->memberId) return;

        FamilyMember::where('resident_id', Auth::guard('resident')->id())
            ->findOrFail($this->memberId)
            ->update(['password' => null]);

        $this->hasLogin      = false;
        $this->loginPassword = '';
        session()->flash('success', 'Akses login anggota keluarga dicabut.');
    }

    public function removePhoto(): void
    {
        if ($this->existingPhoto) {
            Storage::disk('public')->delete($this->existingPhoto);
            $this->existingPhoto = null;

            if ($this->memberId) {
                FamilyMember::find($this->memberId)?->update(['photo' => null]);
            }
        }
        $this->photo = null;
    }

    public function save(): void
    {
        $this->validate();

        $resident = Auth::guard('resident')->user();

        // Jika akan mengaktifkan/ mengubah login: email wajib & tidak boleh bentrok.
        $settingPassword = $this->loginPassword !== '';
        if ($settingPassword && $this->loginEmail === '') {
            $this->addError('loginEmail', 'Email wajib diisi untuk membuat akses login.');
            return;
        }
        if ($this->loginEmail !== '' && $this->emailIsTaken($this->loginEmail)) {
            $this->addError('loginEmail', 'Email ini sudah dipakai akun lain. Gunakan email berbeda.');
            return;
        }

        $photoPath = $this->existingPhoto;
        if ($this->photo) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $this->photo->store('family-photos', 'public');
        }

        $data = [
            'resident_id'  => $resident->id,
            'name'         => $this->name,
            'photo'        => $photoPath,
            'relationship' => $this->relationship,
            'gender'       => $this->gender,
            'nik'          => $this->nik ?: null,
            'birth_date'   => $this->birth_date ?: null,
            'notes'        => $this->memberNotes ?: null,
            'email'        => $this->loginEmail ?: null,
        ];

        // Password hanya di-set ulang bila diisi (kosong = tidak diubah).
        if ($settingPassword) {
            $data['password'] = Hash::make($this->loginPassword);
        }
        // Bila email dikosongkan, cabut sekalian akses login.
        if ($this->loginEmail === '') {
            $data['password'] = null;
        }

        if ($this->memberId) {
            FamilyMember::where('resident_id', $resident->id)
                ->findOrFail($this->memberId)
                ->update($data);
        } else {
            $data['sort_order'] = $resident->familyMembers()->count();
            FamilyMember::create($data);
        }

        session()->flash('success', 'Data anggota keluarga berhasil disimpan.');
        $this->redirect(route('penghuni.keluarga'), navigate: true);
    }

    /** Cek apakah email sudah dipakai penghuni lain atau anggota keluarga lain (untuk login). */
    private function emailIsTaken(string $email): bool
    {
        $usedByResident = \App\Models\Resident::where('email', $email)->exists();

        $usedByOtherMember = FamilyMember::where('email', $email)
            ->when($this->memberId, fn ($q) => $q->where('id', '!=', $this->memberId))
            ->exists();

        return $usedByResident || $usedByOtherMember;
    }

    public function render()
    {
        return view('livewire.penghuni.create-edit-family-member');
    }
}
