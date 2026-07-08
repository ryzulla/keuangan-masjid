<div>
    @if(session('success'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @php
        use Carbon\Carbon as CarbonAlias;

        $activeBillings  = $billings->whereNotIn('status', ['paid']);
        $totalOutstanding = $activeBillings->sum('outstanding');
        $countUnpaid     = $activeBillings->count();
    @endphp

    {{-- Print styles --}}
    @push('styles')
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: #fff !important; font-size: 11pt; color: #1d2939; }
            .print-header { text-align: center; margin-bottom: 20px; }
            .print-header h2 { font-size: 16pt; margin: 0; }
            .print-header p { font-size: 10pt; color: #667085; margin: 4px 0 0; }
        }
        .print-only { display: none; }
    </style>
    @endpush

    {{-- Header --}}
    <div class="rounded-2xl p-6 mb-5 no-print"
         style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(16,24,40,0.35);">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg" style="color:#111827;font-family:'IBM Plex Sans',serif;">IPL Saya</h3>
                <p class="text-sm mt-1" style="color:#111827;">Iuran Pemeliharaan Lingkungan</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()"
                    class="shrink-0 flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold transition-colors"
                    style="background:rgba(16,24,40,0.08);color:#111827;border:1px solid rgba(16,24,40,0.2);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak
                </button>
                <button wire:click="openChecklist"
                    class="shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
                    style="background:rgba(16,24,40,0.15);color:#111827;border:1px solid rgba(16,24,40,0.3);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Bayar IPL
                </button>
            </div>
        </div>

        {{-- Summary row --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-5">
            <div class="rounded-xl px-4 py-3" style="background:rgba(0,0,0,0.07);">
                <p class="text-xs mb-1" style="color:#7c8698;">Total Tagihan Aktif</p>
                <p class="text-base font-bold" style="color:{{ $totalOutstanding > 0 ? '#c0453b' : '#12805c' }};">
                    Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
                </p>
            </div>
            <div class="rounded-xl px-4 py-3" style="background:rgba(0,0,0,0.07);">
                <p class="text-xs mb-1" style="color:#7c8698;">Bulan Belum Lunas</p>
                <p class="text-base font-bold" style="color:{{ $countUnpaid > 0 ? '#c77d1a' : '#12805c' }};">
                    {{ $countUnpaid }} bulan
                </p>
            </div>
            <div class="rounded-xl px-4 py-3" style="background:rgba(0,0,0,0.07);">
                <p class="text-xs mb-1" style="color:#7c8698;">Menunggu Konfirmasi</p>
                <p class="text-base font-bold" style="color:{{ $pendingRequests->count() > 0 ? '#c77d1a' : '#98a2b3' }};">
                    {{ $pendingRequests->count() }} pengajuan
                </p>
            </div>
        </div>
    </div>

    {{-- ═══ Matrix Per-Blok Tahunan (seperti admin) ═══ --}}
    @if(!empty($blockMatrix))
    <div class="rounded-2xl mb-5 overflow-hidden no-print" style="background:#ffffff;border:1px solid #e4e7ec;">
        <div class="px-5 py-3 flex items-center justify-between" style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <h4 class="text-sm font-semibold" style="color:#111827;font-family:'IBM Plex Sans',serif;">Rekap Tahun {{ $selectedYear }}</h4>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded" style="background:rgba(18,128,92,0.15);border:1px solid rgba(18,128,92,0.3);display:inline-block;"></span> Lunas</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded" style="background:rgba(199,125,26,0.15);border:1px solid rgba(199,125,26,0.3);display:inline-block;"></span> Sebagian</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded" style="background:rgba(192,69,59,0.15);border:1px solid rgba(192,69,59,0.3);display:inline-block;"></span> Belum</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded" style="background:#eef0f3;border:1px solid #f5f6f8;display:inline-block;"></span> Nihil</span>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 px-5 py-4" style="border-bottom:1px solid #eef0f3;">
            <div class="rounded-xl px-4 py-3" style="background:rgba(18,128,92,0.05);border:1px solid rgba(18,128,92,0.2);">
                <p class="text-xs mb-1" style="color:#7c8698;">Total Unit / Blok</p>
                <p class="text-xl font-bold" style="color:#1d2939;">{{ count($blockMatrix) }}</p>
            </div>
            <div class="rounded-xl px-4 py-3" style="background:rgba(192,69,59,0.05);border:1px solid rgba(192,69,59,0.2);">
                <p class="text-xs mb-1" style="color:#7c8698;">Blok Ada Tunggakan</p>
                <p class="text-xl font-bold" style="color:#c0453b;">{{ $totalUnpaidBlocks }}</p>
            </div>
            <div class="rounded-xl px-4 py-3" style="background:rgba(192,69,59,0.05);border:1px solid rgba(192,69,59,0.2);">
                <p class="text-xs mb-1" style="color:#7c8698;">Total Tunggakan {{ $selectedYear }}</p>
                <p class="text-xl font-bold" style="color:#c0453b;">Rp {{ number_format($grandTotalOutstanding,0,',','.') }}</p>
            </div>
        </div>

        {{-- Matrix table (desktop) --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="border-collapse:collapse;min-width:700px;">
                <thead>
                    <tr style="background:#ffffff;border-bottom:2px solid #eef0f3;">
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#7c8698;min-width:80px;">Blok</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#7c8698;min-width:100px;">Penghuni</th>
                        @foreach($monthLabels as $ml)
                            <th class="text-center px-2 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#7c8698;min-width:38px;">{{ $ml }}</th>
                        @endforeach
                        <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#c0453b;min-width:100px;">Tunggakan</th>
                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#7c8698;min-width:120px;">Blm Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blockMatrix as $row)
                        @php $blk = $row['block']; @endphp
                        <tr style="border-bottom:1px solid #f5f6f8;">
                            <td class="px-4 py-3 font-semibold" style="color:#111827;">{{ $blk->block_code }}</td>
                            <td class="px-4 py-3 truncate max-w-[120px]" style="color:#475467;" title="{{ $resident->name }}">
                                {{ $resident->name }}
                            </td>
                            @foreach($monthLabels as $mn => $ml)
                                @php $cell = $row['months'][$mn] ?? ['status'=>'no_period']; @endphp
                                <td class="text-center px-2 py-3">
                                    @switch($cell['status'])
                                        @case('paid')
                                            <span class="inline-flex items-center justify-center w-7 h-6 rounded text-xs font-bold" style="background:rgba(18,128,92,0.15);border:1px solid rgba(18,128,92,0.3);color:#12805c;" title="Lunas">&check;</span>
                                            @break
                                        @case('partial')
                                            <span class="inline-flex items-center justify-center w-7 h-6 rounded text-xs font-bold" style="background:rgba(199,125,26,0.15);border:1px solid rgba(199,125,26,0.3);color:#c77d1a;" title="Sebagian — Sisa Rp {{ number_format($cell['outstanding']??0,0,',','.') }}">&half;</span>
                                            @break
                                        @case('unpaid')
                                            <span class="inline-flex items-center justify-center w-7 h-6 rounded text-xs font-bold" style="background:rgba(192,69,59,0.15);border:1px solid rgba(192,69,59,0.3);color:#c0453b;" title="Belum Bayar — Rp {{ number_format($cell['outstanding']??0,0,',','.') }}">&times;</span>
                                            @break
                                        @case('no_billing')
                                            <span class="inline-flex items-center justify-center w-7 h-6 rounded text-xs" style="background:#eef0f3;border:1px solid #f5f6f8;color:#98a2b3;" title="Tidak ada tagihan">&mdash;</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center justify-center w-7 h-6 rounded text-xs" style="background:#ffffff;color:#98a2b3;" title="Periode belum dibuat">&middot;</span>
                                    @endswitch
                                </td>
                            @endforeach
                            <td class="px-4 py-3 text-right font-bold" style="color:{{ $row['totalOutstanding'] > 0 ? '#c0453b' : '#12805c' }};">
                                {{ $row['totalOutstanding'] > 0 ? 'Rp '.number_format($row['totalOutstanding'],0,',','.') : 'Lunas' }}
                            </td>
                            <td class="px-4 py-3">
                                @if(!empty($row['unpaidMonths']))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($row['unpaidMonths'] as $um)
                                            <span class="text-xs px-1.5 py-0.5 rounded font-medium" style="background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">{{ $monthLabels[$um] }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs" style="color:#12805c;">&mdash;</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($grandTotalOutstanding > 0)
        <div class="px-5 py-3 text-xs flex items-center gap-2" style="background:#ffffff;border-top:1px solid #eef0f3;color:#c0453b;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/></svg>
            <span>{{ $totalUnpaidBlocks }} dari {{ count($blockMatrix) }} unit memiliki tunggakan. Total Tunggakan: <strong>Rp {{ number_format($grandTotalOutstanding,0,',','.') }}</strong></span>
        </div>
        @else
        <div class="px-5 py-3 text-xs flex items-center gap-2" style="background:#ffffff;border-top:1px solid #eef0f3;color:#12805c;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>Semua unit sudah lunas untuk tahun {{ $selectedYear }}.</span>
        </div>
        @endif
    </div>
    @endif

    {{-- ═══ Pantauan IPL unit kontrak (khusus pemilik) ═══ --}}
    @if($monitoredBillings->isNotEmpty())
    <div class="rounded-2xl mb-5 overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;">
        <div class="px-5 py-3 flex items-center gap-2" style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
            <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h4 class="text-sm font-semibold" style="color:#111827;font-family:'IBM Plex Sans',serif;">Status IPL Unit Kontrak</h4>
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full" style="background:rgba(16,24,40,0.1);color:#111827;">{{ $monitoredBillings->count() }} tagihan</span>
        </div>
        <div class="divide-y" style="border-color:#eef0f3;">
            @foreach($monitoredBillings as $mb)
            @php
                $statusColor = match($mb->status) {
                    'paid'    => ['#12805c', 'rgba(18,128,92,0.1)', 'rgba(18,128,92,0.25)', 'Lunas'],
                    'partial' => ['#c77d1a', 'rgba(199,125,26,0.1)', 'rgba(199,125,26,0.25)', 'Sebagian'],
                    default   => ['#c0453b', 'rgba(192,69,59,0.08)', 'rgba(192,69,59,0.2)', 'Belum bayar'],
                };
            @endphp
            <div class="px-5 py-3 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="text-xs px-2 py-0.5 rounded-lg font-medium shrink-0" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.25);">{{ $mb->houseBlock?->block_code }}</span>
                    <span class="text-sm truncate" style="color:#475467;">
                        {{ $mb->period ? CarbonAlias::create($mb->period->year, $mb->period->month)->translatedFormat('F Y') : '—' }}
                    </span>
                    @if($mb->responsibleResident)
                        <span class="text-xs hidden sm:inline" style="color:#98a2b3;">&middot; {{ $mb->responsibleResident->name }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <span class="text-sm font-semibold" style="color:#1d2939;">Rp {{ number_format($mb->total_amount,0,',','.') }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:{{ $statusColor[1] }};color:{{ $statusColor[0] }};border:1px solid {{ $statusColor[2] }};">{{ $statusColor[3] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tagihan Aktif (unpaid / partial only), dikelompokkan per blok --}}
    @if($activeBillings->isNotEmpty())
    @php
        $groupedBillings = $activeBillings->groupBy(fn($b) => $b->houseBlock?->block_code ?: 'Tanpa Blok')->sortKeys();
    @endphp
    <div class="mb-5">
        <p class="text-xs font-semibold uppercase tracking-wider mb-3 px-1" style="color:#98a2b3;">Tagihan Belum Lunas</p>
        @foreach($groupedBillings as $blockCode => $blockBillings)
        <div class="mb-4">
            @if($groupedBillings->count() > 1)
            <div class="flex items-center gap-2 px-1 mb-2">
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="background:rgba(16,24,40,0.12);color:#111827;border:1px solid rgba(16,24,40,0.25);">{{ $blockCode }}</span>
                <span class="text-xs" style="color:#98a2b3;">{{ $blockBillings->count() }} tagihan</span>
            </div>
            @endif
            <div class="space-y-3">
                @foreach($blockBillings as $billing)
                @php
                    $isPending = in_array($billing->id, $pendingBillingIds);
                    $isPartial = $billing->status === 'partial';
                @endphp
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid {{ $isPending ? 'rgba(199,125,26,0.3)' : ($isPartial ? 'rgba(199,125,26,0.2)' : 'rgba(192,69,59,0.2)') }};">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center flex-wrap gap-2 mb-1">
                                <span class="font-semibold" style="color:#1d2939;">
                                    IPL {{ $billing->period ? CarbonAlias::create($billing->period->year, $billing->period->month)->translatedFormat('F Y') : '—' }}
                                </span>
                                @if($groupedBillings->count() <= 1 && $billing->houseBlock)
                                    <span class="text-xs px-2 py-0.5 rounded-full" style="background:#f5f6f8;color:#7c8698;">{{ $billing->houseBlock->block_code }}</span>
                                @endif
                                @if($isPending)
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(199,125,26,0.12);color:#c77d1a;border:1px solid rgba(199,125,26,0.25);">Menunggu konfirmasi</span>
                                @elseif($isPartial)
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">Dibayar sebagian</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">Belum bayar</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 mt-2">
                                @if($isPartial)
                                <div>
                                    <p class="text-xs" style="color:#98a2b3;">Tagihan</p>
                                    <p class="text-sm" style="color:#667085;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs" style="color:#98a2b3;">Terbayar</p>
                                    <p class="text-sm" style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</p>
                                </div>
                                @endif
                                <div>
                                    <p class="text-xs" style="color:#98a2b3;">{{ $isPartial ? 'Sisa' : 'Jumlah' }}</p>
                                    <p class="text-lg font-bold" style="color:#c0453b;">Rp {{ number_format($billing->outstanding, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="shrink-0 flex items-center">
                            @if($isPending)
                                <div class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs" style="background:rgba(199,125,26,0.08);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">
                                    <svg class="w-3.5 h-3.5 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill-opacity=".15"/><path d="M12 6v6l4 2"/></svg>
                                    Menunggu konfirmasi
                                </div>
                            @else
                                <button wire:click="openPay({{ $billing->id }})"
                                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold"
                                    style="background:#111827;color:#ffffff;">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Konfirmasi Bayar
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @elseif($billings->isNotEmpty())
    {{-- All billings paid --}}
    <div class="rounded-2xl p-6 mb-5 flex items-center gap-4" style="background:#ffffff;border:1px solid rgba(18,128,92,0.2);">
        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" style="background:rgba(18,128,92,0.12);">
            <svg class="w-5 h-5" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <p class="font-semibold" style="color:#12805c;">Semua tagihan lunas</p>
            <p class="text-sm mt-0.5" style="color:#98a2b3;">Tidak ada tagihan IPL yang perlu dibayar saat ini.</p>
        </div>
    </div>
    @else
    {{-- No billings at all --}}
    <div class="rounded-2xl p-6 mb-5" style="background:#ffffff;border:1px solid #f5f6f8;">
        <div class="text-center py-4">
            <svg class="w-10 h-10 mx-auto mb-3" style="color:#98a2b3;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-sm font-medium" style="color:#98a2b3;">Belum ada tagihan IPL untuk akun Anda</p>
            <p class="text-xs mt-1 mb-4" style="color:#98a2b3;">Sudah bayar tapi belum tercatat? Gunakan fitur di bawah.</p>
            <button wire:click="openDirectPay"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold"
                style="background:rgba(16,24,40,0.15);color:#111827;border:1px solid rgba(16,24,40,0.3);">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Kirim Bukti Pembayaran
            </button>
        </div>
    </div>
    @endif

    {{-- ─── Riwayat Pembayaran ────────────────────────────────────── --}}
    <div class="rounded-2xl overflow-hidden mt-6" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
        <div class="px-5 py-3 flex items-center gap-2" style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
            <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            <h4 class="text-sm font-semibold" style="color:#111827;font-family:'IBM Plex Sans',serif;">Riwayat Pembayaran IPL</h4>
            @if($history->isNotEmpty())
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full" style="background:rgba(16,24,40,0.1);color:#111827;">{{ $history->count() }} transaksi</span>
            @endif
        </div>

        @forelse($history as $h)
        @php
            $isAdmin = $h['source'] === 'admin';
            $status  = $h['status'];
            [$sbg, $scolor, $sborder, $slabel] = match(true) {
                $isAdmin                => ['rgba(18,128,92,0.1)',  '#12805c', 'rgba(18,128,92,0.25)',  'Dikonfirmasi'],
                $status === 'pending'   => ['rgba(199,125,26,0.1)',  '#c77d1a', 'rgba(199,125,26,0.25)',  'Menunggu'],
                $status === 'confirmed' => ['rgba(18,128,92,0.1)',  '#12805c', 'rgba(18,128,92,0.25)',  'Dikonfirmasi'],
                $status === 'rejected'  => ['rgba(192,69,59,0.1)',   '#c0453b', 'rgba(192,69,59,0.25)',   'Ditolak'],
                default                 => ['rgba(107,114,128,0.1)', '#475467', 'rgba(107,114,128,0.2)', $status],
            };
            $methodLabel = match($h['method'] ?? '') {
                'transfer' => 'Transfer Bank', 'cash' => 'Tunai', 'other' => 'Lainnya', default => $h['method'] ?? '—'
            };
        @endphp
        <div x-data="{ open: false }" style="border-bottom:1px solid #eef0f3;">
            {{-- Header: always visible — periode, jumlah, badge status --}}
            <button type="button" @click="open = !open"
                class="w-full text-left px-5 py-4 flex items-center justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center flex-wrap gap-2 mb-1">
                        <span class="text-sm font-semibold" style="color:#1d2939;">IPL {{ $h['period'] }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                              style="background:{{ $isAdmin ? 'rgba(99,102,241,0.12)' : 'rgba(16,24,40,0.1)' }};color:{{ $isAdmin ? '#4f46e5' : '#111827' }};border:1px solid {{ $isAdmin ? 'rgba(99,102,241,0.25)' : 'rgba(16,24,40,0.2)' }};">
                            {{ $isAdmin ? 'Dicatat Admin' : 'Via Portal' }}
                        </span>
                    </div>
                    <p class="text-xs" style="color:#98a2b3;">{{ CarbonAlias::parse($h['date'])->translatedFormat('d M Y') }}</p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="text-right">
                        <p class="text-sm font-bold" style="color:#111827;">Rp {{ number_format($h['amount'],0,',','.') }}</p>
                        <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-medium"
                              style="background:{{ $sbg }};color:{{ $scolor }};border:1px solid {{ $sborder }};">{{ $slabel }}</span>
                    </div>
                    <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :style="open ? 'transform:rotate(180deg);color:#111827;' : 'color:#98a2b3;'"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            {{-- Detail: expandable on click --}}
            <div x-show="open" x-cloak x-transition class="px-5 pb-4" style="border-top:1px dashed #f5f6f8;">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 mb-3">
                    <div>
                        <p class="text-xs mb-0.5" style="color:#98a2b3;">Periode</p>
                        <p class="text-sm" style="color:#475467;">{{ $h['period'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs mb-0.5" style="color:#98a2b3;">Tanggal</p>
                        <p class="text-sm" style="color:#475467;">{{ CarbonAlias::parse($h['date'])->translatedFormat('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs mb-0.5" style="color:#98a2b3;">Metode</p>
                        <p class="text-sm" style="color:#475467;">{{ $methodLabel }}</p>
                    </div>
                    @if($h['reference'])
                    <div>
                        <p class="text-xs mb-0.5" style="color:#98a2b3;">No. Referensi</p>
                        <p class="text-sm font-mono truncate" style="color:#475467;">{{ $h['reference'] }}</p>
                    </div>
                    @endif
                </div>

                {{-- Rincian komponen (breakdown dari pembayaran tercatat admin) --}}
                @if($h['breakdown'])
                <div class="rounded-xl p-3 mb-3" style="background:rgba(18,128,92,0.05);border:1px solid rgba(18,128,92,0.15);">
                    <p class="text-xs font-semibold mb-2" style="color:#12805c;">Rincian Komponen</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @if($h['breakdown']['security'] > 0)
                        <div class="flex items-center justify-between sm:block">
                            <span class="text-xs" style="color:#7c8698;">Keamanan</span>
                            <span class="text-sm font-medium sm:block sm:mt-0.5" style="color:#475467;">Rp {{ number_format($h['breakdown']['security'],0,',','.') }}</span>
                        </div>
                        @endif
                        @if($h['breakdown']['garbage'] > 0)
                        <div class="flex items-center justify-between sm:block">
                            <span class="text-xs" style="color:#7c8698;">Sampah</span>
                            <span class="text-sm font-medium sm:block sm:mt-0.5" style="color:#475467;">Rp {{ number_format($h['breakdown']['garbage'],0,',','.') }}</span>
                        </div>
                        @endif
                        @if($h['breakdown']['kas_rt'] > 0)
                        <div class="flex items-center justify-between sm:block">
                            <span class="text-xs" style="color:#7c8698;">Kas RT</span>
                            <span class="text-sm font-medium sm:block sm:mt-0.5" style="color:#475467;">Rp {{ number_format($h['breakdown']['kas_rt'],0,',','.') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Catatan --}}
                @if($h['notes'])
                <div class="mb-2">
                    <p class="text-xs mb-0.5" style="color:#98a2b3;">Catatan</p>
                    <p class="text-sm" style="color:#667085;">{{ $h['notes'] }}</p>
                </div>
                @endif

                {{-- Admin notes if rejected --}}
                @if($status === 'rejected' && $h['admin_notes'])
                    <div class="mt-2 px-3 py-2 rounded-lg text-xs" style="background:rgba(192,69,59,0.06);border:1px solid rgba(192,69,59,0.15);color:#c0453b;">
                        <span style="color:#667085;">Catatan pengurus:</span> {{ $h['admin_notes'] }}
                    </div>
                @endif
            </div>
        </div>
        @empty
        <div class="px-5 py-10 text-center">
            <svg class="w-10 h-10 mx-auto mb-3" style="color:#98a2b3;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-sm" style="color:#98a2b3;">Belum ada riwayat pembayaran IPL.</p>
            <p class="text-xs mt-1" style="color:#98a2b3;">Riwayat akan muncul setelah Anda mengirim atau pengurus mencatat pembayaran.</p>
        </div>
        @endforelse
    </div>


    {{-- ─── Pay Existing Billing Modal ──────────────────────────── --}}
    @if($isPayModalOpen)
    @php $payBilling = $billings->firstWhere('id', $payingBillingId); @endphp
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isPayModalOpen', false)"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col"
             style="background:#ffffff;border:1px solid #d0d5dd;max-height:92vh;">

            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl"
                 style="background:#f2f4f7;border-bottom:1px solid rgba(16,24,40,0.35);">
                <h3 class="font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">Konfirmasi Pembayaran IPL</h3>
                <button wire:click="$set('isPayModalOpen', false)" class="p-1 rounded-lg" style="color:#1d2939;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1">
                <form wire:submit="submitPayment" id="payForm" class="px-6 py-5 space-y-4">
                    @if($payBilling)
                    <div class="rounded-xl p-4" style="background:#ffffff;border:1px solid #e4e7ec;">
                        <p class="text-xs mb-1" style="color:#7c8698;">Periode Tagihan</p>
                        <p class="font-semibold" style="color:#1d2939;">
                            {{ $payBilling->period ? CarbonAlias::create($payBilling->period->year, $payBilling->period->month)->translatedFormat('F Y') : '—' }}
                            @if($payBilling->houseBlock) — {{ $payBilling->houseBlock->block_code }} @endif
                        </p>
                        <div class="flex items-center justify-between mt-3 pt-3" style="border-top:1px solid #eef0f3;">
                            <span class="text-xs" style="color:#667085;">Jumlah yang harus dibayar</span>
                            <span class="text-base font-bold" style="color:#111827;">Rp {{ number_format($payBilling->outstanding, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Metode Pembayaran</label>
                            <select wire:model.live="paymentMethod"
                                style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                                <option value="transfer">Transfer Bank</option>
                                <option value="cash">Tunai</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Nama Bank</label>
                            <input type="text" wire:model="bankName" placeholder="BCA, Mandiri..."
                                {{ $paymentMethod !== 'transfer' ? 'disabled' : '' }}
                                style="background:{{ $paymentMethod !== 'transfer' ? '#ffffff' : '#ffffff' }};border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                            @error('bankName') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">No. Referensi / Bukti Transfer</label>
                        <input type="text" wire:model="referenceNum" placeholder="No. transaksi atau referensi pembayaran"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                        @error('referenceNum') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Foto Bukti Pembayaran</label>
                        <label class="flex items-center gap-2 px-4 py-3 rounded-xl cursor-pointer"
                            style="background:#ffffff;border:1px dashed #d0d5dd;">
                            <svg class="w-5 h-5 shrink-0" style="color:#98a2b3;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-sm" style="color:#7c8698;">
                                @if($proofPhoto) {{ $proofPhoto->getClientOriginalName() }} @else Pilih foto bukti (opsional) @endif
                            </span>
                            <input type="file" wire:model="proofPhoto" accept="image/*" class="hidden">
                        </label>
                        <div wire:loading wire:target="proofPhoto" class="text-xs mt-1" style="color:#111827;">Mengunggah...</div>
                        @error('proofPhoto') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Catatan</label>
                        <textarea wire:model="notes" rows="2" placeholder="Catatan tambahan (opsional)"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:none;"></textarea>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 shrink-0 flex gap-3" style="border-top:1px solid #e4e7ec;">
                <button type="button" wire:click="$set('isPayModalOpen', false)"
                    class="flex-1 py-2.5 rounded-xl text-sm font-medium"
                    style="background:#f5f6f8;color:#344054;border:1px solid #d0d5dd;">Batal</button>
                <button type="submit" form="payForm"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold"
                    style="background:#111827;color:#ffffff;" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submitPayment">Kirim Konfirmasi</span>
                    <span wire:loading wire:target="submitPayment" class="inline-flex items-center gap-1">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Mengirim...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif


    {{-- ─── Direct Pay Modal (multi-bulan) ───────────────────────── --}}
    {{-- ═══ MODAL: Checklist Pembayaran IPL ═══ --}}
    @if($isChecklistOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isChecklistOpen', false)"></div>
        <div class="relative rounded-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #d0d5dd;box-shadow:0 25px 60px rgba(0,0,0,0.15);max-height:92vh;">
            <div class="px-6 py-4 flex items-center justify-between rounded-t-2xl" style="background:#f2f4f7;border-bottom:1px solid rgba(16,24,40,0.35);">
                <div>
                    <h3 class="font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">Bayar IPL</h3>
                    <p class="text-xs mt-0.5" style="color:#1d2939;">Centang bulan yang ingin dibayar.</p>
                </div>
                <button wire:click="$set('isChecklistOpen', false)" class="p-1 rounded-lg" style="color:#1d2939;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-6 py-4 space-y-4 overflow-y-auto">
                {{-- Komponen yang dibayar --}}
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#111827;">Komponen yang Dibayar</p>
                    <div class="flex flex-wrap gap-2">
                        <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm cursor-pointer" style="background:{{ $paySecurity ? 'rgba(16,24,40,0.12)' : '#ffffff' }};border:1px solid {{ $paySecurity ? 'rgba(16,24,40,0.4)' : '#e4e7ec' }};color:{{ $paySecurity ? '#111827' : '#7c8698' }};">
                            <input type="checkbox" wire:model.live="paySecurity" style="accent-color:#111827;"> Keamanan
                        </label>
                        <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm cursor-pointer" style="background:{{ $payGarbage ? 'rgba(16,24,40,0.12)' : '#ffffff' }};border:1px solid {{ $payGarbage ? 'rgba(16,24,40,0.4)' : '#e4e7ec' }};color:{{ $payGarbage ? '#111827' : '#7c8698' }};">
                            <input type="checkbox" wire:model.live="payGarbage" style="accent-color:#111827;"> Sampah
                        </label>
                        <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm cursor-pointer" style="background:{{ $payKasRt ? 'rgba(16,24,40,0.12)' : '#ffffff' }};border:1px solid {{ $payKasRt ? 'rgba(16,24,40,0.4)' : '#e4e7ec' }};color:{{ $payKasRt ? '#111827' : '#7c8698' }};">
                            <input type="checkbox" wire:model.live="payKasRt" style="accent-color:#111827;"> Kas RT
                        </label>
                    </div>
                    @error('paySecurity') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    <p class="text-xs mt-1.5" style="color:#98a2b3;">Berlaku untuk semua bulan tercentang — mis. centang "Sampah" saja untuk bayar sampah dulu.</p>
                </div>

                {{-- Daftar bulan --}}
                @php
                    $selTotal = 0;
                    foreach($payMonths as $mm){
                        if(in_array($mm['key'], $pickedMonths) && !$mm['locked']){
                            $selTotal += ($paySecurity?$mm['rem_security']:0)+($payGarbage?$mm['rem_garbage']:0)+($payKasRt?$mm['rem_kas_rt']:0);
                        }
                    }
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color:#111827;">Pilih Bulan</p>
                        <div class="flex items-center gap-1.5">
                            <input type="number" wire:model.live="futureMonthsToAdd" min="1" max="12"
                                   class="w-12 px-2 py-1 text-xs rounded-lg outline-none text-center"
                                   style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;">
                            <button type="button" wire:click="addFutureMonth" class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-lg" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.25);">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Bulan di muka
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5 rounded-xl p-2" style="border:1px solid #e4e7ec;max-height:44vh;overflow-y:auto;">
                        @forelse($payMonths as $m)
                            @php
                                $badge = match($m['status']){
                                    'partial'  => ['Sebagian', '#c77d1a', 'rgba(199,125,26,0.12)'],
                                    'unbilled' => ['Di muka', '#111827', 'rgba(16,24,40,0.12)'],
                                    default    => ['Belum bayar', '#c0453b', 'rgba(192,69,59,0.1)'],
                                };
                                $rowSel = ($paySecurity?$m['rem_security']:0)+($payGarbage?$m['rem_garbage']:0)+($payKasRt?$m['rem_kas_rt']:0);
                            @endphp
                            <label wire:key="pm-{{ $m['key'] }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg cursor-pointer"
                                   style="background:#ffffff;border:1px solid #eef0f3;">
                                <input type="checkbox" wire:model.live="pickedMonths" value="{{ $m['key'] }}" style="accent-color:#111827;">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-sm font-medium" style="color:#1d2939;">{{ $m['label'] }}</span>
                                        @if($m['block_code'])
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(201,159,46,0.1);color:#111827;border:1px solid rgba(16,24,40,0.3);">{{ $m['block_code'] }}</span>
                                        @endif
                                        <span class="text-xs px-2 py-0.5 rounded-full" style="background:{{ $badge[2] }};color:{{ $badge[1] }};">{{ $badge[0] }}</span>
                                    </div>
                                    <div class="text-xs mt-0.5" style="color:#7c8698;">
                                        Sisa:
                                        @if($m['rem_security']>0) Keamanan {{ number_format($m['rem_security'],0,',','.') }}@endif
                                        @if($m['rem_garbage']>0) · Sampah {{ number_format($m['rem_garbage'],0,',','.') }}@endif
                                        @if($m['rem_kas_rt']>0) · Kas RT {{ number_format($m['rem_kas_rt'],0,',','.') }}@endif
                                    </div>
                                </div>
                                <span class="text-sm font-semibold shrink-0" style="color:#111827;">Rp {{ number_format($rowSel,0,',','.') }}</span>
                            </label>
                        @empty
                            <div class="text-center py-8 px-4">
                                <svg class="w-10 h-10 mx-auto mb-3" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-sm font-semibold" style="color:#12805c;">Tidak ada tagihan untuk Anda</p>
                                <p class="text-xs mt-1.5" style="color:#7c8698;">Terima kasih telah membayar tepat waktu. Iuran Pemeliharaan Lingkungan dari kita untuk lingkungan kita.</p>
                            </div>
                        @endforelse
                    </div>
                    @error('pickedMonths') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    <div class="flex items-center justify-between mt-2 px-1">
                        <span class="text-xs" style="color:#7c8698;">Total terpilih</span>
                        <span class="text-base font-bold" style="color:#111827;">Rp {{ number_format($selTotal,0,',','.') }}</span>
                    </div>
                </div>

                {{-- Metode & bukti --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium mb-1" style="color:#475467;">Metode</label>
                        <select wire:model="paymentMethod" class="w-full px-3 py-2 text-sm rounded-xl outline-none" style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;">
                            <option value="transfer">Transfer</option>
                            <option value="cash">Tunai</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1" style="color:#475467;">Bank / No. Ref (opsional)</label>
                        <input type="text" wire:model="referenceNum" placeholder="No. referensi" class="w-full px-3 py-2 text-sm rounded-xl outline-none" style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;">
                    </div>
                </div>
                <div>
                    <label class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm cursor-pointer" style="background:#ffffff;border:1px solid #e4e7ec;color:#475467;">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @if($proofPhoto) {{ $proofPhoto->getClientOriginalName() }} @else Unggah bukti (opsional) @endif
                        <input type="file" wire:model="proofPhoto" accept="image/*" class="hidden">
                    </label>
                    @error('proofPhoto') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    <div wire:loading wire:target="proofPhoto" class="text-xs mt-1" style="color:#111827;">Mengunggah...</div>
                </div>
                <div>
                    <textarea wire:model="notes" rows="2" placeholder="Catatan (opsional)" class="w-full px-3 py-2 text-sm rounded-xl outline-none" style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;resize:none;"></textarea>
                </div>
            </div>

            <div class="px-6 py-4 flex justify-end gap-3 rounded-b-2xl" style="border-top:1px solid #e4e7ec;">
                <button type="button" wire:click="$set('isChecklistOpen', false)" class="px-4 py-2 text-sm rounded-xl font-medium" style="background:#f5f6f8;color:#344054;border:1px solid #d0d5dd;">Batal</button>
                <button wire:click="submitChecklist" wire:loading.attr="disabled" class="px-5 py-2 text-sm rounded-xl font-semibold" style="background:#111827;color:#ffffff;">
                    <span wire:loading.remove wire:target="submitChecklist">Kirim Pembayaran</span>
                    <span wire:loading wire:target="submitChecklist">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
