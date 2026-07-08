<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateSpecialAccountsSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'superadmin@perumahan.com'],
            [
                'name'               => 'Super Admin',
                'password'           => Hash::make('123456'),
                'role'               => 'super_admin',
                'email_verified_at'  => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'perumahan@perumahan.com'],
            [
                'name'               => 'Akun Perumahan',
                'password'           => Hash::make('123456'),
                'role'               => 'perumahan',
                'email_verified_at'  => now(),
            ]
        );

        $this->command->info('Akun super_admin dan perumahan berhasil dibuat/diverifikasi.');
    }
}
