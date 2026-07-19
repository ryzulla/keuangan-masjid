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
    public $dataAsOf;
    public $denah = [];
    public $denahExtra = 0;

    public function mount()
    {
        $this->activeCampaignsPerumahan = collect();
        $this->activeCampaignsDkm = collect();
        $this->rentalListings = collect();
        $this->dataAsOf = now()->locale('id')->isoFormat('D MMMM Y, HH:mm');

        try {
            $this->totalResidents = Resident::active()->count();
            $this->totalBlocks = HouseBlock::active()->count();
            $occupiedIds = DB::table('resident_house_blocks')
                ->where('occupancy_status', 'dihuni')
                ->pluck('house_block_id')
                ->unique();
            $this->occupiedBlocks = $occupiedIds->count();

            // Denah okupansi untuk hero — tiap sel = 1 unit nyata (dihuni vs kosong).
            $denahCap = 154; // batas visual agar grid tetap rapi
            $blocks = HouseBlock::active()
                ->orderBy('block_letter')
                ->orderBy('unit_number')
                ->take($denahCap)
                ->get(['id', 'block_letter', 'unit_number']);
            $this->denah = $blocks->map(fn ($b) => [
                'code'     => $b->block_letter . '-' . $b->unit_number,
                'occupied' => $occupiedIds->contains($b->id),
            ])->all();
            $this->denahExtra = max(0, $this->totalBlocks - count($this->denah));

            $perumahanOn = \App\Models\Setting::moduleEnabled('perumahan');
            $dkmOn = \App\Models\Setting::moduleEnabled('dkm');

            if ($perumahanOn) {
                $this->activeCampaignsPerumahan = Campaign::withSum('transactions', 'amount')
                    ->where('status', 'active')
                    ->where('organization_type', 'perumahan')
                    ->orderBy('start_date', 'desc')
                    ->take(3)
                    ->get();
            }

            if ($dkmOn) {
                $this->activeCampaignsDkm = Campaign::withSum('transactions', 'amount')
                    ->where('status', 'active')
                    ->where('organization_type', 'dkm')
                    ->orderBy('start_date', 'desc')
                    ->take(3)
                    ->get();
            }

            $currentPeriod = $perumahanOn ? IplPeriod::where('year', now()->year)->where('month', now()->month)->first() : null;
            if ($currentPeriod) {
                $this->currentIplPeriod = $currentPeriod;
                $this->iplSummary = [
                    'lunas' => IplBilling::where('ipl_period_id', $currentPeriod->id)->where('status', 'paid')->count(),
                    'belum' => IplBilling::where('ipl_period_id', $currentPeriod->id)->where('status', 'unpaid')->count(),
                    'sebagian' => IplBilling::where('ipl_period_id', $currentPeriod->id)->where('status', 'partial')->count(),
                    'total_unit' => IplBilling::where('ipl_period_id', $currentPeriod->id)->count(),
                    'terkumpul' => IplBilling::where('ipl_period_id', $currentPeriod->id)
                        ->sum(DB::raw('paid_security + paid_garbage + paid_kas_rt')),
                    'terkumpul_security' => IplBilling::where('ipl_period_id', $currentPeriod->id)->sum('paid_security'),
                    'terkumpul_garbage'  => IplBilling::where('ipl_period_id', $currentPeriod->id)->sum('paid_garbage'),
                    'terkumpul_kas_rt'   => IplBilling::where('ipl_period_id', $currentPeriod->id)->sum('paid_kas_rt'),
                    'tunggakan' => IplBilling::where('ipl_period_id', $currentPeriod->id)
                        ->sum(DB::raw(
                            '(ipl_security_amount - paid_security - waived_security)'
                            . ' + (ipl_garbage_amount - paid_garbage - waived_garbage)'
                            . ' + (ipl_kas_rt_amount - paid_kas_rt - waived_kas_rt)'
                        )),
                ];
            }

            if ($dkmOn) {
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
            }

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
