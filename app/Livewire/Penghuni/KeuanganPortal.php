<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Account;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.penghuni')]
class KeuanganPortal extends Component
{
    public $month;
    public $year;
    public string $activeOrg = 'semua';

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;

        // Bila org aktif ternyata modulnya dimatikan, kembali ke 'semua'.
        if ($this->activeOrg !== 'semua' && ! \App\Models\Setting::moduleEnabled($this->activeOrg)) {
            $this->activeOrg = 'semua';
        }
    }

    public function updatedActiveOrg($value)
    {
        if ($value !== 'semua' && ! \App\Models\Setting::moduleEnabled($value)) {
            $this->activeOrg = 'semua';
        }
    }

    /** Org yang boleh ditampilkan di keuangan (modul aktif + akun umum). */
    private function keuanganOrgs(): array
    {
        return array_merge(\App\Models\Setting::enabledOrgs(), ['umum']);
    }

    #[Computed]
    public function accounts()
    {
        return Account::when(
                $this->activeOrg !== 'semua',
                fn($q) => $q->where('organization_type', $this->activeOrg),
                fn($q) => $q->whereIn('organization_type', $this->keuanganOrgs())
            )
            ->orderBy('organization_type')->orderBy('name')->get();
    }

    #[Computed]
    public function monthlySummary()
    {
        $monthPadded = str_pad($this->month, 2, '0', STR_PAD_LEFT);
        $startDate = "{$this->year}-{$monthPadded}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $orgAccountIds = $this->activeOrg !== 'semua'
            ? Account::where('organization_type', $this->activeOrg)->pluck('id')
            : Account::whereIn('organization_type', $this->keuanganOrgs())->pluck('id');

        $totalIncome = Transaction::where('transactions.type', 'debit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->when($orgAccountIds, fn($q) => $q->whereIn('transactions.account_id', $orgAccountIds))
            ->sum('transactions.amount');

        $totalExpense = Transaction::where('transactions.type', 'credit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->when($orgAccountIds, fn($q) => $q->whereIn('transactions.account_id', $orgAccountIds))
            ->sum('transactions.amount');

        $incomeByCategory = Transaction::where('transactions.type', 'debit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->when($orgAccountIds, fn($q) => $q->whereIn('transactions.account_id', $orgAccountIds))
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.name')->orderBy('total', 'desc')->get();

        $expenseByCategory = Transaction::where('transactions.type', 'credit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->when($orgAccountIds, fn($q) => $q->whereIn('transactions.account_id', $orgAccountIds))
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.name')->orderBy('total', 'desc')->get();

        return compact('totalIncome', 'totalExpense', 'incomeByCategory', 'expenseByCategory');
    }

    #[Computed]
    public function recentTransactions()
    {
        $orgAccountIds = $this->activeOrg !== 'semua'
            ? Account::where('organization_type', $this->activeOrg)->pluck('id')
            : Account::whereIn('organization_type', $this->keuanganOrgs())->pluck('id');

        return Transaction::with(['category', 'account'])
            ->when($orgAccountIds, fn($q) => $q->whereIn('account_id', $orgAccountIds))
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->take(10)
            ->get();
    }

    #[Computed]
    public function monthlyTrend()
    {
        $orgAccountIds = $this->activeOrg !== 'semua'
            ? Account::where('organization_type', $this->activeOrg)->pluck('id')
            : Account::whereIn('organization_type', $this->keuanganOrgs())->pluck('id');

        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $month = $d->month;
            $year = $d->year;
            $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
            $start = "{$year}-{$monthPadded}-01";
            $end = date('Y-m-t', strtotime($start));

            $income = Transaction::where('transactions.type', 'debit')
                ->whereBetween('transaction_date', [$start, $end])
                ->when($orgAccountIds, fn($q) => $q->whereIn('transactions.account_id', $orgAccountIds))
                ->sum('transactions.amount');

            $expense = Transaction::where('transactions.type', 'credit')
                ->whereBetween('transaction_date', [$start, $end])
                ->when($orgAccountIds, fn($q) => $q->whereIn('transactions.account_id', $orgAccountIds))
                ->sum('transactions.amount');

            $labels[] = $d->locale('id')->isoFormat('MMM');
            $incomeData[] = (float) $income;
            $expenseData[] = (float) $expense;
        }

        return compact('labels', 'incomeData', 'expenseData');
    }

    public function printPdf()
    {
        $accounts = $this->accounts;
        $summary = $this->monthlySummary;

        $pdf = Pdf::loadView('exports.keuangan-portal-pdf', [
            'accounts'   => $accounts,
            'reportData' => (object) $summary,
            'month'      => $this->month,
            'year'       => $this->year,
            'activeOrg'  => $this->activeOrg,
            'appName'    => \App\Models\Setting::appName(),
        ])->setPaper('a4', 'portrait');

        $orgLabel   = $this->activeOrg === 'semua' ? 'semua' : $this->activeOrg;
        $monthPad   = str_pad((string) $this->month, 2, '0', STR_PAD_LEFT);
        $filename   = "laporan-keuangan-{$orgLabel}-{$this->year}-{$monthPad}.pdf";

        return response()->streamDownload(fn() => print($pdf->output()), $filename);
    }

    public function render()
    {
        $months = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $availableYears = range(now()->year, now()->year - 5);

        return view('livewire.penghuni.keuangan-portal', [
            'months' => $months,
            'availableYears' => $availableYears,
        ]);
    }
}
