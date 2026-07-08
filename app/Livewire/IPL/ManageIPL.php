<?php
namespace App\Livewire\IPL;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\IplBillingChargeItem;
use App\Models\IplPayment;
use App\Models\IplPeriodTariffRate;
use App\Models\IplTariffType;
use App\Models\Resident;
use App\Models\HouseBlock;
use App\Models\Account;
use App\Models\ResidentPaymentRequest;
use App\Support\IplLedger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\IplBillingReminder;
use Carbon\Carbon;

#[Layout('layouts.app')]
class ManageIPL extends Component
{
    use WithPagination;

    public string $activeTab = 'billings';

    // Period management
    public ?int $selectedPeriodId = null;
    public string $periodFilterMonth = '';
    public string $periodFilterYear = '';
    public bool $isPeriodModalOpen = false;
    public ?int $editingPeriodId = null;
    public string $periodYear = '';
    public string $periodMonth = '';
    public string $periodSecurityAmount = '';
    public string $periodGarbageAmount = '';
    public string $periodKasRtAmount = '';
    public string $periodNotes = '';

    // Billing filters
    public string $filterBillingStatus = '';
    public string $filterBillingBlock = '';

    // Period extra tariff rates (type_id => amount) for non-system types
    public array $extraTariffRates = [];

    // Payment modal
    public bool $isPaymentModalOpen = false;
    public ?int $payingBillingId = null;
    public string $paymentDate = '';
    public string $paymentAmountSecurity = '';
    public string $paymentAmountGarbage = '';
    public string $paymentAmountKasRt = '';
    public array $extraChargePayments = [];
    public string $paymentMethod = 'cash';
    public string $paymentReference = '';
    public string $paymentReceivedBy = '';
    public string $paymentNotes = '';

    // Multi-month payment
    public int $paymentMonths = 1;
    public string $paymentDirection = 'forward'; // 'forward' | 'backward'

    // Checklist payment (bayar per penghuni — pilih blok & periode)
    public bool $isChecklistPaymentOpen = false;
    public ?int $checklistResidentId = null;
    public array $checklistSelectedIds = [];
    public string $checkPayDate = '';
    public string $checkPayMethod = 'cash';
    public string $checkPayReference = '';
    public string $checkPayReceivedBy = '';
    public string $checkPayNotes = '';

    // Pemutihan / pembebasan tunggakan (waiver) — aksi admin, bukan kas
    public bool $isWaiveModalOpen = false;
    public ?int $waivingBillingId = null;
    public bool $waiveSecurity = true;
    public bool $waiveGarbage  = true;
    public bool $waiveKasRt    = true;
    public string $waiverReason = '';

    public $perumahanAccounts = [];
    public $houseBlocks = [];

    protected function periodRules(): array
    {
        $rules = [
            'periodYear'           => 'required|integer|min:2020|max:2100',
            'periodMonth'          => 'required|integer|min:1|max:12',
            'periodSecurityAmount' => 'required|numeric|min:0',
            'periodGarbageAmount'  => 'required|numeric|min:0',
            'periodKasRtAmount'    => 'required|numeric|min:0',
            'periodNotes'          => 'nullable|string',
        ];
        foreach (array_keys($this->extraTariffRates) as $typeId) {
            $rules["extraTariffRates.{$typeId}"] = 'required|numeric|min:0';
        }
        return $rules;
    }

    protected function paymentRules(): array
    {
        $rules = [
            'paymentDate'      => 'required|date',
            'paymentMethod'    => 'required|in:cash,transfer,other',
            'paymentReference' => 'nullable|string|max:100',
            'paymentReceivedBy'=> 'nullable|string|max:100',
            'paymentNotes'     => 'nullable|string',
        ];

        if ($this->paymentMonths <= 1) {
            $rules['paymentAmountSecurity'] = 'required|numeric|min:0';
            $rules['paymentAmountGarbage']  = 'required|numeric|min:0';
            $rules['paymentAmountKasRt']    = 'required|numeric|min:0';
        } else {
            $rules['paymentMonths']    = 'required|integer|min:2|max:12';
            $rules['paymentDirection'] = 'required|in:forward,backward';
        }

        return $rules;
    }

    public function mount(): void
    {
        $this->perumahanAccounts = Account::byOrg('perumahan')->orderBy('name')->get();
        $this->houseBlocks = HouseBlock::active()->orderBy('block_letter')->orderBy('unit_number')->get();
        $this->paymentDate = now()->format('Y-m-d');
        $this->periodYear = now()->year;
        $this->periodMonth = now()->month;

        $this->periodFilterMonth = (string) now()->month;
        $this->periodFilterYear = (string) now()->year;
        $currentPeriod = IplPeriod::where('year', now()->year)->where('month', now()->month)->first();
        if ($currentPeriod) {
            $this->selectedPeriodId = $currentPeriod->id;
        }
    }

