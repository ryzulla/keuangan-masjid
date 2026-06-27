<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\WelcomePage;
use App\Livewire\Dashboard;
use App\Livewire\Accounts\ManageAccounts;
use App\Livewire\Categories\ManageCategories;
use App\Livewire\Campaigns\ManageCampaigns;
use App\Livewire\Transactions\BukuBesar;
use App\Livewire\Reports\CashFlowReport;
use App\Livewire\Reports\BalanceSheetReport;
use App\Livewire\Admin\ManageUsers;
use App\Livewire\Residents\ManageResidents;
use App\Livewire\IPL\ManageIPL;
use App\Livewire\IPL\IPLReport;

Route::get('/', WelcomePage::class)->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::view('profile', 'profile')->name('profile.edit');

    Route::middleware('can:manage-residents')->group(function () {
        Route::get('residents', ManageResidents::class)->name('residents.index');
    });

    Route::middleware('can:manage-ipl')->group(function () {
        Route::get('ipl', ManageIPL::class)->name('ipl.index');
        Route::get('ipl/report', IPLReport::class)->name('ipl.report');
    });

    Route::middleware('can:manage-transactions')->group(function () {
        Route::get('transactions', BukuBesar::class)->name('transactions.index');
        Route::get('accounts', ManageAccounts::class)->name('accounts.index');
        Route::get('categories', ManageCategories::class)->name('categories.index');
        Route::get('campaigns', ManageCampaigns::class)->name('campaigns.index');
    });

    Route::middleware('can:view-reports')->group(function () {
        Route::get('reports/cash-flow', CashFlowReport::class)->name('reports.cashflow');
        Route::get('reports/balance-sheet', BalanceSheetReport::class)->name('reports.balancesheet');
    });

    Route::get('users', ManageUsers::class)->name('users.index')->middleware('can:manage-admin');
});

require __DIR__.'/auth.php';
