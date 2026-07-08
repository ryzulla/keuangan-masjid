<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #1d2939; }
        h1 { font-size: 16px; margin: 0 0 4px; text-align: center; }
        .subtitle { text-align: center; font-size: 9px; color: #7c8698; margin-bottom: 16px; }
        h2 { font-size: 11px; margin: 16px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #e4e7ec; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        th, td { padding: 4px 6px; text-align: left; border: 1px solid #e4e7ec; }
        th { font-size: 9px; background: #ffffff; }
        td { font-size: 9px; }
        .text-right { text-align: right; }
        .text-green { color: #12805c; }
        .text-red { color: #c0453b; }
        .text-orange { color: #111827; }
        .font-bold { font-weight: 700; }
        .summary-cards { margin-bottom: 16px; }
        .summary-card { display: inline-block; width: 23%; padding: 8px; border: 1px solid #e4e7ec; text-align: center; margin-right: 1%; }
        .summary-label { font-size: 8px; color: #98a2b3; }
        .summary-value { font-size: 11px; font-weight: 700; margin-top: 2px; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #98a2b3; }
    </style>
</head>
<body>
    <h1>Laporan Keuangan</h1>
    <p class="subtitle">
        {{ $activeOrg === 'semua' ? 'Seluruh Organisasi' : ucfirst($activeOrg) }} &mdash;
        {{ \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY') }}
    </p>

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

    <h2>Arus Kas — Ringkasan</h2>
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-label">Saldo Awal</div>
            <div class="summary-value" style="color:#1890ff;">Rp {{ number_format($reportData->startingBalance, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pemasukan</div>
            <div class="summary-value text-green">Rp {{ number_format($reportData->totalIncome, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Pengeluaran</div>
            <div class="summary-value text-red">Rp {{ number_format($reportData->totalExpense, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="summary-label">Saldo Akhir</div>
            <div class="summary-value text-orange">Rp {{ number_format($reportData->endingBalance, 0, ',', '.') }}</div>
        </div>
    </div>

    <h2>Pemasukan per Kategori</h2>
    <table>
        <thead><tr><th>Kategori</th><th class="text-right">Jumlah</th></tr></thead>
        <tbody>
            @forelse($reportData->incomeSummary as $item)
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
            @forelse($reportData->expenseSummary as $item)
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
