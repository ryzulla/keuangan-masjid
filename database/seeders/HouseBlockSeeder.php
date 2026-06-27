<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HouseBlock;

class HouseBlockSeeder extends Seeder
{
    public function run(): void
    {
        HouseBlock::generateAll();
        $this->command->info('Generated ' . HouseBlock::count() . ' house blocks (A-1 to P-9).');
    }
}
