<?php
namespace App\Livewire\Penghuni;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign;
use App\Models\IplBilling;
use App\Models\Account;
use App\Models\Transaction;

#[Layout('layouts.penghuni')]
class Dashboard extends Component
{
    public string $activeOrg = 'semua';

    #[Computed]
    public function accounts()
    {
        return Account::when($this->activeOrg !== 'semua', fn($q) => $q->where('organization_type', $this->activeOrg))
            ->orderBy('organization_type')->orderBy('name')->get();
    }

    #[Computed]
    public function monthlySummary()
    {
        $month = now()->month;
        $year = now()->year;
        $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT);
        $startDate = "{$year}-{$monthPadded}-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $orgAccountIds = $this->activeOrg !== 'semua'
            ? Account::where('organization_type', $this->activeOrg)->pluck('id')
            : null;

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
            ->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $expenseByCategory = Transaction::where('transactions.type', 'credit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->when($orgAccountIds, fn($q) => $q->whereIn('transactions.account_id', $orgAccountIds))
            ->select('categories.name', DB::raw('SUM(transactions.amount) as total'))
            ->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        return compact('totalIncome', 'totalExpense', 'incomeByCategory', 'expenseByCategory');
    }

    public function render()
    {
        $resident = Auth::guard('resident')->user()
            ->load(['currentAssignments.houseBlock', 'familyMembers']);

        $billings = IplBilling::with('period')
            ->where('responsible_resident_id', $resident->id)
            ->orderByDesc('due_date')
            ->take(4)
            ->get();

        $totalOutstanding = IplBilling::where('responsible_resident_id', $resident->id)
            ->where('status', '!=', 'paid')
            ->get()
            ->sum('outstanding');

        $campaigns = Campaign::where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        $pendingRequests = $resident->paymentRequests()
            ->where('status', 'pending')
            ->count();

        return view('livewire.penghuni.dashboard', compact(
            'resident', 'billings', 'totalOutstanding', 'campaigns', 'pendingRequests'
        ));
    }
}
