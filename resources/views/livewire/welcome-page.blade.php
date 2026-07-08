<div>
    {{-- Error alert --}}
    @if(session('page_error'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-6 px-4">
            <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ session('page_error') }}</span>
            </div>
        </div>
    @endif

    {{-- Hero Section --}}
    <div class="relative overflow-hidden" style="background:#ffffff;border-bottom:1px solid #e4e7ec;">
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 py-16 md:py-20">
            <div class="text-center">
                {{-- Logo icon --}}
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-6"
                    style="background:#111827;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="#ffffff" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4" style="color:#111827;font-family:'IBM Plex Sans',sans-serif;letter-spacing:-.02em;">Portal Informasi Perumahan</h1>
                <p class="text-lg max-w-2xl mx-auto" style="color:#667085;">Transparansi pengelolaan keuangan &amp; program perumahan dan DKM Masjid</p>
            </div>

            {{-- Quick Stats --}}
            <div class="grid grid-cols-3 gap-4 mt-12 max-w-2xl mx-auto">
                <div class="text-center rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04);">
                    <div class="text-2xl sm:text-3xl font-bold" style="color:#111827;font-family:'IBM Plex Sans',sans-serif;">{{ $totalBlocks }}</div>
                    <div class="text-xs mt-0.5" style="color:#667085;">Unit Rumah</div>
                </div>
                <div class="text-center rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04);">
                    <div class="text-2xl sm:text-3xl font-bold" style="color:#111827;font-family:'IBM Plex Sans',sans-serif;">{{ $occupiedBlocks }}</div>
                    <div class="text-xs mt-0.5" style="color:#667085;">Unit Dihuni</div>
                </div>
                <div class="text-center rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04);">
                    <div class="text-2xl sm:text-3xl font-bold" style="color:#111827;font-family:'IBM Plex Sans',sans-serif;">{{ $totalResidents }}</div>
                    <div class="text-xs mt-0.5" style="color:#667085;">Kepala Keluarga</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 py-10 space-y-10">

        {{-- IPL Status Section --}}
        @if($currentIplPeriod && !empty($iplSummary))
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-1 h-6 rounded-full" style="background:#111827;"></div>
                <h2 class="text-xl font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">Status IPL — {{ $currentIplPeriod->period_label }}</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#7c8698;">Unit Lunas</p>
                    <p class="text-2xl font-bold mt-1" style="color:#12805c;">{{ $iplSummary['lunas'] ?? 0 }}</p>
                    <div class="mt-2 h-1.5 rounded-full overflow-hidden" style="background:#e4e7ec;">
                        @php $pctLunas = ($iplSummary['total_unit'] ?? 0) > 0 ? min(100, round(($iplSummary['lunas'] / $iplSummary['total_unit']) * 100)) : 0; @endphp
                        <div class="h-full rounded-full" style="width:{{ $pctLunas }}%;background:#12805c;"></div>
                    </div>
                    <p class="text-xs mt-1" style="color:#98a2b3;">{{ $pctLunas }}% dari {{ $iplSummary['total_unit'] ?? 0 }} unit</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#7c8698;">Belum Bayar</p>
                    <p class="text-2xl font-bold mt-1" style="color:#c0453b;">{{ $iplSummary['belum'] ?? 0 }}</p>
                    <p class="text-xs mt-3" style="color:#98a2b3;">unit belum membayar</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#7c8698;">Dana Terkumpul</p>
                    <p class="text-lg font-bold mt-1" style="color:#111827;">Rp {{ number_format($iplSummary['terkumpul'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#98a2b3;">periode {{ $currentIplPeriod->period_label }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#7c8698;">Masih Tunggakan</p>
                    <p class="text-lg font-bold mt-1" style="color:#c77d1a;">Rp {{ number_format(max(0, $iplSummary['tunggakan'] ?? 0), 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#98a2b3;">perlu ditagih</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Programs / Campaigns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Perumahan Programs --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1 h-6 rounded-full" style="background:#111827;"></div>
                    <h2 class="text-xl font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">Program Perumahan</h2>
                </div>
                <div class="space-y-4">
                    @forelse($activeCampaignsPerumahan as $campaign)
                        <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);" wire:key="p-campaign-{{ $campaign->id }}">
                            <div class="flex justify-between items-start gap-3 mb-3">
                                <h3 class="font-semibold leading-tight" style="color:#1d2939;">{{ $campaign->name }}</h3>
                                <span class="text-xs font-medium px-2.5 py-1 rounded-lg whitespace-nowrap" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">Aktif</span>
                            </div>
                            @php
                                $target = (float)($campaign->target_amount ?? 0);
                                $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                $progress = $target > 0 ? min(100, round($raised / $target * 100)) : ($raised > 0 ? 100 : 0);
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs mb-1.5" style="color:#98a2b3;">
                                    <span>Terkumpul: <span style="color:#1d2939;font-weight:600;">Rp {{ number_format($raised, 0, ',', '.') }}</span></span>
                                    <span style="color:#111827;">{{ $progress }}%</span>
                                </div>
                                <div class="h-2 rounded-full overflow-hidden" style="background:#e4e7ec;">
                                    <div class="h-full rounded-full transition-all" style="width:{{ $progress }}%;background:linear-gradient(to right,#111827,#111827);"></div>
                                </div>
                                @if($target > 0)
                                    <div class="text-xs mt-1" style="color:#98a2b3;">Target: Rp {{ number_format($target, 0, ',', '.') }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl p-6 text-center" style="background:#ffffff;border:1px dashed #e4e7ec;">
                            <p class="text-sm" style="color:#98a2b3;">Tidak ada program perumahan aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- DKM Programs --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1 h-6 rounded-full" style="background:#12805c;"></div>
                    <h2 class="text-xl font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">Program DKM Masjid</h2>
                </div>
                <div class="space-y-4">
                    @forelse($activeCampaignsDkm as $campaign)
                        <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);" wire:key="d-campaign-{{ $campaign->id }}">
                            <div class="flex justify-between items-start gap-3 mb-3">
                                <h3 class="font-semibold leading-tight" style="color:#1d2939;">{{ $campaign->name }}</h3>
                                <span class="text-xs font-medium px-2.5 py-1 rounded-lg whitespace-nowrap" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Aktif</span>
                            </div>
                            @php
                                $target = (float)($campaign->target_amount ?? 0);
                                $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                $progress = $target > 0 ? min(100, round($raised / $target * 100)) : ($raised > 0 ? 100 : 0);
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs mb-1.5" style="color:#98a2b3;">
                                    <span>Terkumpul: <span style="color:#1d2939;font-weight:600;">Rp {{ number_format($raised, 0, ',', '.') }}</span></span>
                                    <span style="color:#12805c;">{{ $progress }}%</span>
                                </div>
                                <div class="h-2 rounded-full overflow-hidden" style="background:#e4e7ec;">
                                    <div class="h-full rounded-full transition-all" style="width:{{ $progress }}%;background:linear-gradient(to right,#12805c,#12805c);"></div>
                                </div>
                                @if($target > 0)
                                    <div class="text-xs mt-1" style="color:#98a2b3;">Target: Rp {{ number_format($target, 0, ',', '.') }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl p-6 text-center" style="background:#ffffff;border:1px dashed #e4e7ec;">
                            <p class="text-sm" style="color:#98a2b3;">Tidak ada program DKM aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="text-center py-6" style="border-top:1px solid #eef0f3;">
            <p class="text-sm" style="color:#98a2b3;">Data diperbarui secara real-time &bull; Sistem Informasi Perumahan &copy; {{ date('Y') }}</p>
        </div>

    </div>
</div>
