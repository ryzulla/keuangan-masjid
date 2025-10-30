<?php
namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http; // <-- PASTIKAN BARIS INI ADA
use Illuminate\Support\Facades\Log; // <-- Pastikan ini juga ada

// Komponen ini TIDAK menggunakan layout sendiri
class PrayerTimes extends Component
{
    public $lokasi = 'Bogor, Indonesia'; // Lokasi default
    public $tanggalMasehi;
    public $tanggalHijriah = 'Memuat...'; // Default sementara

    // Jadwal Sholat Default
    public $imsak = '--:--';
    public $subuh = '--:--';
    public $terbit = '--:--';
    public $dhuha = '--:--';
    public $dzuhur = '--:--';
    public $ashar = '--:--';
    public $maghrib = '--:--';
    public $isya = '--:--';

    public function mount()
    {
        // Set tanggal Masehi saat ini
        $this->tanggalMasehi = Carbon::now()->isoFormat('dddd, D MMMM YYYY');

        try {
            // Panggil API Aladhan (Gunakan Http facade yang sudah di-import)
            $response = Http::timeout(15)->get('http://api.aladhan.com/v1/timingsByCity', [
                'city' => 'Bogor',
                'country' => 'Indonesia',
                'method' => 8 // Metode Kemenag RI
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Ambil jadwal sholat
                if (isset($data['data']['timings'])) {
                    $timings = $data['data']['timings'];
                    $this->imsak = $timings['Imsak'] ?? $this->imsak;
                    $this->subuh = $timings['Fajr'] ?? $this->subuh;
                    $this->terbit = $timings['Sunrise'] ?? $this->terbit;
                    $this->dzuhur = $timings['Dhuhr'] ?? $this->dzuhur;
                    $this->ashar = $timings['Asr'] ?? $this->ashar;
                    $this->maghrib = $timings['Maghrib'] ?? $this->maghrib;
                    $this->isya = $timings['Isha'] ?? $this->isya;
                    // Hitung Dhuha
                    if(isset($timings['Sunrise'])){
                        try { $this->dhuha = Carbon::createFromTimeString($timings['Sunrise'])->addMinutes(30)->format('H:i'); } catch (\Exception $timeEx) {}
                    }
                } else { Log::warning("Prayer times structure not found in Aladhan API response."); }

                // Ambil tanggal Hijriah
                if (isset($data['data']['date']['hijri'])) {
                    $hijri = $data['data']['date']['hijri'];
                    $monthName = $hijri['month']['en'] ?? ''; // Ambil nama bulan
                    // Coba terjemahkan nama bulan (opsional)
                    $monthName = $this->translateHijriMonth($monthName);
                    $this->tanggalHijriah = sprintf('%s %s %s %s',
                        $hijri['day'] ?? '??',
                        $monthName,
                        $hijri['year'] ?? '????',
                        $hijri['designation']['abbreviated'] ?? 'H'
                    );
                } else { Log::warning("Hijri date structure not found in Aladhan API response."); }

                 Log::info("Successfully fetched prayer times for ".$this->lokasi);

            } else {
                Log::error("Failed to fetch prayer times. API Status: " . $response->status());
                session()->flash('prayer_time_error', 'Gagal memuat jadwal sholat otomatis.');
            }
        } catch (\Exception $e) {
            Log::error("Exception while fetching prayer times: " . $e->getMessage());
            session()->flash('prayer_time_error', 'Gagal menghubungi server jadwal sholat.');
        }
    }

    /**
     * Helper sederhana untuk menerjemahkan nama bulan Hijriah (opsional)
     */
    private function translateHijriMonth($englishMonth) {
        $months = [
            'Muharram' => 'Muharram', 'Safar' => 'Safar', 'Rabi al-Awwal' => 'Rabiul Awal',
            'Rabi al-Thani' => 'Rabiul Akhir', 'Jumada al-Ula' => 'Jumadil Awal', 'Jumada al-Thani' => 'Jumadil Akhir',
            'Rajab' => 'Rajab', 'Sha\'ban' => 'Syaban', 'Ramadan' => 'Ramadan',
            'Shawwal' => 'Syawal', 'Dhu al-Qi\'dah' => 'Zulkaidah', 'Dhu al-Hijjah' => 'Zulhijah'
        ];
        return $months[$englishMonth] ?? $englishMonth; // Kembalikan asli jika tidak ketemu
    }

    public function render()
    {
        return view('livewire.prayer-times');
    }
}
