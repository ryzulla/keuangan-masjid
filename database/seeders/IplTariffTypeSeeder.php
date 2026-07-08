<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IplTariffType;
use App\Models\IplPeriod;

class IplTariffTypeSeeder extends Seeder
{
    public function run(): void
    {
        // System types — linked to existing ipl_billings columns via billing_key
        $securityAmount = IplPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->value('ipl_security_amount') ?? 75000;
        $garbageAmount  = IplPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')->value('ipl_garbage_amount')  ?? 25000;

        IplTariffType::updateOrCreate(
            ['billing_key' => 'security'],
            [
                'name'           => 'Iuran Keamanan',
                'description'    => 'Iuran bulanan untuk keamanan lingkungan perumahan',
                'default_amount' => $securityAmount,
                'is_active'      => true,
                'sort_order'     => 1,
            ]
        );

        IplTariffType::updateOrCreate(
            ['billing_key' => 'garbage'],
            [
                'name'           => 'Kebersihan / Sampah',
                'description'    => 'Iuran bulanan untuk pengelolaan kebersihan dan sampah',
                'default_amount' => $garbageAmount,
                'is_active'      => true,
                'sort_order'     => 2,
            ]
        );

        $kasRtAmount = IplPeriod::orderBy('year', 'desc')->orderBy('month', 'desc')
            ->whereRaw('ipl_kas_rt_amount > 0')
            ->value('ipl_kas_rt_amount') ?? 50000;

        IplTariffType::updateOrCreate(
            ['billing_key' => 'kas_rt'],
            [
                'name'           => 'Kas RT',
                'description'    => 'Iuran bulanan untuk kas RT (digunakan untuk program dan kegiatan RT)',
                'default_amount' => $kasRtAmount,
                'is_active'      => true,
                'sort_order'     => 3,
            ]
        );
    }
}
