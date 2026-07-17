<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Welcome Header --}}
    <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
        <div class="flex items-center gap-4">
            @if($resident->photo)
                <img src="{{ Storage::disk('public')->url($resident->photo) }}"
                    alt="{{ $resident->name }}"
                    class="w-16 h-16 rounded-2xl object-cover shrink-0"
                    style="border:2px solid rgba(21,99,223,0.5);">
            @else
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl font-bold shrink-0"
                    style="background:rgba(21,99,223,0.15);color:#161e2d;border:1px solid rgba(21,99,223,0.3);">
                    {{ strtoupper(substr($resident->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <p class="text-xs mb-1" style="color:#161e2d;">Selamat datang,</p>
                <h2 class="text-xl font-bold" style="color:#161e2d;font-family:'Manrope',serif;">{{ $resident->name }}</h2>
                <div class="flex flex-wrap items-center gap-3 mt-2">
                    @forelse($resident->currentAssignments as $assignment)
                        <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-lg font-medium"
                            style="background:rgba(21,99,223,0.12);color:#161e2d;border:1px solid rgba(21,99,223,0.25);">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            {{ $assignment->houseBlock?->block_code ?? '-' }}
                            <span style="color:#161e2d;">{{ ucfirst($assignment->ownership_type) }}</span>
                        </span>
                    @empty
                        <span class="text-xs" style="color:#a3abb0;">Belum ada blok ditetapkan</span>
                    @endforelse
                    <span class="text-xs" style="color:#a3abb0;">
                        {{ $resident->familyMembers->count() }} anggota keluarga
                    </span>
                </div>
            </div>
        </div>
    </div>

    @php $monthly = $this->monthlySummary; @endphp

    {{-- Keuangan Transparan --}}
    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold" style="color:#161e2d;">Info Keuangan</h3>
            <div class="flex items-center gap-1.5">
                <button wire:click="$set('activeOrg', 'semua')" class="text-xs px-2.5 py-1 rounded-lg font-medium transition-colors"
                    style="{{ $activeOrg === 'semua' ? 'background:#1563df;color:#ffffff;' : 'background:rgba(21,99,223,0.08);color:#5c6368;border:1px solid rgba(21,99,223,0.15);' }}">Semua</button>
                <button wire:click="$set('activeOrg', 'perumahan')" class="text-xs px-2.5 py-1 rounded-lg font-medium transition-colors"
                    style="{{ $activeOrg === 'perumahan' ? 'background:#1563df;color:#ffffff;' : 'background:rgba(21,99,223,0.08);color:#5c6368;border:1px solid rgba(21,99,223,0.15);' }}">Perumahan</button>
                <button wire:click="$set('activeOrg', 'dkm')" class="text-xs px-2.5 py-1 rounded-lg font-medium transition-colors"
                    style="{{ $activeOrg === 'dkm' ? 'background:#1563df;color:#ffffff;' : 'background:rgba(21,99,223,0.08);color:#5c6368;border:1px solid rgba(21,99,223,0.15);' }}">DKM</button>
                <a href="{{ route('penghuni.keuangan') }}" wire:navigate class="text-xs ml-2 hover:underline" style="color:#5c6368;">Detail</a>
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
                        <div class="rounded-lg p-2.5" style="background:rgba(21,99,223,0.04);border:1px solid rgba(21,99,223,0.12);">
                            <p class="text-[10px]" style="color:#a3abb0;">{{ ucfirst($account->organization_type) }}</p>
                            <p class="text-xs font-semibold" style="color:#161e2d;">{{ $account->name }}</p>
                            <p class="text-sm font-bold" style="color:#12805c;">
                                Rp {{ number_format($account->balance, 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs">
                    <span style="color:#161e2d;">
                        <span class="inline-block w-2 h-2 rounded-full mr-1" style="background:#12805c;"></span>
                        Pemasukan: <strong>Rp {{ number_format($monthly['totalIncome'], 0, ',', '.') }}</strong>
                    </span>
                    <span style="color:#161e2d;">
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
        <div class="rounded-2xl p-4 sm:p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <p class="text-xs mb-2" style="color:#a3abb0;">IPL Tunggakan</p>
            <p class="text-base sm:text-lg font-bold leading-tight" style="color:{{ $totalOutstanding > 0 ? '#c0453b' : '#12805c' }};">
                Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
            </p>
            @if($totalOutstanding > 0)
                <a href="{{ route('penghuni.ipl') }}" wire:navigate class="text-xs mt-1.5 inline-block hover:underline" style="color:#161e2d;">Bayar sekarang</a>
            @else
                <p class="text-xs mt-1.5" style="color:#12805c;">Lunas</p>
            @endif
        </div>

        <div class="rounded-2xl p-4 sm:p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <p class="text-xs mb-2" style="color:#a3abb0;">Anggota Keluarga</p>
            <p class="text-base sm:text-lg font-bold" style="color:#161e2d;">{{ $resident->familyMembers->count() }}</p>
            <a href="{{ route('penghuni.keluarga') }}" wire:navigate class="text-xs mt-1.5 inline-block hover:underline" style="color:#5c6368;">Kelola data</a>
        </div>

        <div class="rounded-2xl p-4 sm:p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <p class="text-xs mb-2" style="color:#a3abb0;">Menunggu Konfirmasi</p>
            <p class="text-base sm:text-lg font-bold" style="color:{{ $pendingRequests > 0 ? '#c77d1a' : '#5c6368' }};">{{ $pendingRequests }}</p>
            <p class="text-xs mt-1.5" style="color:#a3abb0;">pembayaran</p>
        </div>
    </div>

    {{-- Rumah Disewakan --}}
    @if($contractedHouses->isNotEmpty())
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #f7f7f7;">
            <h3 class="text-sm font-semibold" style="color:#161e2d;">Rumah Disewakan</h3>
            <a href="{{ route('penghuni.rumah-saya') }}" wire:navigate class="text-xs hover:underline" style="color:#5c6368;">Kelola</a>
        </div>
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid #f7f7f7;">
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Blok</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Penyewa</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Sewa/Bulan</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Kontrak Berakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contractedHouses as $ch)
                    @php
                        $endingSoon = $ch->contract_end_date && $ch->contract_end_date->diffInDays(now()) <= 30;
                    @endphp
                    <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#f7f7f7'" onmouseout="this.style.backgroundColor=''">
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium"
                                style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                                {{ $ch->houseBlock?->block_code ?? '—' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                    style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">
                                    {{ strtoupper(substr($ch->resident->name, 0, 1)) }}
                                </div>
                                <span class="text-xs font-medium" style="color:#161e2d;">{{ $ch->resident->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-medium" style="color:#161e2d;">
                            @if($ch->monthly_rent)
                                Rp {{ number_format($ch->monthly_rent, 0, ',', '.') }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs" style="color:{{ $endingSoon ? '#c77d1a' : '#5c6368' }};">
                            @if($ch->contract_end_date)
                                {{ $ch->contract_end_date->format('d M Y') }}
                                @if($endingSoon)
                                    <span class="ml-1 font-medium">(segera berakhir)</span>
                                @endif
                            @else
                                &mdash;
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Mobile --}}
        <div class="md:hidden divide-y" style="border-color:#f7f7f7;">
            @foreach($contractedHouses as $ch)
            @php $endingSoon = $ch->contract_end_date && $ch->contract_end_date->diffInDays(now()) <= 30; @endphp
            <div class="px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium"
                            style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                            {{ $ch->houseBlock?->block_code ?? '—' }}
                        </span>
                        <span class="text-xs" style="color:#5c6368;">{{ $ch->resident->name }}</span>
                    </div>
                    <span class="text-xs" style="color:{{ $endingSoon ? '#c77d1a' : '#a3abb0' }};">
                        @if($ch->contract_end_date)
                            {{ $ch->contract_end_date->format('d M Y') }}
                        @else
                            &mdash;
                        @endif
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Rumah Dijual / Disewakan --}}
    @if($listedHouses->isNotEmpty())
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #f7f7f7;">
            <h3 class="text-sm font-semibold" style="color:#161e2d;">Rumah Dijual / Disewakan</h3>
            <a href="{{ route('penghuni.rumah-saya') }}" wire:navigate class="text-xs hover:underline" style="color:#5c6368;">Kelola</a>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($listedHouses as $lh)
                @php
                    $photo = $lh->photos->firstWhere('is_primary') ?? $lh->photos->first();
                    $isForSale = ($lh->listing_type ?? 'sewa') === 'jual';
                @endphp
                <a href="{{ route('penghuni.detail-rumah', $lh->id) }}" wire:navigate
                    class="flex gap-3 p-3 rounded-xl transition-colors"
                    style="background:#f7f7f7;border:1px solid #e4e4e4;"
                    onmouseover="this.style.borderColor='rgba(21,99,223,0.3)'" onmouseout="this.style.borderColor='#e4e4e4'">
                    <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0" style="background:#e4e4e4;">
                        @if($photo)
                            <img src="{{ Storage::disk('public')->url($photo->photo_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-6 h-6" style="color:#d0d0d0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold" style="color:#161e2d;">Blok {{ $lh->block_code }}</span>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                style="background:{{ $isForSale ? 'rgba(21,99,223,0.1)' : 'rgba(199,125,26,0.1)' }};color:{{ $isForSale ? '#1563df' : '#c77d1a' }};">
                                {{ $isForSale ? 'Jual' : 'Sewa' }}
                            </span>
                        </div>
                        @if($lh->rental_price)
                            <p class="text-sm font-bold" style="color:#1563df;">
                                Rp {{ number_format($lh->rental_price, 0, ',', '.') }}
                                @if(!$isForSale)
                                    <span class="text-[10px] font-medium" style="color:#a3abb0;">/ {{ match($lh->rental_duration ?? 'bulanan') { '6bulan' => '6bln', 'tahunan' => 'thn', default => 'bln' } }}</span>
                                @endif
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- IPL Terbaru --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #f7f7f7;">
                <h3 class="text-sm font-semibold" style="color:#161e2d;">IPL Terbaru</h3>
                <a href="{{ route('penghuni.ipl') }}" wire:navigate class="text-xs hover:underline" style="color:#5c6368;">Lihat semua</a>
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
                        <p class="text-sm font-medium" style="color:#161e2d;">
                            {{ \Carbon\Carbon::create($billing->period->year, $billing->period->month)->translatedFormat('F Y') }}
                        </p>
                        <p class="text-xs mt-0.5" style="color:#a3abb0;">
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
                                style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                                Bayar
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm" style="color:#a3abb0;">Belum ada tagihan IPL.</p>
                </div>
            @endforelse
        </div>

        {{-- Program Aktif --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #f7f7f7;">
                <h3 class="text-sm font-semibold" style="color:#161e2d;">Program Aktif</h3>
                <a href="{{ route('penghuni.program') }}" wire:navigate class="text-xs hover:underline" style="color:#5c6368;">Lihat semua</a>
            </div>
            @forelse($campaigns as $campaign)
                @php
                    $raised   = $campaign->donations->sum(fn($d) => optional($d->transaction)->amount ?? 0);
                    $progress = $campaign->target_amount > 0 ? min(100, ($raised / $campaign->target_amount) * 100) : 0;
                @endphp
                <div class="px-5 py-4" style="border-bottom:1px solid #ffffff;">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color:#161e2d;">{{ $campaign->name }}</p>
                            <p class="text-xs mt-0.5" style="color:#a3abb0;">
                                Target: Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                            </p>
                            <div class="mt-2 h-1.5 rounded-full" style="background:#e4e4e4;">
                                <div class="h-1.5 rounded-full transition-all"
                                    style="width:{{ $progress }}%;background:linear-gradient(90deg,#1563df,#1563df);"></div>
                            </div>
                            <p class="text-xs mt-1" style="color:#a3abb0;">{{ number_format($progress, 0) }}% terkumpul</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
                                class="text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
                                style="background:#1563df;color:#ffffff;">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <p class="text-sm" style="color:#a3abb0;">Belum ada program aktif.</p>
                </div>
            @endforelse
        </div>

    </div>

</div>
