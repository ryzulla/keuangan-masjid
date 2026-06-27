<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        // DKM accounts
        Account::firstOrCreate(['name' => 'Kas Utama'], ['organization_type' => 'dkm', 'balance' => 0]);
        Account::firstOrCreate(['name' => 'Bank Syariah Operasional'], ['organization_type' => 'dkm', 'balance' => 0]);
        Account::firstOrCreate(['name' => 'Bank Syariah Pembangunan'], ['organization_type' => 'dkm', 'balance' => 0]);

        // Perumahan accounts
        Account::firstOrCreate(['name' => 'Kas Perumahan/RT'], ['organization_type' => 'perumahan', 'balance' => 0]);
        Account::firstOrCreate(['name' => 'Kas Keamanan'], ['organization_type' => 'perumahan', 'balance' => 0]);
        Account::firstOrCreate(['name' => 'Kas Kebersihan'], ['organization_type' => 'perumahan', 'balance' => 0]);
    }
}
