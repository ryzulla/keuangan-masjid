<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#111827;">Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        @if(session()->has('error'))
            <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ── WELCOME BANNER ── --}}
        <div class="relative overflow-hidden rounded-2xl p-6 text-white"
            style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(16,24,40,0.35);box-shadow:0 4px 24px rgba(0,0,0,0.1);">
            <div class="absolute inset-0 opacity-5"
                style="background-image:url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23D4AF37' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
            {{-- Gold line accent --}}
            <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-2xl" style="background:linear-gradient(to bottom,#111827,#111827);"></div>
            <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pl-4">
                <div>
                    <p class="text-xs font-medium" style="color:#111827;">Selamat datang,</p>
                    <h1 class="text-2xl font-bold mt-0.5" style="color:#111827;font-family:'IBM Plex Sans',serif;">{{ auth()->user()->name }}</h1>
                    <p class="text-sm mt-1" style="color:#7c8698;">
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </p>
                </div>
                <div class="text-right hidden sm:block">
                    <div class="text-4xl font-light" style="color:#111827;opacity:0.8;">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
                    <div class="text-xs mt-1" style="color:#98a2b3;">Sistem Manajemen Perumahan &amp; DKM</div>
                </div>
            </div>
        </div>

        {{-- ── 4 STAT CARDS ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Kas Perumahan --}}
            <div class="relative overflow-hidden rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="absolute top-0 right-0 w-16 h-16 -mt-3 -mr-3 rounded-full opacity-20" style="background:#111827;filter:blur(20px);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:rgba(16,24,40,0.12);border:1px solid rgba(16,24,40,0.2);">
                            <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(16,24,40,0.12);color:#111827;border:1px solid rgba(16,24,40,0.2);">Perumahan</span>
                    </div>
                    <p class="text-xs font-medium" style="color:#7c8698;">Total Kas Perumahan</p>
                    <p class="text-xl font-bold mt-0.5" style="color:#1d2939;">Rp {{ number_format($perumahanBalance ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#98a2b3;">{{ $totalAssignedBlocks ?? 0 }} unit terisi</p>
                </div>
            </div>

            {{-- IPL Terkumpul --}}
            <div class="relative overflow-hidden rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="absolute top-0 right-0 w-16 h-16 -mt-3 -mr-3 rounded-full opacity-10" style="background:#12805c;filter:blur(20px);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.2);">
                            <svg class="w-4 h-4" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">IPL</span>
                    </div>
                    <p class="text-xs font-medium" style="color:#7c8698;">IPL Terkumpul</p>
                    <p class="text-xl font-bold mt-0.5" style="color:#1d2939;">Rp {{ number_format($iplSummary['total_terbayar'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#98a2b3;">{{ $iplSummary['period'] ?? 'N/A' }}</p>
                </div>
            </div>

            {{-- Tunggakan IPL --}}
            <div class="relative overflow-hidden rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="absolute top-0 right-0 w-16 h-16 -mt-3 -mr-3 rounded-full opacity-10" style="background:#c0453b;filter:blur(20px);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.2);">
                            <svg class="w-4 h-4" style="color:#c0453b;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">Tunggakan</span>
                    </div>
                    <p class="text-xs font-medium" style="color:#7c8698;">Tunggakan IPL</p>
                    <p class="text-xl font-bold mt-0.5" style="color:#1d2939;">Rp {{ number_format($iplSummary['tunggakan'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#c0453b;">{{ ($iplSummary['jumlah_unpaid'] ?? 0) + ($iplSummary['jumlah_partial'] ?? 0) }} unit belum lunas</p>
                </div>
            </div>

            {{-- Kas DKM --}}
            <div class="relative overflow-hidden rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="absolute top-0 right-0 w-16 h-16 -mt-3 -mr-3 rounded-full opacity-10" style="background:#111827;filter:blur(20px);"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 flex items-center justify-center rounded-xl" style="background:rgba(16,24,40,0.1);border:1px solid rgba(16,24,40,0.2);">
                            <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">DKM</span>
                    </div>
                    <p class="text-xs font-medium" style="color:#7c8698;">Total Kas DKM</p>
                    <p class="text-xl font-bold mt-0.5" style="color:#1d2939;">Rp {{ number_format($dkmBalance ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#98a2b3;">{{ $totalResidents ?? 0 }} penghuni terdaftar</p>
                </div>
            </div>

        </div>{{-- end stat cards --}}

        {{-- ── MIDDLE ROW: IPL Chart + DKM Finance ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- IPL Donut Chart --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-semibold text-sm" style="color:#1d2939;">Status IPL</h3>
                        <p class="text-xs mt-0.5" style="color:#98a2b3;">{{ $iplSummary['period'] ?? 'Belum ada periode' }}</p>
                    </div>
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg" style="background:rgba(16,24,40,0.1);border:1px solid rgba(16,24,40,0.2);">
                        <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    </div>
                </div>

                @if(!empty($iplSummary))
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <div class="relative w-40 h-40 flex-shrink-0">
                        <canvas id="iplDonutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-bold" style="color:#1d2939;">{{ $iplSummary['jumlah_unit'] ?? 0 }}</span>
                            <span class="text-xs" style="color:#98a2b3;">Total Unit</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2.5 w-full">
                        <div class="flex items-center justify-between p-3 rounded-xl" style="background:rgba(18,128,92,0.08);border:1px solid rgba(18,128,92,0.15);">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full" style="background:#12805c;"></div>
                                <span class="text-sm" style="color:#475467;">Lunas</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold" style="color:#12805c;">{{ $iplSummary['jumlah_lunas'] ?? 0 }}</div>
                                <div class="text-xs" style="color:#98a2b3;">unit</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl" style="background:rgba(199,125,26,0.08);border:1px solid rgba(199,125,26,0.15);">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full" style="background:#c77d1a;"></div>
                                <span class="text-sm" style="color:#475467;">Kurang Bayar</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold" style="color:#c77d1a;">{{ $iplSummary['jumlah_partial'] ?? 0 }}</div>
                                <div class="text-xs" style="color:#98a2b3;">unit</div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-xl" style="background:rgba(192,69,59,0.08);border:1px solid rgba(192,69,59,0.15);">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full" style="background:#c0453b;"></div>
                                <span class="text-sm" style="color:#475467;">Belum Bayar</span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold" style="color:#c0453b;">{{ $iplSummary['jumlah_unpaid'] ?? 0 }}</div>
                                <div class="text-xs" style="color:#98a2b3;">unit</div>
                            </div>
                        </div>
                        <div class="mt-1">
                            <div class="flex justify-between text-xs mb-1.5">
                                <span style="color:#98a2b3;">Progres Pembayaran</span>
                                @php
                                    $pct = $iplSummary['jumlah_unit'] > 0 ? round(($iplSummary['jumlah_lunas'] / $iplSummary['jumlah_unit']) * 100) : 0;
                                @endphp
                                <span class="font-medium" style="color:#12805c;">{{ $pct }}%</span>
                            </div>
                            <div class="w-full rounded-full h-1.5" style="background:#e4e7ec;">
                                <div class="h-1.5 rounded-full transition-all" style="width:{{ $pct }}%;background:linear-gradient(to right,#12805c,#12805c);"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 grid grid-cols-2 gap-3" style="border-top:1px solid #e4e7ec;">
                    <div class="text-center">
                        <p class="text-xs" style="color:#98a2b3;">Total Tagihan</p>
                        <p class="font-semibold text-sm" style="color:#1d2939;">Rp {{ number_format($iplSummary['total_tagihan'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs" style="color:#98a2b3;">Tunggakan</p>
                        <p class="font-semibold text-sm" style="color:#c0453b;">Rp {{ number_format($iplSummary['tunggakan'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
                @else
                <div class="text-center py-10" style="color:#98a2b3;">
                    <svg class="w-10 h-10 mx-auto mb-2 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm">Belum ada periode IPL</p>
                    <a href="{{ route('ipl.index') }}" wire:navigate class="text-xs hover:underline mt-1 inline-block" style="color:#111827;">Buat periode →</a>
                </div>
                @endif
            </div>

            {{-- DKM Finance --}}
            <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h3 class="font-semibold text-sm" style="color:#1d2939;">Keuangan DKM</h3>
                        <p class="text-xs mt-0.5" style="color:#98a2b3;">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y') }}</p>
                    </div>
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg" style="background:rgba(16,24,40,0.1);border:1px solid rgba(16,24,40,0.2);">
                        <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                </div>
                <div class="relative h-36">
                    <canvas id="dkmBarChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-xl p-3" style="background:rgba(18,128,92,0.08);border:1px solid rgba(18,128,92,0.15);">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-2 h-2 rounded-full" style="background:#12805c;"></div>
                            <span class="text-xs" style="color:#7c8698;">Pemasukan</span>
                        </div>
                        <p class="font-bold text-base" style="color:#12805c;">Rp {{ number_format($monthlyIncomeDkm ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs" style="color:#98a2b3;">bulan ini</p>
                    </div>
                    <div class="rounded-xl p-3" style="background:rgba(192,69,59,0.08);border:1px solid rgba(192,69,59,0.15);">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-2 h-2 rounded-full" style="background:#c0453b;"></div>
                            <span class="text-xs" style="color:#7c8698;">Pengeluaran</span>
                        </div>
                        <p class="font-bold text-base" style="color:#c0453b;">Rp {{ number_format($monthlyExpenseDkm ?? 0, 0, ',', '.') }}</p>
                        <p class="text-xs" style="color:#98a2b3;">bulan ini</p>
                    </div>
                </div>
                <div class="mt-3 p-3 rounded-xl" style="background:rgba(16,24,40,0.06);border:1px solid rgba(16,24,40,0.15);">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-medium" style="color:#111827;">Selisih Bersih</span>
                        @php $netDkm = ($monthlyIncomeDkm ?? 0) - ($monthlyExpenseDkm ?? 0); @endphp
                        <span class="font-bold {{ $netDkm >= 0 ? '' : '' }}" style="{{ $netDkm >= 0 ? 'color:#111827;' : 'color:#c0453b;' }}">
                            {{ $netDkm >= 0 ? '+' : '' }}Rp {{ number_format($netDkm, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── ACTIVE CAMPAIGNS ── --}}
        @php
            $allCampaigns = collect($activeCampaignsDkm)->merge(collect($activeCampaignsPerumahan));
        @endphp
        @if($allCampaigns->count() > 0)
        <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-sm flex items-center gap-2" style="color:#1d2939;">
                    <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Program &amp; Kampanye Aktif
                </h3>
                <a href="{{ route('campaigns.index') }}" wire:navigate class="text-xs font-medium hover:underline" style="color:#111827;">Lihat semua →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($allCampaigns as $c)
                @php
                    $collected = (float)($c->transactions_sum_amount ?? 0);
                    $target = (float)($c->target_amount ?? 0);
                    $pct = ($target > 0) ? min(100, round(($collected / $target) * 100)) : 0;
                    $isDkm = $c->organization_type === 'dkm';
                @endphp
                <div class="rounded-xl p-4 transition-all hover:border-opacity-50" style="background:#ffffff;border:1px solid #e4e7ec;">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full"
                            style="{{ $isDkm ? 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);' : 'background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);' }}">
                            {{ $isDkm ? 'DKM' : 'RT' }}
                        </span>
                        <span class="text-xs font-bold" style="{{ $isDkm ? 'color:#12805c;' : 'color:#111827;' }}">{{ $pct }}%</span>
                    </div>
                    <h4 class="font-semibold text-sm leading-tight mb-3 line-clamp-2" style="color:#1d2939;">{{ $c->name }}</h4>
                    <div class="w-full rounded-full h-1 mb-2" style="background:#e4e7ec;">
                        <div class="h-1 rounded-full transition-all" style="width:{{ $pct }}%;background:{{ $isDkm ? '#12805c' : '#111827' }};"></div>
                    </div>
                    <div class="flex justify-between text-xs" style="color:#98a2b3;">
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

        </div>
    </div>

    @php
        $iplChartData = [
            intval($iplSummary['jumlah_lunas'] ?? 0),
            intval($iplSummary['jumlah_partial'] ?? 0),
            intval($iplSummary['jumlah_unpaid'] ?? 0),
        ];
        $dkmBarData = [floatval($monthlyIncomeDkm ?? 0), floatval($monthlyExpenseDkm ?? 0)];
    @endphp

    @script
    <script>
        const iplChartData = @json($iplChartData);
        const dkmBarData   = @json($dkmBarData);

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
                            backgroundColor: ['#12805c', '#c77d1a', '#c0453b'],
                            borderWidth: 0,
                            hoverOffset: 4,
                        }]
                    },
                    options: {
                        cutout: '72%', responsive: true, maintainAspectRatio: true,
                        plugins: { legend: { display: false }, tooltip: {
                            backgroundColor: '#ffffff', borderColor: '#e4e7ec', borderWidth: 1,
                            titleColor: '#111827', bodyColor: '#475467',
                            callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.parsed} unit` }
                        }}
                    }
                });
            }
            const barEl = document.getElementById('dkmBarChart');
            if (barEl) {
                if (barEl._chartInstance) { barEl._chartInstance.destroy(); }
                barEl._chartInstance = new Chart(barEl, {
                    type: 'bar',
                    data: {
                        labels: ['Pemasukan', 'Pengeluaran'],
                        datasets: [{
                            data: dkmBarData,
                            backgroundColor: ['rgba(18,128,92,0.15)', 'rgba(192,69,59,0.15)'],
                            borderColor: ['#12805c', '#c0453b'],
                            borderWidth: 2, borderRadius: 8, borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                        plugins: { legend: { display: false }, tooltip: {
                            backgroundColor: '#ffffff', borderColor: '#e4e7ec', borderWidth: 1,
                            titleColor: '#111827', bodyColor: '#475467',
                            callbacks: { label: (ctx) => ' Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.x) }
                        }},
                        scales: {
                            x: { grid: { color: '#eef0f3' }, ticks: { color: '#98a2b3', callback: (v) => 'Rp ' + new Intl.NumberFormat('id-ID', {notation:'compact'}).format(v), font: { size: 10 } } },
                            y: { grid: { display: false }, ticks: { color: '#667085', font: { size: 12 } } }
                        }
                    }
                });
            }
        }

        initCharts();
    </script>
    @endscript
</div>
