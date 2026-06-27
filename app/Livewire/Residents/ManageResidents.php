<?php
namespace App\Livewire\Residents;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Resident;
use App\Models\HouseBlock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class ManageResidents extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterBlock = '';
    public string $filterOwnership = '';
    public string $filterOccupancy = '';

    public bool $isModalOpen = false;
    public bool $isDetailOpen = false;
    public ?int $selected_id = null;
    public ?int $detail_id = null;

    public ?int $house_block_id = null;
    public string $name = '';
    public string $nik = '';
    public string $phone = '';
    public string $whatsapp = '';
    public string $email = '';
    public string $ownership_status = 'pemilik';
    public string $occupancy_status = 'dihuni';
    public string $resident_since = '';
    public string $notes = '';
    public bool $is_active = true;

    public $houseBlocks = [];

    protected function rules(): array
    {
        return [
            'house_block_id' => 'nullable|exists:house_blocks,id',
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'ownership_status' => 'required|in:pemilik,kontrak,kos',
            'occupancy_status' => 'required|in:dihuni,kosong',
            'resident_since' => 'nullable|date',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }

    public function mount(): void
    {
        $this->houseBlocks = HouseBlock::orderBy('block_letter')->orderBy('unit_number')->get();
        $this->resident_since = now()->format('Y-m-d');
    }

    public function render()
    {
        $residents = Resident::query()
            ->with('houseBlock')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->filterBlock, fn($q) => $q->where('house_block_id', $this->filterBlock))
            ->when($this->filterOwnership, fn($q) => $q->where('ownership_status', $this->filterOwnership))
            ->when($this->filterOccupancy, fn($q) => $q->where('occupancy_status', $this->filterOccupancy))
            ->orderBy('name')
            ->paginate(15);

        $detailResident = $this->detail_id ? Resident::with(['houseBlock', 'iplBillings.period'])->find($this->detail_id) : null;

        return view('livewire.residents.manage-residents', [
            'residents' => $residents,
            'detailResident' => $detailResident,
        ]);
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFilterBlock(): void { $this->resetPage(); }
    public function updatedFilterOwnership(): void { $this->resetPage(); }
    public function updatedFilterOccupancy(): void { $this->resetPage(); }

    public function create(): void
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        $resident = Resident::findOrFail($id);
        $this->selected_id = $id;
        $this->house_block_id = $resident->house_block_id;
        $this->name = $resident->name;
        $this->nik = $resident->nik ?? '';
        $this->phone = $resident->phone ?? '';
        $this->whatsapp = $resident->whatsapp ?? '';
        $this->email = $resident->email ?? '';
        $this->ownership_status = $resident->ownership_status;
        $this->occupancy_status = $resident->occupancy_status;
        $this->resident_since = $resident->resident_since?->format('Y-m-d') ?? '';
        $this->notes = $resident->notes ?? '';
        $this->is_active = $resident->is_active;
        $this->resetErrorBag();
        $this->isModalOpen = true;
    }

    public function showDetail(int $id): void
    {
        $this->detail_id = $id;
        $this->isDetailOpen = true;
    }

    public function store(): void
    {
        $validated = $this->validate();
        try {
            DB::beginTransaction();
            Resident::updateOrCreate(
                ['id' => $this->selected_id ?? 0],
                array_merge($validated, ['nik' => $this->nik ?: null])
            );
            DB::commit();
            session()->flash('success', $this->selected_id ? 'Data penghuni berhasil diperbarui.' : 'Data penghuni berhasil ditambahkan.');
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving resident: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('show-resident-delete-confirmation', id: $id);
    }

    public function delete(int $id): void
    {
        try {
            DB::beginTransaction();
            $resident = Resident::findOrFail($id);
            if ($resident->iplBillings()->exists()) {
                throw new \Exception('Penghuni ini memiliki tagihan IPL terkait. Nonaktifkan saja daripada menghapus.');
            }
            $resident->delete();
            DB::commit();
            $this->dispatch('residentDeleted');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('deleteFailed', message: $e->getMessage());
        }
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetForm();
        session()->forget('modal_error');
        $this->resetErrorBag();
    }

    public function closeDetail(): void
    {
        $this->isDetailOpen = false;
        $this->detail_id = null;
    }

    private function resetForm(): void
    {
        $this->reset([
            'selected_id', 'house_block_id', 'name', 'nik', 'phone', 'whatsapp',
            'email', 'notes',
        ]);
        $this->ownership_status = 'pemilik';
        $this->occupancy_status = 'dihuni';
        $this->is_active = true;
        $this->resident_since = now()->format('Y-m-d');
        $this->resetErrorBag();
    }
}
