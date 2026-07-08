<?php
namespace App\Livewire\Residents;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Resident;
use App\Models\HouseBlock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class ManageResidents extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $filterBlock  = '';
    public string $filterOwnership = '';

    public $houseBlocks = [];

    public function mount(): void
    {
        $this->houseBlocks = HouseBlock::orderBy('block_letter')->orderBy('unit_number')->get();
    }

    public function render()
    {
        $residents = Resident::query()
            ->with(['currentAssignments.houseBlock'])
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->filterBlock, fn($q) => $q->whereHas('currentAssignments', fn($q2) => $q2->where('house_block_id', $this->filterBlock)))
            ->when($this->filterOwnership, fn($q) => $q->whereHas('currentAssignments', fn($q2) => $q2->where('ownership_type', $this->filterOwnership)))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.residents.manage-residents', [
            'residents'   => $residents,
            'houseBlocks' => $this->houseBlocks,
        ]);
    }

    public function updatedSearch(): void      { $this->resetPage(); }
    public function updatedFilterBlock(): void  { $this->resetPage(); }
    public function updatedFilterOwnership(): void { $this->resetPage(); }

    public function delete(int $id): void
    {
        try {
            DB::beginTransaction();
            $resident = Resident::findOrFail($id);
            if ($resident->iplBillings()->exists()) {
                session()->flash('error', 'Penghuni ini memiliki tagihan IPL. Nonaktifkan saja daripada menghapus.');
                DB::rollBack();
                return;
            }
            $resident->assignments()->delete();
            $resident->familyMembers()->delete();
            $resident->delete();
            DB::commit();
            session()->flash('success', 'Penghuni berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ManageResidents::delete ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