    private function loadExtraTariffDefaults(): void
    {
        $this->extraTariffRates = IplTariffType::active()->extra()
            ->orderBy('sort_order')->orderBy('id')
            ->get()
            ->mapWithKeys(fn($t) => [$t->id => (string) $t->default_amount])
            ->toArray();
    }

    public function render()
    {
        $extraTariffTypes = IplTariffType::active()->extra()->orderBy('sort_order')->orderBy('id')->get();

        $periods = IplPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $currentPeriod = $this->selectedPeriodId ? IplPeriod::find($this->selectedPeriodId) : null;

        $billingsQuery = IplBilling::query()
            ->with(['responsibleResident', 'houseBlock', 'houseBlock.residents', 'payments', 'chargeItems.tariffType'])
            ->whereNotNull('responsible_resident_id')
            ->when($this->selectedPeriodId, fn($q) => $q->where('ipl_period_id', $this->selectedPeriodId))
            ->when($this->filterBillingStatus, fn($q) => $q->where('status', $this->filterBillingStatus))
            ->when($this->filterBillingBlock, fn($q) => $q->where('house_block_id', $this->filterBillingBlock));

        $summary = [
            'total_tagihan'    => (clone $billingsQuery)->sum(DB::raw('ipl_security_amount + ipl_garbage_amount + ipl_kas_rt_amount')),
            'total_terbayar'   => (clone $billingsQuery)->sum(DB::raw('paid_security + paid_garbage + paid_kas_rt')),
            'total_dibebaskan' => (clone $billingsQuery)->sum(DB::raw('waived_security + waived_garbage + waived_kas_rt')),
            'jumlah_lunas'     => (clone $billingsQuery)->where('status', 'paid')->count(),
            'jumlah_belum'     => (clone $billingsQuery)->where('status', 'unpaid')->count(),
            'jumlah_sebagian'  => (clone $billingsQuery)->where('status', 'partial')->count(),
        ];
        // Tunggakan = tagihan − terbayar (kas) − dibebaskan (pemutihan, bukan kas).
        $summary['total_tunggakan'] = max(0, $summary['total_tagihan'] - $summary['total_terbayar'] - $summary['total_dibebaskan']);

        $billings = $billingsQuery
            ->join('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id', 'left')
            ->orderBy('house_blocks.block_letter')
            ->orderBy('house_blocks.unit_number')
            ->select('ipl_billings.*')
            ->paginate(20);

        $payingBilling = $this->payingBillingId
            ? IplBilling::with(['responsibleResident', 'houseBlock', 'chargeItems.tariffType'])->find($this->payingBillingId)
            : null;

        $waivingBilling = $this->waivingBillingId
            ? IplBilling::with(['responsibleResident', 'houseBlock', 'period'])->find($this->waivingBillingId)
            : null;

        // Compute multi-month preview when modal is open and months > 1
        $multiMonthPreview = [];
        $multiMonthTotal   = 0;
        if ($this->isPaymentModalOpen && $this->payingBillingId && $this->paymentMonths > 1 && $payingBilling?->period) {
            $startDate = Carbon::create($payingBilling->period->year, $payingBilling->period->month, 1);
            $hbId      = $payingBilling->house_block_id;
            $refSec    = (float) $payingBilling->period->ipl_security_amount;
            $refGarb   = (float) $payingBilling->period->ipl_garbage_amount;
            $refKasRt  = (float) $payingBilling->period->ipl_kas_rt_amount;
            $mn        = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
            $rows      = [];

            for ($i = 0; $i < $this->paymentMonths; $i++) {
                $d = $startDate->copy();
                $this->paymentDirection === 'forward' ? $d->addMonths($i) : $d->subMonths($i);

                $period  = IplPeriod::where('year', $d->year)->where('month', $d->month)->first();
                $billing = $period
                    ? IplBilling::where('ipl_period_id', $period->id)->where('house_block_id', $hbId)->first()
                    : null;

                // Backward: skip if no billing (don't auto-create past periods)
                if ($this->paymentDirection === 'backward' && !$billing) {
                    $rows[] = [
                        'label'          => ($mn[$d->month] ?? $d->month) . ' ' . $d->year,
                        'period_exists'  => $period !== null,
                        'billing_exists' => false,
                        'status'         => 'no_billing',
                        'amount'         => 0,
                        'outstanding'    => 0,
                    ];
                    continue;
                }

                $amtSec   = $billing ? (float)$billing->ipl_security_amount : ($period ? (float)$period->ipl_security_amount : $refSec);
                $amtGarb  = $billing ? (float)$billing->ipl_garbage_amount  : ($period ? (float)$period->ipl_garbage_amount  : $refGarb);
                $amtKasRt = $billing ? (float)$billing->ipl_kas_rt_amount   : ($period ? (float)$period->ipl_kas_rt_amount   : $refKasRt);

                $paidSec  = (float)($billing?->paid_security ?? 0);
                $paidGarb = (float)($billing?->paid_garbage  ?? 0);
                $paidKrt  = (float)($billing?->paid_kas_rt   ?? 0);

                $total       = $amtSec + $amtGarb + $amtKasRt;
                $outstanding = max(0, $total - $paidSec - $paidGarb - $paidKrt);
                $status      = $billing?->status ?? 'new';

                if ($status !== 'paid' && $outstanding > 0) {
                    $multiMonthTotal += $outstanding;
                }

                $rows[] = [
                    'label'          => ($mn[$d->month] ?? $d->month) . ' ' . $d->year,
                    'period_exists'  => $period !== null,
                    'billing_exists' => $billing !== null,
                    'status'         => $status,
                    'amount'         => $total,
                    'outstanding'    => $outstanding,
                ];
            }

            $multiMonthPreview = $this->paymentDirection === 'backward' ? array_reverse($rows) : $rows;
        }

        $checklistBillings = $this->isChecklistPaymentOpen && $this->checklistResidentId
            ? $this->getResidentUnpaidBillings()
            : collect();

        $residentsList = Resident::active()->orderBy('name')->get(['id', 'name']);

        $periodYears = IplPeriod::select('year')->distinct()->orderByDesc('year')->pluck('year')->toArray();
        $minYear = $periodYears ? min(min($periodYears), now()->year) : now()->year;
        $maxYear = $periodYears ? max(max($periodYears), now()->year + 1) : now()->year + 1;
        $availableYears = range($minYear, $maxYear);

        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        return view('livewire.ipl.manage-ipl', [
            'periods'         => $periods,
            'currentPeriod'   => $currentPeriod,
            'billings'        => $billings,
            'summary'         => $summary,
            'payingBilling'   => $payingBilling,
            'waivingBilling'  => $waivingBilling,
            'extraTariffTypes'=> $extraTariffTypes,
            'multiMonthPreview' => $multiMonthPreview,
            'multiMonthTotal' => $multiMonthTotal,
            'checklistBillings' => $checklistBillings,
            'residentsList'   => $residentsList,
            'availableYears'  => $availableYears,
            'months'          => $months,
        ]);
    }

