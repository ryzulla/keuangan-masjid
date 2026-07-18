<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Campaign;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\Resident;
use App\Models\ResidentHouseBlock;
use App\Models\ResidentPaymentRequest;
use App\Models\HouseBlock;
use Illuminate\Support\Facades\DB as DBFacade;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function mount()
    {
        // intentionally empty — all data loaded in render()
    }

    public function render()
    {
        try {
            $now = Carbon::now();
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();

            // ─── Saldo ──────────────────────────────────────────────────
            $perumahanBalance = Account::byOrg('perumahan')->sum('balance');
            $dkmBalance = Account::byOrg('dkm')->sum('balance');

            // ─── Keuangan Bulan Ini ─────────────────────────────────────
            $perumahanAccountIds = Account::byOrg('perumahan')->pluck('id');
            $dkmAccountIds = Account::byOrg('dkm')->pluck('id');

            $monthlyIncomePerumahan = Transaction::where('type', 'debit')
                ->whereIn('account_id', $perumahanAccountIds)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');
            $monthlyExpensePerumahan = Transaction::where('type', 'credit')
                ->whereIn('account_id', $perumahanAccountIds)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');
            $monthlyIncomeDkm = Transaction::where('type', 'debit')
                ->whereIn('account_id', $dkmAccountIds)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');
            $monthlyExpenseDkm = Transaction::where('type', 'credit')
                ->whereIn('account_id', $dkmAccountIds)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            // ─── IPL Summary ────────────────────────────────────────────
            $iplSummary = [];
            $latestPeriod = IplPeriod::has('billings')->orderByDesc('year')->orderByDesc('month')->first();
            if ($latestPeriod) {
                $billingBase = IplBilling::where('ipl_period_id', $latestPeriod->id);
                $iplSummary = [
                    'period'         => $latestPeriod->period_label,
                    'total_tagihan'  => (clone $billingBase)->sum(DB::raw('ipl_security_amount + ipl_garbage_amount + ipl_kas_rt_amount')),
                    'total_terbayar' => (clone $billingBase)->sum(DB::raw('paid_security + paid_garbage + paid_kas_rt')),
                    'jumlah_lunas'   => (clone $billingBase)->where('status', 'paid')->count(),
                    'jumlah_partial' => (clone $billingBase)->where('status', 'partial')->count(),
                    'jumlah_unpaid'  => (clone $billingBase)->where('status', 'unpaid')->count(),
                    'jumlah_unit'    => (clone $billingBase)->count(),
                ];
                $iplSummary['tunggakan'] = $iplSummary['total_tagihan'] - $iplSummary['total_terbayar'];
            }

            // ─── Alerts ─────────────────────────────────────────────────
            $pendingPayments = ResidentPaymentRequest::where('status', 'pending')->count();

            $expiringContracts = ResidentHouseBlock::whereNull('ended_at')
                ->where('ownership_type', '!=', 'pemilik')
                ->whereNotNull('contract_end_date')
                ->where('contract_end_date', '<=', $now->copy()->addDays(30))
                ->with(['resident', 'houseBlock'])
                ->orderBy('contract_end_date')
                ->get();

            // ─── Occupancy ──────────────────────────────────────────────
            $totalBlocks = HouseBlock::count();
            $assignedBlocks = DBFacade::table('resident_house_blocks')
                ->whereNull('ended_at')
                ->distinct('house_block_id')
                ->count('house_block_id');

            // ─── Campaigns ──────────────────────────────────────────────
            $campaigns = Campaign::withSum(['transactions' => fn($q) => $q->where('transactions.type', 'debit')], 'amount')
                ->where('status', 'active')
                ->orderBy('start_date', 'desc')
                ->take(4)
                ->get();

            // ─── Contracted Houses ──────────────────────────────────────
            $contractedHouses = ResidentHouseBlock::whereNull('ended_at')
                ->where('ownership_type', '!=', 'pemilik')
                ->with(['resident', 'houseBlock', 'houseBlock.owners'])
                ->orderBy('contract_end_date')
                ->get();

            // ─── Total Residents ────────────────────────────────────────
            $totalResidents = Resident::where('is_active', true)->count();

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat data dashboard.');
            $perumahanBalance = $dkmBalance = 0;
            $monthlyIncomePerumahan = $monthlyExpensePerumahan = 0;
            $monthlyIncomeDkm = $monthlyExpenseDkm = 0;
            $iplSummary = [];
            $pendingPayments = 0;
            $expiringContracts = collect();
            $totalBlocks = $assignedBlocks = 0;
            $campaigns = collect();
            $contractedHouses = collect();
            $totalResidents = 0;
        }

        return view('livewire.dashboard', compact(
            'perumahanBalance', 'dkmBalance',
            'monthlyIncomePerumahan', 'monthlyExpensePerumahan',
            'monthlyIncomeDkm', 'monthlyExpenseDkm',
            'iplSummary', 'pendingPayments', 'expiringContracts',
            'totalBlocks', 'assignedBlocks', 'campaigns',
            'contractedHouses', 'totalResidents'
        ));
    }
}
