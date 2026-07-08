<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Campaign;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\Resident;
use Illuminate\Support\Facades\DB as DBFacade;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public $dkmBalance = 0;
    public $perumahanBalance = 0;
    public $monthlyIncomeDkm = 0;
    public $monthlyExpenseDkm = 0;
    public $monthlyIncomePerumahan = 0;
    public $monthlyExpensePerumahan = 0;

    public $activeCampaignsDkm;
    public $activeCampaignsPerumahan;
    public $iplSummary = [];
    public $totalResidents = 0;
    public $totalAssignedBlocks = 0;

    public function mount()
    {
        try {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $this->dkmBalance = Account::byOrg('dkm')->sum('balance');
            $this->perumahanBalance = Account::byOrg('perumahan')->sum('balance');

            $dkmAccountIds = Account::byOrg('dkm')->pluck('id');
            $this->monthlyIncomeDkm = Transaction::where('type', 'debit')
                ->whereIn('account_id', $dkmAccountIds)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');
            $this->monthlyExpenseDkm = Transaction::where('type', 'credit')
                ->whereIn('account_id', $dkmAccountIds)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

            $this->totalResidents = Resident::where('is_active', true)->count();
            $this->totalAssignedBlocks = DBFacade::table('resident_house_blocks')->distinct('house_block_id')->count('house_block_id');

            $this->activeCampaignsDkm = Campaign::withSum(['transactions' => fn($q) => $q->where('transactions.type', 'debit')], 'amount')
                ->where('status', 'active')->where('organization_type', 'dkm')
                ->orderBy('start_date', 'desc')->take(3)->get();

            $this->activeCampaignsPerumahan = Campaign::withSum(['transactions' => fn($q) => $q->where('transactions.type', 'debit')], 'amount')
                ->where('status', 'active')->where('organization_type', 'perumahan')
                ->orderBy('start_date', 'desc')->take(3)->get();

            $latestPeriod = IplPeriod::orderByDesc('year')->orderByDesc('month')->first();
            if ($latestPeriod) {
                $billingBase = IplBilling::where('ipl_period_id', $latestPeriod->id);
                $this->iplSummary = [
                    'period'         => $latestPeriod->period_label,
                    'total_tagihan'  => (clone $billingBase)->sum(DB::raw('ipl_security_amount + ipl_garbage_amount')),
                    'total_terbayar' => (clone $billingBase)->sum(DB::raw('paid_security + paid_garbage')),
                    'jumlah_lunas'   => (clone $billingBase)->where('status', 'paid')->count(),
                    'jumlah_partial' => (clone $billingBase)->where('status', 'partial')->count(),
                    'jumlah_unpaid'  => (clone $billingBase)->where('status', 'unpaid')->count(),
                    'jumlah_unit'    => (clone $billingBase)->count(),
                ];
                $this->iplSummary['tunggakan'] = $this->iplSummary['total_tagihan'] - $this->iplSummary['total_terbayar'];
            }

            $perumahanAccountIds = Account::byOrg('perumahan')->pluck('id');
            $this->monthlyIncomePerumahan = Transaction::where('type', 'debit')
                ->whereIn('account_id', $perumahanAccountIds)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');
            $this->monthlyExpensePerumahan = Transaction::where('type', 'credit')
                ->whereIn('account_id', $perumahanAccountIds)
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat data dashboard.');
            $this->activeCampaignsDkm = collect();
            $this->activeCampaignsPerumahan = collect();
            $this->iplSummary = [];
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
