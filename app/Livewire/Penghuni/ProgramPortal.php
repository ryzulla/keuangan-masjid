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

    public string $donationForm    = 'uang';
    public string $donorType       = 'penghuni';
    public string $donationType    = 'infaq';
    public string $donorName       = '';
    public string $amount          = '';
    public string $paymentMethod   = 'transfer';
    public string $bankName        = '';
    public string $referenceNum    = '';
    public        $proofPhoto      = null;
    public string $itemDescription = '';
    public string $itemQuantity    = '';
    public        $itemPhoto       = null;
    public string $notes           = '';

    public function openDonate(int $campaignId): void
    {
        $resident = Auth::guard('resident')->user();
        $this->donatingCampaignId = $campaignId;
        $this->donationForm       = 'uang';
        $this->donorType          = 'penghuni';
        $this->donationType       = 'infaq';
        $this->donorName          = $resident->name;
        $this->amount             = '';
        $this->paymentMethod      = 'transfer';
        $this->bankName           = '';
        $this->referenceNum       = '';
        $this->proofPhoto         = null;
        $this->itemDescription    = '';
        $this->itemQuantity       = '';
        $this->itemPhoto          = null;
        $this->notes              = '';
        $this->isDonateModalOpen  = true;
    }

    public function updatedDonorType(string $value): void
    {
        $resident = Auth::guard('resident')->user();
        if ($value === 'penghuni') {
            $this->donorName = $resident->name;
        } elseif ($value === 'hamba_allah') {
            $this->donorName = 'Hamba Allah';
        } else {
            $this->donorName = '';
        }
    }

    public function submitDonation(): void
    {
        $rules = [
            'donationForm' => 'required|in:uang,barang',
            'donorType'    => 'required|in:penghuni,hamba_allah,luar',
            'notes'        => 'nullable|string|max:500',
        ];

        if ($this->donorType === 'luar') {
            $rules['donorName'] = 'required|string|max:255';
        }

        if ($this->donationForm === 'uang') {
            $rules['amount']        = 'required|numeric|min:1000';
            $rules['donationType']  = 'required|string|max:50';
            $rules['paymentMethod'] = 'required|in:cash,transfer,other';
            $rules['bankName']      = 'nullable|string|max:100';
            $rules['referenceNum']  = 'nullable|string|max:100';
            $rules['proofPhoto']    = 'nullable|image|max:3072';
        } else {
            $rules['itemDescription'] = 'required|string|max:255';
            $rules['itemQuantity']    = 'required|string|max:100';
            $rules['itemPhoto']       = 'nullable|image|max:3072';
        }

        $this->validate($rules);

        $photoPath = null;
        if ($this->proofPhoto) {
            $photoPath = $this->proofPhoto->store('payment-proofs', 'public');
        }
        if ($this->itemPhoto) {
            $path  = 'donation_items/' . date('Y/m');
            $safe  = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $this->itemPhoto->getClientOriginalName());
            $photoPath = $this->itemPhoto->storeAs($path, uniqid() . '-' . $safe, 'public');
        }

        $resident = Auth::guard('resident')->user();
        $block    = $resident->currentAssignments()->with('houseBlock')->first()?->houseBlock;

        if ($this->donorType === 'penghuni') {
            $finalDonorName = $this->donorName ?: $resident->name;
            $dbDonorType    = 'warga';
        } elseif ($this->donorType === 'hamba_allah') {
            $finalDonorName = 'Hamba Allah';
            $dbDonorType    = 'luaran';
        } else {
            $finalDonorName = $this->donorName;
            $dbDonorType    = 'luaran';
        }

        $payload = [
            'resident_id'      => $resident->id,
            'type'             => 'donation',
            'campaign_id'      => $this->donatingCampaignId,
            'donation_form'    => $this->donationForm,
            'donation_type'    => $this->donationType,
            'donor_name'       => $finalDonorName,
            'donor_type'       => $dbDonorType,
            'amount'           => $this->donationForm === 'uang' ? (float) $this->amount : 0,
            'payment_method'   => $this->donationForm === 'uang' ? $this->paymentMethod : null,
            'bank_name'        => $this->donationForm === 'uang' ? ($this->bankName ?: null) : null,
            'reference_number' => $this->donationForm === 'uang' ? ($this->referenceNum ?: null) : null,
            'proof_photo'      => $this->donationForm === 'uang' ? $photoPath : null,
            'notes'            => $this->notes ?: null,
            'status'           => 'pending',
        ];

        if ($this->donationForm === 'barang') {
            $payload['notes'] = trim(
                ($this->notes ?: '')
                . "\n\n— Barang: " . $this->itemDescription
                . "\n— Jumlah: " . $this->itemQuantity
                . ($photoPath ? "\n— Foto: tersedia" : '')
            );
        }

        ResidentPaymentRequest::create($payload);

        try {
            $admins = User::whereIn('role', ['super_admin', 'admin', 'perumahan', 'ketua_dkm'])->get();
            foreach ($admins as $admin) {
                $admin->notify(new ResidentPaymentSubmitted(
                    $resident->name, 'donation',
                    $this->donationForm === 'uang' ? (float) $this->amount : 0,
                    $block?->block_code,
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
