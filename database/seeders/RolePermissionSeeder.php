<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RolePermission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'admin'        => ['manage-users', 'manage-admin', 'manage-dkm', 'manage-perumahan', 'manage-programs-dkm', 'manage-programs-perumahan', 'manage-transactions', 'view-reports', 'manage-residents', 'manage-ipl'],
            'bendahara'    => ['manage-dkm', 'manage-programs-dkm', 'manage-transactions', 'view-reports'],
            'bendahara_rt' => ['manage-perumahan', 'manage-programs-perumahan', 'manage-transactions', 'view-reports', 'manage-ipl'],
            'ketua_dkm'    => ['manage-users', 'view-reports'],
            'dkm'          => ['manage-dkm', 'manage-programs-dkm'],
            'perumahan'    => ['manage-users', 'manage-perumahan', 'manage-programs-perumahan', 'manage-residents', 'manage-ipl'],
            'pengurus_rt'  => ['manage-perumahan', 'manage-programs-perumahan', 'manage-transactions', 'view-reports', 'manage-residents', 'manage-ipl'],
        ];

        foreach ($defaults as $role => $gates) {
            foreach ($gates as $gate) {
                RolePermission::firstOrCreate(['role' => $role, 'gate' => $gate]);
            }
        }

        $this->command->info('Role permissions seeded.');
    }
}
