<?php
namespace App\Livewire\HouseBlocks;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\HouseBlock;
use App\Models\Resident;
use App\Models\ResidentHouseBlock;
use App\Models\IplBilling;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class HouseBlockDetail extends Component
{
    public HouseBlock $houseBlock;

    public string $openForm = ''; // 'block' | 'owner' | 'tenant' | 'end_tenant' | ''

    // Block edit form
    public string $editBlockLetter   = '';
    public string $editBlockNumber   = '';
    public bool   $editBlockIsActive = true;
    public string $editBlockNotes    = '';

    // Owner form
    public ?int   $ownerResidentId = null;
    public string $ownerSince      = '';
    public string $ownerNotes      = '';

    // Tenant form
    public ?int   $tenantResidentId    = null;
    public string $tenantType          = 'kontrak';
    public string $tenantContractStart = '';
    public string $tenantContractEnd   = '';
    public string $tenantMonthlyRent   = '';
    public string $tenantNotes         = '';

    // End-tenant form
    public ?int   $endingAssignmentId = null;
    public string $endTenantDate      = '';

    public $residents = [];

    public function mount(HouseBlock $houseBlock): void
    {
        $this->houseBlock          = $houseBlock;
        $this->ownerSince          = now()->format('Y-m-d');
        $this->tenantContractStart = now()->format('Y-m-d');
        $this->endTenantDate       = now()->format('Y-m-d');
        $this->residents           = Resident::where('is_active', true)
            ->orderBy('name')->get(['id', 'name']);
    }

    public function render()
    {
        $currentOwner  = $this->houseBlock->ownerAssignment()->with('resident')->first();
        $currentTenant = $this->houseBlock->tenantAssignment()->with('resident')->first();

        $allHistory = ResidentHouseBlock::where('house_block_id', $this->houseBlock->id)
            ->with('resident')
            ->orderByRaw('ended_at IS NULL DESC')
            ->orderByDesc('created_at')
            ->get();

        // Isi ended_at yg masih NULL untuk riwayat yg sudah tidak aktif
        // dengan tanggal masuk pemilik/penyewa berikutnya
        $chronological = $allHistory->sortBy('created_at');
        $prevByType = [];
        foreach ($chronological as $record) {
            $type = $record->ownership_type;
            if (isset($prevByType[$type])) {
                $prev = $prevByType[$type];
                if (!$prev->ended_at && !$prev->is_current) {
                    $prev->ended_at = $record->resident_since;
                }
            }
            $prevByType[$type] = $record;
        }

        $recentBillings = IplBilling::where('house_block_id', $this->houseBlock->id)
            ->with('period')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        return view('livewire.house-blocks.house-block-detail', compact(
            'currentOwner', 'currentTenant', 'allHistory', 'recentBillings'
        ));
    }

    // ──────────────────── BLOCK EDIT ────────────────────

    public function openBlockForm(): void
    {
        $this->editBlockLetter   = $this->houseBlock->block_letter;
        $this->editBlockNumber   = (string) $this->houseBlock->unit_number;
        $this->editBlockIsActive = $this->houseBlock->is_active;
        $this->editBlockNotes    = $this->houseBlock->notes ?? '';
        $this->resetErrorBag();
        $this->openForm = 'block';
    }

    public function saveBlock(): void
    {
        $this->validate([
            'editBlockLetter'   => 'required|string|max:5|regex:/^[A-Za-z0-9]+$/',
            'editBlockNumber'   => 'required|integer|min:1|max:99',
            'editBlockIsActive' => 'boolean',
            'editBlockNotes'    => 'nullable|string|max:500',
        ]);

        try {
            $letter = strtoupper($this->editBlockLetter);
            $number = (int) $this->editBlockNumber;
            $duplicate = HouseBlock::where('block_letter', $letter)
                ->where('unit_number', $number)
                ->where('id', '!=', $this->houseBlock->id)
                ->exists();
            if ($duplicate) {
                $this->addError('editBlockLetter', 'Blok ' . $letter . '-' . $number . ' sudah ada.');
                return;
            }
            $this->houseBlock->update([
                'block_letter' => $letter,
                'unit_number'  => $number,
                'is_active'    => $this->editBlockIsActive,
                'notes'        => $this->editBlockNotes ?: null,
            ]);
            $this->houseBlock->refresh();
            $this->openForm = '';
            session()->flash('success', 'Data blok berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('saveBlock: ' . $e->getMessage());
            $this->addError('editBlockLetter', 'Gagal: ' . $e->getMessage());
        }
    }

    // ──────────────────── OWNER ────────────────────

    public function openOwnerForm(): void
    {
        $current = $this->houseBlock->ownerAssignment()->with('resident')->first();
        if ($current) {
            $this->ownerResidentId = $current->resident_id;
            $this->ownerSince      = $current->resident_since?->format('Y-m-d') ?? now()->format('Y-m-d');
            $this->ownerNotes      = $current->notes ?? '';
        } else {
            $this->ownerResidentId = null;
            $this->ownerSince      = now()->format('Y-m-d');
            $this->ownerNotes      = '';
        }
        $this->resetErrorBag();
        $this->openForm = 'owner';
    }

    public function saveOwner(): void
    {
        $this->validate([
            'ownerResidentId' => 'required|exists:residents,id',
            'ownerSince'      => 'required|date',
            'ownerNotes'      => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // End existing pemilik assignments
            ResidentHouseBlock::where('house_block_id', $this->houseBlock->id)
                ->where('ownership_type', 'pemilik')
                ->whereNull('ended_at')
                ->update(['ended_at' => $this->ownerSince]);

            ResidentHouseBlock::create([
                'resident_id'    => $this->ownerResidentId,
                'house_block_id' => $this->houseBlock->id,
                'ownership_type' => 'pemilik',
                'occupancy_status' => 'dihuni',
                'resident_since' => $this->ownerSince,
                'notes'          => $this->ownerNotes ?: null,
                'ended_at'       => null,
            ]);

            DB::commit();
            $this->openForm = '';
            session()->flash('success', 'Pemilik berhasil ditetapkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('saveOwner: ' . $e->getMessage());
            $this->addError('ownerResidentId', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // ──────────────────── TENANT ────────────────────

    public function openTenantForm(?int $assignmentId = null): void
    {
        if ($assignmentId) {
            $a = ResidentHouseBlock::find($assignmentId);
            if ($a) {
                $this->tenantResidentId    = $a->resident_id;
                $this->tenantType          = $a->ownership_type;
                $this->tenantContractStart = $a->contract_start_date?->format('Y-m-d') ?? now()->format('Y-m-d');
                $this->tenantContractEnd   = $a->contract_end_date?->format('Y-m-d')   ?? '';
                $this->tenantMonthlyRent   = $a->monthly_rent ? (string) $a->monthly_rent : '';
                $this->tenantNotes         = $a->notes ?? '';
            }
        } else {
            $this->tenantResidentId    = null;
            $this->tenantType          = 'kontrak';
            $this->tenantContractStart = now()->format('Y-m-d');
            $this->tenantContractEnd   = '';
            $this->tenantMonthlyRent   = '';
            $this->tenantNotes         = '';
        }
        $this->resetErrorBag();
        $this->openForm = 'tenant';
    }

    public function saveTenant(): void
    {
        $this->validate([
            'tenantResidentId'    => 'required|exists:residents,id',
            'tenantType'          => 'required|in:kontrak,kos',
            'tenantContractStart' => 'required|date',
            'tenantContractEnd'   => 'nullable|date|after_or_equal:tenantContractStart',
            'tenantMonthlyRent'   => 'nullable|numeric|min:0',
            'tenantNotes'         => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            ResidentHouseBlock::where('house_block_id', $this->houseBlock->id)
                ->whereIn('ownership_type', ['kontrak', 'kos'])
                ->whereNull('ended_at')
                ->update(['ended_at' => $this->tenantContractStart]);

            ResidentHouseBlock::create([
                'resident_id'          => $this->tenantResidentId,
                'house_block_id'       => $this->houseBlock->id,
                'ownership_type'       => $this->tenantType,
                'occupancy_status'     => 'dihuni',
                'resident_since'       => $this->tenantContractStart,
                'contract_start_date'  => $this->tenantContractStart,
                'contract_end_date'    => $this->tenantContractEnd ?: null,
                'monthly_rent'         => $this->tenantMonthlyRent ? (float) $this->tenantMonthlyRent : null,
                'notes'                => $this->tenantNotes ?: null,
                'ended_at'             => null,
            ]);

            DB::commit();
            $this->openForm = '';
            session()->flash('success', 'Data penyewa berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('saveTenant: ' . $e->getMessage());
            $this->addError('tenantResidentId', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // ──────────────────── END TENANT ────────────────────

    public function openEndTenantForm(int $assignmentId): void
    {
        $this->endingAssignmentId = $assignmentId;
        $this->endTenantDate      = now()->format('Y-m-d');
        $this->resetErrorBag();
        $this->openForm = 'end_tenant';
    }

    public function endTenant(): void
    {
        $this->validate([
            'endTenantDate' => 'required|date',
        ]);

        try {
            $assignment = ResidentHouseBlock::findOrFail($this->endingAssignmentId);
            $assignment->update([
                'ended_at'          => $this->endTenantDate,
                'contract_end_date' => $this->endTenantDate,
                'occupancy_status'  => 'kosong',
            ]);
            $this->openForm           = '';
            $this->endingAssignmentId = null;
            session()->flash('success', 'Kontrak berhasil diakhiri.');
        } catch (\Exception $e) {
            Log::error('endTenant: ' . $e->getMessage());
            $this->addError('endTenantDate', 'Gagal: ' . $e->getMessage());
        }
    }

    public function cancelForm(): void
    {
        $this->openForm = '';
        $this->resetErrorBag();
    }

    public function toggleBlockActive(): void
    {
        $this->houseBlock->update(['is_active' => !$this->houseBlock->is_active]);
        $this->houseBlock->refresh();
        session()->flash('success', 'Status blok berhasil diubah.');
    }
}
