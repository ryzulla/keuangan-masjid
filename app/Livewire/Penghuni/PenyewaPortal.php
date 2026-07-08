<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\Resident;
use App\Models\ResidentHouseBlock;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

#[Layout('layouts.penghuni')]
class PenyewaPortal extends Component
{
    public bool   $isModalOpen = false;
    public ?int   $editingId   = null;
    public ?int   $houseBlockId = null;

    // Penyewa bisa penghuni BARU atau penghuni TERDAFTAR (mis. dari blok lain
    // yang rumahnya sedang direnovasi).
    public string $tenantMode         = 'baru';   // baru | terdaftar
    public ?int   $existingResidentId = null;

    public string $name             = '';
    public string $phone            = '';
    public string $whatsapp         = '';
    public string $contractStart    = '';
    public string $contractEnd      = '';
    public string $monthlyRent      = '';
    public bool   $paysIpl          = false;   // penanggung IPL (is_ipl_payer)
    public string $notes            = '';

    protected function rules(): array
    {
        return [
            'tenantMode'         => 'required|in:baru,terdaftar',
            'existingResidentId' => [Rule::requiredIf($this->tenantMode === 'terdaftar'), 'nullable', 'exists:residents,id'],
            'name'          => [Rule::requiredIf($this->tenantMode === 'baru'), 'nullable', 'string', 'max:255'],
            'phone'         => 'nullable|string|max:20',
            'whatsapp'      => 'nullable|string|max:20',
            'contractStart' => 'nullable|date',
            'contractEnd'   => 'nullable|date|after_or_equal:contractStart',
            'monthlyRent'   => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string|max:500',
        ];
    }

    public function mount(): void
    {
        $resident = Auth::guard('resident')->user();
        if (!$resident->isPemilik()) {
            $this->redirect(route('penghuni.dashboard'), navigate: true);
        }
    }

    public function openCreate(int $houseBlockId): void
    {
        $this->editingId          = null;
        $this->houseBlockId       = $houseBlockId;
        $this->tenantMode         = 'baru';
        $this->existingResidentId = null;
        $this->name           = '';
        $this->phone          = '';
        $this->whatsapp       = '';
        $this->contractStart  = now()->format('Y-m-d');
        $this->contractEnd    = '';
        $this->monthlyRent    = '';
        $this->paysIpl        = false;
        $this->notes          = '';
        $this->isModalOpen    = true;
    }

    public function openEdit(int $assignmentId): void
    {
        $assignment = ResidentHouseBlock::with('resident')
            ->findOrFail($assignmentId);

        $owner = Auth::guard('resident')->user();
        $ownerBlockIds = $owner->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->pluck('house_block_id');

        if (!$ownerBlockIds->contains($assignment->house_block_id)) return;

        $this->editingId          = $assignmentId;
        $this->houseBlockId       = $assignment->house_block_id;
        $this->tenantMode         = 'baru';
        $this->existingResidentId = null;
        $this->name          = $assignment->resident->name;
        $this->phone         = $assignment->resident->phone ?? '';
        $this->whatsapp      = $assignment->resident->whatsapp ?? '';
        $this->contractStart = $assignment->contract_start_date?->format('Y-m-d') ?? '';
        $this->contractEnd   = $assignment->contract_end_date?->format('Y-m-d') ?? '';
        $this->monthlyRent   = $assignment->monthly_rent ?? '';
        $this->paysIpl       = (bool) $assignment->is_ipl_payer;
        $this->notes         = $assignment->notes ?? '';
        $this->isModalOpen   = true;
    }

