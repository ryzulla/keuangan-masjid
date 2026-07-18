<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; margin: 0; padding: 0;}
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #17231E; padding: 5px; text-align: left; vertical-align: top; }
        th { background-color: #17231E; font-weight: bold; }
        .text-right { text-align: right; }
        .currency { font-family: monospace; white-space: nowrap; }
        .header-info { margin-bottom: 20px; text-align: center; }
        h2, h3 { margin: 0; }
        p { margin: 2px 0; }
        .page-break { page-break-after: always; } /* Jika perlu */
        tfoot th { background-color: #17231E; }
    </style>
</head>
<body>
    <div class="header-info">
        <h2>Laporan Transaksi Keuangan Masjid</h2>
        <h3>Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM YYYY') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM YYYY') }}</h3>
        @if($category) <p>Kategori: {{ $category->name }}</p> @endif
        @if($campaign) <p>Program/Kampanye: {{ $campaign->name }}</p> @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Tanggal</th>
                <th>Keterangan</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Program</th>
                <th style="width: 15%;">Akun/Kas</th>
                <th style="width: 10%;">User</th>
                <th class="text-right" style="width: 10%;">Debit (+)</th>
                <th class="text-right" style="width: 10%;">Kredit (-)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDebit = 0; $totalCredit = 0; @endphp
            @forelse($transactions as $tx)
                <tr>
                    <td>{{ optional($tx->transaction_date)->format('d/m/Y') }}</td>
                    <td>{{ $tx->description }}</td>
                    <td>{{ optional($tx->category)->name ?? '-' }}</td>
                    <td>{{ optional($tx->donation?->campaign)->name ?? '-' }}</td>
                    <td>{{ optional($tx->account)->name ?? '-' }}</td>
                    <td>{{ optional($tx->user)->name ?? '-' }}</td>
                    @if($tx->type == 'debit')
                        @php $totalDebit += $tx->amount; @endphp
                        <td class="text-right currency">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                        <td class="text-right currency">-</td>
                    @else
                        @php $totalCredit += $tx->amount; @endphp
                        <td class="text-right currency">-</td>
                        <td class="text-right currency">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data transaksi pada periode/filter ini.</td></tr>
            @endforelse
        </tbody>
        {{-- Baris Total hanya jika ada data --}}
        @if($transactions->isNotEmpty())
            <tfoot>
                <tr>
                    <th colspan="6" class="text-right">TOTAL</th>
                    <th class="text-right currency">Rp {{ number_format($totalDebit, 0, ',', '.') }}</th>
                    <th class="text-right currency">Rp {{ number_format($totalCredit, 0, ',', '.') }}</th>
                </tr>
                 <tr>
                    <th colspan="6" class="text-right">SELISIH (DEBIT - KREDIT)</th>
                    <th colspan="2" class="text-right currency">Rp {{ number_format($totalDebit - $totalCredit, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        @endif
    </table>
</body>
</html>
