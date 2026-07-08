<?php
namespace App\Livewire\Transactions;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use App\Models\Campaign;
use App\Models\IplPayment;
use App\Models\IplPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class TransaksiPerumahan extends Component
{
    use WithPagination;

    // --- Filter ---
    public string $filterAccount = '';
    public string $filterType    = '';
    public string $startDate     = '';
    public string $endDate       = '';
    public string $filterPeriodId = '';

    // --- Modal ---
    public bool $isModalOpen = false;
    public ?int $selectedId  = null;

    // Form fields
    public string $type            = 'debit';
    public string $amount          = '';
    public string $description     = '';
    public string $accountId       = '';
    public string $categoryId      = '';
    public string $transactionDate = '';
    public ?int   $campaignId      = null;
    public bool   $showCampaignDropdown = false;

    // Loaded data
    public $perumahanAccounts   = [];   // all — for expense & filter
    public $incomeAccounts      = [];   // Kas RT only — for income entries
    public $modalCategories     = [];
    public $availableCampaigns  = [];   // perumahan + dkm active campaigns

    const PROGRAM_EXPENSE_CATEGORY_NAMES = ['Pengeluaran Program Perumahan'];

    protected function rules(): array
    {
        return [
            'type'            => 'required|in:debit,credit',
            'amount'          => 'required|numeric|min:1',
            'description'     => 'required|string|max:255',
            'accountId'       => 'required|exists:accounts,id',
            'categoryId'      => ['required', Rule::exists('categories', 'id')],
            'transactionDate' => 'required|date',
            'campaignId'      => [Rule::requiredIf($this->showCampaignDropdown), 'nullable', 'exists:campaigns,id'],
        ];
    }

    public function mount(): void
    {
        $this->startDate       = now()->startOfMonth()->format('Y-m-d');
        $this->endDate         = now()->format('Y-m-d');
        $this->transactionDate = now()->format('Y-m-d');
        $this->perumahanAccounts = Account::byOrg('perumahan')->orderBy('name')->get();
        // Income entries: Keamanan & Kebersihan already recorded via IPL — only Kas RT can receive misc income
        $this->incomeAccounts = Account::byOrg('perumahan')
            ->where('name', 'not like', '%Keamanan%')
            ->where('name', 'not like', '%Kebersihan%')
            ->orderBy('name')->get();
        $this->loadModalCategories();
        // Campaigns: include both perumahan and DKM so expense can fund Masjid programs too
        $this->availableCampaigns = Campaign::whereIn('organization_type', ['perumahan', 'dkm'])
            ->where('status', 'active')->orderBy('organization_type')->orderBy('name')->get();
    }

    public function render()
    {
        $perumahanAccountIds = Account::byOrg('perumahan')->pluck('id');

        $query = Transaction::with(['account', 'category', 'campaign'])
            ->whereIn('account_id', $perumahanAccountIds)
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->when($this->filterAccount, fn($q) => $q->where('account_id', $this->filterAccount))
            ->when($this->filterType,    fn($q) => $q->where('type', $this->filterType))
            ->latest('transaction_date')
            ->latest('id');

        $totalDebit  = (clone $query)->where('type', 'debit')->sum('amount');
        $totalCredit = (clone $query)->where('type', 'credit')->sum('amount');
        $transactions = $query->paginate(15);

        // Account balances
        $balances = [];
        foreach ($this->perumahanAccounts as $acc) {
            $debit  = Transaction::where('account_id', $acc->id)->where('type', 'debit')->sum('amount');
            $credit = Transaction::where('account_id', $acc->id)->where('type', 'credit')->sum('amount');
            $balances[$acc->id] = ['name' => $acc->name, 'saldo' => $debit - $credit];
        }

        // IPL summary for current filter period
        $periods = IplPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $selectedPeriod = $this->filterPeriodId
            ? IplPeriod::find($this->filterPeriodId)
            : $periods->first();

        $iplSummary = null;
        if ($selectedPeriod) {
            $iplPayments = IplPayment::whereHas('billing', fn($q) => $q->where('ipl_period_id', $selectedPeriod->id))->get();
            $iplSummary = [
                'period'    => $selectedPeriod->period_label,
                'security'  => $iplPayments->sum('amount_security'),
                'garbage'   => $iplPayments->sum('amount_garbage'),
                'kas_rt'    => $iplPayments->sum('amount_kas_rt'),
                'extra'     => $iplPayments->sum(fn($p) => array_sum($p->extra_charges_paid ?? [])),
                'total'     => $iplPayments->sum(fn($p)
                    => (float)$p->amount_security + (float)$p->amount_garbage + (float)$p->amount_kas_rt
                       + array_sum($p->extra_charges_paid ?? [])),
            ];
        }

        return view('livewire.transactions.transaksi-perumahan', [
            'transactions'   => $transactions,
            'totalDebit'     => $totalDebit,
            'totalCredit'    => $totalCredit,
            'balances'       => $balances,
            'iplSummary'     => $iplSummary,
            'periods'        => $periods,
            'selectedPeriod' => $selectedPeriod,
        ]);
    }

    // --- Hooks ---
    public function updatedFilterAccount(): void { $this->resetPage(); }
    public function updatedFilterType(): void    { $this->resetPage(); }
    public function updatedStartDate(): void     { $this->resetPage(); }
    public function updatedEndDate(): void       { $this->resetPage(); }
    public function updatedFilterPeriodId(): void{ $this->resetPage(); }

    public function updatedType(): void
    {
        $this->categoryId   = '';
        $this->campaignId   = null;
        $this->showCampaignDropdown = false;
        $this->loadModalCategories();
        // If switching to income and selected account is Keamanan/Kebersihan, clear it
        if ($this->type === 'debit' && $this->accountId) {
            $validIds = $this->incomeAccounts->pluck('id')->map(fn($id) => (string)$id);
            if (!$validIds->contains((string)$this->accountId)) {
                $this->accountId = '';
            }
        }
        $this->resetErrorBag(['categoryId', 'campaignId', 'accountId']);
    }

    public function updatedCategoryId($value): void
    {
        $cat = Category::find($value);
        $this->showCampaignDropdown = $cat && in_array($cat->name, self::PROGRAM_EXPENSE_CATEGORY_NAMES);
        if (!$this->showCampaignDropdown) $this->campaignId = null;
        $this->resetErrorBag('campaignId');
    }

    private function loadModalCategories(): void
    {
        $catType = $this->type === 'debit' ? 'income' : 'expense';
        $this->modalCategories = Category::byOrg('perumahan')
            ->where('type', $catType)
            ->orderBy('name')
            ->get();
    }

    // --- CRUD ---
    public function create(): void
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function edit(int $id): void
    {
        try {
            $tx = Transaction::findOrFail($id);
            $this->selectedId       = $id;
            $this->type             = $tx->type;
            $this->amount           = (string)$tx->amount;
            $this->description      = $tx->description;
            $this->accountId        = (string)$tx->account_id;
            $this->transactionDate  = $tx->transaction_date->format('Y-m-d');
            $this->campaignId       = $tx->campaign_id;
            $this->loadModalCategories();
            $this->categoryId = (string)$tx->category_id;
            $cat = Category::find($tx->category_id);
            $this->showCampaignDropdown = $cat && in_array($cat->name, self::PROGRAM_EXPENSE_CATEGORY_NAMES);
            $this->resetErrorBag();
            session()->forget('modal_error');
            $this->isModalOpen = true;
        } catch (\Exception $e) {
            Log::error('TransaksiPerumahan edit error: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat transaksi.');
        }
    }

    public function store(): void
    {
        $this->validate();
        try {
            DB::beginTransaction();

            $data = [
                'type'             => $this->type,
                'amount'           => $this->amount,
                'description'      => $this->description,
                'account_id'       => $this->accountId,
                'category_id'      => $this->categoryId,
                'transaction_date' => $this->transactionDate,
                'user_id'          => Auth::id(),
                'campaign_id'      => $this->showCampaignDropdown ? $this->campaignId : null,
            ];

            if ($this->selectedId) {
                Transaction::findOrFail($this->selectedId)->update($data);
                session()->flash('success', 'Transaksi berhasil diperbarui.');
            } else {
                Transaction::create($data);
                session()->flash('success', 'Transaksi berhasil ditambahkan.');
            }

            DB::commit();
            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TransaksiPerumahan store error: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->dispatch('show-perumahan-tx-delete', id: $id);
    }

    public function delete(int $id): void
    {
        try {
            Transaction::findOrFail($id)->delete();
            $this->dispatch('perumahanTxDeleted');
            session()->flash('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('perumahanTxDeleteFailed', message: $e->getMessage());
        }
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['selectedId', 'amount', 'description', 'accountId', 'categoryId', 'campaignId']);
        $this->type               = 'debit';
        $this->transactionDate    = now()->format('Y-m-d');
        $this->showCampaignDropdown = false;
        $this->loadModalCategories();
        $this->resetErrorBag();
        session()->forget('modal_error');
    }
}
