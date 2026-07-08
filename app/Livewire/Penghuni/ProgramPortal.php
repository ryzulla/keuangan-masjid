<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\Campaign;
use App\Models\ResidentPaymentRequest;
use App\Models\User;
use App\Notifications\ResidentPaymentSubmitted;

#[Layout('layouts.penghuni')]
class ProgramPortal extends Component
{
    use WithFileUploads;

    // Quick donate from list (without going to detail page)
    public bool   $isDonateModalOpen  = false;
    public ?int   $donatingCampaignId = null;

    public string $donorName     = '';
    public string $amount        = '';
    public string $paymentMethod = 'transfer';
    public string $bankName      = '';
    public string $referenceNum  = '';
    public        $proofPhoto    = null;
    public string $notes         = '';

    public function openDonate(int $campaignId): void
    {
        $resident = Auth::guard('resident')->user();
        $this->donatingCampaignId = $campaignId;
        $this->donorName          = $resident->name;
        $this->amount             = '';
        $this->paymentMethod      = 'transfer';
        $this->bankName           = '';
        $this->referenceNum       = '';
        $this->proofPhoto         = null;
        $this->notes              = '';
        $this->isDonateModalOpen  = true;
    }

    public function submitDonation(): void
    {
        $this->validate([
            'donorName'     => 'required|string|max:255',
            'amount'        => 'required|numeric|min:1000',
            'paymentMethod' => 'required|in:cash,transfer,other',
            'bankName'      => 'nullable|string|max:100',
            'referenceNum'  => 'nullable|string|max:100',
            'proofPhoto'    => 'nullable|image|max:3072',
            'notes'         => 'nullable|string|max:500',
        ]);

        $photoPath = null;
        if ($this->proofPhoto) {
            $photoPath = $this->proofPhoto->store('payment-proofs', 'public');
        }

        $resident = Auth::guard('resident')->user();
        $block    = $resident->currentAssignments()->with('houseBlock')->first()?->houseBlock;

        ResidentPaymentRequest::create([
            'resident_id'      => $resident->id,
            'type'             => 'donation',
            'campaign_id'      => $this->donatingCampaignId,
            'amount'           => (float) $this->amount,
            'donor_name'       => $this->donorName,
            'payment_method'   => $this->paymentMethod,
            'bank_name'        => $this->bankName ?: null,
            'reference_number' => $this->referenceNum ?: null,
            'proof_photo'      => $photoPath,
            'notes'            => $this->notes ?: null,
            'status'           => 'pending',
        ]);

        try {
            $admins = User::whereIn('role', ['super_admin', 'admin', 'perumahan', 'ketua_dkm'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new ResidentPaymentSubmitted(
                    $resident->name, 'donation', (float) $this->amount, $block?->block_code,
                ));
            }
        } catch (\Exception) {}

        $this->isDonateModalOpen = false;
        session()->flash('success', 'Donasi berhasil dikirim! Tim kami akan memproses dan mengkonfirmasi pembayaran Anda.');
    }

    public function render()
    {
        $campaigns = Campaign::where('status', 'active')
            ->with(['donations.transaction', 'residentPaymentRequests' => fn($q) => $q->where('status', 'confirmed')])
            ->latest()
            ->get();

        return view('livewire.penghuni.program-portal', compact('campaigns'));
    }
}
