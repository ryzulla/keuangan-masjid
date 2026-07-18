<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #17231E; }
        h1 { font-size: 16px; margin: 0 0 4px; text-align: center; }
        .subtitle { text-align: center; font-size: 9px; color: #909A8F; margin-bottom: 16px; }
        h2 { font-size: 11px; margin: 16px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #E0DFD4; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { padding: 4px 6px; text-align: left; border: 1px solid #E0DFD4; }
        th { font-size: 9px; background: #ffffff; }
        td { font-size: 9px; }
        .text-right { text-align: right; }
        .text-green { color: #12805c; }
        .text-red { color: #B0402C; }
        .font-bold { font-weight: 700; }
        .summary-cards { margin-bottom: 16px; }
        .summary-card { display: inline-block; width: 31%; padding: 8px; border: 1px solid #E0DFD4; text-align: center; margin-right: 1%; }
        .summary-label { font-size: 8px; color: #909A8F; }
        .summary-value { font-size: 11px; font-weight: 700; margin-top: 2px; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #909A8F; }
    </style>
</head>
<body>
    <h1>Laporan Keuangan</h1>
    <p class="subtitle">
        {{ $activeOrg === 'semua' ? 'Seluruh Organisasi' : ucfirst($activeOrg) }} &mdash;
        {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
    </p>

    <h2>Ringkasan Bulan Ini</h2>
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-label">Pemasukan</div>
            <div class="summary-value text-green">Rp {{ number_format($reportData->totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pengeluaran</div>
            <div class="summary-value text-red">Rp {{ number_format($reportData->totalExpense, 0, ',', '.') }}</div>
        </div>
        @php $selisih = $reportData->totalIncome - $reportData->totalExpense; @endphp
        <div class="summary-card">
            <div class="summary-label">Selisih</div>
            <div class="summary-value" style="color:{{ $selisih >= 0 ? '#12805c' : '#B0402C' }};">
                {{ $selisih >= 0 ? '+' : '' }}Rp {{ number_format($selisih, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <h2>Saldo Kas</h2>
    <table>
        <thead>
            <tr>
                <th>Organisasi</th>
                <th>Nama Akun</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td>{{ ucfirst($account->organization_type) }}</td>
                <td>{{ $account->name }}</td>
                <td class="text-right">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Pemasukan per Kategori</h2>
    <table>
        <thead><tr><th>Kategori</th><th class="text-right">Jumlah</th></tr></thead>
        <tbody>
            @forelse($reportData->incomeByCategory as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="text-right text-green">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="2">Tidak ada pemasukan.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Pengeluaran per Kategori</h2>
    <table>
        <thead><tr><th>Kategori</th><th class="text-right">Jumlah</th></tr></thead>
        <tbody>
            @forelse($reportData->expenseByCategory as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="text-right text-red">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr><td colspan="2">Tidak ada pengeluaran.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini digenerate otomatis dari sistem — {{ now()->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}
    </div>
</body>
</html>
