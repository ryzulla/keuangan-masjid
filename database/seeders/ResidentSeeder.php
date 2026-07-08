<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\HouseBlock;
use Carbon\Carbon;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'H. Ahmad Fauzi', 'phone' => '081234567890', 'whatsapp' => '081234567890', 'email' => 'ahmad.fauzi@gmail.com'],
            ['name' => 'Siti Rahayu', 'phone' => '082345678901', 'whatsapp' => '082345678901', 'email' => 'siti.rahayu@yahoo.com'],
            ['name' => 'Budi Santoso', 'phone' => '083456789012', 'whatsapp' => '083456789012'],
            ['name' => 'Dewi Lestari', 'phone' => '084567890123', 'whatsapp' => '084567890123', 'email' => 'dewi.lestari@gmail.com'],
            ['name' => 'Hendra Wijaya', 'phone' => '085678901234', 'whatsapp' => '085678901234'],
            ['name' => 'Ibu Sri Mulyani', 'phone' => '086789012345', 'whatsapp' => '086789012345'],
            ['name' => 'Rizky Pratama', 'phone' => '087890123456', 'whatsapp' => '087890123456', 'email' => 'rizky.pratama@gmail.com'],
            ['name' => 'Andi Kurniawan', 'phone' => '088901234567', 'whatsapp' => '088901234567'],
            ['name' => 'Nurul Hidayah', 'phone' => '089012345678', 'whatsapp' => '089012345678'],
            ['name' => 'Drs. Bambang Susilo', 'phone' => '081123456789', 'whatsapp' => '081123456789', 'email' => 'bambang.susilo@gmail.com'],
            ['name' => 'Fitri Handayani', 'phone' => '082234567890', 'whatsapp' => '082234567890'],
            ['name' => 'Agus Setiawan', 'phone' => '083345678901', 'whatsapp' => '083345678901'],
            ['name' => 'Rini Wulandari', 'phone' => '084456789012', 'whatsapp' => '084456789012', 'email' => 'rini.wulandari@outlook.com'],
            ['name' => 'Ir. Teguh Prasetyo', 'phone' => '085567890123', 'whatsapp' => '085567890123'],
            ['name' => 'Yuliana Sari', 'phone' => '086678901234', 'whatsapp' => '086678901234'],
            ['name' => 'Wahyu Hidayat', 'phone' => '087789012345', 'whatsapp' => '087789012345', 'email' => 'wahyu.hidayat@gmail.com'],
            ['name' => 'Endah Purwanti', 'phone' => '088890123456', 'whatsapp' => '088890123456'],
            ['name' => 'Dedi Supriadi', 'phone' => '089901234567', 'whatsapp' => '089901234567'],
            ['name' => 'Mira Susanti', 'phone' => '081012345678', 'whatsapp' => '081012345678'],
            ['name' => 'Fajar Nugroho', 'phone' => '082123456789', 'whatsapp' => '082123456789', 'email' => 'fajar.nugroho@gmail.com'],
            ['name' => 'H. Mulyono', 'phone' => '083234567890', 'whatsapp' => '083234567890'],
            ['name' => 'Lestari Ningsih', 'phone' => '084345678901', 'whatsapp' => '084345678901'],
            ['name' => 'Hj. Suryana', 'phone' => '085456789012', 'whatsapp' => '085456789012'],
            ['name' => 'Gunawan Prasetya', 'phone' => '086567890123', 'whatsapp' => '086567890123', 'email' => 'gunawan.prasetya@yahoo.com'],
            ['name' => 'Anita Rahmawati', 'phone' => '087678901234', 'whatsapp' => '087678901234'],
            ['name' => 'Dr. Eko Priyanto', 'phone' => '088789012345', 'whatsapp' => '088789012345', 'email' => 'eko.priyanto@gmail.com'],
            ['name' => 'Sulistyowati', 'phone' => '089890123456', 'whatsapp' => '089890123456'],
            ['name' => 'Heri Susanto', 'phone' => '081901234567', 'whatsapp' => '081901234567'],
            ['name' => 'Novi Andriani', 'phone' => '082012345678', 'whatsapp' => '082012345678'],
            ['name' => 'Purwanto', 'phone' => '083123456789', 'whatsapp' => '083123456789'],
            ['name' => 'Ratna Dewi', 'phone' => '084234567890', 'whatsapp' => '084234567890', 'email' => 'ratna.dewi@gmail.com'],
            ['name' => 'Irwan Setiadi', 'phone' => '085345678901', 'whatsapp' => '085345678901'],
            ['name' => 'Erna Yunita', 'phone' => '086456789012', 'whatsapp' => '086456789012'],
            ['name' => 'Suparman', 'phone' => '087567890123', 'whatsapp' => '087567890123'],
            ['name' => 'Diana Puspita', 'phone' => '088678901234', 'whatsapp' => '088678901234', 'email' => 'diana.puspita@outlook.com'],
            ['name' => 'M. Yusuf', 'phone' => '089789012345', 'whatsapp' => '089789012345'],
            ['name' => 'Kartini Sari', 'phone' => '081890123456', 'whatsapp' => '081890123456'],
            ['name' => 'Hendro Wibowo', 'phone' => '082901234567', 'whatsapp' => '082901234567'],
            ['name' => 'Lilik Mulyati', 'phone' => '083012345678', 'whatsapp' => '083012345678'],
            ['name' => 'Ridwan Fauzan', 'phone' => '084123456789', 'whatsapp' => '084123456789', 'email' => 'ridwan.fauzan@gmail.com'],
            ['name' => 'Hj. Maryati', 'phone' => '085234567890', 'whatsapp' => '085234567890'],
            ['name' => 'Agung Prabowo', 'phone' => '086345678901', 'whatsapp' => '086345678901'],
            ['name' => 'Tri Wahyuni', 'phone' => '087456789012', 'whatsapp' => '087456789012'],
            ['name' => 'Yanto Susilo', 'phone' => '088567890123', 'whatsapp' => '088567890123'],
            ['name' => 'Megawati', 'phone' => '089678901234', 'whatsapp' => '089678901234'],
            ['name' => 'Drs. Hadiyanto', 'phone' => '081789012345', 'whatsapp' => '081789012345', 'email' => 'hadiyanto@gmail.com'],
            ['name' => 'Warsini', 'phone' => '082890123456', 'whatsapp' => '082890123456'],
            ['name' => 'Fandi Ahmad', 'phone' => '083901234567', 'whatsapp' => '083901234567'],
            ['name' => 'Sri Wahyuni', 'phone' => '084012345678', 'whatsapp' => '084012345678'],
            ['name' => 'Hermanto', 'phone' => '085123456789', 'whatsapp' => '085123456789'],
        ];

        $residents = [];
        foreach ($data as $d) {
            $residents[] = Resident::create(array_merge($d, ['is_active' => true]));
        }

        // House block assignment data
        // Format: [resident_index, block_letter, unit_number, ownership_type, occupancy_status, is_primary, months_ago]
        $assignments = [
            [0, 'A', 1, 'pemilik', 'dihuni', true, 36],
            [1, 'A', 2, 'pemilik', 'dihuni', true, 24],
            [2, 'A', 3, 'pemilik', 'dihuni', true, 18],
            [3, 'A', 4, 'pemilik', 'dihuni', true, 30],
            [4, 'A', 5, 'pemilik', 'dihuni', true, 12],
            [5, 'A', 6, 'pemilik', 'dihuni', true, 48],
            [6, 'A', 7, 'pemilik', 'dihuni', true, 24],
            [7, 'A', 8, 'pemilik', 'kosong', true, 6],
            [8, 'A', 9, 'kontrak', 'dihuni', true, 8],
            [9, 'B', 1, 'pemilik', 'dihuni', true, 60],
            [10, 'B', 2, 'pemilik', 'dihuni', true, 36],
            [11, 'B', 3, 'pemilik', 'dihuni', true, 18],
            [12, 'B', 4, 'kontrak', 'dihuni', true, 10],
            [13, 'B', 5, 'pemilik', 'dihuni', true, 42],
            [14, 'B', 6, 'pemilik', 'dihuni', true, 24],
            [15, 'B', 7, 'pemilik', 'dihuni', true, 30],
            [16, 'B', 8, 'kos', 'dihuni', true, 5],
            [17, 'B', 9, 'pemilik', 'dihuni', true, 18],
            [18, 'C', 1, 'pemilik', 'dihuni', true, 36],
            [19, 'C', 2, 'pemilik', 'dihuni', true, 24],
            [20, 'C', 3, 'pemilik', 'kosong', true, 12],
            [21, 'C', 4, 'pemilik', 'dihuni', true, 48],
            [22, 'C', 5, 'kontrak', 'dihuni', true, 7],
            [23, 'C', 6, 'pemilik', 'dihuni', true, 60],
            [24, 'C', 7, 'pemilik', 'dihuni', true, 36],
            [25, 'C', 8, 'pemilik', 'dihuni', true, 18],
            [26, 'C', 9, 'pemilik', 'dihuni', true, 24],
            [27, 'D', 1, 'pemilik', 'dihuni', true, 30],
            [28, 'D', 2, 'pemilik', 'dihuni', true, 42],
            [29, 'D', 3, 'kos', 'dihuni', true, 6],
            [30, 'D', 4, 'pemilik', 'dihuni', true, 36],
            [31, 'D', 5, 'pemilik', 'dihuni', true, 18],
            [32, 'D', 6, 'pemilik', 'dihuni', true, 24],
            [33, 'D', 7, 'pemilik', 'kosong', true, 12],
            [34, 'D', 8, 'pemilik', 'dihuni', true, 48],
            [35, 'D', 9, 'pemilik', 'dihuni', true, 30],
            [36, 'E', 1, 'pemilik', 'dihuni', true, 36],
            [37, 'E', 2, 'pemilik', 'dihuni', true, 18],
            [38, 'E', 3, 'kontrak', 'dihuni', true, 9],
            [39, 'E', 4, 'pemilik', 'dihuni', true, 24],
            [40, 'E', 5, 'pemilik', 'dihuni', true, 42],
            [41, 'E', 6, 'pemilik', 'dihuni', true, 30],
            [42, 'E', 7, 'pemilik', 'dihuni', true, 18],
            [43, 'E', 8, 'pemilik', 'kosong', true, 6],
            [44, 'E', 9, 'pemilik', 'dihuni', true, 24],
            [45, 'F', 1, 'pemilik', 'dihuni', true, 36],
            [46, 'F', 2, 'pemilik', 'dihuni', true, 48],
            [47, 'F', 3, 'pemilik', 'dihuni', true, 18],
            [48, 'F', 4, 'pemilik', 'dihuni', true, 24],
            [49, 'F', 5, 'pemilik', 'dihuni', true, 30],
            // Residents who own 2 houses (investor)
            [9,  'F', 6, 'pemilik', 'kosong', false, 12],  // Bambang owns A? No, owns B-1 and F-6
            [23, 'F', 7, 'pemilik', 'kosong', false, 18],
            [13, 'F', 8, 'pemilik', 'dihuni', false, 24],
            [25, 'F', 9, 'pemilik', 'kosong', false, 12],
            [0,  'G', 1, 'pemilik', 'kosong', false, 24],  // H. Ahmad Fauzi owns A-1 and G-1
            [4,  'G', 2, 'pemilik', 'kosong', false, 18],
            [14, 'G', 3, 'pemilik', 'dihuni', false, 30],
            [30, 'G', 4, 'pemilik', 'kosong', false, 12],
            // Single-owner houses G-H area
            [1,  'G', 5, 'kontrak', 'dihuni', false, 9],
            [5,  'G', 6, 'kos', 'dihuni', false, 6],
            [10, 'H', 1, 'pemilik', 'dihuni', false, 24],
            [15, 'H', 2, 'pemilik', 'dihuni', false, 18],
        ];

        foreach ($assignments as $a) {
            [$resIdx, $letter, $unit, $ownerType, $occStatus, $isPrimary, $monthsAgo] = $a;
            $block = HouseBlock::where('block_letter', $letter)->where('unit_number', $unit)->first();
            if (!$block || !isset($residents[$resIdx])) continue;

            $residents[$resIdx]->houseBlocks()->syncWithoutDetaching([
                $block->id => [
                    'ownership_type' => $ownerType,
                    'occupancy_status' => $occStatus,
                    'resident_since' => Carbon::now()->subMonths($monthsAgo)->format('Y-m-d'),
                    'is_primary_residence' => $isPrimary,
                ]
            ]);
        }
    }
}
