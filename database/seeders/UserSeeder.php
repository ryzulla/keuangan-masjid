<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name' => 'Admin Masjid', 'email' => 'admin@masjid.com',
            'password' => Hash::make('password'), 'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Bendahara Masjid', 'email' => 'bendahara@masjid.com',
            'password' => Hash::make('password'), 'role' => 'bendahara',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Ketua DKM', 'email' => 'dkm@masjid.com',
            'password' => Hash::make('password'), 'role' => 'ketua_dkm',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Pengurus RT', 'email' => 'rt@perumahan.com',
            'password' => Hash::make('password'), 'role' => 'pengurus_rt',
            'email_verified_at' => now(),
        ]);
    }
}
