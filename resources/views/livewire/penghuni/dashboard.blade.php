<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Welcome Header --}}
    <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(16,24,40,0.35);">
        <div class="flex items-center gap-4">
            @if($resident->photo)
                <img src="{{ Storage::disk('public')->url($resident->photo) }}"
                    alt="{{ $resident->name }}"
                    class="w-16 h-16 rounded-2xl object-cover shrink-0"
                    style="border:2px solid rgba(16,24,40,0.5);">
            @else
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold shrink-0"
                    style="background:rgba(16,24,40,0.15);color:#111827;border:1px solid rgba(16,24,40,0.3);">
                    {{ strtoupper(substr($resident->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <p class="text-xs mb-1" style="color:#111827;">Selamat datang,</p>
                <h2 class="text-xl font-bold" style="color:#111827;font-family:'IBM Plex Sans',serif;">{{ $resident->name }}</h2>
                <div class="flex flex-wrap items-center gap-3 mt-2">
                    @forelse($resident->currentAssignments as $assignment)
                        <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-lg font-medium"
                            style="background:rgba(16,24,40,0.12);color:#111827;border:1px solid rgba(16,24,40,0.25);">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            {{ $assignment->houseBlock?->block_code ?? '-' }}
                            <span style="color:#111827;">{{ ucfirst($assignment->ownership_type) }}</span>
                        </span>
                    @empty
                        <span class="text-xs" style="color:#98a2b3;">Belum ada blok ditetapkan</span>
                    @endforelse
                    <span class="text-xs" style="color:#7c8698;">
                        {{ $resident->familyMembers->count() }} anggota keluarga
                    </span>
                </div>
            </div>
        </div>
    </div>

    @php $monthly = $this->monthlySummary; @endphp

    {{-- Keuangan Transparan --}}
    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold" style="color:#111827;">Info Keuangan</h3>
            <div class="flex items-center gap-1.5">
                <button wire:click="$set('activeOrg', 'semua')" class="text-xs px-2.5 py-1 rounded-lg font-medium transition-colors"
                    style="{{ $activeOrg === 'semua' ? 'background:#111827;color:#ffffff;' : 'background:rgba(16,24,40,0.08);color:#667085;border:1px solid rgba(16,24,40,0.15);' }}">Semua</button>
                <button wire:click="$set('activeOrg', 'perumahan')" class="text-xs px-2.5 py-1 rounded-lg font-medium transition-colors"
                    style="{{ $activeOrg === 'perumahan' ? 'background:#111827;color:#ffffff;' : 'background:rgba(16,24,40,0.08);color:#667085;border:1px solid rgba(16,24,40,0.15);' }}">Perumahan</button>
                <button wire:click="$set('activeOrg', 'dkm')" class="text-xs px-2.5 py-1 rounded-lg font-medium transition-colors"
                    style="{{ $activeOrg === 'dkm' ? 'background:#111827;color:#ffffff;' : 'background:rgba(16,24,40,0.08);color:#667085;border:1px solid rgba(16,24,40,0.15);' }}">DKM</button>
                <a href="{{ route('penghuni.keuangan') }}" wire:navigate class="text-xs ml-2 hover:underline" style="color:#667085;">Detail</a>
            </div>
        </div>

        <div class="flex gap-6">
            {{-- Left: Chart --}}
            <div class="shrink-0 w-28 h-28 flex items-center justify-center"
                wire:key="dash-donut-{{ $activeOrg }}"
                x-data="{
                    chart: null,
                    init() { this.$nextTick(() => this.renderChart()); },
                    renderChart() {
                        if (this.chart) this.chart.destroy();
                        const ti = {{ $monthly['totalIncome'] }};
                        const te = {{ $monthly['totalExpense'] }};
                        if (ti === 0 && te === 0) return;
                        const ctx = document.getElementById('dashDonut');
                        if (!ctx) return;
                        this.chart = new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Pemasukan', 'Pengeluaran'],
                                datasets: [{ data: [ti, te], backgroundColor: ['#12805c', '#c0453b'], borderWidth: 0 }]
                            },
                            options: { responsive: true, cutout: '70%', plugins: { legend: { display: false } } }
                        });
                    }
                }">
                <canvas id="dashDonut"></canvas>
            </div>
            {{-- Right: accounts + summary --}}
            <div class="flex-1 min-w-0">
                <div class="grid grid-cols-2 gap-2 mb-3">
                    @foreach($this->accounts as $account)
                        <div class="rounded-lg p-2.5" style="background:rgba(16,24,40,0.04);border:1px solid rgba(16,24,40,0.12);">
                            <p class="text-[10px]" style="color:#98a2b3;">{{ ucfirst($account->organization_type) }}</p>
                            <p class="text-xs font-semibold" style="color:#111827;">{{ $account->name }}</p>
                            <p class="text-sm font-bold" style="color:#12805c;">
                                Rp {{ number_format($account->balance, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs">
                    <span style="color:#1d2939;">
                        <span class="inline-block w-2 h-2 rounded-full mr-1" style="background:#12805c;"></span>
                        Pemasukan: <strong>Rp {{ number_format($monthly['totalIncome'], 0, ',', '.') }}</strong>
                    </span>
                    <span style="color:#1d2939;">
                        <span class="inline-block w-2 h-2 rounded-full mr-1" style="background:#c0453b;"></span>
                        Pengeluaran: <strong>Rp {{ number_format($monthly['totalExpense'], 0, ',', '.') }}</strong>
                    </span>
                    <span style="color:{{ $monthly['totalIncome'] >= $monthly['totalExpense'] ? '#12805c' : '#c0453b' }};">
                        Selisih: <strong>Rp {{ number_format($monthly['totalIncome'] - $monthly['totalExpense'], 0, ',', '.') }}</strong>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="rounded-2xl p-4 sm:p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            <p class="text-xs mb-2" style="color:#98a2b3;">IPL Tunggakan</p>
            <p class="text-base sm:text-lg font-bold leading-tight" style="color:{{ $totalOutstanding > 0 ? '#c0453b' : '#12805c' }};">
                Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
            </p>
            @if($totalOutstanding > 0)
                <a href="{{ route('penghuni.ipl') }}" wire:navigate class="text-xs mt-1.5 inline-block hover:underline" style="color:#111827;">Bayar sekarang</a>
            @else
                <p class="text-xs mt-1.5" style="color:#12805c;">Lunas</p>
            @endif
        </div>

        <div class="rounded-2xl p-4 sm:p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            <p class="text-xs mb-2" style="color:#98a2b3;">Anggota Keluarga</p>
            <p class="text-base sm:text-lg font-bold" style="color:#111827;">{{ $resident->familyMembers->count() }}</p>
            <a href="{{ route('penghuni.keluarga') }}" wire:navigate class="text-xs mt-1.5 inline-block hover:underline" style="color:#667085;">Kelola data</a>
        </div>

        <div class="rounded-2xl p-4 sm:p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            <p class="text-xs mb-2" style="color:#98a2b3;">Menunggu Konfirmasi</p>
            <p class="text-base sm:text-lg font-bold" style="color:{{ $pendingRequests > 0 ? '#c77d1a' : '#667085' }};">{{ $pendingRequests }}</p>
            <p class="text-xs mt-1.5" style="color:#98a2b3;">pembayaran</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- IPL Terbaru --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #f5f6f8;">
                <h3 class="text-sm font-semibold" style="color:#111827;">IPL Terbaru</h3>
                <a href="{{ route('penghuni.ipl') }}" wire:navigate class="text-xs hover:underline" style="color:#667085;">Lihat semua</a>
            </div>
            @forelse($billings as $billing)
                @php
                    $statusStyle = match($billing->status) {
                        'paid'    => 'background:rgba(18,128,92,0.12);color:#12805c;border:1px solid rgba(18,128,92,0.25);',
                        'partial' => 'background:rgba(199,125,26,0.12);color:#c77d1a;border:1px solid rgba(199,125,26,0.25);',
                        default   => 'background:rgba(192,69,59,0.12);color:#c0453b;border:1px solid rgba(192,69,59,0.25);',
                    };
                    $statusLabel = match($billing->status) {
                        'paid'    => 'Lunas',
                        'partial' => 'Sebagian',
                        default   => 'Belum Bayar',
                    };
                @endphp
                <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom:1px solid #ffffff;">
                    <div>
                        <p class="text-sm font-medium" style="color:#1d2939;">
                            {{ \Carbon\Carbon::create($billing->period->year, $billing->period->month)->translatedFormat('F Y') }}
                        </p>
                        <p class="text-xs mt-0.5" style="color:#98a2b3;">
                            {{ $billing->houseBlock?->block_code ?? '-' }}
                            &middot;
                            Rp {{ number_format($billing->total_amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                        @if($billing->status !== 'paid')
                            <a href="{{ route('penghuni.ipl') }}" wire:navigate
                                class="text-xs px-2.5 py-1 rounded-lg font-medium transition-colors"
                                style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">
                                Bayar
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm" style="color:#98a2b3;">Belum ada tagihan IPL.</p>
                </div>
            @endforelse
        </div>

        {{-- Program Aktif --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #f5f6f8;">
                <h3 class="text-sm font-semibold" style="color:#111827;">Program Aktif</h3>
                <a href="{{ route('penghuni.program') }}" wire:navigate class="text-xs hover:underline" style="color:#667085;">Lihat semua</a>
            </div>
            @forelse($campaigns as $campaign)
                @php
                    $raised   = $campaign->donations->sum(fn($d) => optional($d->transaction)->amount ?? 0);
                    $progress = $campaign->target_amount > 0 ? min(100, ($raised / $campaign->target_amount) * 100) : 0;
                @endphp
                <div class="px-5 py-4" style="border-bottom:1px solid #ffffff;">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color:#1d2939;">{{ $campaign->name }}</p>
                            <p class="text-xs mt-0.5" style="color:#98a2b3;">
                                Target: Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                            </p>
                            <div class="mt-2 h-1.5 rounded-full" style="background:#e4e7ec;">
                                <div class="h-1.5 rounded-full transition-all"
                                    style="width:{{ $progress }}%;background:linear-gradient(90deg,#111827,#111827);"></div>
                            </div>
                            <p class="text-xs mt-1" style="color:#7c8698;">{{ number_format($progress, 0) }}% terkumpul</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
                                class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
                                style="background:#111827;color:#ffffff;">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm" style="color:#98a2b3;">Belum ada program aktif.</p>
                </div>
            @endforelse
        </div>

    </div>

</div>
