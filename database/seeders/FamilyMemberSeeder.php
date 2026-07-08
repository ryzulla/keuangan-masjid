<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident;
use App\Models\FamilyMember;

class FamilyMemberSeeder extends Seeder
{
    public function run(): void
    {
        // Key = exact name as in ResidentSeeder/DB
        $families = [
            'Budi Santoso' => [
                ['name' => 'Siti Rahayu',       'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1985-04-12'],
                ['name' => 'Rizki Santoso',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2010-07-20'],
                ['name' => 'Sari Santoso',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2013-02-05'],
            ],
            'H. Ahmad Fauzi' => [
                ['name' => 'Dewi Fauzi',         'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1988-09-30'],
                ['name' => 'Farhan Fauzi',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2012-03-15'],
                ['name' => 'Faza Fauzi',         'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2015-11-08'],
            ],
            'Hendra Wijaya' => [
                ['name' => 'Lina Wijaya',        'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1990-11-22'],
                ['name' => 'Kevin Wijaya',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2015-08-10'],
                ['name' => 'Karin Wijaya',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2018-01-25'],
            ],
            'Drs. Bambang Susilo' => [
                ['name' => 'Endang Susilo',      'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1982-06-18'],
                ['name' => 'Bagas Susilo',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2008-12-01'],
                ['name' => 'Bintang Susilo',     'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2011-05-14'],
                ['name' => 'Bunga Susilo',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2014-09-07'],
            ],
            'Wahyu Hidayat' => [
                ['name' => 'Indah Hidayat',      'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1991-06-22'],
                ['name' => 'Wahid Hidayat',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2015-03-11'],
                ['name' => 'Wirda Hidayat',      'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2018-09-29'],
            ],
            'Ir. Teguh Prasetyo' => [
                ['name' => 'Kanti Prasetyo',     'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1986-08-23'],
                ['name' => 'Teguh Jr',           'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2010-06-14'],
                ['name' => 'Tegar Prasetyo',     'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2013-01-07'],
            ],
            'Agus Setiawan' => [
                ['name' => 'Halimah Setiawan',   'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1986-03-09'],
                ['name' => 'Agil Setiawan',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2010-07-23'],
                ['name' => 'Agni Setiawan',      'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2013-02-16'],
            ],
            'Andi Kurniawan' => [
                ['name' => 'Nadia Kurniawan',    'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1991-12-11'],
                ['name' => 'Aldo Kurniawan',     'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2017-04-20'],
            ],
            'Anita Rahmawati' => [
                ['name' => 'Doni Rahmawati',     'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1984-05-18'],
                ['name' => 'Alya Rahmawati',     'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2011-08-25'],
                ['name' => 'Aldi Rahmawati',     'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2014-03-10'],
            ],
            'Dedi Supriadi' => [
                ['name' => 'Rini Supriadi',      'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1989-07-14'],
                ['name' => 'Dito Supriadi',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2014-11-03'],
            ],
            'Dewi Lestari' => [
                ['name' => 'Pandu Lestari',      'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1983-07-07'],
                ['name' => 'Dian Lestari',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2009-04-15'],
                ['name' => 'Dito Lestari',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2012-08-22'],
            ],
            'Diana Puspita' => [
                ['name' => 'Hendri Puspita',     'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1988-05-10'],
                ['name' => 'Dara Puspita',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2014-09-27'],
            ],
            'Dr. Eko Priyanto' => [
                ['name' => 'Yuni Priyanto',      'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1986-05-25'],
                ['name' => 'Edo Priyanto',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2009-08-31'],
                ['name' => 'Eka Priyanto',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2012-04-19'],
            ],
            'Drs. Hadiyanto' => [
                ['name' => 'Sumi Hadiyanto',     'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1983-10-12'],
                ['name' => 'Hadi Jr',            'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2007-06-09'],
                ['name' => 'Hapsari Hadiyanto',  'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2010-02-28'],
                ['name' => 'Nenek Hadiyanto',    'relationship' => 'mertua',   'gender' => 'perempuan', 'birth_date' => '1957-04-15'],
            ],
            'Endah Purwanti' => [
                ['name' => 'Eko Purwanti',       'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1985-01-21'],
                ['name' => 'Enggar Purwanti',    'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2012-11-14'],
            ],
            'Erna Yunita' => [
                ['name' => 'Yudi Yunita',        'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1985-11-27'],
                ['name' => 'Evan Yunita',        'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2011-03-14'],
                ['name' => 'Elsa Yunita',        'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2014-07-09'],
            ],
            'Fajar Nugroho' => [
                ['name' => 'Wulan Nugroho',      'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1993-04-08'],
                ['name' => 'Farel Nugroho',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2018-09-19'],
            ],
            'Fandi Ahmad' => [
                ['name' => 'Yesi Ahmad',         'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1994-10-13'],
                ['name' => 'Fahri Ahmad',        'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2020-05-22'],
            ],
            'Fitri Handayani' => [
                ['name' => 'Roni Handayani',     'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1987-08-16'],
                ['name' => 'Farid Handayani',    'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2012-05-03'],
                ['name' => 'Fatimah Handayani',  'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2015-10-28'],
            ],
            'Gunawan Prasetya' => [
                ['name' => 'Ratna Prasetya',     'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1989-03-16'],
                ['name' => 'Ganes Prasetya',     'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2014-07-08'],
                ['name' => 'Gita Prasetya',      'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2017-01-22'],
            ],
            'H. Mulyono' => [
                ['name' => 'Hj. Sulastri',       'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1968-09-04'],
                ['name' => 'Mulyadi Mulyono',    'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '1992-03-18'],
                ['name' => 'Mulyani Mulyono',    'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '1995-07-11'],
            ],
            'Hendro Wibowo' => [
                ['name' => 'Ningsih Wibowo',     'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1987-09-08'],
                ['name' => 'Hendra Jr Wibowo',   'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2012-01-19'],
            ],
            'Heri Susanto' => [
                ['name' => 'Lastri Susanto',     'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1992-04-14'],
                ['name' => 'Haris Susanto',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2017-10-07'],
            ],
            'Hermanto' => [
                ['name' => 'Romauli Hermanto',   'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1989-02-20'],
                ['name' => 'Harlan Hermanto',    'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2014-06-03'],
            ],
            'Hj. Maryati' => [
                ['name' => 'H. Suryadi',         'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1958-07-17'],
                ['name' => 'Sury Maryati',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '1985-03-24'],
                ['name' => 'Surya Maryati',      'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '1988-09-12'],
            ],
            'Hj. Suryana' => [
                ['name' => 'H. Subhan',          'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1956-11-30'],
                ['name' => 'Subhi Suryana',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '1983-05-08'],
            ],
            'Ibu Sri Mulyani' => [
                ['name' => 'Pak Mulyani',        'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1960-04-22'],
                ['name' => 'Sri Wahyu Mulyani',  'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '1988-08-15'],
                ['name' => 'Sriningsih Mulyani', 'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '1991-12-03'],
            ],
            'Irwan Setiadi' => [
                ['name' => 'Yolanda Setiadi',    'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1993-10-16'],
                ['name' => 'Ivan Setiadi',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2019-01-08'],
            ],
            'Kartini Sari' => [
                ['name' => 'Hendra Sari',        'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1984-06-30'],
                ['name' => 'Karina Sari',        'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2011-11-05'],
            ],
            'Lestari Ningsih' => [
                ['name' => 'Agung Ningsih',      'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1985-04-17'],
                ['name' => 'Leni Ningsih',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2012-08-31'],
                ['name' => 'Lutfi Ningsih',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2015-03-20'],
            ],
            'Lilik Mulyati' => [
                ['name' => 'Slamet Mulyati',     'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1983-02-09'],
                ['name' => 'Luki Mulyati',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2010-07-14'],
            ],
            'M. Yusuf' => [
                ['name' => 'Aisyah Yusuf',       'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1992-05-27'],
                ['name' => 'Yahya Yusuf',        'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2017-09-16'],
                ['name' => 'Yasmin Yusuf',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2020-02-04'],
            ],
            'Megawati' => [
                ['name' => 'Susilo Megawati',    'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1980-06-22'],
                ['name' => 'Mega Jr',            'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2006-11-10'],
                ['name' => 'Megi Megawati',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2009-04-28'],
                ['name' => 'Kakek Megawati',     'relationship' => 'orang_tua','gender' => 'laki-laki', 'birth_date' => '1952-03-15'],
            ],
            'Mira Susanti' => [
                ['name' => 'Rudi Susanti',       'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1987-07-19'],
                ['name' => 'Miko Susanti',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2014-12-07'],
            ],
            'Novi Andriani' => [
                ['name' => 'Aldi Andriani',      'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1990-12-04'],
                ['name' => 'Nova Andriani',      'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2017-07-21'],
            ],
            'Nurul Hidayah' => [
                ['name' => 'Rizal Hidayah',      'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1986-01-29'],
                ['name' => 'Naila Hidayah',      'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2012-06-07'],
                ['name' => 'Nabil Hidayah',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2015-10-23'],
            ],
            'Purwanto' => [
                ['name' => 'Sukarni Purwanto',   'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1982-09-13'],
                ['name' => 'Prapto Purwanto',    'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2007-04-02'],
                ['name' => 'Preti Purwanto',     'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2010-08-19'],
            ],
            'Ratna Dewi' => [
                ['name' => 'Budi Dewi',          'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1984-11-08'],
                ['name' => 'Rahel Dewi',         'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2013-05-17'],
            ],
            'Ridwan Fauzan' => [
                ['name' => 'Siska Fauzan',       'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1993-08-26'],
                ['name' => 'Rafi Fauzan',        'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2018-03-12'],
            ],
            'Rini Wulandari' => [
                ['name' => 'Taufik Wulandari',   'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1986-06-14'],
                ['name' => 'Rendi Wulandari',    'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2011-01-29'],
            ],
            'Rizky Pratama' => [
                ['name' => 'Safira Pratama',     'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1995-08-17'],
            ],
            'Siti Rahayu' => [
                ['name' => 'Joko Rahayu',        'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1981-04-06'],
                ['name' => 'Sari Rahayu',        'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2008-10-18'],
                ['name' => 'Sandi Rahayu',       'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2011-05-27'],
                ['name' => 'Nenek Rahayu',       'relationship' => 'mertua',   'gender' => 'perempuan', 'birth_date' => '1955-02-14'],
            ],
            'Sri Wahyuni' => [
                ['name' => 'Bambang Wahyuni',    'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1980-03-25'],
                ['name' => 'Sari Wahyuni',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2006-09-12'],
                ['name' => 'Sandi Wahyuni',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2009-01-04'],
            ],
            'Sulistyowati' => [
                ['name' => 'Guntur Sulistyowati','relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1982-07-19'],
                ['name' => 'Suli Jr',            'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2008-03-10'],
                ['name' => 'Sulton Sulistyowati','relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2011-11-22'],
            ],
            'Suparman' => [
                ['name' => 'Supini Suparman',    'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1984-05-30'],
                ['name' => 'Supri Suparman',     'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2009-08-04'],
                ['name' => 'Suprita Suparman',   'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2013-02-17'],
                ['name' => 'Kakek Suparman',     'relationship' => 'orang_tua','gender' => 'laki-laki', 'birth_date' => '1955-06-11'],
            ],
            'Tri Wahyuni' => [
                ['name' => 'Tarno Wahyuni',      'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1987-12-03'],
                ['name' => 'Tari Wahyuni',       'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2013-07-21'],
            ],
            'Warsini' => [
                ['name' => 'Warno Warsini',      'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1980-11-15'],
                ['name' => 'Wardi Warsini',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2005-04-28'],
                ['name' => 'Wardah Warsini',     'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2008-09-13'],
                ['name' => 'Warih Warsini',      'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2012-01-06'],
            ],
            'Yanto Susilo' => [
                ['name' => 'Sumiati Susilo',     'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1985-11-11'],
                ['name' => 'Yoga Susilo',        'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2009-07-06'],
                ['name' => 'Yeni Susilo',        'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2012-02-24'],
            ],
            'Yuliana Sari' => [
                ['name' => 'Yulianto Sari',      'relationship' => 'suami',    'gender' => 'laki-laki', 'birth_date' => '1983-06-17'],
                ['name' => 'Yulia Jr Sari',      'relationship' => 'anak',     'gender' => 'perempuan', 'birth_date' => '2010-10-14'],
                ['name' => 'Yudha Sari',         'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2013-04-08'],
            ],
            'Agung Prabowo' => [
                ['name' => 'Rina Prabowo',       'relationship' => 'istri',    'gender' => 'perempuan', 'birth_date' => '1992-08-11'],
                ['name' => 'Agung Jr Prabowo',   'relationship' => 'anak',     'gender' => 'laki-laki', 'birth_date' => '2018-05-26'],
            ],
        ];

        $seeded   = 0;
        $skipped  = 0;
        foreach ($families as $residentName => $members) {
            $resident = Resident::where('name', $residentName)->first();
            if (!$resident) {
                $skipped++;
                continue;
            }
            foreach ($members as $order => $memberData) {
                FamilyMember::create([
                    'resident_id'  => $resident->id,
                    'name'         => $memberData['name'],
                    'relationship' => $memberData['relationship'],
                    'gender'       => $memberData['gender'],
                    'birth_date'   => $memberData['birth_date'],
                    'sort_order'   => $order,
                ]);
            }
            $seeded++;
        }

        $this->command->info("FamilyMemberSeeder: {$seeded} families seeded, {$skipped} skipped.");
        $this->command->info('Total family members: ' . FamilyMember::count());
    }
}
