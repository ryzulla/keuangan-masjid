<?php
namespace App\Livewire\Campaigns;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Campaign;
use App\Models\CampaignPhoto;
use App\Models\Account;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class CreateEditCampaign extends Component
{
    use WithFileUploads;

    public ?int $campaignId = null;

    public string $name              = '';
    public string $organization_type = 'dkm';
    public string $description       = '';
    public string $content           = '';
    public string $location          = '';
    public string $videoUrl          = '';
    public string $status            = 'active';
    public ?string $target_amount    = null;
    public string $start_date        = '';
    public string $end_date          = '';
    public ?int   $source_account_id = null;
    public $image                    = null;
    public ?string $existingImage    = null;

    public $dkmAccounts       = [];
    public $perumahanAccounts = [];

    protected function rules(): array
    {
        return [
            'name'              => 'required|string|min:3|max:255',
            'organization_type' => 'required|in:perumahan,dkm',
            'description'       => 'nullable|string',
            'content'           => 'nullable|string',
            'location'          => 'nullable|string|max:255',
            'videoUrl'          => 'nullable|url|max:500',
            'target_amount'     => 'nullable|numeric|min:0',
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'status'            => 'required|in:active,completed,cancelled',
            'source_account_id' => 'nullable|exists:accounts,id',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

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

    public function mount($campaign = null): void
    {
        $this->dkmAccounts       = Account::byOrg('dkm')->orderBy('name')->get();
        $this->perumahanAccounts = Account::byOrg('perumahan')->orderBy('name')->get();
        $this->start_date        = now()->format('Y-m-d');

        // Jika $campaign memiliki nilai (berupa ID dari URL route 'edit')
        if ($campaign) {
            // Cari data campaign ke database secara manual
            $campaignData = \App\Models\Campaign::find($campaign);

            // Jika data ditemukan, masukkan ke public property
            if ($campaignData) {
                $this->campaignId        = $campaignData->id;
                $this->name              = $campaignData->name;
                $this->organization_type = $campaignData->organization_type;
                $this->description       = $campaignData->description ?? '';
                $this->content           = $campaignData->content ?? '';
                $this->location          = $campaignData->location ?? '';
                $this->videoUrl          = $campaignData->video_url ?? '';
                $this->target_amount     = $campaignData->target_amount ? (string)$campaignData->target_amount : null;
                $this->start_date        = optional($campaignData->start_date)->format('Y-m-d') ?? now()->format('Y-m-d');
                $this->end_date          = optional($campaignData->end_date)->format('Y-m-d') ?? '';
                $this->status            = $campaignData->status;
                $this->existingImage     = $campaignData->image;
                $this->source_account_id = $campaignData->source_account_id;
            } else {
                // Opsional: jika ID dikirim tapi data tidak ada di database, lempar 404
                abort(404, 'Data Campaign tidak ditemukan.');
            }
        } else {
            // Jika $campaign kosong (berarti ini halaman 'Create')
            // Ambil org dari query, jatuh ke org yang boleh diurus bila tidak sesuai.
            $allowed = $this->allowedOrgs();
            abort_if(empty($allowed), 403);
            $requested = request()->query('org', 'dkm');
            $this->organization_type = in_array($requested, $allowed, true) ? $requested : $allowed[0];
        }

        // Pastikan user berwenang atas org program ini (DKM vs Perumahan).
        abort_unless($this->canOrg($this->organization_type), 403);
    }

    public function render()
    {
        $sourceAccounts = $this->organization_type === 'perumahan' ? $this->perumahanAccounts : $this->dkmAccounts;
        $existingPhotos = $this->campaignId
            ? CampaignPhoto::where('campaign_id', $this->campaignId)->orderBy('sort_order')->get()
            : collect();

        return view('livewire.campaigns.create-edit-campaign', [
            'sourceAccounts' => $sourceAccounts,
            'existingPhotos' => $existingPhotos,
            'pageTitle'      => $this->campaignId ? 'Edit Program' : 'Buat Program Baru',
        ]);
    }

    public function updatedImage(): void
    {
        $this->validateOnly('image');
        $this->existingImage = null;
    }

    public function removeImage(): void
    {
        $this->existingImage = null;
        $this->image         = null;
        $this->resetErrorBag('image');
    }

    public function deleteGalleryPhoto(int $photoId): void
    {
        $photo = CampaignPhoto::find($photoId);
        if ($photo && $photo->campaign_id == $this->campaignId) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
        }
    }

    public function store(): void
    {
        $validated = $this->validate();

        // Tolak menyimpan program di luar wewenang org user.
        abort_unless($this->canOrg($validated['organization_type']), 403);

        try {
            DB::beginTransaction();

            $imagePath = $this->existingImage;

            if ($this->image) {
                if ($this->campaignId && $this->existingImage) {
                    Storage::disk('public')->delete($this->existingImage);
                }
                $safeFilename = preg_replace('/[^A-Za-z0-9\.\-\_]/', '_', $this->image->getClientOriginalName());
                $imagePath    = $this->image->storeAs('campaign_images/' . date('Y/m'), uniqid() . '-' . $safeFilename, 'public');
            }

            // Handle manual image removal during edit
            if ($this->campaignId && $this->existingImage === null && !$this->image) {
                $old = Campaign::find($this->campaignId);
                if ($old?->image) Storage::disk('public')->delete($old->image);
                $imagePath = null;
            }

            $data = [
                'name'              => $validated['name'],
                'organization_type' => $validated['organization_type'],
                'source_account_id' => $validated['source_account_id'] ?? null,
                'description'       => $this->description ?: null,
                'content'           => $this->content ?: null,
                'location'          => $validated['location'] ?: null,
                'video_url'         => $validated['videoUrl'] ?: null,
                'target_amount'     => $validated['target_amount'] ?? null,
                'start_date'        => $validated['start_date'],
                'end_date'          => $validated['end_date'] ?? null,
                'status'            => $validated['status'],
                'image'             => $imagePath,
            ];

            $campaign = Campaign::updateOrCreate(['id' => $this->campaignId], $data);
            DB::commit();

            session()->flash('success', $this->campaignId ? 'Program berhasil diperbarui.' : 'Program berhasil dibuat.');
            $this->redirect(route('campaigns.index', ['org' => $campaign->organization_type]), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CreateEditCampaign store error: ' . $e->getMessage());
            if (isset($imagePath) && $this->image && $imagePath !== $this->existingImage) {
                Storage::disk('public')->delete($imagePath);
            }
            session()->flash('error', 'Gagal menyimpan program: ' . $e->getMessage());
        }
    }

    public function cancel(): void
    {
        $this->redirect(route('campaigns.index', ['org' => $this->organization_type]), navigate: true);
    }
}
