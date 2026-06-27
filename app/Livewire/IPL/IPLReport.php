<?php
namespace App\Livewire\IPL;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\HouseBlock;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class IPLReport extends Component
{
    public ?int $selectedPeriodId = null;
    public string $groupBy = 'block';

    public function mount(): void
    {
        $latestPeriod = IplPeriod::latest('year')->latest('month')->first();
        if ($latestPeriod) {
            $this->selectedPeriodId = $latestPeriod->id;
        }
    }

    public function render()
    {
        $periods = IplPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        $currentPeriod = $this->selectedPeriodId ? IplPeriod::find($this->selectedPeriodId) : null;

        $summaryByBlock = [];
        $unpaidResidents = [];

        if ($currentPeriod) {
            $summaryByBlock = IplBilling::query()
                ->select(
                    'house_blocks.block_letter',
                    DB::raw('SUM(ipl_billings.ipl_security_amount + ipl_billings.ipl_garbage_amount) as total_tagihan'),
                    DB::raw('SUM(ipl_billings.paid_security + ipl_billings.paid_garbage) as total_terbayar'),
                    DB::raw('COUNT(*) as jumlah_unit'),
                    DB::raw('SUM(CASE WHEN ipl_billings.status = "paid" THEN 1 ELSE 0 END) as lunas'),
                    DB::raw('SUM(CASE WHEN ipl_billings.status = "unpaid" THEN 1 ELSE 0 END) as belum_bayar'),
                    DB::raw('SUM(CASE WHEN ipl_billings.status = "partial" THEN 1 ELSE 0 END) as sebagian')
                )
                ->leftJoin('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id')
                ->where('ipl_period_id', $currentPeriod->id)
                ->groupBy('house_blocks.block_letter')
                ->orderBy('house_blocks.block_letter')
                ->get();

            $unpaidResidents = IplBilling::query()
                ->with(['resident', 'houseBlock'])
                ->where('ipl_period_id', $currentPeriod->id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->join('house_blocks', 'ipl_billings.house_block_id', '=', 'house_blocks.id', 'left')
                ->orderBy('house_blocks.block_letter')
                ->orderBy('house_blocks.unit_number')
                ->select('ipl_billings.*')
                ->get();
        }

        $totals = $currentPeriod ? [
            'tagihan' => IplBilling::where('ipl_period_id', $currentPeriod->id)->sum(DB::raw('ipl_security_amount + ipl_garbage_amount')),
            'terbayar' => IplBilling::where('ipl_period_id', $currentPeriod->id)->sum(DB::raw('paid_security + paid_garbage')),
            'lunas' => IplBilling::where('ipl_period_id', $currentPeriod->id)->where('status', 'paid')->count(),
            'belum' => IplBilling::where('ipl_period_id', $currentPeriod->id)->where('status', 'unpaid')->count(),
            'unit' => IplBilling::where('ipl_period_id', $currentPeriod->id)->count(),
        ] : [];

        if (isset($totals['tagihan'], $totals['terbayar'])) {
            $totals['tunggakan'] = $totals['tagihan'] - $totals['terbayar'];
        }

        return view('livewire.ipl.ipl-report', [
            'periods' => $periods,
            'currentPeriod' => $currentPeriod,
            'summaryByBlock' => $summaryByBlock,
            'unpaidResidents' => $unpaidResidents,
            'totals' => $totals,
        ]);
    }

    public function selectPeriod(int $id): void
    {
        $this->selectedPeriodId = $id;
    }
}
