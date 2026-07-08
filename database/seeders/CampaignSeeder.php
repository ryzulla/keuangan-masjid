<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = [
            [
                'name' => 'Renovasi Taman & Fasilitas Perumahan',
                'description' => 'Program renovasi taman bermain anak, jalur pejalan kaki, dan area parkir bersama. Dana digunakan untuk pembelian material, tanaman, dan peralatan taman.',
                'target_amount' => 25000000,
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'status' => 'active',
                'organization_type' => 'perumahan',
            ],
            [
                'name' => 'Pembangunan Pos Keamanan Baru',
                'description' => 'Pembangunan pos keamanan 24 jam di pintu masuk utama perumahan dilengkapi CCTV dan sistem akses kartu. Meningkatkan keamanan dan kenyamanan warga.',
                'target_amount' => 50000000,
                'start_date' => '2026-03-01',
                'end_date' => '2026-09-30',
                'status' => 'active',
                'organization_type' => 'perumahan',
            ],
            [
                'name' => 'Renovasi Kubah & Mihrab Masjid 2026',
                'description' => 'Program renovasi kubah masjid yang sudah mengalami kerusakan akibat cuaca. Meliputi pelapisan ulang kubah, perbaikan mihrab, dan pengecatan interior masjid.',
                'target_amount' => 100000000,
                'start_date' => '2026-02-01',
                'end_date' => '2026-11-30',
                'status' => 'active',
                'organization_type' => 'dkm',
            ],
            [
                'name' => 'Pengadaan AC Masjid (2 Unit)',
                'description' => 'Pengadaan 2 unit AC untuk ruang utama masjid guna meningkatkan kenyamanan jamaah saat ibadah. Program ini telah selesai dengan total donasi melebihi target.',
                'target_amount' => 15000000,
                'start_date' => '2025-10-01',
                'end_date' => '2026-01-31',
                'status' => 'completed',
                'organization_type' => 'dkm',
            ],
        ];

        foreach ($campaigns as $c) {
            Campaign::create($c);
        }
    }
}