    public function save(): void
    {
        $this->validate();

        $owner = Auth::guard('resident')->user();
        $ownerBlockIds = $owner->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->pluck('house_block_id');

        if (!$ownerBlockIds->contains($this->houseBlockId)) return;

        if ($this->editingId) {
            $assignment = ResidentHouseBlock::findOrFail($this->editingId);
            // Edit data penyewa yang sudah terdaftar.
            $assignment->resident->update([
                'name'     => $this->name,
                'phone'    => $this->phone ?: null,
                'whatsapp' => $this->whatsapp ?: null,
            ]);
            $assignment->update([
                'contract_start_date' => $this->contractStart ?: null,
                'contract_end_date'   => $this->contractEnd ?: null,
                'monthly_rent'        => $this->monthlyRent ?: null,
                'is_ipl_payer'        => $this->paysIpl,
                'notes'               => $this->notes ?: null,
            ]);
        } else {
            if ($this->tenantMode === 'terdaftar') {
                // Penyewa adalah penghuni yang sudah terdaftar (mis. dari blok lain).
                $penyewa = Resident::find($this->existingResidentId);
                if (!$penyewa || !$penyewa->is_active) {
                    $this->addError('existingResidentId', 'Penghuni tidak ditemukan atau nonaktif.');
                    return;
                }
                // Cegah duplikat: sudah aktif di blok ini?
                $dup = ResidentHouseBlock::where('resident_id', $penyewa->id)
                    ->where('house_block_id', $this->houseBlockId)
                    ->whereNull('ended_at')->exists();
                if ($dup) {
                    $this->addError('existingResidentId', 'Penghuni ini sudah terdaftar aktif di blok tersebut.');
                    return;
                }
            } else {
                $penyewa = Resident::create([
                    'name'      => $this->name,
                    'phone'     => $this->phone ?: null,
                    'whatsapp'  => $this->whatsapp ?: null,
                    'is_active' => true,
                ]);
            }

            // UNIQUE(resident_id, house_block_id): pakai ulang baris bila pernah ada
            // (mis. kontrak lama yang sudah diakhiri) agar tidak melanggar constraint.
            ResidentHouseBlock::updateOrCreate(
                ['resident_id' => $penyewa->id, 'house_block_id' => $this->houseBlockId],
                [
                    'ownership_type'      => 'kontrak',
                    'occupancy_status'    => 'dihuni',
                    'resident_since'      => $this->contractStart ?: now()->format('Y-m-d'),
                    'contract_start_date' => $this->contractStart ?: null,
                    'contract_end_date'   => $this->contractEnd ?: null,
                    'monthly_rent'        => $this->monthlyRent ?: null,
                    // Penghuni terdaftar tetap punya domisili utama di rumahnya sendiri.
                    'is_primary_residence'=> $this->tenantMode === 'baru',
                    'is_ipl_payer'        => $this->paysIpl,
                    'notes'               => $this->notes ?: null,
                    'ended_at'            => null,
                ]
            );
        }

        $this->isModalOpen = false;
        session()->flash('success', $this->editingId ? 'Data penyewa diperbarui.' : 'Penyewa baru berhasil ditambahkan.');
    }

    public function endContract(int $assignmentId): void
    {
        $owner = Auth::guard('resident')->user();
        $ownerBlockIds = $owner->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->pluck('house_block_id');

        $assignment = ResidentHouseBlock::findOrFail($assignmentId);
        if (!$ownerBlockIds->contains($assignment->house_block_id)) return;

        $assignment->update(['ended_at' => now()]);
        session()->flash('success', 'Kontrak penyewa diakhiri.');
    }

    public function render()
    {
        $owner = Auth::guard('resident')->user();

        $ownedBlocks = $owner->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->with('houseBlock')
            ->get();

        $ownedBlockIds = $ownedBlocks->pluck('house_block_id');

        $tenants = ResidentHouseBlock::whereIn('house_block_id', $ownedBlockIds)
            ->whereIn('ownership_type', ['kontrak', 'kos'])
            ->whereNull('ended_at')
            ->with(['resident', 'houseBlock'])
            ->get();

        // Riwayat penyewa (kontrak sudah berakhir).
        $pastTenants = ResidentHouseBlock::whereIn('house_block_id', $ownedBlockIds)
            ->whereIn('ownership_type', ['kontrak', 'kos'])
            ->whereNotNull('ended_at')
            ->with(['resident', 'houseBlock'])
            ->orderBy('ended_at', 'desc')
            ->get();

        // Penghuni terdaftar yang bisa dijadikan penyewa: aktif & bukan pemilik ybs.
        $availableResidents = Resident::active()
            ->where('id', '!=', $owner->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.penghuni.penyewa-portal', compact('ownedBlocks', 'tenants', 'pastTenants', 'availableResidents'));
    }
}
