<?php
namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

// 3. Tambahkan WithColumnFormatting ke implements
class TransactionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    protected $startDate;
    protected $endDate;
    protected $categoryId;
    protected $campaignId;
    protected $showCampaignFilter;

    // Construct tetap sama
    public function __construct($startDate, $endDate, $categoryId, $campaignId, $showCampaignFilter)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->categoryId = $categoryId;
        $this->campaignId = $campaignId;
        $this->showCampaignFilter = $showCampaignFilter;
    }

    public function query()
    {
        $query = Transaction::query()
                // Eager load semua relasi yang dibutuhkan
                ->with(['account', 'category', 'user', 'campaign', 'donation'])
                ->whereBetween('transaction_date', [$this->startDate, $this->endDate]);

        if (!empty($this->categoryId)) {
            $query->where('category_id', $this->categoryId);
        }

        // Filter campaign LANGSUNG di tabel transactions
        if ($this->showCampaignFilter && !empty($this->campaignId)) {
            $query->where('campaign_id', $this->campaignId);
        }

        return $query->latest('transaction_date')->latest('id');
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Keterangan', 'Kategori', 'Program/Kampanye',
            'Akun/Kas', 'User Pencatat', 'Donatur', // <-- Tambah Donatur
            'Debit (+)', 'Kredit (-)',
        ];
    }

    public function map($transaction): array
    {
         return [
            optional($transaction->transaction_date)->format('d/m/Y'),
            $transaction->description,
            optional($transaction->category)->name ?? '-',
            optional($transaction->campaign)->name ?? '-', // <-- Ambil dari relasi campaign
            optional($transaction->account)->name ?? '-',
            optional($transaction->user)->name ?? '-',
            optional($transaction->donation)->donor_name ?? '-', // <-- Ambil donor_name
            $transaction->type == 'debit' ? $transaction->amount : 0,
            $transaction->type == 'credit' ? $transaction->amount : 0,
         ];
    }
    // 4. Hapus format angka dari styles()
    public function styles(Worksheet $sheet)
    {
        return [
            // Style baris pertama (header) saja
            1    => ['font' => ['bold' => true]],
            // 'G'  => ['numberFormat' => '#,##0'], // <-- HAPUS
            // 'H'  => ['numberFormat' => '#,##0'], // <-- HAPUS
        ];
    }

    // 5. Tambahkan method columnFormats()
    public function columnFormats(): array { /* ... (Sama, kolom H & I sekarang) ... */
         return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
         ];
    }
}
