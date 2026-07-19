<div class="space-y-5">

    {{-- ═══ HEADER ═══ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Laporan Keuangan</h2>
            <p class="text-xs mt-0.5" style="color:#909A8F;">Informasi keuangan transparan Perumahan & DKM</p>
        </div>
        <button wire:click="printPdf"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
            style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.25);">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Download PDF
        </button>
    </div>

    {{-- ═══ FILTERS ═══ --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="flex gap-1.5">
            @php $kpPerumahan = \App\Models\Setting::moduleEnabled('perumahan'); $kpDkm = \App\Models\Setting::moduleEnabled('dkm'); @endphp
            @if($kpPerumahan && $kpDkm)
            <button wire:click="$set('activeOrg', 'semua')" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
                style="{{ $activeOrg === 'semua' ? 'background:#164A40;color:#ffffff;' : 'background:rgba(22,74,64,0.08);color:#586359;border:1px solid rgba(22,74,64,0.15);' }}">Semua</button>
            @endif
            @if($kpPerumahan)
            <button wire:click="$set('activeOrg', 'perumahan')" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
                style="{{ $activeOrg === 'perumahan' ? 'background:#164A40;color:#ffffff;' : 'background:rgba(22,74,64,0.08);color:#586359;border:1px solid rgba(22,74,64,0.15);' }}">Perumahan</button>
            @endif
            @if($kpDkm)
            <button wire:click="$set('activeOrg', 'dkm')" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
                style="{{ $activeOrg === 'dkm' ? 'background:#164A40;color:#ffffff;' : 'background:rgba(22,74,64,0.08);color:#586359;border:1px solid rgba(22,74,64,0.15);' }}">DKM</button>
            @endif
        </div>
        <select wire:model.live="month" class="text-xs rounded-lg px-3 py-1.5" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;">
            @foreach($months as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
        <select wire:model.live="year" class="text-xs rounded-lg px-3 py-1.5" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;">
            @foreach($availableYears as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>

    @php $summary = $this->monthlySummary; @endphp

    {{-- ═══ RINGKASAN BULAN INI ═══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="rounded-2xl p-5" style="background:rgba(18,128,92,0.06);border:1px solid rgba(18,128,92,0.2);">
            <p class="text-xs font-medium" style="color:#12805c;">Pemasukan</p>
            <p class="text-xl font-bold mt-1 pp-rp" style="color:#12805c;">
                Rp {{ number_format($summary['totalIncome'], 0, ',', '.') }}
            </p>
            <p class="text-[10px] mt-1" style="color:#909A8F;">{{ $months[$month] }} {{ $year }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background:rgba(176,64,44,0.06);border:1px solid rgba(176,64,44,0.2);">
            <p class="text-xs font-medium" style="color:#B0402C;">Pengeluaran</p>
            <p class="text-xl font-bold mt-1 pp-rp" style="color:#B0402C;">
                Rp {{ number_format($summary['totalExpense'], 0, ',', '.') }}
            </p>
            <p class="text-[10px] mt-1" style="color:#909A8F;">{{ $months[$month] }} {{ $year }}</p>
        </div>
        @php $selisih = $summary['totalIncome'] - $summary['totalExpense']; @endphp
        <div class="rounded-2xl p-5" style="background:{{ $selisih >= 0 ? 'rgba(22,74,64,0.06)' : 'rgba(176,64,44,0.06)' }};border:1px solid {{ $selisih >= 0 ? 'rgba(22,74,64,0.2)' : 'rgba(176,64,44,0.2)' }};">
            <p class="text-xs font-medium" style="color:{{ $selisih >= 0 ? '#164A40' : '#B0402C' }};">Selisih</p>
            <p class="text-xl font-bold mt-1 pp-rp" style="color:{{ $selisih >= 0 ? '#164A40' : '#B0402C' }};">
                {{ $selisih >= 0 ? '+' : '' }}Rp {{ number_format($selisih, 0, ',', '.') }}
            </p>
            <p class="text-[10px] mt-1" style="color:#909A8F;">{{ $selisih >= 0 ? 'Surplus' : 'Defisit' }}</p>
        </div>
    </div>

    {{-- ═══ SALDO REKENING ═══ --}}
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <div class="px-5 py-4" style="border-bottom:1px solid #F1F3EC;">
            <h3 class="text-sm font-semibold" style="color:#17231E;">Saldo Rekening</h3>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            @forelse($this->accounts as $account)
                <div class="flex items-center justify-between rounded-xl px-4 py-3" style="background:#F1F3EC;">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-medium" style="color:#909A8F;">{{ $account->organization_type }}</p>
                        <p class="text-xs font-medium mt-0.5" style="color:#17231E;">{{ $account->name }}</p>
                    </div>
                    <p class="text-sm font-bold pp-rp" style="color:#12805c;">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                </div>
            @empty
                <p class="text-xs" style="color:#909A8F;">Belum ada rekening.</p>
            @endforelse
        </div>
    </div>

    {{-- ═══ TRANSAKSI TERBARU ═══ --}}
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <div class="px-5 py-4" style="border-bottom:1px solid #F1F3EC;">
            <h3 class="text-sm font-semibold" style="color:#17231E;">Transaksi Terbaru</h3>
        </div>

        {{-- Desktop table --}}
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid #F1F3EC;">
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Tanggal</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Keterangan</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Kategori</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Akun</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->recentTransactions as $tx)
                    <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                        <td class="px-5 py-3 text-xs" style="color:#586359;">{{ $tx->transaction_date->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-xs font-medium" style="color:#17231E;">{{ $tx->description ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-medium"
                                style="background:rgba(22,74,64,0.08);color:#17231E;">
                                {{ $tx->category?->name ?? '—' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs" style="color:#586359;">{{ $tx->account?->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-xs font-bold text-right" style="color:{{ $tx->type === 'debit' ? '#12805c' : '#B0402C' }};">
                            {{ $tx->type === 'debit' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center">
                            <p class="text-sm" style="color:#909A8F;">Belum ada transaksi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
            @forelse($this->recentTransactions as $tx)
            <div class="px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-sm font-medium truncate" style="color:#17231E;">{{ $tx->description ?? '—' }}</p>
                        <p class="text-[10px] mt-0.5" style="color:#909A8F;">
                            {{ $tx->transaction_date->format('d M Y') }}
                            · {{ $tx->category?->name ?? '—' }}
                            · {{ $tx->account?->name ?? '—' }}
                        </p>
                    </div>
                    <span class="text-sm font-bold shrink-0 ml-3" style="color:{{ $tx->type === 'debit' ? '#12805c' : '#B0402C' }};">
                        {{ $tx->type === 'debit' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <p class="text-sm" style="color:#909A8F;">Belum ada transaksi.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ═══ TREN 6 BULAN ═══ --}}
    @php $trend = $this->monthlyTrend; @endphp
    @if(array_sum($trend['incomeData']) > 0 || array_sum($trend['expenseData']) > 0)
    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <h3 class="text-sm font-semibold mb-3" style="color:#17231E;">Tren 6 Bulan Terakhir</h3>
        <div wire:key="bar-{{ $activeOrg }}"
            x-data="{
                chart: null,
                init() { this.$nextTick(() => this.render()); },
                render() {
                    if (this.chart) this.chart.destroy();
                    const ctx = document.getElementById('keuBar');
                    if (!ctx) return;
                    const labels = {{ Js::from($trend['labels']) }};
                    const income = {{ Js::from($trend['incomeData']) }};
                    const expense = {{ Js::from($trend['expenseData']) }};
                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                { label: 'Pemasukan', data: income, backgroundColor: 'rgba(18,128,92,0.8)', borderRadius: 4 },
                                { label: 'Pengeluaran', data: expense, backgroundColor: 'rgba(176,64,44,0.8)', borderRadius: 4 }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8 } } },
                            scales: {
                                y: { beginAtZero: true, ticks: { font: { size: 9 }, callback: v => 'Rp' + (v/1000).toFixed(0) + 'k' } },
                                x: { ticks: { font: { size: 9 } } }
                            }
                        }
                    });
                }
            }">
            <canvas id="keuBar" height="180"></canvas>
        </div>
    </div>
    @endif

    {{-- ═══ RINCIAN PER KATEGORI ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Pemasukan --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="px-5 py-4" style="border-bottom:1px solid #F1F3EC;">
                <h4 class="text-sm font-semibold" style="color:#12805c;">Pemasukan per Kategori</h4>
            </div>
            <div class="divide-y" style="border-color:#F1F3EC;">
                @forelse($summary['incomeByCategory'] as $item)
                    @php $pct = $summary['totalIncome'] > 0 ? ($item->total / $summary['totalIncome']) * 100 : 0; @endphp
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium" style="color:#17231E;">{{ $item->name }}</span>
                            <span class="text-xs font-bold" style="color:#12805c;">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-1.5 rounded-full" style="background:#F1F3EC;">
                            <div class="h-1.5 rounded-full" style="width:{{ $pct }}%;background:#12805c;"></div>
                        </div>
                        <p class="text-[10px] mt-1" style="color:#909A8F;">{{ number_format($pct, 0) }}%</p>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-sm" style="color:#909A8F;">Tidak ada pemasukan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pengeluaran --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="px-5 py-4" style="border-bottom:1px solid #F1F3EC;">
                <h4 class="text-sm font-semibold" style="color:#B0402C;">Pengeluaran per Kategori</h4>
            </div>
            <div class="divide-y" style="border-color:#F1F3EC;">
                @forelse($summary['expenseByCategory'] as $item)
                    @php $pct = $summary['totalExpense'] > 0 ? ($item->total / $summary['totalExpense']) * 100 : 0; @endphp
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium" style="color:#17231E;">{{ $item->name }}</span>
                            <span class="text-xs font-bold" style="color:#B0402C;">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                        </div>
                        <div class="h-1.5 rounded-full" style="background:#F1F3EC;">
                            <div class="h-1.5 rounded-full" style="width:{{ $pct }}%;background:#B0402C;"></div>
                        </div>
                        <p class="text-[10px] mt-1" style="color:#909A8F;">{{ number_format($pct, 0) }}%</p>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <p class="text-sm" style="color:#909A8F;">Tidak ada pengeluaran.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