    // --- Period Management ---

    public function openCreatePeriod(): void
    {
        $this->editingPeriodId = null;
        $this->periodYear   = now()->year;
        $this->periodMonth  = now()->month;
        $this->periodNotes  = '';

        $security = IplTariffType::where('billing_key', 'security')->value('default_amount') ?? 0;
        $garbage  = IplTariffType::where('billing_key', 'garbage')->value('default_amount')  ?? 0;
        $kasRt    = IplTariffType::where('billing_key', 'kas_rt')->value('default_amount')   ?? 0;
        $this->periodSecurityAmount = (string) $security;
        $this->periodGarbageAmount  = (string) $garbage;
        $this->periodKasRtAmount    = (string) $kasRt;

        $this->loadExtraTariffDefaults();
        $this->resetErrorBag();
        $this->isPeriodModalOpen = true;
    }

    public function openEditPeriod(int $id): void
    {
        $period = IplPeriod::with('tariffRates.tariffType')->findOrFail($id);
        $this->editingPeriodId      = $id;
        $this->periodYear           = $period->year;
        $this->periodMonth          = $period->month;
        $this->periodSecurityAmount = $period->ipl_security_amount;
        $this->periodGarbageAmount  = $period->ipl_garbage_amount;
        $this->periodKasRtAmount    = $period->ipl_kas_rt_amount;
        $this->periodNotes          = $period->notes ?? '';

        $savedRates = $period->tariffRates
            ->filter(fn($r) => $r->tariffType && is_null($r->tariffType->billing_key))
            ->keyBy('ipl_tariff_type_id')
            ->map(fn($r) => (string) $r->amount)
            ->toArray();

        $this->extraTariffRates = IplTariffType::active()->extra()
            ->orderBy('sort_order')->orderBy('id')->get()
            ->mapWithKeys(fn($t) => [$t->id => $savedRates[$t->id] ?? (string) $t->default_amount])
            ->toArray();

        $this->resetErrorBag();
        $this->isPeriodModalOpen = true;
    }

