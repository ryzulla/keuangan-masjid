<?php
namespace App\Livewire\IPL;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\HouseBlock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class IPLReport extends Component
{
    // --- View toggle ---
    public string $activeView = 'summary'; // 'summary' | 'per_block'

    // --- Summary view (per period) ---
    public ?int $selectedPeriodId = null;
    public string $periodFilterMonth = '';
    public string $periodFilterYear = '';

    // --- Per-block view (per year) ---
    public string $filterYear    = '';
    public string $filterBlockId = '';

    public $allBlocks = [];

    public function mount(): void
    {
        $this->periodFilterMonth = (string) now()->month;
        $this->periodFilterYear = (string) now()->year;
        $this->filterYear = (string) now()->year;

        $currentPeriod = IplPeriod::where('year', now()->year)->where('month', now()->month)->first();
        if ($currentPeriod) {
            $this->selectedPeriodId = $currentPeriod->id;
        }
        $this->allBlocks = HouseBlock::active()
            ->orderBy('block_letter')->orderBy('unit_number')
            ->get(['id', 'block_letter', 'unit_number']);
    }

    public function render()
    {
        $periods = IplPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->get();

        if ($this->activeView === 'per_block') {
            return $this->renderPerBlock($periods);
        }
        return $this->renderSummary($periods);
    }

    // ──────────────────────────────────────────────
    // SUMMARY VIEW (per period/month)
    // ──────────────────────────────────────────────
    private function renderSummary($periods)
    {
        $currentPeriod = $this->selectedPeriodId ? IplPeriod::find($this->selectedPeriodId) : null;

        $summaryByBlock = [];
        $unpaidResidents = [];

        if ($currentPeriod) {
            $summaryByBlock = IplBilling::query()
                ->select(
                    'house_blocks.block_letter',
                    DB::raw('SUM(ipl_billings.ipl_security_amount + ipl_billings.ipl_garbage_amount + ipl_billings.ipl_kas_rt_amount) as total_tagihan'),
                    DB::raw('SUM(ipl_billings.paid_security + ipl_billings.paid_garbage + ipl_billings.paid_kas_rt) as total_terbayar'),
                    DB::raw('SUM(ipl_billings.waived_security + ipl_billings.waived_garbage + ipl_billings.waived_kas_rt) as total_dibebaskan'),
                    DB::raw('COUNT(*) as jumlah_unit'),
                    DB::raw('SUM(CASE WHEN ipl_billings.status = "paid"    THEN 1 ELSE 0 END) as lunas'),
                    DB::raw('SUM(CASE WHEN ipl_billings.status = "unpaid"  THEN 1 ELSE 0 END) as belum_bayar'),
                    DB::raw('SUM(CASE WHEN ipl_billings.status = "partial" THEN 1 ELSE 0 END) as sebagian')
                )
                ->leftJoin('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id')
                ->where('ipl_period_id', $currentPeriod->id)
                ->whereNotNull('ipl_billings.responsible_resident_id')
                ->groupBy('house_blocks.block_letter')
                ->orderBy('house_blocks.block_letter')
                ->get();

            $unpaidResidents = IplBilling::query()
                ->with(['responsibleResident', 'houseBlock'])
                ->where('ipl_period_id', $currentPeriod->id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->whereNotNull('responsible_resident_id')
                ->leftJoin('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id')
                ->orderBy('house_blocks.block_letter')
                ->orderBy('house_blocks.unit_number')
                ->select('ipl_billings.*')
                ->get();
        }

        $totals = [];
        if ($currentPeriod) {
            $base = IplBilling::where('ipl_period_id', $currentPeriod->id)
                ->whereNotNull('responsible_resident_id');
            $totals = [
                'tagihan'    => (clone $base)->sum(DB::raw('ipl_security_amount + ipl_garbage_amount + ipl_kas_rt_amount')),
                'terbayar'   => (clone $base)->sum(DB::raw('paid_security + paid_garbage + paid_kas_rt')),
                'dibebaskan' => (clone $base)->sum(DB::raw('waived_security + waived_garbage + waived_kas_rt')),
                'lunas'      => (clone $base)->where('status', 'paid')->count(),
                'belum'      => (clone $base)->where('status', 'unpaid')->count(),
                'sebagian'   => (clone $base)->where('status', 'partial')->count(),
                'unit'       => (clone $base)->count(),
            ];
            // Tunggakan riil = tagihan − terbayar (kas) − dibebaskan (pemutihan, non-kas).
            $totals['tunggakan'] = max(0, $totals['tagihan'] - $totals['terbayar'] - $totals['dibebaskan']);
        }

        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];

        $periodYears = IplPeriod::select('year')->distinct()->orderByDesc('year')->pluck('year')->toArray();
        $minYear = $periodYears ? min(min($periodYears), now()->year) : now()->year;
        $maxYear = $periodYears ? max(max($periodYears), now()->year + 1) : now()->year + 1;
        $availableYears = range($minYear, $maxYear);

        return view('livewire.ipl.ipl-report', [
            'periods'         => $periods,
            'currentPeriod'   => $currentPeriod,
            'summaryByBlock'  => $summaryByBlock,
            'unpaidResidents' => $unpaidResidents,
            'totals'          => $totals,
            'blockMatrix'     => [],
            'yearPeriods'     => collect(),
            'availableYears'  => $availableYears,
            'months'          => $months,
        ]);
    }

