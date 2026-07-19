<?php
namespace App\Providers;

use App\Models\User;
use App\Models\RolePermission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $gates = [
            'manage-admin',
            'manage-users',
            'manage-dkm',
            'manage-perumahan',
            'manage-programs',
            'manage-transactions',
            'view-reports',
            'manage-residents',
            'manage-ipl',
        ];

        foreach ($gates as $gate) {
            Gate::define($gate, function (User $user) use ($gate) {
                if ($user->role === 'super_admin') return true;

                try {
                    $allowed = Cache::remember("gate_roles_{$gate}", 3600, function () use ($gate) {
                        return RolePermission::where('gate', $gate)->pluck('role')->toArray();
                    });
                    return in_array($user->role, $allowed);
                } catch (\Exception) {
                    // Fallback jika tabel belum ada (misal saat migrate pertama)
                    return false;
                }
            });
        }

        // Akses konfirmasi pembayaran/donasi: pengurus Perumahan (IPL) maupun DKM.
        Gate::define('approve-payments', function (User $user) {
            return $user->can('manage-ipl') || $user->can('manage-dkm');
        });
    }
}
