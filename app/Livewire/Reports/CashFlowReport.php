<?php
namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Untuk logging error
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CashFlowReport extends Component
{
    public $month;
    public $year;
    public $availableYears = [];

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
        $this->availableYears = range(now()->year, now()->year - 5);
    }

    #[Computed]
    public function reportData()
    {
        try {
            // Pastikan format bulan benar (tambahkan 0 jika perlu)
            $monthPadded = str_pad($this->month, 2, '0', STR_PAD_LEFT);
            $startDate = "{$this->year}-{$monthPadded}-01";
            $endDate = date('Y-m-t', strtotime($startDate));

            // 1. Saldo Awal
            // Query ini tidak join, jadi 'type' tidak ambigu
            $totalDebitBefore = Transaction::where('transaction_date', '<', $startDate)
                                ->where('type', 'debit')->sum('amount');
            $totalCreditBefore = Transaction::where('transaction_date', '<', $startDate)
                                 ->where('type', 'credit')->sum('amount');
            $startingBalance = (float)($totalDebitBefore ?? 0) - (float)($totalCreditBefore ?? 0);

            // 2. Pemasukan - PERBAIKAN DI SINI
            $incomeSummary = Transaction::where('transactions.type', 'debit') // <-- Gunakan transactions.type
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('categories.name')->orderBy('total', 'desc')->get();

            // 3. Pengeluaran - PERBAIKAN DI SINI
            $expenseSummary = Transaction::where('transactions.type', 'credit') // <-- Gunakan transactions.type
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
                ->groupBy('categories.name')->orderBy('total', 'desc')->get();

            // 4. Total
            $totalIncome = $incomeSummary->sum('total');
            $totalExpense = $expenseSummary->sum('total');

            // 5. Saldo Akhir
            $endingBalance = $startingBalance + (float)($totalIncome ?? 0) - (float)($totalExpense ?? 0);

            // 6. Validasi
            $actualBalance = Account::sum('balance');
            $discrepancy = $endingBalance - (float)($actualBalance ?? 0);

            $data = compact('startingBalance', 'incomeSummary', 'expenseSummary', 'totalIncome', 'totalExpense', 'endingBalance', 'discrepancy', 'startDate', 'endDate');
            return $data;

        } catch (\Exception $e) {
            Log::error('Error calculating reportData in CashFlowReport: ' . $e->getMessage());
            session()->flash('report_error', 'Gagal menghitung data laporan: ' . $e->getMessage());
            // Kembalikan array default jika terjadi error
             return [
                'startingBalance' => 0, 'incomeSummary' => collect(), 'expenseSummary' => collect(),
                'totalIncome' => 0, 'totalExpense' => 0, 'endingBalance' => 0, 'discrepancy' => 0,
                'startDate' => now()->startOfMonth()->toDateString(), 'endDate' => now()->endOfMonth()->toDateString()
            ];
        }
    }

    public function render()
    {
        return view('livewire.reports.cash-flow-report');
         // Tidak perlu passing 'reportData' karena sudah computed
    }
}
