<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#111827;">Laporan IPL Perumahan</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        {{-- Header Banner --}}
        <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(16,24,40,0.35);">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg" style="color:#111827;font-family:'IBM Plex Sans',serif;">Laporan IPL Perumahan</h3>
                    <p class="text-sm mt-1" style="color:#111827;">Rekapitulasi iuran bulanan per blok dan penghuni</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('ipl.index') }}" wire:navigate
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium shrink-0"
                        style="background:rgba(16,24,40,0.15);color:#111827;border:1px solid rgba(16,24,40,0.3);"
                        onmouseover="this.style.background='rgba(16,24,40,0.25)'" onmouseout="this.style.background='rgba(16,24,40,0.15)'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke IPL
                    </a>
                    <button wire:click="printPdf"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                        style="background:#1d2939;color:#ffffff;"
                        onmouseover="this.style.background='#111827'" onmouseout="this.style.background='#1d2939'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print PDF
                    </button>
                </div>
            </div>
        </div>

        {{-- View Tabs --}}
        <div class="flex gap-1 p-1 rounded-xl w-fit" style="background:#ffffff;border:1px solid #e4e7ec;">
            <button wire:click="switchView('summary')"
                class="px-5 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2"
                style="{{ $activeView === 'summary' ? 'background:#111827;color:#ffffff;' : 'color:#7c8698;' }}"
                @if($activeView !== 'summary') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#7c8698'" @endif>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Rekap Bulanan
            </button>
            <button wire:click="switchView('per_block')"
                class="px-5 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2"
                style="{{ $activeView === 'per_block' ? 'background:#111827;color:#ffffff;' : 'color:#7c8698;' }}"
                @if($activeView !== 'per_block') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#7c8698'" @endif>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 4v16M14 4v16M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                Per Blok / Tahunan
            </button>
        </div>

        {{-- ══════════════════════════════════════════ --}}
        {{-- SUMMARY VIEW (per period/month)           --}}
        {{-- ══════════════════════════════════════════ --}}
        @if($activeView === 'summary')

            {{-- Period Selectors --}}
            <div class="rounded-2xl p-4" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="flex flex-wrap gap-3 items-center">
                    <span class="text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Periode:</span>
                    <select wire:model.live="periodFilterMonth" wire:change="selectByPeriod"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;min-width:140px;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        @foreach($months as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="periodFilterYear" wire:change="selectByPeriod"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;min-width:110px;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if($currentPeriod)
                {{-- Period Title + Tariffs --}}
                <div class="rounded-xl px-4 py-3" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <h2 class="font-bold" style="color:#1d2939;">Laporan Periode: <span style="color:#111827;">{{ $currentPeriod->period_label }}</span></h2>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm mt-1" style="color:#98a2b3;">
                        <span>Security: <span class="font-medium" style="color:#475467;">Rp {{ number_format($currentPeriod->ipl_security_amount, 0, ',', '.') }}</span></span>
                        <span>Sampah: <span class="font-medium" style="color:#475467;">Rp {{ number_format($currentPeriod->ipl_garbage_amount, 0, ',', '.') }}</span></span>
                        @if(($currentPeriod->ipl_kas_rt_amount ?? 0) > 0)
                            <span>Kas RT: <span class="font-medium" style="color:#475467;">Rp {{ number_format($currentPeriod->ipl_kas_rt_amount, 0, ',', '.') }}</span></span>
                        @endif
                    </div>
                </div>

                {{-- Summary Stats --}}
                @if(!empty($totals))
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#98a2b3;">Total Unit</p>
                        <p class="text-2xl font-bold mt-1" style="color:#1d2939;">{{ $totals['unit'] ?? 0 }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#98a2b3;">Total Tagihan</p>
                        <p class="text-lg font-bold mt-1" style="color:#111827;">Rp {{ number_format($totals['tagihan'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#98a2b3;">Terbayar</p>
                        <p class="text-lg font-bold mt-1" style="color:#12805c;">Rp {{ number_format($totals['terbayar'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#98a2b3;">Tunggakan</p>
                        <p class="text-lg font-bold mt-1" style="color:#c0453b;">Rp {{ number_format($totals['tunggakan'] ?? 0, 0, ',', '.') }}</p>
                        @if(($totals['dibebaskan'] ?? 0) > 0)
                            <p class="text-xs mt-0.5" style="color:#111827;">Dibebaskan: Rp {{ number_format($totals['dibebaskan'], 0, ',', '.') }} (non-kas)</p>
                        @endif
                    </div>
                    <div class="rounded-2xl p-5" style="background:rgba(16,24,40,0.05);border:1px solid rgba(16,24,40,0.2);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#111827;">Unit Lunas</p>
                        <p class="text-2xl font-bold mt-1" style="color:#111827;">{{ $totals['lunas'] ?? 0 }}</p>
                        <p class="text-xs mt-0.5" style="color:#98a2b3;">dari {{ $totals['unit'] ?? 0 }} unit</p>
                    </div>
                </div>
                @endif

                {{-- Summary by Block --}}
                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <div class="px-6 py-4 flex items-center gap-2" style="border-bottom:1px solid #f5f6f8;">
                        <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <h3 class="font-semibold text-sm" style="color:#1d2939;">Rekapitulasi per Blok</h3>
                    </div>
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Blok</th>
                                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Unit</th>
                                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Lunas</th>
                                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Belum</th>
                                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Sebagian</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#98a2b3;">Total Tagihan</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#98a2b3;">Terbayar</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Tunggakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($summaryByBlock as $row)
                                    @php $tunggakanRow = max(0, ($row->total_tagihan ?? 0) - ($row->total_terbayar ?? 0) - ($row->total_dibebaskan ?? 0)); @endphp
                                    <tr style="border-bottom:1px solid #eef0f3;"
                                        onmouseover="this.style.backgroundColor='#f5f6f8'" onmouseout="this.style.backgroundColor=''">
                                        <td class="px-4 py-3 font-bold" style="color:#111827;">Blok {{ $row->block_letter ?? '?' }}</td>
                                        <td class="px-4 py-3 text-center font-medium" style="color:#475467;">{{ $row->jumlah_unit }}</td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">{{ $row->lunas }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">{{ $row->belum_bayar }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">{{ $row->sebagian }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-xs hidden sm:table-cell" style="color:#7c8698;">Rp {{ number_format($row->total_tagihan ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-xs hidden sm:table-cell" style="color:#12805c;">Rp {{ number_format($row->total_terbayar ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-xs" style="{{ $tunggakanRow > 0 ? 'color:#c0453b;font-weight:600;' : 'color:#98a2b3;' }}">
                                            Rp {{ number_format($tunggakanRow, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-10 text-center text-sm" style="color:#98a2b3;">Belum ada data tagihan untuk periode ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="md:hidden divide-y" style="border-color:#eef0f3;">
                        @forelse($summaryByBlock as $row)
                            @php $tunggakanRow = max(0, ($row->total_tagihan ?? 0) - ($row->total_terbayar ?? 0) - ($row->total_dibebaskan ?? 0)); @endphp
                            <div wire:key="block-summary-card-{{ $loop->index }}" class="px-4 py-3.5">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold" style="color:#111827;">Blok {{ $row->block_letter ?? '?' }}</span>
                                    <span class="font-mono text-sm" style="{{ $tunggakanRow > 0 ? 'color:#c0453b;font-weight:600;' : 'color:#98a2b3;' }}">
                                        Rp {{ number_format($tunggakanRow, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span class="text-xs" style="color:#98a2b3;">Unit: <span class="font-medium" style="color:#475467;">{{ $row->jumlah_unit }}</span></span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Lunas {{ $row->lunas }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">Belum {{ $row->belum_bayar }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">Sebagian {{ $row->sebagian }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 mt-3 text-sm">
                                    <div>
                                        <p class="text-xs" style="color:#98a2b3;">Total Tagihan</p>
                                        <p class="font-mono text-xs" style="color:#7c8698;">Rp {{ number_format($row->total_tagihan ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs" style="color:#98a2b3;">Terbayar</p>
                                        <p class="font-mono text-xs" style="color:#12805c;">Rp {{ number_format($row->total_terbayar ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-10 text-center text-sm" style="color:#98a2b3;">Belum ada data tagihan untuk periode ini.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Unpaid Residents --}}
                @if(count($unpaidResidents) > 0)
                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid rgba(192,69,59,0.2);">
                    <div class="px-6 py-4 flex items-center gap-2" style="background:rgba(192,69,59,0.05);border-bottom:1px solid rgba(192,69,59,0.15);">
                        <svg class="w-4 h-4 shrink-0" style="color:#c0453b;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <h3 class="font-semibold text-sm" style="color:#c0453b;">Daftar Tunggakan — {{ count($unpaidResidents) }} Unit</h3>
                    </div>
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Blok</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Penghuni</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Total Tagihan</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#98a2b3;">Terbayar</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Sisa</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unpaidResidents as $billing)
                                    <tr style="border-bottom:1px solid #eef0f3;"
                                        onmouseover="this.style.backgroundColor='#f5f6f8'" onmouseout="this.style.backgroundColor=''">
                                        <td class="px-4 py-3">
                                            <span class="font-mono font-bold" style="color:#111827;">{{ $billing->houseBlock?->block_code ?? '—' }}</span>
                                        </td>
                                        <td class="px-4 py-3 font-medium" style="color:#1d2939;">{{ $billing->responsibleResident?->name ?? '—' }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-sm font-semibold" style="color:#1d2939;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-xs hidden md:table-cell" style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right font-mono text-sm font-bold" style="color:#c0453b;">Rp {{ number_format($billing->outstanding, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            @if($billing->status === 'partial')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">Sebagian</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">Belum Bayar</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="md:hidden divide-y" style="border-color:#eef0f3;">
                        @foreach($unpaidResidents as $billing)
                            <div wire:key="unpaid-resident-card-{{ $billing->id }}" class="px-4 py-3.5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="font-mono font-bold text-xs" style="color:#111827;">{{ $billing->houseBlock?->block_code ?? '—' }}</span>
                                        <p class="font-medium mt-0.5" style="color:#1d2939;">{{ $billing->responsibleResident?->name ?? '—' }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="font-mono text-sm font-bold" style="color:#c0453b;">Rp {{ number_format($billing->outstanding, 0, ',', '.') }}</p>
                                        @if($billing->status === 'partial')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">Sebagian</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">Belum Bayar</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 mt-3 text-sm">
                                    <div>
                                        <p class="text-xs" style="color:#98a2b3;">Total Tagihan</p>
                                        <p class="font-mono text-sm font-semibold" style="color:#1d2939;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs" style="color:#98a2b3;">Terbayar</p>
                                        <p class="font-mono text-xs" style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @else
                    @if(!empty($totals) && ($totals['unit'] ?? 0) > 0)
                        <div class="rounded-2xl px-6 py-5 flex items-center gap-3" style="background:rgba(18,128,92,0.05);border:1px solid rgba(18,128,92,0.2);">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" style="background:rgba(18,128,92,0.1);">
                                <svg class="w-5 h-5" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="font-medium" style="color:#12805c;">Semua unit sudah melunasi IPL untuk periode ini!</span>
                        </div>
                    @endif
                @endif

            @else
                <div class="rounded-2xl px-4 py-10 text-center" style="background:rgba(16,24,40,0.03);border:1px solid rgba(16,24,40,0.1);">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="font-medium" style="color:#111827;">Pilih periode IPL untuk melihat laporan</p>
                </div>
            @endif

        @endif {{-- end summary view --}}

        {{-- ══════════════════════════════════════════ --}}
        {{-- PER-BLOCK VIEW (yearly matrix)            --}}
        {{-- ══════════════════════════════════════════ --}}
        @if($activeView === 'per_block')

            @php
                $monthLabels = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
            @endphp

            {{-- Filters --}}
            <div class="rounded-2xl p-4" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium mb-1.5 uppercase tracking-wider" style="color:#98a2b3;">Tahun</label>
                        <select wire:model.live="filterYear"
                            class="px-3 py-2 rounded-lg text-sm outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;">
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}">{{ $yr }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5 uppercase tracking-wider" style="color:#98a2b3;">Filter Blok</label>
                        <select wire:model.live="filterBlockId"
                            class="px-3 py-2 rounded-lg text-sm outline-none min-w-36"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;">
                            <option value="">Semua Blok</option>
                            @foreach($allBlocks as $bl)
                                <option value="{{ $bl->id }}">{{ $bl->block_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center gap-3 ml-auto text-xs" style="color:#98a2b3;">
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block w-3.5 h-3.5 rounded-sm" style="background:rgba(18,128,92,0.7);"></span> Lunas
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block w-3.5 h-3.5 rounded-sm" style="background:rgba(199,125,26,0.7);"></span> Sebagian
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block w-3.5 h-3.5 rounded-sm" style="background:rgba(192,69,59,0.6);"></span> Belum Bayar
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block w-3.5 h-3.5 rounded-sm" style="background:#eef0f3;border:1px solid #e4e7ec;"></span> Tidak Ada Tagihan
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="inline-block w-3.5 h-3.5 rounded-sm" style="background:#ffffff;"></span> Belum Ada Periode
                        </span>
                    </div>
                </div>
            </div>

            {{-- Summary Stats --}}
            @if(!empty($blockMatrix))
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#98a2b3;">Total Unit</p>
                    <p class="text-2xl font-bold mt-1" style="color:#1d2939;">{{ count($blockMatrix) }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid rgba(192,69,59,0.3);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#98a2b3;">Unit Ada Tunggakan</p>
                    <p class="text-2xl font-bold mt-1" style="color:#c0453b;">{{ $totalUnpaidBlocks ?? 0 }}</p>
                </div>
                <div class="rounded-2xl p-5 col-span-2" style="background:rgba(192,69,59,0.05);border:1px solid rgba(192,69,59,0.2);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#98a2b3;">Total Tunggakan Tahun {{ $selectedYear ?? '' }}</p>
                    <p class="text-2xl font-bold mt-1" style="color:#c0453b;">Rp {{ number_format($grandTotalOutstanding ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            @endif

            {{-- Matrix Table --}}
            @if(empty($blockMatrix))
                <div class="rounded-2xl px-4 py-10 text-center" style="background:rgba(16,24,40,0.03);border:1px solid rgba(16,24,40,0.1);">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 10h18M3 14h18M10 4v16M14 4v16"/></svg>
                    <p class="font-medium" style="color:#111827;">Tidak ada data untuk tahun ini</p>
                </div>
            @else
                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-2" style="border-bottom:1px solid #f5f6f8;">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" style="color:#111827;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 4v16M14 4v16M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>
                            <h3 class="font-semibold text-sm" style="color:#1d2939;">Status Pembayaran Per Blok — Tahun {{ $selectedYear ?? '' }}</h3>
                        </div>
                        <span class="text-xs" style="color:#98a2b3;">{{ count($blockMatrix) }} unit ditampilkan</span>
                    </div>
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-xs" style="min-width:900px;">
                            <thead>
                                <tr style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
                                    <th class="text-left px-4 py-3 font-semibold uppercase tracking-wider sticky left-0" style="color:#98a2b3;background:#ffffff;min-width:100px;">Blok</th>
                                    <th class="text-left px-3 py-3 font-semibold uppercase tracking-wider" style="color:#98a2b3;min-width:130px;">Penghuni</th>
                                    @foreach($monthLabels as $mn => $ml)
                                        <th class="text-center px-2 py-3 font-semibold uppercase tracking-wider" style="color:#98a2b3;min-width:52px;">{{ $ml }}</th>
                                    @endforeach
                                    <th class="text-right px-4 py-3 font-semibold uppercase tracking-wider" style="color:#c0453b;min-width:110px;">Tunggakan</th>
                                    <th class="text-left px-4 py-3 font-semibold uppercase tracking-wider" style="color:#98a2b3;min-width:140px;">Bulan Belum Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($blockMatrix as $row)
                                    @php
                                        $resident = $row['block']->residents->first();
                                        $residentName = $resident?->name ?? '—';
                                    @endphp
                                    <tr style="border-bottom:1px solid #ffffff;"
                                        onmouseover="this.style.backgroundColor='#eef0f3'" onmouseout="this.style.backgroundColor=''">
                                        {{-- Block code --}}
                                        <td class="px-4 py-2.5 font-bold font-mono sticky left-0" style="color:#111827;background:#ffffff;">
                                            {{ $row['block']->block_code }}
                                        </td>
                                        {{-- Resident name --}}
                                        <td class="px-3 py-2.5 truncate max-w-xs" style="color:#667085;" title="{{ $residentName }}">
                                            {{ Str::limit($residentName, 18) }}
                                        </td>
                                        {{-- Month cells --}}
                                        @foreach($monthLabels as $m => $ml)
                                            @php $cell = $row['months'][$m] ?? ['status' => 'no_period']; @endphp
                                            <td class="px-1 py-2.5 text-center">
                                                @if($cell['status'] === 'paid')
                                                    <span class="inline-flex items-center justify-center w-9 h-6 rounded text-xs font-bold"
                                                        style="background:rgba(18,128,92,0.15);color:#12805c;border:1px solid rgba(18,128,92,0.3);"
                                                        title="Lunas">✓</span>
                                                @elseif($cell['status'] === 'partial')
                                                    <span class="inline-flex items-center justify-center w-9 h-6 rounded text-xs font-bold"
                                                        style="background:rgba(199,125,26,0.15);color:#c77d1a;border:1px solid rgba(199,125,26,0.3);"
                                                        title="Sebagian — Sisa Rp {{ number_format($cell['outstanding'] ?? 0, 0, ',', '.') }}">½</span>
                                                @elseif($cell['status'] === 'unpaid')
                                                    <span class="inline-flex items-center justify-center w-9 h-6 rounded text-xs font-bold"
                                                        style="background:rgba(192,69,59,0.15);color:#c0453b;border:1px solid rgba(192,69,59,0.3);"
                                                        title="Belum Bayar — Rp {{ number_format($cell['outstanding'] ?? 0, 0, ',', '.') }}">✕</span>
                                                @elseif($cell['status'] === 'no_billing')
                                                    <span class="inline-flex items-center justify-center w-9 h-6 rounded"
                                                        style="background:#eef0f3;color:#98a2b3;border:1px solid #f5f6f8;"
                                                        title="Tidak ada tagihan">—</span>
                                                @else {{-- no_period --}}
                                                    <span class="inline-flex items-center justify-center w-9 h-6 rounded"
                                                        style="background:#ffffff;color:#98a2b3;"
                                                        title="Periode belum dibuat">·</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        {{-- Total outstanding --}}
                                        <td class="px-4 py-2.5 text-right font-mono font-bold"
                                            style="{{ ($row['totalOutstanding'] ?? 0) > 0 ? 'color:#c0453b;' : 'color:#98a2b3;' }}">
                                            @if(($row['totalOutstanding'] ?? 0) > 0)
                                                Rp {{ number_format($row['totalOutstanding'], 0, ',', '.') }}
                                            @else
                                                <span style="color:#12805c;">Lunas</span>
                                            @endif
                                        </td>
                                        {{-- Unpaid months summary --}}
                                        <td class="px-4 py-2.5" style="color:#667085;">
                                            @if(!empty($row['unpaidMonths']))
                                                <span class="inline-flex flex-wrap gap-1">
                                                    @foreach($row['unpaidMonths'] as $um)
                                                        <span class="px-1.5 py-0.5 rounded text-xs font-medium"
                                                            style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">
                                                            {{ $monthLabels[$um] }}
                                                        </span>
                                                    @endforeach
                                                </span>
                                            @else
                                                <span style="color:#12805c;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="md:hidden divide-y" style="border-color:#eef0f3;">
                        @foreach($blockMatrix as $row)
                            @php
                                $residentCard = $row['block']->residents->first();
                                $residentCardName = $residentCard?->name ?? '—';
                            @endphp
                            <div wire:key="block-matrix-card-{{ $row['block']->id }}" class="px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <span class="font-bold font-mono" style="color:#111827;">{{ $row['block']->block_code }}</span>
                                        <p class="text-sm mt-0.5" style="color:#667085;">{{ $residentCardName }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        @if(($row['totalOutstanding'] ?? 0) > 0)
                                            <p class="font-mono font-bold text-sm" style="color:#c0453b;">Rp {{ number_format($row['totalOutstanding'], 0, ',', '.') }}</p>
                                        @else
                                            <p class="font-bold text-sm" style="color:#12805c;">Lunas</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-6 gap-1.5 mt-3">
                                    @foreach($monthLabels as $m => $ml)
                                        @php $cell = $row['months'][$m] ?? ['status' => 'no_period']; @endphp
                                        <div class="flex flex-col items-center gap-0.5">
                                            <span class="text-xs" style="color:#98a2b3;">{{ $ml }}</span>
                                            @if($cell['status'] === 'paid')
                                                <span class="inline-flex items-center justify-center w-9 h-6 rounded text-xs font-bold"
                                                    style="background:rgba(18,128,92,0.15);color:#12805c;border:1px solid rgba(18,128,92,0.3);"
                                                    title="Lunas">✓</span>
                                            @elseif($cell['status'] === 'partial')
                                                <span class="inline-flex items-center justify-center w-9 h-6 rounded text-xs font-bold"
                                                    style="background:rgba(199,125,26,0.15);color:#c77d1a;border:1px solid rgba(199,125,26,0.3);"
                                                    title="Sebagian — Sisa Rp {{ number_format($cell['outstanding'] ?? 0, 0, ',', '.') }}">½</span>
                                            @elseif($cell['status'] === 'unpaid')
                                                <span class="inline-flex items-center justify-center w-9 h-6 rounded text-xs font-bold"
                                                    style="background:rgba(192,69,59,0.15);color:#c0453b;border:1px solid rgba(192,69,59,0.3);"
                                                    title="Belum Bayar — Rp {{ number_format($cell['outstanding'] ?? 0, 0, ',', '.') }}">✕</span>
                                            @elseif($cell['status'] === 'no_billing')
                                                <span class="inline-flex items-center justify-center w-9 h-6 rounded"
                                                    style="background:#eef0f3;color:#98a2b3;border:1px solid #f5f6f8;"
                                                    title="Tidak ada tagihan">—</span>
                                            @else {{-- no_period --}}
                                                <span class="inline-flex items-center justify-center w-9 h-6 rounded"
                                                    style="background:#ffffff;color:#98a2b3;"
                                                    title="Periode belum dibuat">·</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if(!empty($row['unpaidMonths']))
                                    <div class="mt-3">
                                        <p class="text-xs mb-1" style="color:#98a2b3;">Bulan Belum Bayar</p>
                                        <span class="inline-flex flex-wrap gap-1">
                                            @foreach($row['unpaidMonths'] as $um)
                                                <span class="px-1.5 py-0.5 rounded text-xs font-medium"
                                                    style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">
                                                    {{ $monthLabels[$um] }}
                                                </span>
                                            @endforeach
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer summary --}}
                    @if(($grandTotalOutstanding ?? 0) > 0)
                    <div class="px-5 py-3 flex flex-wrap items-center justify-between gap-3" style="border-top:1px solid #f5f6f8;background:#ffffff;">
                        <span class="text-xs" style="color:#98a2b3;">
                            {{ $totalUnpaidBlocks ?? 0 }} dari {{ count($blockMatrix) }} unit memiliki tunggakan
                        </span>
                        <span class="font-semibold text-sm" style="color:#c0453b;">
                            Total Tunggakan: Rp {{ number_format($grandTotalOutstanding ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    @else
                    <div class="px-5 py-3 flex items-center gap-2" style="border-top:1px solid #f5f6f8;background:#ffffff;">
                        <svg class="w-4 h-4" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-xs font-medium" style="color:#12805c;">Semua unit sudah lunas untuk tahun {{ $selectedYear ?? '' }}</span>
                    </div>
                    @endif
                </div>
            @endif

            {{-- Detail: Blocks with unpaid months listed --}}
            @php
                $blocksWithArrears = collect($blockMatrix)->filter(fn($r) => !empty($r['unpaidMonths']))->values();
            @endphp
            @if($blocksWithArrears->count() > 0)
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid rgba(192,69,59,0.2);">
                <div class="px-5 py-4 flex items-center gap-2" style="background:rgba(192,69,59,0.05);border-bottom:1px solid rgba(192,69,59,0.15);">
                    <svg class="w-4 h-4 shrink-0" style="color:#c0453b;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="font-semibold text-sm" style="color:#c0453b;">Daftar Unit Dengan Tunggakan — Tahun {{ $selectedYear ?? '' }}</h3>
                </div>
                <div class="divide-y" style="border-color:#eef0f3;">
                    @foreach($blocksWithArrears as $row)
                        <div class="px-5 py-3.5 flex flex-wrap items-center gap-3"
                            onmouseover="this.style.backgroundColor='#eef0f3'" onmouseout="this.style.backgroundColor=''">
                            <span class="font-bold font-mono text-sm w-16 shrink-0" style="color:#111827;">{{ $row['block']->block_code }}</span>
                            <span class="text-sm shrink-0 w-40" style="color:#667085;">
                                {{ Str::limit($row['block']->residents->first()?->name ?? '—', 22) }}
                            </span>
                            <div class="flex flex-wrap gap-1 flex-1">
                                @foreach($row['unpaidMonths'] as $um)
                                    <span class="px-2 py-0.5 rounded text-xs font-medium"
                                        style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">
                                        {{ $monthLabels[$um] }}
                                    </span>
                                @endforeach
                            </div>
                            <span class="font-bold text-sm ml-auto shrink-0" style="color:#c0453b;">
                                Rp {{ number_format($row['totalOutstanding'], 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        @endif {{-- end per_block view --}}

    </div>
</div>
