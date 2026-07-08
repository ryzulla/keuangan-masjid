<?php
namespace App\Livewire\Residents;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Resident;
use App\Models\HouseBlock;
use App\Models\ResidentHouseBlock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class CreateEditResident extends Component
{
    use WithFileUploads;

    public ?int $residentId = null;

    public string $name      = '';
    public string $nik       = '';
    public string $phone     = '';
    public string $whatsapp  = '';
    public string $email     = '';
    public string $notes     = '';
    public bool   $is_active = true;

    public $photo         = null;
    public ?string $existingPhoto = null;

    public array $houseAssignments = [];
    public array $familyMembers    = [];

    public $houseBlocks = [];

    protected function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'nik'      => 'nullable|string|max:20',
            'phone'    => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'notes'    => 'nullable|string',
            'is_active' => 'boolean',
            'photo'    => 'nullable|image|max:2048',

            'houseAssignments'                           => 'array',
            'houseAssignments.*.house_block_id'          => 'nullable|exists:house_blocks,id',
            'houseAssignments.*.ownership_type'          => 'required_with:houseAssignments.*.house_block_id|in:pemilik,kontrak,kos',
            'houseAssignments.*.occupancy_status'        => 'nullable|in:dihuni,kosong',
            'houseAssignments.*.resident_since'          => 'nullable|date',
            'houseAssignments.*.contract_start_date'     => 'nullable|date',
            'houseAssignments.*.contract_end_date'       => 'nullable|date',
            'houseAssignments.*.monthly_rent'            => 'nullable|numeric|min:0',
            'houseAssignments.*.is_primary_residence'    => 'boolean',

            'familyMembers'                => 'array',
            'familyMembers.*.name'         => 'required_with:familyMembers.*.relationship|string|max:255',
            'familyMembers.*.relationship' => 'required|in:istri,suami,anak,orang_tua,mertua,saudara,lainnya',
            'familyMembers.*.gender'       => 'required|in:laki-laki,perempuan',
            'familyMembers.*.nik'          => 'nullable|string|max:20',
            'familyMembers.*.birth_date'   => 'nullable|date',
            'familyMembers.*.notes'        => 'nullable|string',
        ];
    }

    public function mount($resident = null): void
    {
        $this->houseBlocks = HouseBlock::where('is_active', true)
            ->orderBy('block_letter')->orderBy('unit_number')->get();

        if ($resident) {
            // $resident bisa berupa model (route-model binding) atau ID mentah.
            $residentData = $resident instanceof Resident
                ? $resident
                : Resident::find($resident);

            if ($residentData) {
                // Simpan hanya ID (model bertipe tidak aman diserialisasi Livewire).
                $this->residentId = $residentData->id;
                $residentData->load(['currentAssignments.houseBlock', 'familyMembers']);

                $this->name      = $residentData->name;
                $this->nik       = $residentData->nik ?? '';
                $this->phone     = $residentData->phone ?? '';
                $this->whatsapp  = $residentData->whatsapp ?? '';
                $this->email     = $residentData->email ?? '';
                $this->notes     = $residentData->notes ?? '';
                $this->is_active    = $residentData->is_active;
                $this->existingPhoto = $residentData->photo;

                $this->houseAssignments = $residentData->currentAssignments->map(fn($a) => [
                    'house_block_id'       => $a->house_block_id,
                    'ownership_type'       => $a->ownership_type,
                    'occupancy_status'     => $a->occupancy_status ?? 'dihuni',
                    'resident_since'       => $a->resident_since?->format('Y-m-d') ?? now()->format('Y-m-d'),
                    'contract_start_date'  => $a->contract_start_date?->format('Y-m-d') ?? '',
                    'contract_end_date'    => $a->contract_end_date?->format('Y-m-d') ?? '',
                    'monthly_rent'         => $a->monthly_rent ? (string) $a->monthly_rent : '',
                    'is_primary_residence' => (bool) $a->is_primary_residence,
                    'is_ipl_payer'         => (bool) $a->is_ipl_payer,
                ])->toArray();

                $this->familyMembers = $residentData->familyMembers->map(fn($m) => [
                    'id'           => $m->id,
                    'name'         => $m->name,
                    'relationship' => $m->relationship,
                    'gender'       => $m->gender,
                    'nik'          => $m->nik ?? '',
                    'birth_date'   => $m->birth_date?->format('Y-m-d') ?? '',
                    'notes'        => $m->notes ?? '',
                    'sort_order'   => $m->sort_order,
                ])->toArray();
            } else {
                abort(404, 'Data Warga tidak ditemukan.');
            }
        }
    }

    public function render()
    {
        return view('livewire.residents.create-edit-resident', [
            'resident' => $this->currentResident(),
        ]);
    }

    /** Muat ulang model penghuni dari ID tersimpan (null bila mode tambah). */
    protected function currentResident(): ?Resident
    {
        return $this->residentId ? Resident::find($this->residentId) : null;
    }

    // ─── Photo ───

    public function removePhoto(): void
    {
        if ($this->photo) {
            $this->photo = null;
            return;
        }
        if ($this->existingPhoto) {
            Storage::disk('public')->delete($this->existingPhoto);
            $this->currentResident()?->update(['photo' => null]);
            $this->existingPhoto = null;
        }
    }

    // ─── House Assignments ───

    public function addHouseAssignment(): void
    {
        $this->houseAssignments[] = [
            'house_block_id'       => null,
            'ownership_type'       => 'pemilik',
            'occupancy_status'     => 'dihuni',
            'resident_since'       => now()->format('Y-m-d'),
            'contract_start_date'  => '',
            'contract_end_date'    => '',
            'monthly_rent'         => '',
            'is_primary_residence' => count($this->houseAssignments) === 0,
            'is_ipl_payer'         => false,
        ];
    }

    public function removeHouseAssignment(int $index): void
    {
        array_splice($this->houseAssignments, $index, 1);
        $this->houseAssignments = array_values($this->houseAssignments);
    }

    // ─── Family Members ───

    public function addFamilyMember(): void
    {
        $this->familyMembers[] = [
            'id'           => null,
            'name'         => '',
            'relationship' => 'anak',
            'gender'       => 'laki-laki',
            'nik'          => '',
            'birth_date'   => '',
            'notes'        => '',
            'sort_order'   => count($this->familyMembers),
        ];
    }

    public function removeFamilyMember(int $index): void
    {
        array_splice($this->familyMembers, $index, 1);
        $this->familyMembers = array_values($this->familyMembers);
    }

    // ─── Save ───

    public function save(): void
    {
        $validated = $this->validate();

        // Cegah DOUBLE PEMILIK: satu rumah hanya boleh punya satu pemilik aktif.
        $existing   = $this->currentResident();
        $residentId = $existing?->id;
        $ownerBlocksInForm = [];
        foreach ($this->houseAssignments as $i => $a) {
            if (empty($a['house_block_id']) || ($a['ownership_type'] ?? '') !== 'pemilik') {
                continue;
            }
            $blockId = $a['house_block_id'];

            // Duplikat dalam form yang sama
            if (in_array($blockId, $ownerBlocksInForm)) {
                $this->addError("houseAssignments.$i.ownership_type", 'Blok ini didaftarkan sebagai pemilik lebih dari sekali.');
                return;
            }
            $ownerBlocksInForm[] = $blockId;

            // Pemilik aktif lain (penghuni berbeda) untuk blok yang sama.
            // Penghuni yang sudah dinonaktifkan tidak dihitung sebagai pemilik aktif.
            $conflict = ResidentHouseBlock::where('house_block_id', $blockId)
                ->where('ownership_type', 'pemilik')
                ->whereNull('ended_at')
                ->when($residentId, fn($q) => $q->where('resident_id', '!=', $residentId))
                ->whereHas('resident', fn($q) => $q->where('is_active', true))
                ->with('resident')
                ->first();
            if ($conflict) {
                $block = HouseBlock::find($blockId);
                $this->addError("houseAssignments.$i.ownership_type",
                    'Blok ' . ($block?->block_code ?? '') . ' sudah memiliki pemilik aktif (' .
                    ($conflict->resident?->name ?? 'penghuni lain') . '). Akhiri kepemilikan sebelumnya terlebih dahulu.');
                return;
            }
        }

        try {
            DB::beginTransaction();

            $photoPath = $this->existingPhoto;
            if ($this->photo) {
                if ($this->existingPhoto) {
                    Storage::disk('public')->delete($this->existingPhoto);
                }
                $photoPath = $this->photo->store('residents', 'public');
            }

            $data = [
                'name'      => $validated['name'],
                'photo'     => $photoPath,
                'nik'       => $this->nik ?: null,
                'phone'     => $this->phone ?: null,
                'whatsapp'  => $this->whatsapp ?: null,
                'email'     => $this->email ?: null,
                'notes'     => $this->notes ?: null,
                'is_active' => $this->is_active,
            ];

            if ($existing) {
                $existing->update($data);
                $resident = $existing;
            } else {
                $resident = Resident::create($data);
                $this->residentId = $resident->id;
            }

            // Sinkronkan kepemilikan/kontrak. Tabel punya UNIQUE(resident_id, house_block_id),
            // jadi baris DIPAKAI ULANG (updateOrCreate) — bukan dibuat baru — agar tidak
            // melanggar constraint saat blok yang sama pernah ditugaskan lalu diakhiri.
            $formBlockIds = collect($this->houseAssignments)
                ->pluck('house_block_id')->filter()
                ->map(fn($v) => (int) $v)->unique()->values()->toArray();

            // Akhiri assignment aktif yang bloknya tidak lagi ada di form.
            ResidentHouseBlock::where('resident_id', $resident->id)
                ->whereNull('ended_at')
                ->when($formBlockIds, fn($q) => $q->whereNotIn('house_block_id', $formBlockIds))
                ->update(['ended_at' => now()]);

            foreach ($this->houseAssignments as $assignment) {
                if (empty($assignment['house_block_id'])) continue;
                ResidentHouseBlock::updateOrCreate(
                    ['resident_id' => $resident->id, 'house_block_id' => (int) $assignment['house_block_id']],
                    [
                        'ownership_type'       => $assignment['ownership_type'],
                        'occupancy_status'     => $assignment['occupancy_status'] ?? 'dihuni',
                        'resident_since'       => $assignment['resident_since'] ?: null,
                        'contract_start_date'  => ($assignment['contract_start_date'] ?? '') ?: null,
                        'contract_end_date'    => ($assignment['contract_end_date'] ?? '') ?: null,
                        'monthly_rent'         => ($assignment['monthly_rent'] ?? '') ? (float)$assignment['monthly_rent'] : null,
                        'is_primary_residence' => !empty($assignment['is_primary_residence']),
                        'is_ipl_payer'         => !empty($assignment['is_ipl_payer']),
                        'ended_at'             => null,
                    ]
                );
            }

            // Sync family members
            $keepIds = collect($this->familyMembers)->pluck('id')->filter()->values()->toArray();
            $resident->familyMembers()->whereNotIn('id', $keepIds)->delete();
            foreach ($this->familyMembers as $i => $member) {
                if (empty(trim($member['name'] ?? ''))) continue;
                $resident->familyMembers()->updateOrCreate(
                    ['id' => $member['id'] ?? 0],
                    [
                        'name'         => $member['name'],
                        'relationship' => $member['relationship'],
                        'gender'       => $member['gender'],
                        'nik'          => ($member['nik'] ?? '') ?: null,
                        'birth_date'   => ($member['birth_date'] ?? '') ?: null,
                        'notes'        => ($member['notes'] ?? '') ?: null,
                        'sort_order'   => $i,
                    ]
                );
            }

            DB::commit();
            session()->flash('success', 'Data penghuni berhasil disimpan.');
            $this->redirect(route('residents.show', $resident), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CreateEditResident::save ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function cancel(): void
    {
        if ($this->residentId) {
            $this->redirect(route('residents.show', $this->residentId), navigate: true);
        } else {
            $this->redirect(route('residents.index'), navigate: true);
        }
    }
}
