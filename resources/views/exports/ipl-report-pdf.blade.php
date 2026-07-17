<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan IPL {{ $period->period_label }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; margin: 0; padding: 20px; }
        h2, h3, h4 { margin: 0; }
        .header-info { text-align: center; margin-bottom: 20px; }
        .header-info h2 { font-size: 14px; margin-bottom: 4px; }
        .header-info p { margin: 2px 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; vertical-align: top; font-size: 9px; }
        th { background-color: #161e2d; color: #fff; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .currency { font-family: monospace; white-space: nowrap; }
        .mt-20 { margin-top: 20px; }
        .lunas { color: #12805c; font-weight: bold; }
        .belum { color: #c0453b; font-weight: bold; }
        .sebagian { color: #c77d1a; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 9px; }
        .section-title { font-size: 11px; font-weight: bold; margin-top: 16px; margin-bottom: 6px; }
    </style>
</head>
<body>
    <div class="header-info">
        <h2>Laporan IPL — Iuran Perumahan</h2>
        <h3>Periode: {{ $period->period_label }}</h3>
        <p>Tarif: Security Rp {{ number_format($period->ipl_security_amount, 0, ',', '.') }} | Sampah Rp {{ number_format($period->ipl_garbage_amount, 0, ',', '.') }} | Kas RT Rp {{ number_format($period->ipl_kas_rt_amount, 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Blok</th>
                <th class="text-center">Unit</th>
                <th class="text-center">Lunas</th>
                <th class="text-center">Belum</th>
                <th class="text-center">Sebagian</th>
                <th class="text-right">Tagihan</th>
                <th class="text-right">Terbayar</th>
                <th class="text-right">Tunggakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summaryByBlock as $row)
            <tr>
                <td><strong>{{ $row->block_letter }}</strong></td>
                <td class="text-center">{{ $row->jumlah_unit }}</td>
                <td class="text-center lunas">{{ $row->lunas }}</td>
                <td class="text-center belum">{{ $row->belum_bayar }}</td>
                <td class="text-center sebagian">{{ $row->sebagian }}</td>
                <td class="text-right currency">Rp {{ number_format($row->total_tagihan, 0, ',', '.') }}</td>
                <td class="text-right currency">Rp {{ number_format($row->total_terbayar, 0, ',', '.') }}</td>
                <td class="text-right currency">Rp {{ number_format(max(0, $row->total_tagihan - $row->total_terbayar - $row->total_dibebaskan), 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding:20px;">Tidak ada data tagihan untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">TOTAL</th>
                <th class="text-center">{{ $totals['lunas'] }}</th>
                <th class="text-center">{{ $totals['belum'] }}</th>
                <th class="text-center">{{ $totals['sebagian'] }}</th>
                <th class="text-right currency">Rp {{ number_format($totals['tagihan'], 0, ',', '.') }}</th>
                <th class="text-right currency">Rp {{ number_format($totals['terbayar'], 0, ',', '.') }}</th>
                <th class="text-right currency">Rp {{ number_format($totals['tunggakan'], 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    @if($unpaidResidents->isNotEmpty())
    <div class="section-title">Daftar Penunggak</div>
    <table>
        <thead>
            <tr>
                <th>Blok</th>
                <th>Penghuni</th>
                <th class="text-right">Tagihan</th>
                <th class="text-right">Terbayar</th>
                <th class="text-right">Tunggakan</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($unpaidResidents as $b)
            <tr>
                <td>{{ $b->houseBlock?->block_code ?? '—' }}</td>
                <td>{{ $b->responsibleResident?->name ?? '—' }}</td>
                <td class="text-right currency">Rp {{ number_format($b->total_amount, 0, ',', '.') }}</td>
                <td class="text-right currency">Rp {{ number_format($b->total_paid, 0, ',', '.') }}</td>
                <td class="text-right currency">Rp {{ number_format($b->outstanding, 0, ',', '.') }}</td>
                <td class="text-center {{ $b->status === 'partial' ? 'sebagian' : 'belum' }}">{{ $b->status === 'partial' ? 'Sebagian' : 'Belum Bayar' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->isoFormat('D MMMM YYYY HH:mm') }}</p>
    </div>
</body>
</html>