<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'admin'       => ['manage-admin', 'manage-dkm', 'manage-perumahan', 'manage-programs', 'manage-transactions', 'view-reports', 'manage-residents', 'manage-ipl'],
            'bendahara'   => ['manage-dkm', 'manage-programs', 'manage-transactions', 'view-reports', 'manage-ipl'],
            'ketua_dkm'   => ['view-reports'],
            'dkm'         => ['manage-dkm', 'manage-programs'],
            'perumahan'   => ['manage-perumahan', 'manage-programs', 'manage-residents', 'manage-ipl'],
            'pengurus_rt' => ['manage-perumahan', 'manage-programs', 'manage-transactions', 'view-reports', 'manage-residents', 'manage-ipl'],
        ];

        foreach ($defaults as $role => $gates) {
            foreach ($gates as $gate) {
                RolePermission::firstOrCreate(['role' => $role, 'gate' => $gate]);
            }
        }

        $this->command->info('Role permissions seeded.');
    }
}
