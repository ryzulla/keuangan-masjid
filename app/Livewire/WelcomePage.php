<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Campaign;
use App\Models\Category; // <-- Tambahkan use Category
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Barryvdh\DomPDF\Facade\Pdf; // <-- Tambahkan use PDF

#[Layout('layouts.public')] // Gunakan layout public
class WelcomePage extends Component
{
    // Properti untuk data ringkasan - Inisialisasi dengan nilai default
    public $lastMonthIncome = 0;
    public $lastMonthExpense = 0;
    public $currentMonthIncome = 0;
    public $currentMonthExpense = 0;
    public $activeCampaigns; // Akan diisi sebagai collection

    /**
     * Inisialisasi komponen.
     */
    public function mount()
    {
        // Inisialisasi collection kosong untuk campaign
        $this->activeCampaigns = collect();

        try {
            // --- Ringkasan Keuangan ---
            // Bulan lalu
            $startLastMonth = Carbon::now()->subMonthNoOverflow()->startOfMonth();
            $endLastMonth = Carbon::now()->subMonthNoOverflow()->endOfMonth();
            $this->lastMonthIncome = Transaction::where('type', 'debit')
                ->whereBetween('transaction_date', [$startLastMonth, $endLastMonth])->sum('amount') ?? 0;
            $this->lastMonthExpense = Transaction::where('type', 'credit')
                ->whereBetween('transaction_date', [$startLastMonth, $endLastMonth])->sum('amount') ?? 0;

            // Bulan ini (berjalan)
            $startCurrentMonth = Carbon::now()->startOfMonth();
            $endCurrentMonth = Carbon::now(); // Sampai hari ini
            $this->currentMonthIncome = Transaction::where('type', 'debit')
                ->whereBetween('transaction_date', [$startCurrentMonth, $endCurrentMonth])->sum('amount') ?? 0;
            $this->currentMonthExpense = Transaction::where('type', 'credit')
                ->whereBetween('transaction_date', [$startCurrentMonth, $endCurrentMonth])->sum('amount') ?? 0;

            // --- Kampanye Aktif ---
            $this->activeCampaigns = Campaign::withSum('transactions', 'amount')
                                        ->where('status', 'active')
                                        ->orderBy('start_date', 'desc')
                                        ->take(5) // Ambil 5 teratas
                                        ->get();

        } catch (\Exception $e) {
            Log::error('Error fetching welcome page data: ' . $e->getMessage());
            // Set nilai 'N/A' atau 0 jika terjadi error
            $this->lastMonthIncome = 'N/A';
            $this->lastMonthExpense = 'N/A';
            $this->currentMonthIncome = 'N/A';
            $this->currentMonthExpense = 'N/A';
            // Biarkan activeCampaigns sebagai collection kosong
            session()->flash('page_error', 'Gagal memuat data ringkasan. Silakan cek log.');
        }
    }

    /**
     * Render view Blade.
     */
    public function render()
    {
        // Variabel publik otomatis tersedia di view
        return view('livewire.welcome-page');
    }

    /**
     * Method untuk menangani permintaan download laporan bulanan (PDF)
     * Menerima parameter 'current' atau 'last'.
     */
    public function downloadMonthlyReport($period)
    {
        // Set zona waktu ke Asia/Jakarta (WIB) - Penting untuk Carbon
        date_default_timezone_set('Asia/Jakarta');

        try {
            if ($period === 'current') {
                $date = Carbon::now();
                $monthName = $date->isoFormat('MMMM YYYY');
                $startDate = $date->copy()->startOfMonth()->toDateString(); // Gunakan copy() agar tidak mengubah $date
                $endDate = Carbon::now()->toDateString(); // Sampai hari ini
            } elseif ($period === 'last') {
                $date = Carbon::now()->subMonthNoOverflow();
                $monthName = $date->isoFormat('MMMM YYYY');
                $startDate = $date->copy()->startOfMonth()->toDateString();
                $endDate = $date->copy()->endOfMonth()->toDateString();
            } else {
                // Default ke bulan ini jika periode tidak valid
                Log::warning('Invalid period requested for downloadMonthlyReport: ' . $period);
                $date = Carbon::now();
                $monthName = $date->isoFormat('MMMM YYYY');
                $startDate = $date->copy()->startOfMonth()->toDateString();
                $endDate = Carbon::now()->toDateString();
            }

            Log::info("Generating PDF report for period: {$monthName} ({$startDate} to {$endDate})");

            // Ambil data transaksi untuk periode terpilih (tanpa paginasi)
            // Eager load semua relasi yang mungkin dibutuhkan oleh view PDF
            $transactions = Transaction::with(['account', 'category', 'user', 'campaign', 'donation'])
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->latest('transaction_date')->latest('id')
                ->get();

            Log::info("Found " . $transactions->count() . " transactions for the period.");

            // Buat nama file PDF
            // Ganti spasi dan karakter non-alfanumerik di nama bulan
            $safeMonthName = preg_replace('/[^A-Za-z0-9_]/', '_', $monthName);
            $filename = 'laporan_keuangan_masjid_' . $safeMonthName . '.pdf';

            // Load view PDF 'exports.transactions_pdf' dengan data
            // Pastikan view ini sudah ada dan sesuai
            $pdf = Pdf::loadView('exports.transactions_pdf', [
                'transactions' => $transactions,
                'startDate' => $startDate, // Kirim start date ke view
                'endDate' => $endDate,     // Kirim end date ke view
                'category' => null,     // Tidak ada filter kategori spesifik
                'campaign' => null,     // Tidak ada filter campaign spesifik
            ])->setPaper('a4', 'landscape'); // Atur kertas A4 landscape

            Log::info("PDF generation successful for {$filename}. Preparing download...");

            // Tawarkan file PDF untuk diunduh oleh browser
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output(); // Outputkan konten PDF
            }, $filename);

        } catch (\Exception $e) {
            // Tangkap dan log error yang terjadi saat generate/download PDF
            Log::error('Error generating public monthly report PDF: ' . $e->getMessage(), ['exception' => $e]);
            // Kirim notifikasi error ke user melalui session flash
            session()->flash('page_error', 'Gagal membuat file laporan PDF. Error: ' . $e->getMessage());
            // Return null agar tidak terjadi redirect atau error aneh
            return null;
        }
    }
}
