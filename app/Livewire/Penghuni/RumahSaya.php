<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\HouseBlock;
use App\Models\ResidentHouseBlock;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.penghuni')]
class RumahSaya extends Component
{
    public function mount(): void
    {
        $resident = Auth::guard('resident')->user();
        if (!$resident->isPemilik()) {
            $this->redirect(route('penghuni.dashboard'), navigate: true);
        }
    }

    public function render()
    {
        $resident = Auth::guard('resident')->user();

        $ownedBlocks = $resident->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->with(['houseBlock.photos', 'houseBlock.owners'])
            ->get()
            ->pluck('houseBlock');

        $ownedBlockIds = $ownedBlocks->pluck('id');

        $activeTenants = ResidentHouseBlock::whereIn('house_block_id', $ownedBlockIds)
            ->whereIn('ownership_type', ['kontrak', 'kos'])
            ->whereNull('ended_at')
            ->with('resident')
            ->get()
            ->keyBy('house_block_id');

        return view('livewire.penghuni.rumah-saya', compact('ownedBlocks', 'activeTenants'));
    }
}
