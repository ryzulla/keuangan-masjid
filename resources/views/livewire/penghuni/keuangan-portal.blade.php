<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Laporan Keuangan</h2>
            <p class="text-xs mt-0.5" style="color:#a3abb0;">Informasi keuangan transparan Perumahan & DKM</p>
        </div>
        <button wire:click="printPdf"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
            style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.25);">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Download PDF
        </button>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1.5">
        <button wire:click="$set('activeOrg', 'semua')" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
            style="{{ $activeOrg === 'semua' ? 'background:#1563df;color:#ffffff;' : 'background:rgba(21,99,223,0.08);color:#5c6368;border:1px solid rgba(21,99,223,0.15);' }}">Semua</button>
        <button wire:click="$set('activeOrg', 'perumahan')" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
            style="{{ $activeOrg === 'perumahan' ? 'background:#1563df;color:#ffffff;' : 'background:rgba(21,99,223,0.08);color:#5c6368;border:1px solid rgba(21,99,223,0.15);' }}">Perumahan</button>
        <button wire:click="$set('activeOrg', 'dkm')" class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
            style="{{ $activeOrg === 'dkm' ? 'background:#1563df;color:#ffffff;' : 'background:rgba(21,99,223,0.08);color:#5c6368;border:1px solid rgba(21,99,223,0.15);' }}">DKM</button>
    </div>

    {{-- Filter bulan/tahun --}}
    <div class="flex flex-wrap gap-3">
        <select wire:model.live="month" class="text-xs rounded-lg px-3 py-1.5" style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;">
            @foreach($months as $val => $label)
                <option value="{{ $val }}">{{ $label }}</option>
            @endforeach
        </select>
        <select wire:model.live="year" class="text-xs rounded-lg px-3 py-1.5" style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;">
            @foreach($availableYears as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>

    @php $data = $this->reportData; @endphp

    {{-- Ringkasan Arus Kas --}}
    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
        <h3 class="text-sm font-semibold mb-3" style="color:#161e2d;">
            Arus Kas — {{ $months[$month] }} {{ $year }}
        </h3>
        <p class="text-xs mb-4" style="color:#a3abb0;">
            {{ $activeOrg === 'semua' ? 'Seluruh organisasi' : ucfirst($activeOrg) }}
        </p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="rounded-xl p-3" style="background:rgba(24,144,255,0.06);border:1px solid rgba(24,144,255,0.15);">
                <p class="text-xs" style="color:#a3abb0;">Saldo Awal</p>
                <p class="text-sm font-bold mt-0.5" style="color:#1890ff;">
                    Rp {{ number_format($data['startingBalance'], 0, ',', '.') }}
                </p>
            </div>
            <div class="rounded-xl p-3" style="background:rgba(18,128,92,0.06);border:1px solid rgba(18,128,92,0.15);">
                <p class="text-xs" style="color:#a3abb0;">Pemasukan</p>
                <p class="text-sm font-bold mt-0.5" style="color:#12805c;">
                    Rp {{ number_format($data['totalIncome'], 0, ',', '.') }}
                </p>
            </div>
            <div class="rounded-xl p-3" style="background:rgba(192,69,59,0.06);border:1px solid rgba(192,69,59,0.15);">
                <p class="text-xs" style="color:#a3abb0;">Pengeluaran</p>
                <p class="text-sm font-bold mt-0.5" style="color:#c0453b;">
                    Rp {{ number_format($data['totalExpense'], 0, ',', '.') }}
                </p>
            </div>
            <div class="rounded-xl p-3" style="background:rgba(21,99,223,0.06);border:1px solid rgba(21,99,223,0.15);">
                <p class="text-xs" style="color:#a3abb0;">Saldo Akhir</p>
                <p class="text-sm font-bold mt-0.5" style="color:#161e2d;">
                    Rp {{ number_format($data['endingBalance'], 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Saldo Kas --}}
    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
        <h3 class="text-sm font-semibold mb-3" style="color:#161e2d;">Saldo Kas</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @forelse($this->accounts as $account)
                <div class="rounded-xl p-3" style="background:rgba(21,99,223,0.04);border:1px solid rgba(21,99,223,0.12);">
                    <p class="text-xs" style="color:#a3abb0;">{{ ucfirst($account->organization_type) }}</p>
                    <p class="text-sm font-semibold mt-0.5" style="color:#161e2d;">{{ $account->name }}</p>
                    <p class="text-base font-bold mt-1" style="color:#12805c;">
                        Rp {{ number_format($account->balance, 0, ',', '.') }}
                    </p>
                    @if($account->description)
                        <p class="text-xs mt-1" style="color:#a3abb0;">{{ $account->description }}</p>
                    @endif
                </div>
            @empty
                <p class="text-xs" style="color:#a3abb0;">Belum ada akun kas.</p>
            @endforelse
        </div>
    </div>

    {{-- Tren 6 Bulan --}}
    @php $trend = $this->monthlyTrend; @endphp
    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
        <h3 class="text-sm font-semibold mb-3" style="color:#161e2d;">Tren 6 Bulan Terakhir</h3>
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
                    if (income.every(v => v === 0) && expense.every(v => v === 0)) return;
                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [
                                { label: 'Pemasukan', data: income, backgroundColor: 'rgba(18,128,92,0.8)', borderRadius: 4 },
                                { label: 'Pengeluaran', data: expense, backgroundColor: 'rgba(192,69,59,0.8)', borderRadius: 4 }
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
            @if(array_sum($trend['incomeData']) === 0 && array_sum($trend['expenseData']) === 0)
                <p class="text-xs text-center mt-3" style="color:#a3abb0;">Belum ada data transaksi 6 bulan terakhir.</p>
            @endif
        </div>
    </div>

    {{-- Detail per Kategori --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <h4 class="text-xs font-semibold mb-3" style="color:#12805c;">Pemasukan per Kategori</h4>
            <div wire:key="pie-income-{{ $activeOrg }}-{{ $month }}"
                x-data="{
                    chart: null,
                    init() { this.$nextTick(() => this.render()); },
                    render() {
                        if (this.chart) this.chart.destroy();
                        const labels = {{ Js::from($data['incomeSummary']->pluck('name')) }};
                        const values = {{ Js::from($data['incomeSummary']->pluck('total')->map(fn($v) => (float) $v)) }};
                        if (labels.length === 0 || values.every(v => v === 0)) return;
                        const colors = ['#12805c','#1890ff','#1563df','#722ed1','#13c2c2','#eb2f96','#faad14','#a0d911'];
                        const ctx = document.getElementById('keuPieIncome');
                        if (!ctx) return;
                        this.chart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{ data: values, backgroundColor: colors.slice(0, labels.length), borderWidth: 0 }]
                            },
                            options: {
                                responsive: true, cutout: '55%',
                                plugins: { legend: { position: 'bottom', labels: { font: { size: 9 }, padding: 6 } } }
                            }
                        });
                    }
                }">
                <canvas id="keuPieIncome" height="160"></canvas>
            </div>
            <div class="mt-3 space-y-1">
                @forelse($data['incomeSummary'] as $item)
                    <div class="flex justify-between items-center py-1.5 px-3 rounded-lg" style="background:rgba(18,128,92,0.04);">
                        <span class="text-xs" style="color:#161e2d;">{{ $item->name }}</span>
                        <span class="text-xs font-medium" style="color:#12805c;">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-xs" style="color:#a3abb0;">Tidak ada pemasukan.</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <h4 class="text-xs font-semibold mb-3" style="color:#c0453b;">Pengeluaran per Kategori</h4>
            <div wire:key="pie-expense-{{ $activeOrg }}-{{ $month }}"
                x-data="{
                    chart: null,
                    init() { this.$nextTick(() => this.render()); },
                    render() {
                        if (this.chart) this.chart.destroy();
                        const labels = {{ Js::from($data['expenseSummary']->pluck('name')) }};
                        const values = {{ Js::from($data['expenseSummary']->pluck('total')->map(fn($v) => (float) $v)) }};
                        if (labels.length === 0 || values.every(v => v === 0)) return;
                        const colors = ['#c0453b','#fa8c16','#eb2f96','#722ed1','#13c2c2','#faad14','#a0d911','#1890ff'];
                        const ctx = document.getElementById('keuPieExpense');
                        if (!ctx) return;
                        this.chart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: labels,
                                datasets: [{ data: values, backgroundColor: colors.slice(0, labels.length), borderWidth: 0 }]
                            },
                            options: {
                                responsive: true, cutout: '55%',
                                plugins: { legend: { position: 'bottom', labels: { font: { size: 9 }, padding: 6 } } }
                            }
                        });
                    }
                }">
                <canvas id="keuPieExpense" height="160"></canvas>
            </div>
            <div class="mt-3 space-y-1">
                @forelse($data['expenseSummary'] as $item)
                    <div class="flex justify-between items-center py-1.5 px-3 rounded-lg" style="background:rgba(192,69,59,0.04);">
                        <span class="text-xs" style="color:#161e2d;">{{ $item->name }}</span>
                        <span class="text-xs font-medium" style="color:#c0453b;">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-xs" style="color:#a3abb0;">Tidak ada pengeluaran.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
