<?php
namespace App\Livewire\IPL;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\IplPayment;
use App\Models\Resident;
use App\Models\HouseBlock;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class ManageIPL extends Component
{
    use WithPagination;

    public string $activeTab = 'billings';

    // Period management
    public ?int $selectedPeriodId = null;
    public bool $isPeriodModalOpen = false;
    public ?int $editingPeriodId = null;
    public string $periodYear = '';
    public string $periodMonth = '';
    public string $periodSecurityAmount = '';
    public string $periodGarbageAmount = '';
    public string $periodNotes = '';
    public bool $periodIsClosed = false;

    // Billing filters
    public string $filterBillingStatus = '';
    public string $filterBillingBlock = '';

    // Payment modal
    public bool $isPaymentModalOpen = false;
    public ?int $payingBillingId = null;
    public string $paymentDate = '';
    public string $paymentAmountSecurity = '';
    public string $paymentAmountGarbage = '';
    public string $paymentMethod = 'cash';
    public ?int $paymentAccountId = null;
    public string $paymentReference = '';
    public string $paymentReceivedBy = '';
    public string $paymentNotes = '';

    public $perumahanAccounts = [];
    public $houseBlocks = [];

    protected function periodRules(): array
    {
        return [
            'periodYear' => 'required|integer|min:2020|max:2100',
            'periodMonth' => 'required|integer|min:1|max:12',
            'periodSecurityAmount' => 'required|numeric|min:0',
            'periodGarbageAmount' => 'required|numeric|min:0',
            'periodNotes' => 'nullable|string',
        ];
    }

    protected function paymentRules(): array
    {
        return [
            'paymentDate' => 'required|date',
            'paymentAmountSecurity' => 'required|numeric|min:0',
            'paymentAmountGarbage' => 'required|numeric|min:0',
            'paymentMethod' => 'required|in:cash,transfer,other',
            'paymentAccountId' => 'nullable|exists:accounts,id',
            'paymentReference' => 'nullable|string|max:100',
            'paymentReceivedBy' => 'nullable|string|max:100',
            'paymentNotes' => 'nullable|string',
        ];
    }

    public function mount(): void
    {
        $this->perumahanAccounts = Account::byOrg('perumahan')->orderBy('name')->get();
        $this->houseBlocks = HouseBlock::active()->orderBy('block_letter')->orderBy('unit_number')->get();
        $this->paymentDate = now()->format('Y-m-d');
        $this->periodYear = now()->year;
        $this->periodMonth = now()->month;

        $latestPeriod = IplPeriod::latest('year')->latest('month')->first();
        if ($latestPeriod) {
            $this->selectedPeriodId = $latestPeriod->id;
        }
    }

    public function render()
    {
        $periods = IplPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $currentPeriod = $this->selectedPeriodId ? IplPeriod::find($this->selectedPeriodId) : null;

        $billingsQuery = IplBilling::query()
            ->with(['resident', 'houseBlock', 'payments'])
            ->when($this->selectedPeriodId, fn($q) => $q->where('ipl_period_id', $this->selectedPeriodId))
            ->when($this->filterBillingStatus, fn($q) => $q->where('status', $this->filterBillingStatus))
            ->when($this->filterBillingBlock, fn($q) => $q->where('house_block_id', $this->filterBillingBlock));

        $summary = [
            'total_tagihan' => (clone $billingsQuery)->sum(DB::raw('ipl_security_amount + ipl_garbage_amount')),
            'total_terbayar' => (clone $billingsQuery)->sum(DB::raw('paid_security + paid_garbage')),
            'jumlah_lunas' => (clone $billingsQuery)->where('status', 'paid')->count(),
            'jumlah_belum' => (clone $billingsQuery)->where('status', 'unpaid')->count(),
            'jumlah_sebagian' => (clone $billingsQuery)->where('status', 'partial')->count(),
        ];
        $summary['total_tunggakan'] = $summary['total_tagihan'] - $summary['total_terbayar'];

        $billings = $billingsQuery
            ->join('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id', 'left')
            ->orderBy('house_blocks.block_letter')
            ->orderBy('house_blocks.unit_number')
            ->select('ipl_billings.*')
            ->paginate(20);

        $payingBilling = $this->payingBillingId ? IplBilling::with(['resident', 'houseBlock'])->find($this->payingBillingId) : null;

        return view('livewire.ipl.manage-ipl', [
            'periods' => $periods,
            'currentPeriod' => $currentPeriod,
            'billings' => $billings,
            'summary' => $summary,
            'payingBilling' => $payingBilling,
        ]);
    }

    // --- Period Management ---

    public function openCreatePeriod(): void
    {
        $this->editingPeriodId = null;
        $this->periodYear = now()->year;
        $this->periodMonth = now()->month;
        $this->periodSecurityAmount = '';
        $this->periodGarbageAmount = '';
        $this->periodNotes = '';
        $this->periodIsClosed = false;
        $this->resetErrorBag();
        $this->isPeriodModalOpen = true;
    }

    public function openEditPeriod(int $id): void
    {
        $period = IplPeriod::findOrFail($id);
        $this->editingPeriodId = $id;
        $this->periodYear = $period->year;
        $this->periodMonth = $period->month;
        $this->periodSecurityAmount = $period->ipl_security_amount;
        $this->periodGarbageAmount = $period->ipl_garbage_amount;
        $this->periodNotes = $period->notes ?? '';
        $this->periodIsClosed = $period->is_closed;
        $this->resetErrorBag();
        $this->isPeriodModalOpen = true;
    }

    public function savePeriod(): void
    {
        $this->validate($this->periodRules());
        try {
            IplPeriod::updateOrCreate(
                ['id' => $this->editingPeriodId ?? 0],
                [
                    'year' => $this->periodYear,
                    'month' => $this->periodMonth,
                    'ipl_security_amount' => $this->periodSecurityAmount,
                    'ipl_garbage_amount' => $this->periodGarbageAmount,
                    'notes' => $this->periodNotes ?: null,
                    'is_closed' => $this->periodIsClosed,
                ]
            );
            session()->flash('success', 'Periode IPL berhasil disimpan.');
            $this->isPeriodModalOpen = false;
            $this->resetErrorBag();
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate')) {
                session()->flash('modal_error', 'Periode untuk bulan dan tahun ini sudah ada.');
            } else {
                session()->flash('modal_error', 'Gagal menyimpan periode: ' . $e->getMessage());
            }
        }
    }

    public function selectPeriod(int $id): void
    {
        $this->selectedPeriodId = $id;
        $this->resetPage();
    }

    public function generateBillings(int $periodId): void
    {
        $period = IplPeriod::findOrFail($periodId);
        if ($period->is_closed) {
            session()->flash('error', 'Periode sudah ditutup, tidak bisa generate tagihan.');
            return;
        }

        try {
            DB::beginTransaction();
            $residents = Resident::active()->with('houseBlock')->get();
            $created = 0;
            $dueDate = \Carbon\Carbon::create($period->year, $period->month)->endOfMonth()->toDateString();

            foreach ($residents as $resident) {
                $exists = IplBilling::where('ipl_period_id', $periodId)
                    ->where('resident_id', $resident->id)
                    ->exists();

                if (!$exists) {
                    IplBilling::create([
                        'ipl_period_id' => $periodId,
                        'resident_id' => $resident->id,
                        'house_block_id' => $resident->house_block_id,
                        'ipl_security_amount' => $period->ipl_security_amount,
                        'ipl_garbage_amount' => $period->ipl_garbage_amount,
                        'paid_security' => 0,
                        'paid_garbage' => 0,
                        'status' => 'unpaid',
                        'due_date' => $dueDate,
                    ]);
                    $created++;
                }
            }
            DB::commit();
            session()->flash('success', "Berhasil generate {$created} tagihan IPL untuk periode {$period->period_label}.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating IPL billings: ' . $e->getMessage());
            session()->flash('error', 'Gagal generate tagihan: ' . $e->getMessage());
        }
    }

    // --- Payment ---

    public function openPayment(int $billingId): void
    {
        $billing = IplBilling::with(['resident', 'houseBlock'])->findOrFail($billingId);
        $this->payingBillingId = $billingId;
        $this->paymentDate = now()->format('Y-m-d');
        $remaining_security = max(0, (float)$billing->ipl_security_amount - (float)$billing->paid_security);
        $remaining_garbage = max(0, (float)$billing->ipl_garbage_amount - (float)$billing->paid_garbage);
        $this->paymentAmountSecurity = $remaining_security > 0 ? $remaining_security : '';
        $this->paymentAmountGarbage = $remaining_garbage > 0 ? $remaining_garbage : '';
        $this->paymentMethod = 'cash';
        $this->paymentAccountId = null;
        $this->paymentReference = '';
        $this->paymentReceivedBy = '';
        $this->paymentNotes = '';
        $this->resetErrorBag();
        $this->isPaymentModalOpen = true;
    }

    public function savePayment(): void
    {
        $this->validate($this->paymentRules());

        try {
            DB::beginTransaction();
            IplPayment::create([
                'ipl_billing_id' => $this->payingBillingId,
                'payment_date' => $this->paymentDate,
                'amount_security' => $this->paymentAmountSecurity ?: 0,
                'amount_garbage' => $this->paymentAmountGarbage ?: 0,
                'payment_method' => $this->paymentMethod,
                'account_id' => $this->paymentAccountId ?: null,
                'reference_number' => $this->paymentReference ?: null,
                'received_by' => $this->paymentReceivedBy ?: null,
                'notes' => $this->paymentNotes ?: null,
                'user_id' => Auth::id(),
            ]);
            DB::commit();
            session()->flash('success', 'Pembayaran IPL berhasil dicatat.');
            $this->isPaymentModalOpen = false;
            $this->payingBillingId = null;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving IPL payment: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        }
    }

    public function closePaymentModal(): void
    {
        $this->isPaymentModalOpen = false;
        $this->payingBillingId = null;
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    public function closePeriodModal(): void
    {
        $this->isPeriodModalOpen = false;
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    public function updatedFilterBillingStatus(): void { $this->resetPage(); }
    public function updatedFilterBillingBlock(): void { $this->resetPage(); }
    public function updatedSelectedPeriodId(): void { $this->resetPage(); }
}
