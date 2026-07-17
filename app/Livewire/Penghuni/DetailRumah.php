<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\HouseBlock;
use App\Models\HouseBlockPhoto;
use App\Models\Resident;
use App\Models\ResidentHouseBlock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

#[Layout('layouts.penghuni')]
class DetailRumah extends Component
{
    use WithFileUploads;

    public ?int $houseBlockId = null;
    public string $activeTab = 'penyewa';

    // ── Listing state ──
    public string  $editDescription  = '';
    public string  $editPrice        = '';
    public string  $editListingType  = 'sewa';
    public string  $editDuration     = 'bulanan';
    public bool    $editIsForRent    = false;
    public string  $editLandArea     = '';
    public string  $editBuildingArea = '';
    public string  $editWaterSource  = 'pdam';
    public string  $editElectricity  = '';
    public string  $editBedrooms     = '';
    public string  $editBathrooms    = '';
    public string  $editGarage       = '';
    public $newPhotos       = [];
    public ?int $deletingPhotoId = null;

    // ── Penyewa state ──
    public bool   $isModalOpen = false;
    public ?int   $editingId   = null;
    public ?int   $tenantHouseBlockId = null;
    public string $tenantMode         = 'baru';
    public ?int   $existingResidentId = null;
    public string $name             = '';
    public string $phone            = '';
    public string $whatsapp         = '';
    public string $contractStart    = '';
    public string $contractEnd      = '';
    public string $monthlyRent      = '';
    public bool   $paysIpl          = false;
    public string $notes            = '';

