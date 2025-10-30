<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Transaction;             // <-- Ini sudah ada dari langkah sebelumnya
use App\Observers\TransactionObserver;   // <-- Ini sudah ada dari langkah sebelumnya
use App\Models\User;                     // <-- 1. TAMBAHKAN INI
use Illuminate\Support\Facades\Gate;   // <-- 2. TAMBAHKAN INI

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mendaftarkan Observer Transaksi
        Transaction::observe(TransactionObserver::class);

        // 3. TAMBAHKAN SEMUA GATE ANDA DI SINI
        Gate::define('manage-admin', fn(User $user) => $user->role === 'admin');
        Gate::define('manage-transactions', fn(User $user) => in_array($user->role, ['admin', 'bendahara']));
        Gate::define('view-reports', fn(User $user) => in_array($user->role, ['admin', 'bendahara', 'ketua_dkm']));
    }
}