    // ──────────────────────────────────────────────
    // PER-BLOCK VIEW (per year matrix)
    // ──────────────────────────────────────────────
    private function renderPerBlock($periods)
    {
        $year = (int) ($this->filterYear ?: now()->year);

        // Periods that exist for this year, keyed by month (1–12)
        $yearPeriods = IplPeriod::where('year', $year)
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // House blocks to display (eager-load residents for name display)
        $blocksQuery = HouseBlock::with('residents')->active()
            ->orderBy('block_letter')->orderBy('unit_number');
        if ($this->filterBlockId) {
            $blocksQuery->where('id', $this->filterBlockId);
        }
        $blocks = $blocksQuery->get();

        // All billings for those periods (single query, eager chargeItems)
        $periodIds = $yearPeriods->pluck('id');
        $billingsFlat = $periodIds->isNotEmpty()
            ? IplBilling::with(['responsibleResident', 'houseBlock', 'chargeItems'])
                ->whereIn('ipl_period_id', $periodIds)
                ->whereNotNull('responsible_resident_id')
                ->when($this->filterBlockId, fn($q) => $q->where('house_block_id', $this->filterBlockId))
                ->get()
            : collect();

        // Index: [block_id][period_id] => billing
        $billingsMap = [];
        foreach ($billingsFlat as $b) {
            $billingsMap[$b->house_block_id][$b->ipl_period_id] = $b;
        }

        // Build matrix
        $blockMatrix = [];
        $grandTotalOutstanding = 0;
        $totalUnpaidBlocks     = 0;

        foreach ($blocks as $block) {
            $monthCells      = [];
            $totalOutstanding = 0;
            $unpaidMonths    = [];
            $hasAnyBilling   = false;

            foreach (range(1, 12) as $m) {
                $period = $yearPeriods->get($m);
                if (!$period) {
                    $monthCells[$m] = ['status' => 'no_period'];
                    continue;
                }
                $billing = $billingsMap[$block->id][$period->id] ?? null;
                if (!$billing) {
                    $monthCells[$m] = ['status' => 'no_billing'];
                    continue;
                }
                $hasAnyBilling   = true;
                $outstanding     = $billing->outstanding;
                $totalOutstanding += $outstanding;
                if ($billing->status !== 'paid') {
                    $unpaidMonths[] = $m;
                }
                $monthCells[$m] = [
                    'status'      => $billing->status,
                    'outstanding' => $outstanding,
                    'billing_id'  => $billing->id,
                ];
            }

            if ($totalOutstanding > 0) $totalUnpaidBlocks++;
            $grandTotalOutstanding += $totalOutstanding;

            $blockMatrix[] = [
                'block'           => $block,
                'months'          => $monthCells,
                'totalOutstanding' => $totalOutstanding,
                'unpaidMonths'    => $unpaidMonths,
                'hasAnyBilling'   => $hasAnyBilling,
            ];
        }

        return view('livewire.ipl.ipl-report', [
            'periods'              => $periods,
            'currentPeriod'        => null,
            'summaryByBlock'       => [],
            'unpaidResidents'      => [],
            'totals'               => [],
            'blockMatrix'          => $blockMatrix,
            'yearPeriods'          => $yearPeriods,
            'grandTotalOutstanding' => $grandTotalOutstanding,
            'totalUnpaidBlocks'    => $totalUnpaidBlocks,
            'availableYears'       => $this->getAvailableYears(),
            'selectedYear'         => $year,
        ]);
    }

