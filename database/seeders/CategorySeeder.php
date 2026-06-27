<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // DKM categories
        $dkmIncome = ['Infaq & Sedekah Umum', 'Infaq Jumat', 'Zakat Maal', 'Donasi Pembangunan', 'Donasi Program'];
        $dkmExpense = ['Operasional (Listrik, Air, Internet)', 'Insentif Marbot & Imam', 'Kegiatan Hari Besar', 'Material Pembangunan', 'Pengeluaran Program'];

        foreach ($dkmIncome as $name) {
            Category::firstOrCreate(['name' => $name, 'organization_type' => 'dkm'], ['type' => 'income']);
        }
        foreach ($dkmExpense as $name) {
            Category::firstOrCreate(['name' => $name, 'organization_type' => 'dkm'], ['type' => 'expense']);
        }

        // Perumahan categories
        $perIncome = ['IPL Security', 'Iuran Sampah', 'Donasi Perumahan', 'Denda/Sanksi', 'Pendapatan Lain-lain Perumahan'];
        $perExpense = ['Biaya Keamanan', 'Biaya Kebersihan', 'Perawatan Fasilitas', 'Kegiatan Warga', 'Pengeluaran Program Perumahan', 'Operasional RT'];

        foreach ($perIncome as $name) {
            Category::firstOrCreate(['name' => $name, 'organization_type' => 'perumahan'], ['type' => 'income']);
        }
        foreach ($perExpense as $name) {
            Category::firstOrCreate(['name' => $name, 'organization_type' => 'perumahan'], ['type' => 'expense']);
        }
    }
}