    public function savePeriod(): void
    {
        $this->validate($this->periodRules());
        try {
            DB::beginTransaction();
            $period = IplPeriod::updateOrCreate(
                ['id' => $this->editingPeriodId ?? 0],
                [
                    'year'                => $this->periodYear,
                    'month'               => $this->periodMonth,
                    'ipl_security_amount' => $this->periodSecurityAmount,
                    'ipl_garbage_amount'  => $this->periodGarbageAmount,
                    'ipl_kas_rt_amount'   => $this->periodKasRtAmount,
                    'notes'               => $this->periodNotes ?: null,
                ]
            );

            foreach ($this->extraTariffRates as $typeId => $amount) {
                IplPeriodTariffRate::updateOrCreate(
                    ['ipl_period_id' => $period->id, 'ipl_tariff_type_id' => $typeId],
                    ['amount' => (float) $amount]
                );
            }

            DB::commit();
            session()->flash('success', 'Periode IPL berhasil disimpan.');
            $this->isPeriodModalOpen = false;
            $this->resetErrorBag();
        } catch (\Exception $e) {
            DB::rollBack();
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

    public function selectByPeriod(): void
    {
        $period = IplPeriod::firstOrCreate(
            ['year' => $this->periodFilterYear, 'month' => $this->periodFilterMonth],
            [
                'ipl_security_amount' => IplTariffType::where('billing_key', 'security')->value('default_amount') ?? 0,
                'ipl_garbage_amount'  => IplTariffType::where('billing_key', 'garbage')->value('default_amount') ?? 0,
                'ipl_kas_rt_amount'   => IplTariffType::where('billing_key', 'kas_rt')->value('default_amount') ?? 0,
            ]
        );
        $this->selectedPeriodId = $period->id;
        $yearLabels = collect(range(now()->year - 2, now()->year + 1))->mapWithKeys(fn($y) => [$y => $y]);
        if (!isset($yearLabels[$this->periodFilterYear])) {
            $this->periodFilterYear = $yearLabels->keys()->contains($this->periodFilterYear) ? $this->periodFilterYear : now()->year;
        }
        $this->resetPage();
    }

    public function generateBillings(int $periodId): void
    {
        $period = IplPeriod::findOrFail($periodId);

        try {
            DB::beginTransaction();
            $houseBlocks = HouseBlock::active()->get();
            $created = 0; $skipped = 0;
            $dueDate = Carbon::create($period->year, $period->month, 10)->toDateString();

            $extraRates = IplPeriodTariffRate::where('ipl_period_id', $periodId)
                ->whereHas('tariffType', fn($q) => $q->whereNull('billing_key')->where('is_active', true))
                ->get();

            foreach ($houseBlocks as $block) {
                $exists = IplBilling::where('ipl_period_id', $periodId)
                    ->where('house_block_id', $block->id)->exists();
                if ($exists) { $skipped++; continue; }

                // Penanggung IPL yang MASIH AKTIF (penghuni nonaktif tidak ditagih).
                // Prioritas: assignment ditandai penanggung IPL (is_ipl_payer) — mis. penyewa
                // yang bayar IPL; jika tidak ada, jatuh ke PEMILIK aktif.
                $owner = DB::table('resident_house_blocks as rhb')
                    ->join('residents as r', 'r.id', '=', 'rhb.resident_id')
                    ->where('rhb.house_block_id', $block->id)
                    ->whereNull('rhb.ended_at')
                    ->where('r.is_active', true)
                    ->orderByDesc('rhb.is_ipl_payer')
                    ->orderByRaw("CASE WHEN rhb.ownership_type = 'pemilik' THEN 0 ELSE 1 END")
                    ->orderByDesc('rhb.is_primary_residence')
                    ->select('rhb.*')
                    ->first();

                // Skip jika blok belum ditempati (tidak ada penanggung aktif)
                if (!$owner) { $skipped++; continue; }

                // Skip jika penanggung sudah bayar di muka / menunggu konfirmasi untuk periode ini
                if ($owner) {
                    $prepaid = ResidentPaymentRequest::where('resident_id', $owner->resident_id)
                        ->where('type', 'ipl')
                        ->whereIn('status', ['pending', 'confirmed'])
                        ->where('period_year', $period->year)
                        ->where('period_month', $period->month)
                        ->exists();
                    if ($prepaid) { $skipped++; continue; }
                }

                $billing = IplBilling::create([
                    'ipl_period_id'           => $periodId,
                    'house_block_id'          => $block->id,
                    'responsible_resident_id' => $owner?->resident_id,
                    'ipl_security_amount'     => $period->ipl_security_amount,
                    'ipl_garbage_amount'      => $period->ipl_garbage_amount,
                    'ipl_kas_rt_amount'       => $period->ipl_kas_rt_amount,
                    'paid_security'           => 0,
                    'paid_garbage'            => 0,
                    'paid_kas_rt'             => 0,
                    'status'                  => 'unpaid',
                    'due_date'                => $dueDate,
                ]);

                foreach ($extraRates as $rate) {
                    IplBillingChargeItem::create([
                        'ipl_billing_id'     => $billing->id,
                        'ipl_tariff_type_id' => $rate->ipl_tariff_type_id,
                        'billed_amount'      => $rate->amount,
                        'paid_amount'        => 0,
                    ]);
                }
                $created++;
            }
            DB::commit();

            // Send reminder notifications for all unpaid billings in this period
            $notified = 0;
            try {
                $unpaidBillings = IplBilling::where('ipl_period_id', $periodId)
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->whereNotNull('responsible_resident_id')
                    ->with(['houseBlock', 'period'])
                    ->get();

                foreach ($unpaidBillings as $b) {
                    $resident = Resident::find($b->responsible_resident_id);
                    if ($resident && $resident->is_active && $resident->wantsNotification('ipl_reminder')) {
                        $resident->notify(new IplBillingReminder(
                            $b->period?->period_label ?? "{$period->month}/{$period->year}",
                            (float) $b->outstanding,
                            $b->houseBlock?->block_code,
                        ));
                        $notified++;
                    }
                }
            } catch (\Exception $ne) {
                Log::warning('Failed to send IPL reminders: ' . $ne->getMessage());
            }

            $msg = "Generate tagihan: {$created} unit dibuat, {$skipped} sudah ada/sudah bayar — periode {$period->period_label}.";
            if ($notified > 0) $msg .= " Notifikasi dikirim ke {$notified} penghuni.";
            session()->flash('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generating IPL billings: ' . $e->getMessage());
            session()->flash('error', 'Gagal generate tagihan: ' . $e->getMessage());
        }
    }

    // --- Payment ---

    public function openPayment(int $billingId): void
    {
        $billing = IplBilling::with(['responsibleResident', 'houseBlock', 'chargeItems.tariffType'])->findOrFail($billingId);
        $this->payingBillingId = $billingId;
        $this->paymentDate     = now()->format('Y-m-d');
        $this->paymentMonths   = 1;
        $this->paymentDirection = 'forward';

        $remSecurity = max(0, (float)$billing->ipl_security_amount - (float)$billing->paid_security);
        $remGarbage  = max(0, (float)$billing->ipl_garbage_amount  - (float)$billing->paid_garbage);
        $remKasRt    = max(0, (float)$billing->ipl_kas_rt_amount   - (float)$billing->paid_kas_rt);
        $this->paymentAmountSecurity = $remSecurity > 0 ? (string)$remSecurity : '';
        $this->paymentAmountGarbage  = $remGarbage  > 0 ? (string)$remGarbage  : '';
        $this->paymentAmountKasRt    = $remKasRt    > 0 ? (string)$remKasRt    : '';

        $this->extraChargePayments = [];
        foreach ($billing->chargeItems as $item) {
            $rem = max(0, (float)$item->billed_amount - (float)$item->paid_amount);
            $this->extraChargePayments[$item->ipl_tariff_type_id] = $rem > 0 ? (string)$rem : '0';
        }

        $this->paymentMethod     = 'cash';
        $this->paymentReference  = '';
        $this->paymentReceivedBy = '';
        $this->paymentNotes      = '';
        $this->resetErrorBag();
        session()->forget('modal_error');
        $this->isPaymentModalOpen = true;
    }

    public function savePayment(): void
    {
        $this->validate($this->paymentRules());

        if ($this->paymentMonths > 1) {
            $this->saveMultiMonthPayment();
            return;
        }

        try {
            DB::beginTransaction();

            $extraPaid = [];
            foreach ($this->extraChargePayments as $typeId => $amount) {
                $amt = (float)($amount ?: 0);
                if ($amt > 0) $extraPaid[(string)$typeId] = $amt;
            }

            $billing = IplBilling::with(['period', 'houseBlock', 'responsibleResident'])
                ->find($this->payingBillingId);
            $desc = 'IPL ' . ($billing?->period?->period_label ?? '')
                . ' ' . ($billing?->houseBlock?->block_code ?? '')
                . ' — ' . ($billing?->responsibleResident?->name ?? '-');

            $accountId = IplLedger::record(
                (float)($this->paymentAmountSecurity ?: 0),
                (float)($this->paymentAmountGarbage  ?: 0),
                (float)($this->paymentAmountKasRt    ?: 0),
                $extraPaid,
                $desc,
                $this->paymentDate,
                Auth::id()
            );

            IplPayment::create([
                'ipl_billing_id'     => $this->payingBillingId,
                'payment_date'       => $this->paymentDate,
                'amount_security'    => (float)($this->paymentAmountSecurity ?: 0),
                'amount_garbage'     => (float)($this->paymentAmountGarbage  ?: 0),
                'amount_kas_rt'      => (float)($this->paymentAmountKasRt    ?: 0),
                'payment_method'     => $this->paymentMethod,
                'account_id'         => $accountId,
                'reference_number'   => $this->paymentReference ?: null,
                'received_by'        => $this->paymentReceivedBy ?: null,
                'notes'              => $this->paymentNotes ?: null,
                'user_id'            => Auth::id(),
                'extra_charges_paid' => !empty($extraPaid) ? $extraPaid : null,
            ]);
            DB::commit();
            session()->flash('success', 'Pembayaran IPL berhasil dicatat.');
            $this->isPaymentModalOpen  = false;
            $this->payingBillingId     = null;
            $this->extraChargePayments = [];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving IPL payment: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        }
    }

    private function saveMultiMonthPayment(): void
    {
        try {
            DB::beginTransaction();

            $startBilling = IplBilling::with(['period', 'houseBlock', 'responsibleResident'])
                ->findOrFail($this->payingBillingId);

            $startDate             = Carbon::create($startBilling->period->year, $startBilling->period->month, 1);
            $houseBlockId          = $startBilling->house_block_id;
            $responsibleResidentId = $startBilling->responsible_resident_id;
            $refSecurity           = (float) $startBilling->period->ipl_security_amount;
            $refGarbage            = (float) $startBilling->period->ipl_garbage_amount;
            $refKasRt              = (float) $startBilling->period->ipl_kas_rt_amount;

            $paidCount    = 0;
            $skippedCount = 0;
            $totalPaid    = 0;

            for ($i = 0; $i < $this->paymentMonths; $i++) {
                $d = $startDate->copy();
                $this->paymentDirection === 'forward' ? $d->addMonths($i) : $d->subMonths($i);

                if ($this->paymentDirection === 'backward') {
                    // Only pay existing billings for past months
                    $period = IplPeriod::where('year', $d->year)->where('month', $d->month)->first();
                    if (!$period) { $skippedCount++; continue; }

                    $billing = IplBilling::where('ipl_period_id', $period->id)
                        ->where('house_block_id', $houseBlockId)->first();
                    if (!$billing) { $skippedCount++; continue; }
                } else {
                    // Forward: auto-create period & billing if needed
            $period = IplPeriod::firstOrCreate(
                ['year' => $d->year, 'month' => $d->month],
                [
                    'ipl_security_amount' => $refSecurity,
                    'ipl_garbage_amount'  => $refGarbage,
                    'ipl_kas_rt_amount'   => $refKasRt,
                ]
            );

                    $billing = IplBilling::firstOrCreate(
                        ['ipl_period_id' => $period->id, 'house_block_id' => $houseBlockId],
                        [
                            'responsible_resident_id' => $responsibleResidentId,
                            'ipl_security_amount'     => $period->ipl_security_amount,
                            'ipl_garbage_amount'      => $period->ipl_garbage_amount,
                            'ipl_kas_rt_amount'       => $period->ipl_kas_rt_amount,
                            'paid_security'           => 0,
                            'paid_garbage'            => 0,
                            'paid_kas_rt'             => 0,
                            'status'                  => 'unpaid',
                            'due_date'                => Carbon::create($d->year, $d->month, 10)->toDateString(),
                        ]
                    );
                }

                if ($billing->status === 'paid') { $skippedCount++; continue; }

                $amtSec  = max(0, (float)$billing->ipl_security_amount - (float)$billing->paid_security);
                $amtGarb = max(0, (float)$billing->ipl_garbage_amount  - (float)$billing->paid_garbage);
                $amtKrt  = max(0, (float)$billing->ipl_kas_rt_amount   - (float)$billing->paid_kas_rt);

                if ($amtSec + $amtGarb + $amtKrt <= 0) { $skippedCount++; continue; }

                $descBulan = 'IPL ' . ($period->period_label ?? "{$period->month}/{$period->year}")
                    . ' ' . ($startBilling->houseBlock?->block_code ?? '')
                    . ' — ' . ($startBilling->responsibleResident?->name ?? '-');

                $accountId = IplLedger::record(
                    $amtSec,
                    $amtGarb,
                    $amtKrt,
                    [],
                    $descBulan,
                    $this->paymentDate,
                    Auth::id()
                );

                IplPayment::create([
                    'ipl_billing_id'   => $billing->id,
                    'payment_date'     => $this->paymentDate,
                    'amount_security'  => $amtSec,
                    'amount_garbage'   => $amtGarb,
                    'amount_kas_rt'    => $amtKrt,
                    'payment_method'   => $this->paymentMethod,
                    'account_id'       => $accountId,
                    'reference_number' => $this->paymentReference ?: null,
                    'received_by'      => $this->paymentReceivedBy ?: null,
                    'notes'            => $this->paymentNotes ?: null,
                    'user_id'          => Auth::id(),
                ]);

                $totalPaid += $amtSec + $amtGarb + $amtKrt;
                $paidCount++;
            }

            DB::commit();
            $label = $this->paymentDirection === 'forward' ? 'muka' : 'tunggakan';
            $msg   = "Bayar {$label} berhasil: {$paidCount} bulan dicatat, total Rp " . number_format($totalPaid, 0, ',', '.');
            if ($skippedCount > 0) $msg .= " ({$skippedCount} bulan dilewati)";
            session()->flash('success', $msg);
            $this->isPaymentModalOpen  = false;
            $this->payingBillingId     = null;
            $this->extraChargePayments = [];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving multi-month IPL payment: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function closePaymentModal(): void
    {
        $this->isPaymentModalOpen = false;
        $this->payingBillingId    = null;
        $this->paymentMonths      = 1;
        $this->paymentDirection   = 'forward';
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    public function closePeriodModal(): void
    {
        $this->isPeriodModalOpen = false;
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    // --- Checklist Payment (Bayar per Penghuni) ---

    public function openChecklistPayment(): void
    {
        $this->checklistResidentId = null;
        $this->checklistSelectedIds = [];
        $this->checkPayDate = now()->format('Y-m-d');
        $this->checkPayMethod = 'cash';
        $this->checkPayReference = '';
        $this->checkPayReceivedBy = '';
        $this->checkPayNotes = '';
        $this->resetErrorBag();
        session()->forget('modal_error');
        $this->isChecklistPaymentOpen = true;
    }

    public function updatedChecklistResidentId(): void
    {
        $this->checklistSelectedIds = [];
    }

    public function toggleChecklistAll(): void
    {
        $ids = $this->getResidentUnpaidBillings()->pluck('id')->toArray();
        $this->checklistSelectedIds = count($this->checklistSelectedIds) === count($ids) ? [] : $ids;
    }

    public function getResidentUnpaidBillings()
    {
        if (!$this->checklistResidentId) return collect();

        return IplBilling::with(['period', 'houseBlock', 'chargeItems.tariffType'])
            ->where('responsible_resident_id', $this->checklistResidentId)
            ->whereIn('status', ['unpaid', 'partial'])
            ->join('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id')
            ->orderBy('house_blocks.block_letter')
            ->orderBy('house_blocks.unit_number')
            ->orderBy('ipl_billings.ipl_period_id')
            ->select('ipl_billings.*')
            ->get();
    }

    public function saveChecklistPayment(): void
    {
        $this->validate([
            'checklistResidentId'  => 'required|exists:residents,id',
            'checklistSelectedIds' => 'required|array|min:1',
            'checkPayDate'         => 'required|date',
            'checkPayMethod'       => 'required|in:cash,transfer,other',
        ]);

        try {
            DB::beginTransaction();

            $billings = IplBilling::with(['period', 'houseBlock', 'chargeItems.tariffType'])
                ->whereIn('id', $this->checklistSelectedIds)
                ->get();

            $paidCount = 0;
            $totalPaid = 0;

            foreach ($billings as $billing) {
                $amtSec  = $billing->remainingSecurity();
                $amtGarb = $billing->remainingGarbage();
                $amtKrt  = $billing->remainingKasRt();

                if ($amtSec + $amtGarb + $amtKrt <= 0) continue;

                $desc = 'IPL ' . ($billing->period?->period_label ?? '')
                    . ' ' . ($billing->houseBlock?->block_code ?? '')
                    . ' — ' . ($billing->responsibleResident?->name ?? '-');

                $accountId = IplLedger::record(
                    $amtSec, $amtGarb, $amtKrt, [],
                    $desc, $this->checkPayDate, Auth::id()
                );

                IplPayment::create([
                    'ipl_billing_id'   => $billing->id,
                    'payment_date'     => $this->checkPayDate,
                    'amount_security'  => $amtSec,
                    'amount_garbage'   => $amtGarb,
                    'amount_kas_rt'    => $amtKrt,
                    'payment_method'   => $this->checkPayMethod,
                    'account_id'       => $accountId,
                    'reference_number' => $this->checkPayReference ?: null,
                    'received_by'      => $this->checkPayReceivedBy ?: null,
                    'notes'            => $this->checkPayNotes ?: null,
                    'user_id'          => Auth::id(),
                ]);

                $totalPaid += $amtSec + $amtGarb + $amtKrt;
                $paidCount++;
            }

            DB::commit();

            $msg = "Pembayaran berhasil: {$paidCount} tagihan dibayar, total Rp " . number_format($totalPaid, 0, ',', '.');
            session()->flash('success', $msg);
            $this->isChecklistPaymentOpen = false;
            $this->checklistSelectedIds = [];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving checklist payment: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function closeChecklistPayment(): void
    {
        $this->isChecklistPaymentOpen = false;
        $this->checklistSelectedIds = [];
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    // --- Pemutihan / Pembebasan Tunggakan (Waiver) ---

    public function openWaive(int $billingId): void
    {
        $billing = IplBilling::findOrFail($billingId);
        $this->waivingBillingId = $billingId;

        // Pra-centang hanya komponen yang masih punya sisa.
        $this->waiveSecurity = $billing->remainingSecurity() > 0;
        $this->waiveGarbage  = $billing->remainingGarbage()  > 0;
        $this->waiveKasRt    = $billing->remainingKasRt()    > 0;
        $this->waiverReason  = $billing->waiver_reason ?? '';

        $this->resetErrorBag();
        session()->forget('modal_error');
        $this->isWaiveModalOpen = true;
    }

    public function saveWaive(): void
    {
        $this->validate([
            'waiverReason' => 'required|string|min:3|max:255',
        ], [
            'waiverReason.required' => 'Alasan pembebasan wajib diisi (untuk audit).',
            'waiverReason.min'      => 'Alasan pembebasan terlalu pendek.',
        ]);

        if (!$this->waiveSecurity && !$this->waiveGarbage && !$this->waiveKasRt) {
            session()->flash('modal_error', 'Pilih minimal satu komponen yang akan dibebaskan.');
            return;
        }

        try {
            DB::beginTransaction();
            $billing = IplBilling::findOrFail($this->waivingBillingId);

            // Bebaskan seluruh SISA komponen yang dicentang (tagihan − dibayar).
            // Pembebasan TIDAK dicatat sebagai kas — hanya menutup piutang.
            if ($this->waiveSecurity) {
                $billing->waived_security = max(0, (float)$billing->ipl_security_amount - (float)$billing->paid_security);
            }
            if ($this->waiveGarbage) {
                $billing->waived_garbage = max(0, (float)$billing->ipl_garbage_amount - (float)$billing->paid_garbage);
            }
            if ($this->waiveKasRt) {
                $billing->waived_kas_rt = max(0, (float)$billing->ipl_kas_rt_amount - (float)$billing->paid_kas_rt);
            }

            if ($billing->total_waived <= 0) {
                DB::rollBack();
                session()->flash('modal_error', 'Tidak ada sisa yang bisa dibebaskan pada komponen terpilih.');
                return;
            }

            $billing->waiver_reason = $this->waiverReason;
            $billing->waived_by     = Auth::id();
            $billing->waived_at     = now();
            $billing->updateStatus(); // recompute status (waived dianggap lunas, tanpa kas)

            DB::commit();
            session()->flash('success', 'Tunggakan berhasil dibebaskan (pemutihan). Tidak dicatat sebagai pemasukan kas.');
            $this->isWaiveModalOpen = false;
            $this->waivingBillingId = null;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error waiving IPL billing: ' . $e->getMessage());
            session()->flash('modal_error', 'Gagal membebaskan tunggakan: ' . $e->getMessage());
        }
    }

    public function cancelWaive(int $billingId): void
    {
        try {
            DB::beginTransaction();
            $billing = IplBilling::findOrFail($billingId);
            $billing->waived_security = 0;
            $billing->waived_garbage  = 0;
            $billing->waived_kas_rt   = 0;
            $billing->waiver_reason   = null;
            $billing->waived_by       = null;
            $billing->waived_at       = null;
            $billing->updateStatus();
            DB::commit();
            session()->flash('success', 'Pembebasan dibatalkan — tunggakan kembali aktif.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error cancelling IPL waiver: ' . $e->getMessage());
            session()->flash('error', 'Gagal membatalkan pembebasan: ' . $e->getMessage());
        }
    }

    public function closeWaiveModal(): void
    {
        $this->isWaiveModalOpen = false;
        $this->waivingBillingId = null;
        $this->resetErrorBag();
        session()->forget('modal_error');
    }

    public function updatedFilterBillingStatus(): void { $this->resetPage(); }
    public function updatedFilterBillingBlock(): void { $this->resetPage(); }
    public function updatedSelectedPeriodId(): void { $this->resetPage(); }
}
