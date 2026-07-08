<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    // DKM accounts: 1=Kas Utama, 2=Bank Syariah Operasional, 3=Bank Syariah Pembangunan
    // Perumahan accounts: 4=Kas Perumahan/RT, 5=Kas Keamanan, 6=Kas Kebersihan
    // Category income DKM: 1=Infaq&Sedekah, 2=Infaq Jumat, 3=Zakat, 4=Donasi Program, 10=Donasi Pembangunan
    // Category expense DKM: 5=Operasional, 6=Insentif, 7=Hari Besar, 8=Material, 9=Pengeluaran Program
    // Category income perumahan: 11=IPL Security, 12=Iuran Sampah, 13=Donasi, 14=Denda, 15=Pendapatan Lain
    // Category expense perumahan: 16=Biaya Keamanan, 17=Biaya Kebersihan, 18=Perawatan, 19=Kegiatan, 20=Program, 21=Operasional RT

    public function run(): void
    {
        $userId = User::first()?->id ?? 1;
        $txs = [];

        for ($month = 1; $month <= 6; $month++) {
            $jumatDays = $this->getFridays(2026, $month);

            // DKM: Infaq Jumat (every Friday)
            foreach ($jumatDays as $day) {
                $txs[] = ['account_id' => 1, 'category_id' => 2, 'type' => 'debit',
                    'amount' => mt_rand(800, 2200) * 1000,
                    'description' => 'Infaq Sholat Jumat ' . Carbon::create(2026, $month, $day)->isoFormat('D MMMM Y'),
                    'transaction_date' => Carbon::create(2026, $month, $day)->format('Y-m-d'),
                    'user_id' => $userId];
            }

            // DKM: Infaq & Sedekah Umum (monthly)
            $txs[] = ['account_id' => 1, 'category_id' => 1, 'type' => 'debit',
                'amount' => mt_rand(500, 1500) * 1000,
                'description' => 'Infaq & Sedekah Umum ' . Carbon::create(2026, $month, 1)->isoFormat('MMMM Y'),
                'transaction_date' => Carbon::create(2026, $month, mt_rand(5, 25))->format('Y-m-d'),
                'user_id' => $userId];

            // DKM: Zakat Maal (quarterly)
            if (in_array($month, [1, 4])) {
                $txs[] = ['account_id' => 1, 'category_id' => 3, 'type' => 'debit',
                    'amount' => mt_rand(2000, 8000) * 1000,
                    'description' => 'Penerimaan Zakat Maal ' . Carbon::create(2026, $month, 1)->isoFormat('MMMM Y'),
                    'transaction_date' => Carbon::create(2026, $month, mt_rand(10, 20))->format('Y-m-d'),
                    'user_id' => $userId];
            }

            // DKM: Operasional Listrik, Air (monthly expense)
            $txs[] = ['account_id' => 1, 'category_id' => 5, 'type' => 'credit',
                'amount' => mt_rand(280, 420) * 1000,
                'description' => 'Biaya Listrik & Air Masjid ' . Carbon::create(2026, $month, 1)->isoFormat('MMMM Y'),
                'transaction_date' => Carbon::create(2026, $month, mt_rand(3, 8))->format('Y-m-d'),
                'user_id' => $userId];

            // DKM: Insentif Marbot & Imam
            $txs[] = ['account_id' => 1, 'category_id' => 6, 'type' => 'credit',
                'amount' => 2500000,
                'description' => 'Insentif Imam, Marbot & Muadzin ' . Carbon::create(2026, $month, 1)->isoFormat('MMMM Y'),
                'transaction_date' => Carbon::create(2026, $month, 28)->format('Y-m-d'),
                'user_id' => $userId];

            // DKM: Material/Kegiatan (occasional)
            if (in_array($month, [1, 3, 5])) {
                $txs[] = ['account_id' => 1, 'category_id' => 8, 'type' => 'credit',
                    'amount' => mt_rand(500, 2000) * 1000,
                    'description' => 'Pembelian Material Kebersihan & Perlengkapan Masjid',
                    'transaction_date' => Carbon::create(2026, $month, mt_rand(10, 20))->format('Y-m-d'),
                    'user_id' => $userId];
            }
            if (in_array($month, [1, 4])) {
                $txs[] = ['account_id' => 1, 'category_id' => 7, 'type' => 'credit',
                    'amount' => mt_rand(3000, 8000) * 1000,
                    'description' => $month === 1 ? 'Kegiatan Maulid Nabi SAW' : 'Kegiatan Isra Miraj & Buka Puasa Bersama',
                    'transaction_date' => Carbon::create(2026, $month, mt_rand(12, 18))->format('Y-m-d'),
                    'user_id' => $userId];
            }

            // Perumahan: IPL Security & Sampah receipt (monthly bulk to Kas Keamanan)
            $totalSecurity = mt_rand(45, 55) * 75000; // ~50 units
            $totalGarbage  = mt_rand(45, 55) * 25000;
            $txs[] = ['account_id' => 5, 'category_id' => 11, 'type' => 'debit',
                'amount' => $totalSecurity,
                'description' => 'Penerimaan IPL Security ' . Carbon::create(2026, $month, 1)->isoFormat('MMMM Y'),
                'transaction_date' => Carbon::create(2026, $month, mt_rand(8, 15))->format('Y-m-d'),
                'user_id' => $userId];
            $txs[] = ['account_id' => 6, 'category_id' => 12, 'type' => 'debit',
                'amount' => $totalGarbage,
                'description' => 'Penerimaan Iuran Sampah ' . Carbon::create(2026, $month, 1)->isoFormat('MMMM Y'),
                'transaction_date' => Carbon::create(2026, $month, mt_rand(8, 15))->format('Y-m-d'),
                'user_id' => $userId];

            // Perumahan: Bayar petugas keamanan
            $txs[] = ['account_id' => 5, 'category_id' => 16, 'type' => 'credit',
                'amount' => 3000000,
                'description' => 'Gaji Satpam ' . Carbon::create(2026, $month, 1)->isoFormat('MMMM Y') . ' (2 orang)',
                'transaction_date' => Carbon::create(2026, $month, 28)->format('Y-m-d'),
                'user_id' => $userId];

            // Perumahan: Bayar petugas kebersihan
            $txs[] = ['account_id' => 6, 'category_id' => 17, 'type' => 'credit',
                'amount' => 1500000,
                'description' => 'Gaji Petugas Kebersihan ' . Carbon::create(2026, $month, 1)->isoFormat('MMMM Y'),
                'transaction_date' => Carbon::create(2026, $month, 28)->format('Y-m-d'),
                'user_id' => $userId];

            // Perumahan: Operasional RT (occasional)
            if (in_array($month, [1, 3, 6])) {
                $txs[] = ['account_id' => 4, 'category_id' => 21, 'type' => 'credit',
                    'amount' => mt_rand(200, 500) * 1000,
                    'description' => 'Biaya Operasional RT (ATK, Fotocopy, dll)',
                    'transaction_date' => Carbon::create(2026, $month, mt_rand(5, 15))->format('Y-m-d'),
                    'user_id' => $userId];
            }
            if ($month === 2) {
                $txs[] = ['account_id' => 4, 'category_id' => 19, 'type' => 'credit',
                    'amount' => 5000000,
                    'description' => 'Kegiatan HUT Perumahan - Lomba 17 Agustusan',
                    'transaction_date' => '2026-02-17',
                    'user_id' => $userId];
            }
        }

        // DKM: Donasi Pembangunan (Campaign Kubah)
        $donationAmounts = [5000000, 2500000, 1000000, 3000000, 10000000, 500000, 1500000, 2000000];
        $donationMonths  = [2, 2, 3, 3, 4, 4, 5, 6];
        foreach ($donationAmounts as $i => $amount) {
            $txs[] = ['account_id' => 3, 'category_id' => 10, 'type' => 'debit',
                'amount' => $amount,
                'description' => 'Donasi Renovasi Kubah Masjid 2026',
                'transaction_date' => Carbon::create(2026, $donationMonths[$i], mt_rand(5, 25))->format('Y-m-d'),
                'user_id' => $userId];
        }

        // Perumahan: Donasi Taman (Campaign Perumahan)
        $donasiTaman = [2000000, 1000000, 3000000, 500000, 1500000];
        $tmnMonths   = [1, 2, 3, 4, 5];
        foreach ($donasiTaman as $i => $amount) {
            $txs[] = ['account_id' => 4, 'category_id' => 13, 'type' => 'debit',
                'amount' => $amount,
                'description' => 'Donasi Program Renovasi Taman Perumahan',
                'transaction_date' => Carbon::create(2026, $tmnMonths[$i], mt_rand(5, 25))->format('Y-m-d'),
                'user_id' => $userId];
        }

        foreach ($txs as $tx) {
            Transaction::create($tx);
        }
    }

    private function getFridays(int $year, int $month): array
    {
        $days = [];
        $date = Carbon::create($year, $month, 1);
        while ($date->month === $month) {
            if ($date->isFriday()) $days[] = $date->day;
            $date->addDay();
        }
        return $days;
    }
}