    private function getAvailableYears(): array
    {
        $years = IplPeriod::distinct()->orderBy('year', 'desc')->pluck('year')->toArray();
        if (empty($years)) $years = [now()->year];
        return $years;
    }

    // --- Actions ---
    public function selectPeriod(int $id): void
    {
        $this->selectedPeriodId = $id;
    }

    public function selectByPeriod(): void
    {
        $period = IplPeriod::firstOrCreate(
            ['year' => $this->periodFilterYear, 'month' => $this->periodFilterMonth],
            [
                'ipl_security_amount' => \App\Models\IplTariffType::where('billing_key', 'security')->value('default_amount') ?? 0,
                'ipl_garbage_amount'  => \App\Models\IplTariffType::where('billing_key', 'garbage')->value('default_amount') ?? 0,
                'ipl_kas_rt_amount'   => \App\Models\IplTariffType::where('billing_key', 'kas_rt')->value('default_amount') ?? 0,
            ]
        );
        $this->selectedPeriodId = $period->id;
    }

    public function switchView(string $view): void
    {
        $this->activeView = $view;
    }

    public function printPdf()
    {
        $currentPeriod = $this->selectedPeriodId ? IplPeriod::find($this->selectedPeriodId) : null;
        if (!$currentPeriod) {
            session()->flash('error', 'Pilih periode terlebih dahulu.');
            return;
        }

        $summaryByBlock = IplBilling::query()
            ->select(
                'house_blocks.block_letter',
                DB::raw('SUM(ipl_billings.ipl_security_amount + ipl_billings.ipl_garbage_amount + ipl_billings.ipl_kas_rt_amount) as total_tagihan'),
                DB::raw('SUM(ipl_billings.paid_security + ipl_billings.paid_garbage + ipl_billings.paid_kas_rt) as total_terbayar'),
                DB::raw('SUM(ipl_billings.waived_security + ipl_billings.waived_garbage + ipl_billings.waived_kas_rt) as total_dibebaskan'),
                DB::raw('COUNT(*) as jumlah_unit'),
                DB::raw('SUM(CASE WHEN ipl_billings.status = "paid"    THEN 1 ELSE 0 END) as lunas'),
                DB::raw('SUM(CASE WHEN ipl_billings.status = "unpaid"  THEN 1 ELSE 0 END) as belum_bayar'),
                DB::raw('SUM(CASE WHEN ipl_billings.status = "partial" THEN 1 ELSE 0 END) as sebagian')
            )
            ->leftJoin('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id')
            ->where('ipl_period_id', $currentPeriod->id)
            ->whereNotNull('ipl_billings.responsible_resident_id')
            ->groupBy('house_blocks.block_letter')
            ->orderBy('house_blocks.block_letter')
            ->get();

        $unpaidResidents = IplBilling::query()
            ->with(['responsibleResident', 'houseBlock'])
            ->where('ipl_period_id', $currentPeriod->id)
            ->whereIn('status', ['unpaid', 'partial'])
            ->whereNotNull('responsible_resident_id')
            ->leftJoin('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id')
            ->orderBy('house_blocks.block_letter')
            ->orderBy('house_blocks.unit_number')
            ->select('ipl_billings.*')
            ->get();

        $base = IplBilling::where('ipl_period_id', $currentPeriod->id)
            ->whereNotNull('responsible_resident_id');
        $totals = [
            'tagihan'    => (clone $base)->sum(DB::raw('ipl_security_amount + ipl_garbage_amount + ipl_kas_rt_amount')),
            'terbayar'   => (clone $base)->sum(DB::raw('paid_security + paid_garbage + paid_kas_rt')),
            'dibebaskan' => (clone $base)->sum(DB::raw('waived_security + waived_garbage + waived_kas_rt')),
            'lunas'      => (clone $base)->where('status', 'paid')->count(),
            'belum'      => (clone $base)->where('status', 'unpaid')->count(),
            'sebagian'   => (clone $base)->where('status', 'partial')->count(),
            'unit'       => (clone $base)->count(),
        ];
        $totals['tunggakan'] = max(0, $totals['tagihan'] - $totals['terbayar'] - $totals['dibebaskan']);

        $filename = 'laporan_ipl_' . $currentPeriod->period_label . '.pdf';
        $pdf = Pdf::loadView('exports.ipl-report-pdf', [
            'period'          => $currentPeriod,
            'summaryByBlock'  => $summaryByBlock,
            'unpaidResidents' => $unpaidResidents,
            'totals'          => $totals,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(fn() => print($pdf->output()), $filename);
    }
}
