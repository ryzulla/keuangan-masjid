<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        @page { margin: 24px 28px 40px; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #17231E; margin: 0; }

        /* ── Header brand ── */
        .header { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .header td { vertical-align: middle; padding: 0; }
        .brand-name { font-size: 15px; font-weight: 700; color: #164A40; }
        .brand-sub { font-size: 8px; color: #909A8F; letter-spacing: .5px; text-transform: uppercase; margin-top: 2px; }
        .doc-title { font-size: 13px; font-weight: 700; color: #17231E; }
        .doc-period { font-size: 9px; color: #586359; margin-top: 2px; }
        .rule { height: 2px; background: #164A40; margin: 8px 0 14px; font-size: 0; line-height: 0; }

        /* ── Section headings ── */
        h2 { font-size: 11px; color: #164A40; margin: 16px 0 6px; padding-bottom: 3px; border-bottom: 1px solid #E0DFD4; }

        /* ── Summary cards ── */
        .cards { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-bottom: 4px; }
        .cards td { width: 33.33%; border: 1px solid #E0DFD4; border-radius: 6px; background: #F5F7F1; padding: 9px 10px; text-align: center; }
        .summary-label { font-size: 8px; color: #909A8F; text-transform: uppercase; letter-spacing: .4px; }
        .summary-value { font-size: 12px; font-weight: 700; margin-top: 3px; }

        /* ── Tables ── */
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
        table.data th, table.data td { padding: 5px 7px; text-align: left; border-bottom: 1px solid #E9ECE4; font-size: 9px; }
        table.data thead th { background: #164A40; color: #ffffff; font-size: 8.5px; text-transform: uppercase; letter-spacing: .4px; border-bottom: none; }
        table.data tbody tr:nth-child(even) td { background: #F7F8F3; }
        table.data tfoot td { font-weight: 700; border-top: 1.5px solid #164A40; border-bottom: none; background: #EEF1EA; }
        .text-right { text-align: right; }
        .text-green { color: #12805c; }
        .text-red { color: #B0402C; }
        .muted { color: #909A8F; font-style: italic; }

        /* ── Footer ── */
        .footer { position: fixed; bottom: -24px; left: 0; right: 0; text-align: center; font-size: 7.5px; color: #A9AFA3; border-top: 1px solid #E9ECE4; padding-top: 5px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header">
        <tr>
            <td style="text-align:left;">
                <div class="brand-name">{{ $appName }}</div>
                <div class="brand-sub">Transparansi Keuangan Warga</div>
            </td>
            <td style="text-align:right;">
                <div class="doc-title">Laporan Keuangan</div>
                <div class="doc-period">
                    {{ $activeOrg === 'semua' ? 'Seluruh Organisasi' : ucfirst($activeOrg) }}
                    &middot; {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
                </div>
            </td>
        </tr>
    </table>
    <div class="rule"></div>

    {{-- Ringkasan --}}
    <h2>Ringkasan Bulan Ini</h2>
    @php $selisih = $reportData->totalIncome - $reportData->totalExpense; @endphp
    <table class="cards">
        <tr>
            <td>
                <div class="summary-label">Pemasukan</div>
                <div class="summary-value text-green">Rp {{ number_format($reportData->totalIncome, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Pengeluaran</div>
                <div class="summary-value text-red">Rp {{ number_format($reportData->totalExpense, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-label">Selisih</div>
                <div class="summary-value" style="color:{{ $selisih >= 0 ? '#12805c' : '#B0402C' }};">
                    {{ $selisih >= 0 ? '+' : '−' }}Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Saldo Kas --}}
    <h2>Saldo Kas</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Organisasi</th>
                <th>Nama Akun</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
            <tr>
                <td>{{ ucfirst($account->organization_type) }}</td>
                <td>{{ $account->name }}</td>
                <td class="text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="muted">Belum ada akun kas.</td></tr>
            @endforelse
        </tbody>
        @if(count($accounts))
        <tfoot>
            <tr>
                <td colspan="2">Total Saldo</td>
                <td class="text-right">Rp {{ number_format($accounts->sum('balance'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Pemasukan per Kategori --}}
    <h2>Pemasukan per Kategori</h2>
    <table class="data">
        <thead><tr><th>Kategori</th><th class="text-right">Jumlah</th></tr></thead>
        <tbody>
            @forelse($reportData->incomeByCategory as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="text-right text-green">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="2" class="muted">Tidak ada pemasukan pada periode ini.</td></tr>
            @endforelse
        </tbody>
        @if(count($reportData->incomeByCategory))
        <tfoot>
            <tr>
                <td>Total Pemasukan</td>
                <td class="text-right text-green">Rp {{ number_format($reportData->totalIncome, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- Pengeluaran per Kategori --}}
    <h2>Pengeluaran per Kategori</h2>
    <table class="data">
        <thead><tr><th>Kategori</th><th class="text-right">Jumlah</th></tr></thead>
        <tbody>
            @forelse($reportData->expenseByCategory as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="text-right text-red">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="2" class="muted">Tidak ada pengeluaran pada periode ini.</td></tr>
            @endforelse
        </tbody>
        @if(count($reportData->expenseByCategory))
        <tfoot>
            <tr>
                <td>Total Pengeluaran</td>
                <td class="text-right text-red">Rp {{ number_format($reportData->totalExpense, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        {{ $appName }} &middot; Digenerate otomatis {{ now()->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
    </div>

</body>
</html>
