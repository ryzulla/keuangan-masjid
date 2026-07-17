<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Campaign;
use App\Models\Resident;
use App\Models\HouseBlock;
use App\Models\HouseBlockPhoto;
use App\Models\IplPeriod;
use App\Models\IplBilling;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('layouts.public')]
class WelcomePage extends Component
{
    public $totalResidents = 0;
    public $totalBlocks = 0;
    public $occupiedBlocks = 0;
    public $activeCampaignsPerumahan;
    public $activeCampaignsDkm;
    public $iplSummary = [];
    public $currentIplPeriod = null;
    public $dkmBalance = 0;
    public $dkmMonthlyIncome = 0;
    public $dkmMonthlyExpense = 0;
    public $rentalListings;

    public function mount()
    {
        $this->activeCampaignsPerumahan = collect();
        $this->activeCampaignsDkm = collect();
        $this->rentalListings = collect();

        try {
            $this->totalResidents = Resident::active()->count();
            $this->totalBlocks = HouseBlock::active()->count();
            $this->occupiedBlocks = DB::table('resident_house_blocks')
                ->where('occupancy_status', 'dihuni')
                ->distinct('house_block_id')
                ->count('house_block_id');

            $this->activeCampaignsPerumahan = Campaign::withSum('transactions', 'amount')
                ->where('status', 'active')
                ->where('organization_type', 'perumahan')
                ->orderBy('start_date', 'desc')
                ->take(3)
                ->get();

            $this->activeCampaignsDkm = Campaign::withSum('transactions', 'amount')
                ->where('status', 'active')
                ->where('organization_type', 'dkm')
                ->orderBy('start_date', 'desc')
                ->take(3)
                ->get();

            $currentPeriod = IplPeriod::where('year', now()->year)->where('month', now()->month)->first();
            if ($currentPeriod) {
                $this->currentIplPeriod = $currentPeriod;
                $this->iplSummary = [
                    'lunas' => IplBilling::where('ipl_period_id', $currentPeriod->id)->where('status', 'paid')->count(),
                    'belum' => IplBilling::where('ipl_period_id', $currentPeriod->id)->where('status', 'unpaid')->count(),
                    'sebagian' => IplBilling::where('ipl_period_id', $currentPeriod->id)->where('status', 'partial')->count(),
                    'total_unit' => IplBilling::where('ipl_period_id', $currentPeriod->id)->count(),
                    'terkumpul' => IplBilling::where('ipl_period_id', $currentPeriod->id)->sum(DB::raw('paid_security + paid_garbage')),
                    'tunggakan' => IplBilling::where('ipl_period_id', $currentPeriod->id)
                        ->sum(DB::raw('(ipl_security_amount - paid_security) + (ipl_garbage_amount - paid_garbage)')),
                ];
            }

            $dkmAccountIds = Account::byOrg('dkm')->pluck('id');
            $this->dkmBalance = Account::byOrg('dkm')->sum('balance');
            $this->dkmMonthlyIncome = Transaction::where('type', 'debit')
                ->whereIn('account_id', $dkmAccountIds)
                ->whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year)
                ->sum('amount');
            $this->dkmMonthlyExpense = Transaction::where('type', 'credit')
                ->whereIn('account_id', $dkmAccountIds)
                ->whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year)
                ->sum('amount');

            $this->rentalListings = HouseBlock::active()
                ->where('is_for_rent', true)
                ->with(['photos', 'owners'])
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();

        } catch (\Exception $e) {
            Log::error('Error fetching welcome page data: ' . $e->getMessage());
            session()->flash('page_error', 'Gagal memuat data. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.welcome-page');
    }
}
