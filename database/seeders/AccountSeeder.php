<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Account;
class AccountSeeder extends Seeder {
    public function run(): void {
        Account::create(['name' => 'Kas Utama', 'balance' => 0]);
        Account::create(['name' => 'Bank Syariah Operasional', 'balance' => 0]);
        Account::create(['name' => 'Bank Syariah Pembangunan', 'balance' => 0]);
    }
}
