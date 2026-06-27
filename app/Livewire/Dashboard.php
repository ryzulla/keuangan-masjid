<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Campaign;
use App\Models\IplPeriod;
use App\Models\IplBilling;
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
    public $recentTransactions;
    public $activeCampaignsDkm;
    public $activeCampaignsPerumahan;
    public $iplSummary = [];

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

            $this->recentTransactions = Transaction::with(['category', 'account'])
                ->latest('transaction_date')->latest('id')->take(10)->get();

            $this->activeCampaignsDkm = Campaign::withSum(['transactions' => fn($q) => $q->where('transactions.type', 'debit')], 'amount')
                ->where('status', 'active')->where('organization_type', 'dkm')
                ->orderBy('start_date', 'desc')->take(3)->get();

            $this->activeCampaignsPerumahan = Campaign::withSum(['transactions' => fn($q) => $q->where('transactions.type', 'debit')], 'amount')
                ->where('status', 'active')->where('organization_type', 'perumahan')
                ->orderBy('start_date', 'desc')->take(3)->get();

            $latestPeriod = IplPeriod::latest('year')->latest('month')->first();
            if ($latestPeriod) {
                $this->iplSummary = [
                    'period' => $latestPeriod->period_label,
                    'total_tagihan' => IplBilling::where('ipl_period_id', $latestPeriod->id)->sum(DB::raw('ipl_security_amount + ipl_garbage_amount')),
                    'total_terbayar' => IplBilling::where('ipl_period_id', $latestPeriod->id)->sum(DB::raw('paid_security + paid_garbage')),
                    'jumlah_lunas' => IplBilling::where('ipl_period_id', $latestPeriod->id)->where('status', 'paid')->count(),
                    'jumlah_unit' => IplBilling::where('ipl_period_id', $latestPeriod->id)->count(),
                ];
                $this->iplSummary['tunggakan'] = $this->iplSummary['total_tagihan'] - $this->iplSummary['total_terbayar'];
            }

        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat data dashboard.');
            $this->recentTransactions = collect();
            $this->activeCampaignsDkm = collect();
            $this->activeCampaignsPerumahan = collect();
        }
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
