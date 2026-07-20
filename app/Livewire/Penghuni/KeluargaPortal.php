<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\FamilyMember;

#[Layout('layouts.penghuni')]
class KeluargaPortal extends Component
{
    public function delete(int $id): void
    {
        FamilyMember::where('resident_id', Auth::guard('resident')->id())
            ->findOrFail($id)
            ->delete();
        session()->flash('success', 'Anggota keluarga dihapus.');
    }

    public function render()
    {
        $resident = Auth::guard('resident')->user();
        $members = $resident->familyMembers()->get();

        return view('livewire.penghuni.keluarga-portal', compact('resident', 'members'));
    }
}
