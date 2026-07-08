<?php
namespace App\Livewire\Campaigns;

use Livewire\Component;
use App\Models\Campaign;
use App\Models\CampaignPhoto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class ManageCampaigns extends Component
{
    use WithPagination;

    public string $activeOrgTab = 'dkm';

    public function mount(): void
    {
        $this->activeOrgTab = request()->query('org', 'dkm');
    }

    public function render()
    {
        $campaigns = Campaign::with('photos')->withSum('transactions', 'amount')
            ->where('organization_type', $this->activeOrgTab)
            ->latest('start_date')
            ->paginate(12);

        return view('livewire.campaigns.manage-campaigns', [
            'campaigns' => $campaigns,
        ]);
    }

    public function switchTab(string $tab): void
    {
        $this->activeOrgTab = $tab;
        $this->resetPage();
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
            foreach ($campaign->photos as $photo) {
                Storage::disk('public')->delete($photo->photo_path);
            }
            $campaign->photos()->delete();
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
}
