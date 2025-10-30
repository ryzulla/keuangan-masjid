<?php
namespace App\Providers;

use App\Models\User; // <-- Tambahkan ini
use Illuminate\Support\Facades\Gate; // <-- Tambahkan ini
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ...
    ];

    public function boot(): void
    {
        // TAMBAHKAN GATE ANDA DI SINI
        Gate::define('manage-admin', fn(User $user) => $user->role === 'admin');
        Gate::define('manage-transactions', fn(User $user) => in_array($user->role, ['admin', 'bendahara']));
        Gate::define('view-reports', fn(User $user) => in_array($user->role, ['admin', 'bendahara', 'ketua_dkm']));
    }
}
