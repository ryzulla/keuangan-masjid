<?php
namespace App\Livewire\HouseBlocks;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\HouseBlock;
use App\Models\Resident;
use App\Models\ResidentHouseBlock;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class ManageHouseBlocks extends Component
{
    public string $filterLetter = '';
    public string $filterStatus = '';

    public bool $showCreateForm = false;
    public bool $showEditForm   = false;
    public ?int  $editingBlockId = null;

    public string $blockLetter   = '';
    public string $blockNumber   = '';
    public bool   $blockIsActive = true;
    public string $blockNotes    = '';

    protected function rules(): array
    {
        return [
            'blockLetter'   => 'required|string|max:5|regex:/^[A-Za-z0-9]+$/',
            'blockNumber'   => 'required|integer|min:1|max:99',
            'blockIsActive' => 'boolean',
            'blockNotes'    => 'nullable|string|max:500',
        ];
    }

    public function render()
    {
        $blocks = HouseBlock::query()
            ->with(['currentAssignments.resident'])
            ->when($this->filterLetter, fn($q) => $q->where('block_letter', $this->filterLetter))
            ->when($this->filterStatus === 'nonaktif', fn($q) => $q->where('is_active', false))
            ->when($this->filterStatus === 'dihuni', fn($q) => $q->where('is_active', true)
                ->whereHas('assignments', fn($q2) => $q2->whereNull('ended_at')))
            ->when($this->filterStatus === 'kosong', fn($q) => $q->where('is_active', true)
                ->whereDoesntHave('assignments', fn($q2) => $q2->whereNull('ended_at')))
            ->when($this->filterStatus === 'disewa', fn($q) => $q->where('is_active', true)
                ->whereHas('assignments', fn($q2) => $q2->whereNull('ended_at')
                    ->whereIn('ownership_type', ['kontrak', 'kos'])))
            ->orderBy('block_letter')
            ->orderBy('unit_number')
            ->get();

        $blocksByLetter = $blocks->groupBy('block_letter');
        $allLetters     = HouseBlock::distinct()->orderBy('block_letter')->pluck('block_letter');

        $totalBlocks    = HouseBlock::count();
        $activeBlocks   = HouseBlock::where('is_active', true)->count();
        $occupiedBlocks = HouseBlock::where('is_active', true)
            ->whereHas('assignments', fn($q) => $q->whereNull('ended_at'))->count();
        $rentedBlocks = HouseBlock::where('is_active', true)
            ->whereHas('assignments', fn($q) => $q->whereNull('ended_at')
                ->whereIn('ownership_type', ['kontrak', 'kos']))->count();
        $totalResidents = Resident::where('is_active', true)->count();

        return view('livewire.house-blocks.manage-house-blocks', compact(
            'blocksByLetter', 'allLetters', 'totalBlocks', 'activeBlocks', 'occupiedBlocks', 'rentedBlocks', 'totalResidents'
        ));
    }

    public function openCreate(): void
    {
        $this->reset(['editingBlockId', 'blockLetter', 'blockNumber', 'blockNotes']);
        $this->blockIsActive  = true;
        $this->showEditForm   = false;
        $this->showCreateForm = true;
        $this->resetErrorBag();
    }

    public function openEdit(int $id): void
    {
        $block               = HouseBlock::findOrFail($id);
        $this->editingBlockId = $id;
        $this->blockLetter   = $block->block_letter;
        $this->blockNumber   = (string) $block->unit_number;
        $this->blockIsActive = $block->is_active;
        $this->blockNotes    = $block->notes ?? '';
        $this->showCreateForm = false;
        $this->showEditForm   = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate();
        try {
            $letter = strtoupper($this->blockLetter);
            $number = (int) $this->blockNumber;

            if ($this->editingBlockId) {
                HouseBlock::findOrFail($this->editingBlockId)->update([
                    'block_letter' => $letter,
                    'unit_number'  => $number,
                    'is_active'    => $this->blockIsActive,
                    'notes'        => $this->blockNotes ?: null,
                ]);
                session()->flash('success', 'Blok ' . $letter . '-' . $number . ' berhasil diperbarui.');
            } else {
                if (HouseBlock::where('block_letter', $letter)->where('unit_number', $number)->exists()) {
                    $this->addError('blockLetter', 'Blok ' . $letter . '-' . $number . ' sudah ada.');
                    return;
                }
                HouseBlock::create([
                    'block_letter' => $letter,
                    'unit_number'  => $number,
                    'is_active'    => $this->blockIsActive,
                    'notes'        => $this->blockNotes ?: null,
                ]);
                session()->flash('success', 'Blok ' . $letter . '-' . $number . ' berhasil ditambahkan.');
            }
            $this->cancelForm();
        } catch (\Exception $e) {
            Log::error('HouseBlock save error: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function cancelForm(): void
    {
        $this->showCreateForm = false;
        $this->showEditForm   = false;
        $this->editingBlockId = null;
        $this->reset(['blockLetter', 'blockNumber', 'blockNotes']);
        $this->blockIsActive = true;
        $this->resetErrorBag();
    }

    public function toggleActive(int $id): void
    {
        $block = HouseBlock::findOrFail($id);
        $block->update(['is_active' => !$block->is_active]);
        session()->flash('success', 'Status blok berhasil diubah.');
    }
}
