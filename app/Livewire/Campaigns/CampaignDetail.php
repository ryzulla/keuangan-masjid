<?php
namespace App\Livewire\Campaigns;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Campaign;
use App\Models\CampaignPhoto;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Resident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class CampaignDetail extends Component
{
    use WithFileUploads, WithPagination;

    public Campaign $campaign;

    // Donation modal
    public bool $isDonationModalOpen = false;
    public ?int $editingDonationId = null;
    public string $donorName = '';
    public string $donorType = 'penghuni';   // penghuni | hamba_allah | luar (default: penghuni)
    public ?int $residentId = null;
    public string $donationForm = 'uang';
    public string $donationDate = '';
    public string $donationAmount = '';
    public ?int $accountId = null;
    public string $donationType = 'infaq';
    public string $itemDescription = '';
    public string $itemQuantity = '';
    public $itemPhoto = null;
    public string $donationNotes = '';

    // Gallery upload modal
    public bool $isGalleryModalOpen = false;
    public $galleryPhoto = null;
    public string $photoCaption = '';

    // Filters
    public string $filterDonationForm = '';
    public string $filterDonorType = '';

    public $orgAccounts = [];
    public $expenseAccounts = [];
    public $residents = [];

    // Expense modal
    public bool $isExpenseModalOpen = false;
    public string $expenseAmount = '';
    public string $expenseDescription = '';
    public string $expenseDate = '';
    public ?int $expenseCategoryId = null;
    public ?int $expenseAccountId = null;

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
        $this->donationDate = now()->format('Y-m-d');
        $this->expenseDate = now()->format('Y-m-d');
        $this->orgAccounts = Account::byOrg($campaign->organization_type)->orderBy('name')->get();
        $this->expenseAccounts = Account::byOrg($campaign->organization_type)->orderBy('name')->get();
        $this->residents = Resident::orderBy('name')->get();

        $catName = $campaign->organization_type === 'perumahan'
            ? 'Pengeluaran Program Perumahan'
            : 'Pengeluaran Program';
        $this->expenseCategoryId = Category::where('name', $catName)
            ->where('organization_type', $campaign->organization_type)
            ->where('type', 'expense')
            ->value('id');
    }

    public function render()
    {
        $this->campaign->load([
            'photos',
            'sourceAccount',
            'expenseTransactions.category',
            'expenseTransactions.account',
        ]);

        $donationsQuery = Donation::with(['resident', 'transaction'])
            ->where('campaign_id', $this->campaign->id)
            ->when($this->filterDonationForm, fn($q) => $q->where('donation_form', $this->filterDonationForm))
            ->when($this->filterDonorType, fn($q) => $q->where('donor_type', $this->filterDonorType))
            ->latest();

        $donations = $donationsQuery->paginate(15);

        $cid = $this->campaign->id;
        $stats = [
            'total_uang'   => Donation::where('donations.campaign_id', $cid)
                ->where('donations.donation_form', 'uang')
                ->join('transactions', 'donations.transaction_id', '=', 'transactions.id')
                ->sum('transactions.amount'),
            'count_uang'   => Donation::where('campaign_id', $cid)->where('donation_form', 'uang')->count(),
            'count_barang' => Donation::where('campaign_id', $cid)->where('donation_form', 'barang')->count(),
            'count_warga'  => Donation::where('campaign_id', $cid)->where('donor_type', 'warga')->count(),
            'count_luaran' => Donation::where('campaign_id', $cid)->where('donor_type', 'luaran')->count(),
        ];

        $expenses = $this->campaign->expenseTransactions->sortByDesc('transaction_date');
        $totalExpense = $expenses->sum('amount');

        return view('livewire.campaigns.campaign-detail', [
            'donations'    => $donations,
            'stats'        => $stats,
            'expenses'     => $expenses,
            'totalExpense' => $totalExpense,
        ]);
    }

    public function openAddDonation(): void
    {
        $this->editingDonationId = null;
        $this->donorName         = '';
        $this->donorType         = 'penghuni';
        $this->residentId        = null;
        $this->donationForm      = 'uang';
        $this->donationDate      = now()->format('Y-m-d');
        $this->donationAmount    = '';
        $this->accountId         = null;
        $this->donationType      = 'infaq';
        $this->itemDescription   = '';
        $this->itemQuantity      = '';
        $this->itemPhoto         = null;
        $this->donationNotes     = '';
        $this->resetErrorBag();
        $this->isDonationModalOpen = true;
    }

    public function updatedDonorType(string $value): void
    {
        if ($value !== 'penghuni') $this->residentId = null;
    }

    public function updatedResidentId($value): void
    {
        if ($value) {
            $resident = Resident::find($value);
            if ($resident) $this->donorName = $resident->name;
        }
    }

    public function saveDonation(): void
    {
        $rules = [
            'donorType'     => 'required|in:penghuni,hamba_allah,luar',
            'residentId'    => 'nullable|exists:residents,id',
            'donationForm'  => 'required|in:uang,barang',
            'donationDate'  => 'required|date',
            'donationNotes' => 'nullable|string|max:500',
        ];
        // Nama/penghuni wajib sesuai asal donatur
        if ($this->donorType === 'penghuni') {
            $rules['residentId'] = 'required|exists:residents,id';
        } elseif ($this->donorType === 'luar') {
            $rules['donorName'] = 'required|string|max:150';
        }
        if ($this->donationForm === 'uang') {
            $rules['donationAmount'] = 'required|numeric|min:1';
            $rules['accountId']      = 'required|exists:accounts,id';
            $rules['donationType']   = 'required|string';
        } else {
            $rules['itemDescription'] = 'required|string|max:255';
            $rules['itemQuantity']    = 'required|string|max:100';
            $rules['itemPhoto']       = 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072';
        }
        $this->validate($rules);

        // Resolusi asal donatur → kolom donations
        if ($this->donorType === 'penghuni') {
            $resident        = Resident::find($this->residentId);
            $finalDonorName  = $resident?->name ?? 'Penghuni';
            $dbDonorType     = 'warga';
            $finalResidentId = $this->residentId ?: null;
        } elseif ($this->donorType === 'hamba_allah') {
            $finalDonorName  = 'Hamba Allah';
            $dbDonorType     = 'luaran';
            $finalResidentId = null;
        } else {
            $finalDonorName  = $this->donorName;
            $dbDonorType     = 'luaran';
            $finalResidentId = null;
        }

        try {
            DB::beginTransaction();

            $transactionId = null;
            $itemPhotoPath = null;

            if ($this->donationForm === 'uang') {
                $categoryId  = $this->campaign->organization_type === 'perumahan' ? 13 : 4;
                $transaction = Transaction::create([
                    'account_id'       => $this->accountId,
                    'category_id'      => $categoryId,
                    'type'             => 'debit', // debit = pemasukan (enum transactions: debit/credit)
                    'amount'           => (float) $this->donationAmount,
                    'transaction_date' => $this->donationDate,
                    'description'      => 'Donasi dari ' . $finalDonorName . ' untuk ' . $this->campaign->name
                        . ($this->donationNotes ? ' — ' . $this->donationNotes : ''),
                    'user_id' => Auth::id(),
                ]);
                $transactionId = $transaction->id;
            } else {
                if ($this->itemPhoto) {
                    $path = 'donation_items/' . date('Y/m');
                    $safe = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $this->itemPhoto->getClientOriginalName());
                    $itemPhotoPath = $this->itemPhoto->storeAs($path, uniqid() . '-' . $safe, 'public');
                }
            }

            Donation::create([
                'transaction_id'   => $transactionId,
                'donor_name'       => $finalDonorName,
                'donor_type'       => $dbDonorType,
                'resident_id'      => $finalResidentId,
                'campaign_id'      => $this->campaign->id,
                'donation_form'    => $this->donationForm,
                'type'             => $this->donationForm === 'uang' ? $this->donationType : 'barang',
                'item_description' => $this->donationForm === 'barang' ? $this->itemDescription : null,
                'item_quantity'    => $this->donationForm === 'barang' ? $this->itemQuantity : null,
                'item_photo_path'  => $itemPhotoPath,
            ]);

            DB::commit();
            session()->flash('success', 'Donasi berhasil dicatat.');
            $this->isDonationModalOpen = false;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving donation: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan donasi: ' . $e->getMessage());
        }
    }

    public function confirmDeleteDonation(int $id): void
    {
        $this->dispatch('show-donation-delete', id: $id);
    }

    public function deleteDonation(int $id): void
    {
        try {
            DB::beginTransaction();
            $donation = Donation::findOrFail($id);
            if ($donation->item_photo_path) Storage::disk('public')->delete($donation->item_photo_path);
            if ($donation->transaction_id) $donation->transaction()->delete();
            $donation->delete();
            DB::commit();
            session()->flash('success', 'Donasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting donation: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus donasi: ' . $e->getMessage());
        }
    }

    public function openGalleryUpload(): void
    {
        $this->galleryPhoto = null;
        $this->photoCaption = '';
        $this->resetErrorBag();
        $this->isGalleryModalOpen = true;
    }

    public function saveGalleryPhoto(): void
    {
        $this->validate([
            'galleryPhoto' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'photoCaption' => 'nullable|string|max:255',
        ]);
        try {
            $path   = 'campaign_gallery/' . $this->campaign->id;
            $safe   = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $this->galleryPhoto->getClientOriginalName());
            $stored = $this->galleryPhoto->storeAs($path, uniqid() . '-' . $safe, 'public');
            CampaignPhoto::create([
                'campaign_id' => $this->campaign->id,
                'photo_path'  => $stored,
                'caption'     => $this->photoCaption ?: null,
                'sort_order'  => (CampaignPhoto::where('campaign_id', $this->campaign->id)->max('sort_order') ?? 0) + 1,
            ]);
            $this->isGalleryModalOpen = false;
            $this->galleryPhoto       = null;
            $this->photoCaption       = '';
            session()->flash('success', 'Foto berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error saving gallery photo: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal upload foto: ' . $e->getMessage());
        }
    }

    public function deletePhoto(int $id): void
    {
        $photo = CampaignPhoto::findOrFail($id);
        if ($photo->campaign_id !== $this->campaign->id) return;
        Storage::disk('public')->delete($photo->photo_path);
        $photo->delete();
        session()->flash('success', 'Foto dihapus.');
    }

    public function closeDonationModal(): void
    {
        $this->isDonationModalOpen = false;
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    public function openAddExpense(): void
    {
        $this->resetErrorBag();
        $this->expenseAmount      = '';
        $this->expenseDescription = '';
        $this->expenseDate        = now()->format('Y-m-d');
        $this->expenseAccountId   = $this->expenseAccounts->first()?->id;
        $this->isExpenseModalOpen = true;

        $catName = $this->campaign->organization_type === 'perumahan'
            ? 'Pengeluaran Program Perumahan'
            : 'Pengeluaran Program';
        $this->expenseCategoryId = Category::where('name', $catName)
            ->where('organization_type', $this->campaign->organization_type)
            ->where('type', 'expense')
            ->value('id');
    }

    public function saveExpense(): void
    {
        $this->validate([
            'expenseAmount'      => 'required|numeric|min:1',
            'expenseDescription' => 'required|string|max:500',
            'expenseDate'        => 'required|date',
            'expenseCategoryId'  => 'required|exists:categories,id',
            'expenseAccountId'   => 'required|exists:accounts,id',
        ]);

        try {
            DB::beginTransaction();

            Transaction::create([
                'type'             => 'credit',
                'amount'           => (float) $this->expenseAmount,
                'account_id'       => $this->expenseAccountId,
                'category_id'      => $this->expenseCategoryId,
                'description'      => $this->expenseDescription,
                'transaction_date' => $this->expenseDate,
                'user_id'          => Auth::id(),
                'campaign_id'      => $this->campaign->id,
            ]);

            DB::commit();
            session()->flash('success', 'Pengeluaran berhasil dicatat.');
            $this->isExpenseModalOpen = false;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving expense: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan pengeluaran: ' . $e->getMessage());
        }
    }

    public function confirmDeleteExpense(int $id): void
    {
        $this->dispatch('show-expense-delete', id: $id);
    }

    public function deleteExpense(int $id): void
    {
        try {
            DB::beginTransaction();
            $tx = Transaction::where('campaign_id', $this->campaign->id)
                ->where('id', $id)
                ->where('type', 'credit')
                ->firstOrFail();
            $tx->delete();
            DB::commit();
            session()->flash('success', 'Pengeluaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting expense: ' . $e->getMessage());
            session()->flash('error', 'Gagal menghapus pengeluaran: ' . $e->getMessage());
        }
    }

    public function closeExpenseModal(): void
    {
        $this->isExpenseModalOpen = false;
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    public function closeGalleryModal(): void
    {
        $this->isGalleryModalOpen = false;
        $this->galleryPhoto       = null;
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    public function updatedFilterDonationForm(): void { $this->resetPage(); }
    public function updatedFilterDonorType(): void    { $this->resetPage(); }
}
