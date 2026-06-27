<?php
namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        Gate::define('manage-admin', fn(User $user) => $user->role === 'admin');
        Gate::define('manage-transactions', fn(User $user) => in_array($user->role, ['admin', 'bendahara', 'pengurus_rt']));
        Gate::define('view-reports', fn(User $user) => in_array($user->role, ['admin', 'bendahara', 'ketua_dkm', 'pengurus_rt']));
        Gate::define('manage-residents', fn(User $user) => in_array($user->role, ['admin', 'pengurus_rt']));
        Gate::define('manage-ipl', fn(User $user) => in_array($user->role, ['admin', 'bendahara', 'pengurus_rt']));
    }
}
