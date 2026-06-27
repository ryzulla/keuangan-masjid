<?php
namespace App\Livewire\Campaigns;

use Livewire\Component;
use App\Models\Campaign;
use App\Models\Account;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;

#[Layout('layouts.app')]
class ManageCampaigns extends Component
{
    use WithPagination, WithFileUploads;

    public string $activeOrgTab = 'dkm';

    public $name;
    public string $organization_type = 'dkm';
    #[Rule('nullable|string')]
    public $description = '';
    public $target_amount;
    public $start_date;
    public $end_date;
    public $status = 'active';
    public $image = null;
    public $existingImage = null;
    public ?int $source_account_id = null;

    public $selected_id;
    public $isModalOpen = false;

    public $dkmAccounts = [];
    public $perumahanAccounts = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'organization_type' => 'required|in:perumahan,dkm',
            'description' => 'nullable|string',
            'target_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,completed,cancelled',
            'source_account_id' => 'nullable|exists:accounts,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function mount(): void
    {
        $this->activeOrgTab = request()->query('org', 'dkm');
        $this->organization_type = $this->activeOrgTab;
        $this->start_date = now()->format('Y-m-d');
        $this->dkmAccounts = Account::byOrg('dkm')->orderBy('name')->get();
        $this->perumahanAccounts = Account::byOrg('perumahan')->orderBy('name')->get();
    }

    public function render()
    {
        $campaigns = Campaign::withSum('transactions', 'amount')
            ->where('organization_type', $this->activeOrgTab)
            ->latest('start_date')
            ->paginate(10);

        $sourceAccounts = $this->organization_type === 'perumahan' ? $this->perumahanAccounts : $this->dkmAccounts;

        return view('livewire.campaigns.manage-campaigns', [
            'campaigns' => $campaigns,
            'sourceAccounts' => $sourceAccounts,
        ]);
    }

    public function switchTab(string $tab): void
    {
        $this->activeOrgTab = $tab;
        $this->resetPage();
    }

    public function updatedImage(): void
    {
        $this->validateOnly('image');
        $this->existingImage = null;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->organization_type = $this->activeOrgTab;
        $this->dispatch('resetCkEditorContent', editorId: 'campaignDescriptionCreate');
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $this->selected_id = $id;
            $this->name = $campaign->name;
            $this->organization_type = $campaign->organization_type;
            $this->description = $campaign->description ?? '';
            $this->target_amount = $campaign->target_amount ?? null;
            $this->start_date = optional($campaign->start_date)->format('Y-m-d');
            $this->end_date = optional($campaign->end_date)->format('Y-m-d');
            $this->status = $campaign->status;
            $this->existingImage = $campaign->image;
            $this->source_account_id = $campaign->source_account_id;
            $this->image = null;
            $this->isModalOpen = true;
            $this->resetErrorBag();
            session()->forget('modal_error');
        } catch (\Exception $e) {
            Log::error('Error opening campaign edit modal: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat data program.');
        }
    }

    public function store(): void
    {
        $validatedData = $this->validate();
        try {
            DB::beginTransaction();

            $imagePath = $this->existingImage;

            if ($this->image) {
                if ($this->selected_id && $this->existingImage) {
                    Storage::disk('public')->delete($this->existingImage);
                }
                $filename = $this->image->getClientOriginalName();
                $path = 'campaign_images/' . date('Y/m');
                $safeFilename = preg_replace('/[^A-Za-z0-9\.\-\_]/', '_', $filename);
                $imagePath = $this->image->storeAs($path, uniqid() . '-' . $safeFilename, 'public');
            }

            $campaignData = [
                'name' => $validatedData['name'],
                'organization_type' => $validatedData['organization_type'],
                'source_account_id' => $validatedData['source_account_id'] ?? null,
                'description' => $this->description ?? '',
                'target_amount' => $validatedData['target_amount'] ?? null,
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'] ?? null,
                'status' => $validatedData['status'],
                'image' => $imagePath,
            ];

            if ($this->selected_id && $this->existingImage === null && !$this->image) {
                $oldCampaign = Campaign::find($this->selected_id);
                if ($oldCampaign?->image) {
                    Storage::disk('public')->delete($oldCampaign->image);
                }
                $campaignData['image'] = null;
            }

            Campaign::updateOrCreate(['id' => $this->selected_id], $campaignData);

            DB::commit();
            session()->flash('success', $this->selected_id ? 'Program berhasil diperbarui.' : 'Program berhasil dibuat.');
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving campaign: ' . $e->getMessage());
            if (isset($imagePath) && $this->image && $imagePath !== $this->existingImage) {
                Storage::disk('public')->delete($imagePath);
            }
            session()->flash('modal_error', 'Gagal menyimpan program: ' . $e->getMessage());
        }
    }

    public function removeImage(): void
    {
        $this->existingImage = null;
        $this->image = null;
        $this->resetErrorBag('image');
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('show-campaign-delete-confirmation', id: $id);
    }

    public function delete(int $id): void
    {
        try {
            DB::beginTransaction();
            $campaign = Campaign::withCount('donations')->find($id);
            if (!$campaign) throw new \Exception('Program tidak ditemukan.');
            if ($campaign->donations_count > 0) throw new \Exception('Program ini sudah memiliki donasi terkait.');

            $imageToDelete = $campaign->image;
            $campaign->delete();

            if ($imageToDelete) Storage::disk('public')->delete($imageToDelete);

            DB::commit();
            $this->dispatch('campaignDeleted');
            session()->flash('success', 'Program berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('deleteFailed', message: $e->getMessage());
        }
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'description', 'target_amount', 'end_date', 'selected_id', 'image', 'existingImage', 'source_account_id']);
        $this->description = '';
        $this->start_date = now()->format('Y-m-d');
        $this->status = 'active';
        $this->organization_type = $this->activeOrgTab;
        $this->resetErrorBag();
        session()->forget('modal_error');
        $this->dispatch('resetCkEditorContent', editorId: 'campaignDescriptionCreate');
    }
}
