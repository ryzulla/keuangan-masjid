<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Campaign; // <-- Add use Campaign
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // Specify the layout
class Dashboard extends Component
{
    public $totalBalance = 0;
    public $monthlyIncome = 0;
    public $monthlyExpense = 0;
    public $recentTransactions;
    public $activeCampaigns; // <-- New property for campaigns

    public function mount()
    {
        try {
            // Calculate total balance
            $this->totalBalance = Account::sum('balance');

            // Define current month range
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // Calculate monthly income/expense
            $this->monthlyIncome = Transaction::where('type', 'debit')
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');
            $this->monthlyExpense = Transaction::where('type', 'credit')
                ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('amount');

            // Fetch recent transactions
            $this->recentTransactions = Transaction::with(['category', 'account'])
                ->latest('transaction_date')->latest('id')->take(20)->get();

            // --- FETCH ACTIVE CAMPAIGNS ---
            $this->activeCampaigns = Campaign::withSum(['transactions' => function ($query) {
                                            $query->where('transactions.type', 'debit');
                                        }], 'amount') // Hitung HANYA transaksi 'debit'
                                        ->where('status', 'active') // Campaign harus aktif
                                        // ->where('type', 'debit') <-- HAPUS BARIS INI
                                        ->orderBy('start_date', 'desc')
                                        ->take(3)
                                        ->get();
            // --- END FETCH CAMPAIGNS ---

        } catch (\Exception $e) {
            Log::error('Error fetching dashboard data: ' . $e->getMessage());
            session()->flash('error', 'Gagal memuat data dashboard. Silakan coba lagi.');
            // Set defaults on error
            $this->totalBalance = 'Error';
            $this->monthlyIncome = 'Error';
            $this->monthlyExpense = 'Error';
            $this->recentTransactions = collect();
            $this->activeCampaigns = collect(); // Set empty collection on error
        }
    }

    public function render()
    {
        // Public properties are automatically passed
        return view('livewire.dashboard');
    }
}