    protected function rules(): array
    {
        return [
            'activeTab' => 'required|in:penyewa,listing',
            // Listing rules
            'editDescription' => 'nullable|string|max:1000',
            'editPrice'       => 'nullable|numeric|min:0',
            'editListingType' => 'required|in:sewa,jual',
            'editDuration'    => 'required_if:editListingType,sewa|nullable|in:bulanan,6bulan,tahunan',
            'editLandArea'    => 'nullable|numeric|min:0',
            'editBuildingArea'=> 'nullable|numeric|min:0',
            'editWaterSource' => 'required|in:pdam,tanah,both',
            'editElectricity' => 'nullable|integer|min:0',
            'editBedrooms'    => 'nullable|integer|min:0',
            'editBathrooms'   => 'nullable|integer|min:0',
            'newPhotos'       => 'nullable|array|max:6',
            'newPhotos.*'     => 'image|max:2048',
            // Tenant rules
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

    protected $validationAttributes = [
        'name' => 'nama penyewa',
    ];

    public function mount(int $houseBlock): void
    {
        $resident = Auth::guard('resident')->user();
        if (!$resident->isPemilik()) {
            $this->redirect(route('penghuni.dashboard'), navigate: true);
            return;
        }

        $hb = HouseBlock::with('photos')->findOrFail($houseBlock);
        if (!$hb->owners()->where('residents.id', $resident->id)->exists()) {
            $this->redirect(route('penghuni.rumah-saya'), navigate: true);
            return;
        }

        $this->houseBlockId    = $hb->id;
        $this->editDescription = $hb->rental_description ?? '';
        $this->editPrice       = $hb->rental_price ? (string) $hb->rental_price : '';
        $this->editListingType = $hb->listing_type ?? 'sewa';
        $this->editDuration    = $hb->rental_duration ?? 'bulanan';
        $this->editIsForRent   = (bool) $hb->is_for_rent;
        $this->editLandArea    = $hb->land_area ? (string) $hb->land_area : '';
        $this->editBuildingArea= $hb->building_area ? (string) $hb->building_area : '';
        $this->editWaterSource = $hb->water_source ?? 'pdam';
        $this->editElectricity = $hb->electricity ? (string) $hb->electricity : '';
        $this->editBedrooms    = $hb->bedrooms ? (string) $hb->bedrooms : '';
        $this->editBathrooms   = $hb->bathrooms ? (string) $hb->bathrooms : '';
        $this->editGarage      = $hb->garage ? (string) $hb->garage : '';
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    // ═══════════════════════════════════════════════════════════════
    // LISTING METHODS
    // ═══════════════════════════════════════════════════════════════

    public function saveRentalInfo(): void
    {
        $hb = HouseBlock::findOrFail($this->houseBlockId);
        $resident = Auth::guard('resident')->user();
        if (!$hb->owners()->where('residents.id', $resident->id)->exists()) return;

        $this->validate([
            'editDescription' => 'nullable|string|max:1000',
            'editPrice'       => 'nullable|numeric|min:0',
            'editListingType' => 'required|in:sewa,jual',
            'editDuration'    => 'required_if:editListingType,sewa|nullable|in:bulanan,6bulan,tahunan',
            'editLandArea'    => 'nullable|numeric|min:0',
            'editBuildingArea'=> 'nullable|numeric|min:0',
            'editWaterSource' => 'required|in:pdam,tanah,both',
            'editElectricity' => 'nullable|integer|min:0',
            'editBedrooms'    => 'nullable|integer|min:0',
            'editBathrooms'   => 'nullable|integer|min:0',
        ]);

        try {
            $hb->update([
                'is_for_rent'        => $this->editIsForRent,
                'listing_type'       => $this->editListingType,
                'rental_price'       => $this->editPrice !== '' ? (float) $this->editPrice : null,
                'rental_description' => $this->editDescription ?: null,
                'rental_duration'    => $this->editListingType === 'sewa' ? $this->editDuration : null,
                'land_area'          => $this->editLandArea !== '' ? (float) $this->editLandArea : null,
                'building_area'      => $this->editBuildingArea !== '' ? (float) $this->editBuildingArea : null,
                'water_source'       => $this->editWaterSource,
                'electricity'        => $this->editElectricity !== '' ? (int) $this->editElectricity : null,
                'bedrooms'           => $this->editBedrooms !== '' ? (int) $this->editBedrooms : null,
                'bathrooms'          => $this->editBathrooms !== '' ? (int) $this->editBathrooms : null,
                'garage'             => $this->editGarage !== '' ? (int) $this->editGarage : null,
            ]);
            session()->flash('success', 'Informasi listing berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('DetailRumah::saveRentalInfo ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan informasi listing.');
        }
    }

    public function uploadPhotos(): void
    {
        $hb = HouseBlock::findOrFail($this->houseBlockId);
        $resident = Auth::guard('resident')->user();
        if (!$hb->owners()->where('residents.id', $resident->id)->exists()) return;

        $this->validate([
            'newPhotos'   => 'required|array|max:6',
            'newPhotos.*' => 'image|max:2048',
        ]);

        try {
            $existingCount = $hb->photos()->count();
            $totalAfter = $existingCount + count($this->newPhotos);
            if ($totalAfter > 10) {
                $this->addError('newPhotos', 'Maksimal 10 foto per rumah.');
                return;
            }

            foreach ($this->newPhotos as $photo) {
                $path = $photo->store('house-photos', 'public');
                $isFirst = $existingCount === 0 && $photo === reset($this->newPhotos);
                HouseBlockPhoto::create([
                    'house_block_id' => $hb->id,
                    'photo_path'     => $path,
                    'sort_order'     => $existingCount++,
                    'is_primary'     => $isFirst,
                ]);
            }

            $this->newPhotos = [];
            session()->flash('success', 'Foto berhasil diunggah.');
        } catch (\Exception $e) {
            Log::error('DetailRumah::uploadPhotos ' . $e->getMessage());
            session()->flash('error', 'Gagal mengunggah foto.');
        }
    }

    public function setPrimary(int $photoId): void
    {
        $photo = HouseBlockPhoto::findOrFail($photoId);
        $hb = HouseBlock::findOrFail($photo->house_block_id);
        $resident = Auth::guard('resident')->user();
        if (!$hb->owners()->where('residents.id', $resident->id)->exists()) return;

        HouseBlockPhoto::where('house_block_id', $hb->id)
            ->where('is_primary', true)
            ->update(['is_primary' => false]);

        $photo->update(['is_primary' => true]);
    }

    public function confirmDeletePhoto(int $photoId): void
    {
        $this->deletingPhotoId = $photoId;
    }

    public function deletePhoto(): void
    {
        if (!$this->deletingPhotoId) return;

        $photo = HouseBlockPhoto::findOrFail($this->deletingPhotoId);
        $hb = HouseBlock::findOrFail($photo->house_block_id);
        $resident = Auth::guard('resident')->user();
        if (!$hb->owners()->where('residents.id', $resident->id)->exists()) return;

        try {
            Storage::disk('public')->delete($photo->photo_path);

            $wasPrimary = $photo->is_primary;
            $photo->delete();

            if ($wasPrimary) {
                $next = $hb->photos()->first();
                if ($next) {
                    $next->update(['is_primary' => true]);
                }
            }

            $this->deletingPhotoId = null;
            session()->flash('success', 'Foto berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('DetailRumah::deletePhoto ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus foto.');
        }
    }

    public function cancelDeletePhoto(): void
    {
        $this->deletingPhotoId = null;
    }

    // ═══════════════════════════════════════════════════════════════
    // PENYEWA (TENANT) METHODS
    // ═══════════════════════════════════════════════════════════════

    public function openCreateTenant(): void
    {
        $this->editingId          = null;
        $this->tenantHouseBlockId = $this->houseBlockId;
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

    public function openEditTenant(int $assignmentId): void
    {
        $assignment = ResidentHouseBlock::with('resident')
            ->findOrFail($assignmentId);

        $owner = Auth::guard('resident')->user();
        if (!$owner->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->where('house_block_id', $assignment->house_block_id)
            ->exists()) return;

        $this->editingId          = $assignmentId;
        $this->tenantHouseBlockId = $assignment->house_block_id;
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

    public function saveTenant(): void
    {
        $this->validate();

        $owner = Auth::guard('resident')->user();
        if (!$owner->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->where('house_block_id', $this->tenantHouseBlockId)
            ->exists()) return;

        if ($this->editingId) {
            $assignment = ResidentHouseBlock::findOrFail($this->editingId);
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
                $penyewa = Resident::find($this->existingResidentId);
                if (!$penyewa || !$penyewa->is_active) {
                    $this->addError('existingResidentId', 'Penghuni tidak ditemukan atau nonaktif.');
                    return;
                }
                $dup = ResidentHouseBlock::where('resident_id', $penyewa->id)
                    ->where('house_block_id', $this->tenantHouseBlockId)
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

            ResidentHouseBlock::updateOrCreate(
                ['resident_id' => $penyewa->id, 'house_block_id' => $this->tenantHouseBlockId],
                [
                    'ownership_type'      => 'kontrak',
                    'occupancy_status'    => 'dihuni',
                    'resident_since'      => $this->contractStart ?: now()->format('Y-m-d'),
                    'contract_start_date' => $this->contractStart ?: null,
                    'contract_end_date'   => $this->contractEnd ?: null,
                    'monthly_rent'        => $this->monthlyRent ?: null,
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
        $assignment = ResidentHouseBlock::findOrFail($assignmentId);
        if (!$owner->currentAssignments()
            ->where('ownership_type', 'pemilik')
            ->where('house_block_id', $assignment->house_block_id)
            ->exists()) return;

        $assignment->update(['ended_at' => now()]);
        session()->flash('success', 'Kontrak penyewa diakhiri.');
    }

    public function render()
    {
        $hb = HouseBlock::with('photos')->findOrFail($this->houseBlockId);

        $tenants = ResidentHouseBlock::where('house_block_id', $this->houseBlockId)
            ->whereIn('ownership_type', ['kontrak', 'kos'])
            ->whereNull('ended_at')
            ->with('resident')
            ->get();

        $pastTenants = ResidentHouseBlock::where('house_block_id', $this->houseBlockId)
            ->whereIn('ownership_type', ['kontrak', 'kos'])
            ->whereNotNull('ended_at')
            ->with('resident')
            ->orderBy('ended_at', 'desc')
            ->get();

        $availableResidents = Resident::active()
            ->where('id', '!=', Auth::guard('resident')->id())
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.penghuni.detail-rumah', compact('hb', 'tenants', 'pastTenants', 'availableResidents'));
    }
}
