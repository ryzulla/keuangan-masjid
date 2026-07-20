<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Resident;

#[Layout('layouts.penghuni')]
class EditDataDiri extends Component
{
    use WithFileUploads;

    public string $name        = '';
    public string $gender      = 'laki-laki';
    public string $nik         = '';
    public string $birth_date  = '';
    public string $memberNotes = '';
    public ?string $existingPhoto = null;
    public $photo = null;

    protected function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'gender'      => 'required|in:laki-laki,perempuan',
            'nik'         => 'nullable|string|max:20',
            'birth_date'  => 'nullable|date',
            'memberNotes' => 'nullable|string|max:500',
            'photo'       => 'nullable|image|max:2048',
        ];
    }

    public function mount(): void
    {
        $resident = Auth::guard('resident')->user();

        $this->name          = $resident->name ?? '';
        $this->gender        = $resident->gender ?? 'laki-laki';
        $this->nik           = $resident->nik ?? '';
        $this->birth_date    = $resident->birth_date?->format('Y-m-d') ?? '';
        $this->memberNotes   = $resident->notes ?? '';
        $this->existingPhoto = $resident->photo;
    }

    public function removePhoto(): void
    {
        if ($this->existingPhoto) {
            Storage::disk('public')->delete($this->existingPhoto);
            $this->existingPhoto = null;

            Resident::find(Auth::guard('resident')->id())?->update(['photo' => null]);
        }
        $this->photo = null;
    }

    public function save(): void
    {
        $this->validate();

        $resident = Resident::findOrFail(Auth::guard('resident')->id());

        $photoPath = $this->existingPhoto;
        if ($this->photo) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $this->photo->store('residents', 'public');
        }

        $resident->update([
            'name'       => $this->name,
            'photo'      => $photoPath,
            'gender'     => $this->gender,
            'nik'        => $this->nik ?: null,
            'birth_date' => $this->birth_date ?: null,
            'notes'      => $this->memberNotes ?: null,
        ]);

        session()->flash('success', 'Data diri Anda berhasil disimpan.');
        $this->redirect(route('penghuni.keluarga'), navigate: true);
    }

    public function render()
    {
        return view('livewire.penghuni.edit-data-diri');
    }
}
