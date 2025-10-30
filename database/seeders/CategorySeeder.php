<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder {
    public function run(): void {
        Category::create(['name' => 'Infaq & Sedekah Umum', 'type' => 'income']);
        Category::create(['name' => 'Infaq Jumat', 'type' => 'income']);
        Category::create(['name' => 'Zakat Maal', 'type' => 'income']);
        Category::create(['name' => 'Donasi Pembangunan', 'type' => 'income']);
        Category::create(['name' => 'Operasional (Listrik, Air, Internet)', 'type' => 'expense']);
        Category::create(['name' => 'Insentif Marbot & Imam', 'type' => 'expense']);
        Category::create(['name' => 'Kegiatan Hari Besar', 'type' => 'expense']);
        Category::create(['name' => 'Material Pembangunan', 'type' => 'expense']);
    }
}
