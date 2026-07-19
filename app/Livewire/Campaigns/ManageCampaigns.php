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

    /** Org yang boleh diurus user (dkm/perumahan) sesuai gate program-nya. */
    private function allowedOrgs(): array
    {
        $u = auth()->user();
        $orgs = [];
        if ($u->can('manage-programs-dkm')) $orgs[] = 'dkm';
        if ($u->can('manage-programs-perumahan')) $orgs[] = 'perumahan';
        return $orgs;
    }

    private function canOrg(string $org): bool
    {
        return in_array($org, $this->allowedOrgs(), true);
    }

    public function mount(): void
    {
        $allowed = $this->allowedOrgs();
        abort_if(empty($allowed), 403);

        $requested = request()->query('org', 'dkm');
        $this->activeOrgTab = in_array($requested, $allowed, true) ? $requested : $allowed[0];
    }

    public function render()
    {
        $campaigns = Campaign::with('photos')->withSum('transactions', 'amount')
            ->where('organization_type', $this->activeOrgTab)
            ->latest('start_date')
            ->paginate(12);

        return view('livewire.campaigns.manage-campaigns', [
            'campaigns' => $campaigns,
            'allowedOrgs' => $this->allowedOrgs(),
        ]);
    }

    public function switchTab(string $tab): void
    {
        if (! $this->canOrg($tab)) return;
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
            if (! $this->canOrg($campaign->organization_type)) throw new \Exception('Anda tidak berwenang atas program ini.');
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
