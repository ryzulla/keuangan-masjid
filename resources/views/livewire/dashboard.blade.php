<div>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <p style="font-size:12.5px;color:rgba(244,239,226,0.78);">Selamat datang,</p>
                <h2 style="font-family:'Fraunces',Georgia,serif;font-weight:500;font-size:1.6rem;line-height:1.1;color:#F4EFE2;">{{ auth()->user()->name }}</h2>
                <p style="font-size:0.8rem;margin-top:2px;">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="text-right hidden sm:block">
                <div style="font-family:'Fraunces',Georgia,serif;font-weight:500;font-size:40px;line-height:1;letter-spacing:-.02em;color:#F4EFE2;font-variant-numeric:tabular-nums;">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
                <div style="font-size:0.72rem;color:rgba(244,239,226,0.6);margin-top:4px;">Sistem Manajemen Perumahan &amp; DKM</div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        @if(session()->has('error'))
            <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── QUICK ACTIONS ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('ipl.index') }}" wire:navigate
                class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md"
                style="background:#ffffff;border:1px solid #E0DFD4;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(18,128,92,0.1);">
                    <svg class="w-5 h-5" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                </div>
                <span class="text-xs font-semibold text-center" style="color:#17231E;">Kelola IPL</span>
            </a>
            <a href="{{ route('transactions.index') }}" wire:navigate
                class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md"
                style="background:#ffffff;border:1px solid #E0DFD4;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(22,74,64,0.1);">
                    <svg class="w-5 h-5" style="color:#164A40;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-center" style="color:#17231E;">Transaksi</span>
            </a>
            <a href="{{ route('campaigns.index') }}" wire:navigate
                class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md"
                style="background:#ffffff;border:1px solid #E0DFD4;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(169,116,26,0.1);">
                    <svg class="w-5 h-5" style="color:#A9741A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-center" style="color:#17231E;">Donasi</span>
            </a>
            <a href="{{ route('residents.index') }}" wire:navigate
                class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md"
                style="background:#ffffff;border:1px solid #E0DFD4;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(22,74,64,0.1);">
                    <svg class="w-5 h-5" style="color:#164A40;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs font-semibold text-center" style="color:#17231E;">Penghuni</span>
            </a>
        </div>

        {{-- ── 4 STAT CARDS ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative overflow-hidden rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="absolute top-0 right-0 w-16 h-16 -mt-3 -mr-3 rounded-full opacity-20" style="background:#164A40;filter:blur(20px);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:rgba(22,74,64,0.12);border:1px solid rgba(22,74,64,0.2);">
                            <svg class="w-4 h-4" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(22,74,64,0.12);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Perumahan</span>
                    </div>
                    <p class="text-xs font-medium" style="color:#909A8F;">Kas Perumahan</p>
                    <p class="text-xl font-bold mt-0.5" style="color:#17231E;">Rp {{ number_format($perumahanBalance, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">{{ $assignedBlocks }}/{{ $totalBlocks }} blok terisi</p>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="absolute top-0 right-0 w-16 h-16 -mt-3 -mr-3 rounded-full opacity-10" style="background:#164A40;filter:blur(20px);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:rgba(22,74,64,0.1);border:1px solid rgba(22,74,64,0.2);">
                            <svg class="w-4 h-4" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">DKM</span>
                    </div>
                    <p class="text-xs font-medium" style="color:#909A8F;">Kas DKM</p>
                    <p class="text-xl font-bold mt-0.5" style="color:#17231E;">Rp {{ number_format($dkmBalance, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">{{ $totalResidents }} penghuni aktif</p>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="absolute top-0 right-0 w-16 h-16 -mt-3 -mr-3 rounded-full opacity-10" style="background:#12805c;filter:blur(20px);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.2);">
                            <svg class="w-4 h-4" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">IPL</span>
                    </div>
                    <p class="text-xs font-medium" style="color:#909A8F;">IPL Terkumpul</p>
                    <p class="text-xl font-bold mt-0.5" style="color:#17231E;">Rp {{ number_format($iplSummary['total_terbayar'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">{{ $iplSummary['period'] ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="absolute top-0 right-0 w-16 h-16 -mt-3 -mr-3 rounded-full opacity-10" style="background:#B0402C;filter:blur(20px);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.2);">
                            <svg class="w-4 h-4" style="color:#B0402C;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">Tunggakan</span>
                    </div>
                    <p class="text-xs font-medium" style="color:#909A8F;">Tunggakan IPL</p>
                    <p class="text-xl font-bold mt-0.5" style="color:#17231E;">Rp {{ number_format($iplSummary['tunggakan'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#B0402C;">{{ ($iplSummary['jumlah_unpaid'] ?? 0) + ($iplSummary['jumlah_partial'] ?? 0) }} unit belum lunas</p>
                </div>
            </div>
        </div>

        {{-- ── YANG PERLU PERHATIAN ── --}}
        @php
            $hasAlerts = $pendingPayments > 0 || $expiringContracts->isNotEmpty();
        @endphp
        @if($hasAlerts)
        <div class="rounded-2xl overflow-hidden" style="border:1px solid rgba(176,64,44,0.25);background:rgba(176,64,44,0.04);">
            <div class="px-5 py-3 flex items-center gap-2" style="background:rgba(176,64,44,0.08);border-bottom:1px solid rgba(176,64,44,0.15);">
                <svg class="w-4 h-4 shrink-0" style="color:#B0402C;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <h3 class="text-sm font-semibold" style="color:#B0402C;">Yang Perlu Perhatian</h3>
            </div>
            <div class="divide-y" style="border-color:rgba(176,64,44,0.1);">
                @if($pendingPayments > 0)
                <a href="{{ route('payment-requests.index') }}" wire:navigate class="flex items-center justify-between px-5 py-3 hover:bg-white/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(169,116,26,0.12);">
                            <svg class="w-4 h-4" style="color:#A9741A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:#17231E;">{{ $pendingPayments }} pembayaran menunggu konfirmasi</p>
                            <p class="text-xs" style="color:#909A8F;">Klik untuk meninjau dan mengonfirmasi</p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 shrink-0" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif

                @foreach($expiringContracts->take(3) as $ec)
                <div class="flex items-center justify-between px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(176,64,44,0.12);">
                            <svg class="w-4 h-4" style="color:#B0402C;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium" style="color:#17231E;">Kontrak {{ $ec->houseBlock?->block_code ?? '—' }} berakhir {{ $ec->contract_end_date->diffInDays(now()) }} hari lagi</p>
                            <p class="text-xs" style="color:#909A8F;">{{ $ec->resident?->name ?? '—' }} · {{ $ec->contract_end_date->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── MIDDLE ROW: IPL Chart + Keuangan Bulan Ini ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- IPL Donut Chart --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-semibold text-sm" style="color:#17231E;">Status IPL</h3>
                        <p class="text-xs mt-0.5" style="color:#909A8F;">{{ $iplSummary['period'] ?? 'Belum ada periode' }}</p>
                    </div>
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg" style="background:rgba(22,74,64,0.1);border:1px solid rgba(22,74,64,0.2);">
                        <svg class="w-4 h-4" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    </div>
                </div>

                @if(!empty($iplSummary))
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="relative w-40 h-40 flex-shrink-0">
                        <canvas id="iplDonutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-bold" style="color:#17231E;">{{ $iplSummary['jumlah_unit'] ?? 0 }}</span>
                            <span class="text-xs" style="color:#909A8F;">Total Unit</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2.5 w-full">
                        <div class="flex items-center justify-between p-3 rounded-xl" style="background:rgba(18,128,92,0.08);border:1px solid rgba(18,128,92,0.15);">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full" style="background:#12805c;"></div>
                                <span class="text-sm" style="color:#586359;">Lunas</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold" style="color:#12805c;">{{ $iplSummary['jumlah_lunas'] ?? 0 }}</div>
                                <div class="text-xs" style="color:#909A8F;">unit</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl" style="background:rgba(169,116,26,0.08);border:1px solid rgba(169,116,26,0.15);">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full" style="background:#A9741A;"></div>
                                <span class="text-sm" style="color:#586359;">Kurang Bayar</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold" style="color:#A9741A;">{{ $iplSummary['jumlah_partial'] ?? 0 }}</div>
                                <div class="text-xs" style="color:#909A8F;">unit</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl" style="background:rgba(176,64,44,0.08);border:1px solid rgba(176,64,44,0.15);">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full" style="background:#B0402C;"></div>
                                <span class="text-sm" style="color:#586359;">Belum Bayar</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold" style="color:#B0402C;">{{ $iplSummary['jumlah_unpaid'] ?? 0 }}</div>
                                <div class="text-xs" style="color:#909A8F;">unit</div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="flex justify-between text-xs mb-1.5">
                                <span style="color:#909A8F;">Progres Pembayaran</span>
                                @php
                                    $pct = $iplSummary['jumlah_unit'] > 0 ? round(($iplSummary['jumlah_lunas'] / $iplSummary['jumlah_unit']) * 100) : 0;
                                @endphp
                                <span class="font-medium" style="color:#12805c;">{{ $pct }}%</span>
                            </div>
                            <div class="w-full rounded-full h-1.5" style="background:#E0DFD4;">
                                <div class="h-1.5 rounded-full transition-all" style="width:{{ $pct }}%;background:linear-gradient(to right,#12805c,#12805c);"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 grid grid-cols-2 gap-3" style="border-top:1px solid #E0DFD4;">
                    <div class="text-center">
                        <p class="text-xs" style="color:#909A8F;">Total Tagihan</p>
                        <p class="font-semibold text-sm" style="color:#17231E;">Rp {{ number_format($iplSummary['total_tagihan'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs" style="color:#909A8F;">Tunggakan</p>
                        <p class="font-semibold text-sm" style="color:#B0402C;">Rp {{ number_format($iplSummary['tunggakan'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                @else
                <div class="text-center py-10" style="color:#909A8F;">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm">Belum ada periode IPL</p>
                    <a href="{{ route('ipl.index') }}" wire:navigate class="text-xs hover:underline mt-1 inline-block" style="color:#17231E;">Buat periode →</a>
                </div>
                @endif
            </div>

            {{-- Keuangan Bulan Ini --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-semibold text-sm" style="color:#17231E;">Keuangan Bulan Ini</h3>
                        <p class="text-xs mt-0.5" style="color:#909A8F;">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y') }}</p>
                    </div>
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg" style="background:rgba(22,74,64,0.1);border:1px solid rgba(22,74,64,0.2);">
                        <svg class="w-4 h-4" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>

                <div class="space-y-3">
                    {{-- Perumahan --}}
                    <div class="rounded-xl p-4" style="background:rgba(22,74,64,0.04);border:1px solid rgba(22,74,64,0.12);">
                        <p class="text-[10px] font-semibold uppercase tracking-wider mb-2" style="color:#909A8F;">Perumahan</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <div class="w-2 h-2 rounded-full" style="background:#12805c;"></div>
                                    <span class="text-xs" style="color:#909A8F;">Pemasukan</span>
                                </div>
                                <p class="text-sm font-bold" style="color:#12805c;">Rp {{ number_format($monthlyIncomePerumahan, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <div class="w-2 h-2 rounded-full" style="background:#B0402C;"></div>
                                    <span class="text-xs" style="color:#909A8F;">Pengeluaran</span>
                                </div>
                                <p class="text-sm font-bold" style="color:#B0402C;">Rp {{ number_format($monthlyExpensePerumahan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @php $netP = $monthlyIncomePerumahan - $monthlyExpensePerumahan; @endphp
                        <div class="mt-2 pt-2 flex justify-between items-center" style="border-top:1px solid rgba(22,74,64,0.1);">
                            <span class="text-xs" style="color:#909A8F;">Selisih</span>
                            <span class="text-xs font-bold" style="color:{{ $netP >= 0 ? '#17231E' : '#B0402C' }};">{{ $netP >= 0 ? '+' : '' }}Rp {{ number_format($netP, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- DKM --}}
                    <div class="rounded-xl p-4" style="background:rgba(22,74,64,0.04);border:1px solid rgba(22,74,64,0.12);">
                        <p class="text-[10px] font-semibold uppercase tracking-wider mb-2" style="color:#909A8F;">DKM</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <div class="w-2 h-2 rounded-full" style="background:#12805c;"></div>
                                    <span class="text-xs" style="color:#909A8F;">Pemasukan</span>
                                </div>
                                <p class="text-sm font-bold" style="color:#12805c;">Rp {{ number_format($monthlyIncomeDkm, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <div class="w-2 h-2 rounded-full" style="background:#B0402C;"></div>
                                    <span class="text-xs" style="color:#909A8F;">Pengeluaran</span>
                                </div>
                                <p class="text-sm font-bold" style="color:#B0402C;">Rp {{ number_format($monthlyExpenseDkm, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @php $netD = $monthlyIncomeDkm - $monthlyExpenseDkm; @endphp
                        <div class="mt-2 pt-2 flex justify-between items-center" style="border-top:1px solid rgba(22,74,64,0.1);">
                            <span class="text-xs" style="color:#909A8F;">Selisih</span>
                            <span class="text-xs font-bold" style="color:{{ $netD >= 0 ? '#17231E' : '#B0402C' }};">{{ $netD >= 0 ? '+' : '' }}Rp {{ number_format($netD, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── ACTIVE CAMPAIGNS ── --}}
        @if($campaigns->count() > 0)
        <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-sm" style="color:#17231E;">Program &amp; Kampanye Aktif</h3>
                <a href="{{ route('campaigns.index') }}" wire:navigate class="text-xs font-medium hover:underline" style="color:#17231E;">Lihat semua →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($campaigns as $c)
                @php
                    $collected = (float)($c->transactions_sum_amount ?? 0);
                    $target = (float)($c->target_amount ?? 0);
                    $pct = ($target > 0) ? min(100, round(($collected / $target) * 100)) : 0;
                    $isDkm = $c->organization_type === 'dkm';
                @endphp
                <div class="rounded-xl p-4 transition-all" style="background:#ffffff;border:1px solid #E0DFD4;">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                            style="{{ $isDkm ? 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);' : 'background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);' }}">
                            {{ $isDkm ? 'DKM' : 'RT' }}
                        </span>
                        <span class="text-xs font-bold" style="{{ $isDkm ? 'color:#12805c;' : 'color:#17231E;' }}">{{ $pct }}%</span>
                    </div>
                    <h4 class="font-semibold text-sm leading-tight mb-3 line-clamp-2" style="color:#17231E;">{{ $c->name }}</h4>
                    <div class="w-full rounded-full h-1 mb-2" style="background:#E0DFD4;">
                        <div class="h-1 rounded-full transition-all" style="width:{{ $pct }}%;background:{{ $isDkm ? '#12805c' : '#164A40' }};"></div>
                    </div>
                    <div class="flex justify-between text-xs" style="color:#909A8F;">
                        <span>Rp {{ number_format($collected, 0, ',', '.') }}</span>
                        @if($target > 0)
                            <span>Rp {{ number_format($target, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── RUMAH DISEWAKAN ── --}}
        @if($contractedHouses->isNotEmpty())
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="px-6 py-4 flex items-center justify-between" style="border-bottom:1px solid #F1F3EC;">
                <div>
                    <h3 class="font-semibold text-sm" style="color:#17231E;">Penyewaan Aktif</h3>
                    <p class="text-xs mt-0.5" style="color:#909A8F;">{{ $contractedHouses->count() }} unit sedang disewa</p>
                </div>
                <a href="{{ route('residents.index') }}" wire:navigate class="text-xs font-medium hover:underline" style="color:#17231E;">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom:1px solid #F1F3EC;">
                            <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Blok</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Penyewa</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Pemilik</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Sewa/Bulan</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Kontrak Berakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contractedHouses as $ch)
                        @php
                            $endingSoon = $ch->contract_end_date && $ch->contract_end_date->diffInDays(now()) <= 30;
                        @endphp
                        <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium"
                                    style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                    {{ $ch->houseBlock?->block_code ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                        style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">
                                        {{ strtoupper(substr($ch->resident->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-medium" style="color:#17231E;">{{ $ch->resident->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs" style="color:#586359;">
                                @php $owner = $ch->houseBlock?->owners?->first(); @endphp
                                {{ $owner->name ?? '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-xs font-medium" style="color:#17231E;">
                                @if($ch->monthly_rent)
                                    Rp {{ number_format($ch->monthly_rent, 0, ',', '.') }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-xs" style="color:{{ $endingSoon ? '#A9741A' : '#586359' }};">
                                @if($ch->contract_end_date)
                                    {{ $ch->contract_end_date->format('d M Y') }}
                                    @if($endingSoon)
                                        <span class="ml-1 font-semibold">⚠</span>
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
            <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                @foreach($contractedHouses as $ch)
                @php $endingSoon = $ch->contract_end_date && $ch->contract_end_date->diffInDays(now()) <= 30; @endphp
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium"
                                style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                {{ $ch->houseBlock?->block_code ?? '—' }}
                            </span>
                            <span class="text-xs" style="color:#586359;">{{ $ch->resident->name }}</span>
                        </div>
                        <span class="text-xs" style="color:{{ $endingSoon ? '#A9741A' : '#909A8F' }};">
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

        </div>
    </div>

    @php
        $iplChartData = [
            intval($iplSummary['jumlah_lunas'] ?? 0),
            intval($iplSummary['jumlah_partial'] ?? 0),
            intval($iplSummary['jumlah_unpaid'] ?? 0),
        ];
    @endphp

    @script
    <script>
        const iplChartData = @json($iplChartData);

        function initCharts() {
            const donutEl = document.getElementById('iplDonutChart');
            if (donutEl) {
                if (donutEl._chartInstance) { donutEl._chartInstance.destroy(); }
                donutEl._chartInstance = new Chart(donutEl, {
                    type: 'doughnut',
                    data: {
                        labels: ['Lunas', 'Kurang Bayar', 'Belum Bayar'],
                        datasets: [{
                            data: iplChartData,
                            backgroundColor: ['#12805c', '#A9741A', '#B0402C'],
                            borderWidth: 0,
                            hoverOffset: 4,
                        }]
                    },
                    options: {
                        cutout: '72%', responsive: true, maintainAspectRatio: true,
                        plugins: { legend: { display: false }, tooltip: {
                            backgroundColor: '#ffffff', borderColor: '#E0DFD4', borderWidth: 1,
                            titleColor: '#164A40', bodyColor: '#586359',
                            callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.parsed} unit` }
                        }}
                    }
                });
            }
        }

        initCharts();
    </script>
    @endscript
</div>
