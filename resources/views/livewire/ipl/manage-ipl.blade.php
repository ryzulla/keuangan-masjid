<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#161e2d;">IPL — Iuran Perumahan</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        @if(session('success') && !$isPaymentModalOpen && !$isPeriodModalOpen)
            <div class="rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header Banner --}}
        <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg" style="color:#161e2d;font-family:'Manrope',serif;">IPL — Iuran Perumahan</h3>
                    <p class="text-sm mt-1" style="color:#161e2d;">Kelola iuran bulanan penghuni perumahan</p>
                </div>
                <div class="flex gap-2">
                    @if($currentPeriod)
                    <button wire:click="generateBillings({{ $currentPeriod->id }})"
                            wire:confirm="Generate tagihan untuk semua unit aktif di periode {{ $currentPeriod->period_label }}?"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                            style="background:rgba(21,99,223,0.15);color:#161e2d;border:1px solid rgba(21,99,223,0.3);"
                            onmouseover="this.style.background='rgba(21,99,223,0.25)'" onmouseout="this.style.background='rgba(21,99,223,0.15)'">
                            <span wire:loading.remove wire:target="generateBillings">
                                <svg class="inline w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Generate Tagihan
                            </span>
                            <span wire:loading wire:target="generateBillings" class="flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    @endif
                    <button wire:click="openChecklistPayment()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                        style="background:#161e2d;color:#ffffff;"
                        onmouseover="this.style.background='#1563df'" onmouseout="this.style.background='#161e2d'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Bayar per Penghuni
                    </button>
                    <button wire:click="openCreatePeriod()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                        style="background:#1563df;color:#ffffff;"
                        onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Periode Baru
                    </button>
                </div>
            </div>
        </div>

        {{-- Period Selectors --}}
        <div class="rounded-2xl p-4" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <div class="flex flex-wrap gap-3 items-center">
                <span class="text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Periode:</span>
                <select wire:model.live="periodFilterMonth" wire:change="selectByPeriod"
                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;min-width:140px;"
                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    @foreach($months as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select wire:model.live="periodFilterYear" wire:change="selectByPeriod"
                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;min-width:110px;"
                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                @if($currentPeriod)
                    <span class="text-xs px-2 py-1 rounded-lg font-medium" style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                        {{ $currentPeriod->period_label }}
                    </span>
                @else
                    <span class="text-xs italic" style="color:#a3abb0;">Belum ada periode untuk bulan/tahun ini.</span>
                @endif
            </div>
        </div>

        @if($currentPeriod)
        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <p class="text-xs font-medium uppercase tracking-wide" style="color:#a3abb0;">Total Tagihan</p>
                <p class="text-xl font-bold mt-1 truncate" style="color:#161e2d;">Rp {{ number_format($summary['total_tagihan'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs mt-1" style="color:#a3abb0;">{{ ($summary['jumlah_lunas'] ?? 0) + ($summary['jumlah_belum'] ?? 0) + ($summary['jumlah_sebagian'] ?? 0) }} unit</p>
            </div>
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <p class="text-xs font-medium uppercase tracking-wide" style="color:#a3abb0;">Sudah Terbayar</p>
                <p class="text-xl font-bold mt-1 truncate" style="color:#12805c;">Rp {{ number_format($summary['total_terbayar'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs mt-1" style="color:#a3abb0;">{{ $summary['jumlah_lunas'] ?? 0 }} unit lunas</p>
            </div>
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <p class="text-xs font-medium uppercase tracking-wide" style="color:#a3abb0;">Tunggakan</p>
                <p class="text-xl font-bold mt-1 truncate" style="color:#c0453b;">Rp {{ number_format($summary['total_tunggakan'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs mt-1" style="color:#a3abb0;">{{ $summary['jumlah_belum'] ?? 0 }} belum, {{ $summary['jumlah_sebagian'] ?? 0 }} sebagian</p>
                @if(($summary['total_dibebaskan'] ?? 0) > 0)
                    <p class="text-xs mt-0.5" style="color:#161e2d;">Dibebaskan: Rp {{ number_format($summary['total_dibebaskan'], 0, ',', '.') }} (non-kas)</p>
                @endif
            </div>
            <div class="rounded-2xl p-5" style="background:rgba(21,99,223,0.05);border:1px solid rgba(21,99,223,0.2);">
                <p class="text-xs font-medium uppercase tracking-wide" style="color:#161e2d;">Tarif Periode</p>
                <p class="text-sm font-semibold mt-1" style="color:#161e2d;">Security: Rp {{ number_format($currentPeriod->ipl_security_amount, 0, ',', '.') }}</p>
                <p class="text-sm" style="color:#161e2d;">Sampah: Rp {{ number_format($currentPeriod->ipl_garbage_amount, 0, ',', '.') }}</p>
                <p class="text-sm" style="color:#161e2d;">Kas RT: Rp {{ number_format($currentPeriod->ipl_kas_rt_amount, 0, ',', '.') }}</p>
                <button wire:click="openEditPeriod({{ $currentPeriod->id }})" class="text-xs mt-1 hover:underline" style="color:#161e2d;">Edit Tarif</button>
            </div>
        </div>

        {{-- Filters + Link --}}
        <div class="flex flex-wrap gap-3 items-center">
            <select wire:model.live="filterBillingStatus"
                style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;"
                onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                <option value="">Semua Status</option>
                <option value="unpaid">Belum Bayar</option>
                <option value="partial">Sebagian</option>
                <option value="paid">Lunas</option>
            </select>
            <select wire:model.live="filterBillingBlock"
                style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;"
                onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                <option value="">Semua Blok</option>
                @foreach($houseBlocks as $block)
                    <option value="{{ $block->id }}">{{ $block->block_code }}</option>
                @endforeach
            </select>
            <a href="{{ route('ipl.report') }}" wire:navigate
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                style="background:#f7f7f7;color:#5c6368;border:1px solid #e4e4e4;"
                onmouseover="this.style.color='#1563df';this.style.borderColor='rgba(21,99,223,0.3)'" onmouseout="this.style.color='#5c6368';this.style.borderColor='#e4e4e4'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan IPL
            </a>
        </div>

        {{-- Billings Table --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Blok</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Penghuni</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#a3abb0;">Security</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#a3abb0;">Sampah</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#a3abb0;">Kas RT</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Total</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#a3abb0;">Terbayar</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#a3abb0;">Sisa</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Status</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($billings as $billing)
                            <tr style="border-bottom:1px solid #f7f7f7;" wire:key="billing-{{ $billing->id }}"
                                onmouseover="this.style.backgroundColor='#f7f7f7'" onmouseout="this.style.backgroundColor=''">
                                <td class="px-4 py-3">
                                    @if($billing->houseBlock)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-bold"
                                            style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                                            {{ $billing->houseBlock->block_code }}
                                        </span>
                                    @else
                                        <span style="color:#a3abb0;">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium" style="color:#161e2d;">{{ $billing->responsibleResident?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden sm:table-cell" style="color:#a3abb0;">Rp {{ number_format($billing->ipl_security_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden sm:table-cell" style="color:#a3abb0;">Rp {{ number_format($billing->ipl_garbage_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden sm:table-cell" style="color:#a3abb0;">Rp {{ number_format($billing->ipl_kas_rt_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-sm font-semibold" style="color:#161e2d;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden md:table-cell" style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden md:table-cell" style="{{ $billing->outstanding > 0 ? 'color:#c0453b;font-weight:600;' : 'color:#a3abb0;' }}">
                                    Rp {{ number_format($billing->outstanding, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-1">
                                        @if($billing->status === 'paid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Lunas</span>
                                        @elseif($billing->status === 'partial')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">Sebagian</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">Belum Bayar</span>
                                        @endif
                                        @if($billing->is_waived)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" title="{{ $billing->waiver_reason }}" style="background:rgba(139,109,11,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.3);">Dibebaskan</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($billing->status !== 'paid')
                                            <button wire:click="openPayment({{ $billing->id }})"
                                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold transition-colors"
                                                style="background:#1563df;color:#ffffff;"
                                                onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">Bayar</button>
                                            <button wire:click="openWaive({{ $billing->id }})"
                                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold transition-colors"
                                                style="background:#f7f7f7;color:#161e2d;border:1px solid rgba(21,99,223,0.3);"
                                                onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">Bebaskan</button>
                                        @elseif($billing->is_waived)
                                            <button wire:click="cancelWaive({{ $billing->id }})"
                                                wire:confirm="Batalkan pembebasan? Tunggakan akan kembali aktif."
                                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;"
                                                onmouseover="this.style.color='#c0453b'" onmouseout="this.style.color='#a3abb0'">Batalkan</button>
                                        @else
                                            <span class="text-xs font-medium" style="color:#12805c;">✓ Lunas</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-14 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#1563df"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="font-medium text-sm" style="color:#a3abb0;">Belum ada tagihan untuk periode ini</p>
                                    <p class="text-xs mt-1" style="color:#a3abb0;">Klik "Generate Tagihan" untuk membuat tagihan otomatis.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="md:hidden divide-y" style="border-color:#f7f7f7;">
                @forelse($billings as $billing)
                    <div class="px-4 py-4" wire:key="bill-card-{{ $billing->id }}">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                @if($billing->houseBlock)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-bold shrink-0"
                                        style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                                        {{ $billing->houseBlock->block_code }}
                                    </span>
                                @else
                                    <span style="color:#a3abb0;">—</span>
                                @endif
                                <span class="font-medium truncate" style="color:#161e2d;">{{ $billing->responsibleResident?->name ?? '—' }}</span>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                @if($billing->status === 'paid')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Lunas</span>
                                @elseif($billing->status === 'partial')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">Sebagian</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">Belum Bayar</span>
                                @endif
                                @if($billing->is_waived)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(139,109,11,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.3);">Dibebaskan</span>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 mt-3 text-sm">
                            <div>
                                <span class="block text-xs" style="color:#a3abb0;">Total</span>
                                <span style="color:#161e2d;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-xs" style="color:#a3abb0;">Terbayar</span>
                                <span style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-xs" style="color:#a3abb0;">Sisa</span>
                                <span style="{{ $billing->outstanding > 0 ? 'color:#c0453b;font-weight:600;' : 'color:#a3abb0;' }}">Rp {{ number_format($billing->outstanding, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-xs" style="color:#a3abb0;">Security / Sampah / Kas RT</span>
                                <span class="font-mono text-xs" style="color:#a3abb0;">
                                    Rp {{ number_format($billing->ipl_security_amount, 0, ',', '.') }} / Rp {{ number_format($billing->ipl_garbage_amount, 0, ',', '.') }} / Rp {{ number_format($billing->ipl_kas_rt_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if($billing->status !== 'paid')
                                <div class="flex gap-2">
                                    <button wire:click="openPayment({{ $billing->id }})"
                                        class="flex-1 inline-flex items-center justify-center py-2 rounded-lg text-sm font-semibold transition-colors"
                                        style="background:#1563df;color:#ffffff;"
                                        onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">Bayar</button>
                                    <button wire:click="openWaive({{ $billing->id }})"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                                        style="background:#f7f7f7;color:#161e2d;border:1px solid rgba(21,99,223,0.3);"
                                        onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">Bebaskan</button>
                                </div>
                            @elseif($billing->is_waived)
                                <button wire:click="cancelWaive({{ $billing->id }})"
                                    wire:confirm="Batalkan pembebasan? Tunggakan akan kembali aktif."
                                    class="w-full inline-flex items-center justify-center py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">Batalkan Pembebasan</button>
                            @else
                                <span class="block text-center text-xs font-medium" style="color:#12805c;">✓ Lunas</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-14 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#1563df"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="font-medium text-sm" style="color:#a3abb0;">Belum ada tagihan untuk periode ini</p>
                            <p class="text-xs mt-1" style="color:#a3abb0;">Klik "Generate Tagihan" untuk membuat tagihan otomatis.</p>
                    </div>
                @endforelse
            </div>
            @if($billings->hasPages())
                <div class="px-4 py-3" style="border-top:1px solid #f7f7f7;">{{ $billings->links() }}</div>
            @endif
        </div>
        @else
            <div class="rounded-2xl px-4 py-8 text-center" style="background:rgba(21,99,223,0.05);border:1px solid rgba(21,99,223,0.15);">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="#1563df"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="font-medium" style="color:#161e2d;">Pilih atau buat periode IPL terlebih dahulu</p>
            </div>
        @endif
    </div>

    {{-- Period Modal --}}
    @if($isPeriodModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data x-on:keydown.escape.window="$wire.closePeriodModal()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closePeriodModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #d9d9d9;max-height:90vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#f7f7f7;border-bottom:1px solid rgba(21,99,223,0.35);">
                <h3 class="font-bold" style="color:#161e2d;font-family:'Manrope',serif;">{{ $editingPeriodId ? 'Edit Periode IPL' : 'Buat Periode IPL Baru' }}</h3>
                <button wire:click="closePeriodModal()" class="p-1 rounded-lg transition-colors" style="color:#161e2d;"
                    onmouseover="this.style.background='rgba(21,99,223,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @if(session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">{{ session('modal_error') }}</div>
            @endif
            <form wire:submit="savePeriod" class="overflow-y-auto px-6 py-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Tahun <span style="color:#c0453b;">*</span></label>
                        <input type="number" wire:model="periodYear" min="2020" max="2100"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('periodYear')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Bulan <span style="color:#c0453b;">*</span></label>
                        <select wire:model="periodMonth"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $v => $l)
                                <option value="{{ $v }}">{{ $l }}</option>
                            @endforeach
                        </select>
                        @error('periodMonth')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Iuran Security (Rp) <span style="color:#c0453b;">*</span></label>
                        <input type="number" wire:model="periodSecurityAmount" min="0" step="1000"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('periodSecurityAmount')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Biaya Sampah (Rp) <span style="color:#c0453b;">*</span></label>
                        <input type="number" wire:model="periodGarbageAmount" min="0" step="1000"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('periodGarbageAmount')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Iuran Kas RT (Rp) <span style="color:#c0453b;">*</span></label>
                        <input type="number" wire:model="periodKasRtAmount" min="0" step="1000"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('periodKasRtAmount')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                </div>
                {{-- Extra tariff rates --}}
                @if(count($extraTariffRates) > 0)
                <div class="rounded-xl p-4 space-y-3" style="background:#ffffff;border:1px solid #e4e4e4;">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">Biaya Tambahan</p>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($extraTariffRates as $typeId => $amount)
                            @php $tariffType = $extraTariffTypes->firstWhere('id', $typeId) @endphp
                            <div>
                                <label class="block text-xs font-medium mb-1" style="color:#5c6368;">{{ $tariffType?->name ?? 'Tarif #'.$typeId }} (Rp)</label>
                                <input type="number" wire:model="extraTariffRates.{{ $typeId }}" min="0" step="1000"
                                    style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                @error("extraTariffRates.{$typeId}")<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Catatan</label>
                    <textarea wire:model="periodNotes" rows="2"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closePeriodModal()"
                        class="px-4 py-2 text-sm rounded-xl transition-colors font-medium"
                        style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;"
                        onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                        style="background:#1563df;color:#ffffff;"
                        onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
                        <span wire:loading.remove>Simpan</span>
                        <span wire:loading class="flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Payment Modal --}}
    @if($isPaymentModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data x-on:keydown.escape.window="$wire.closePaymentModal()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closePaymentModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #d9d9d9;max-height:90vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:linear-gradient(135deg,#e3f1ea,#e3f1ea);border-bottom:1px solid rgba(18,128,92,0.35);">
                <h3 class="font-bold" style="color:#12805c;font-family:'Manrope',serif;">Catat Pembayaran IPL</h3>
                <button wire:click="closePaymentModal()" class="p-1 rounded-lg transition-colors" style="color:#12805c;"
                    onmouseover="this.style.background='rgba(18,128,92,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto">
                @if($payingBilling)
                    <div class="mx-6 mt-4 rounded-xl p-4 text-sm" style="background:#ffffff;border:1px solid #e4e4e4;">
                        <div class="font-semibold text-base" style="color:#161e2d;">{{ $payingBilling->responsibleResident?->name ?? '(Tanpa Penanggung Jawab)' }}</div>
                        <div class="text-xs mt-0.5" style="color:#a3abb0;">Blok: <span class="font-mono font-bold" style="color:#161e2d;">{{ $payingBilling->houseBlock?->block_code ?? '—' }}</span></div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-3">
                            <div class="rounded-lg p-2.5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                                <div class="text-xs" style="color:#a3abb0;">Tagihan Security</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#161e2d;">Rp {{ number_format($payingBilling->ipl_security_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-lg p-2.5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                                <div class="text-xs" style="color:#a3abb0;">Tagihan Sampah</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#161e2d;">Rp {{ number_format($payingBilling->ipl_garbage_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-lg p-2.5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                                <div class="text-xs" style="color:#a3abb0;">Tagihan Kas RT</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#161e2d;">Rp {{ number_format($payingBilling->ipl_kas_rt_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-lg p-2.5" style="background:rgba(18,128,92,0.05);border:1px solid rgba(18,128,92,0.2);">
                                <div class="text-xs" style="color:#12805c;">Terbayar Security</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#12805c;">Rp {{ number_format($payingBilling->paid_security, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-lg p-2.5" style="background:rgba(18,128,92,0.05);border:1px solid rgba(18,128,92,0.2);">
                                <div class="text-xs" style="color:#12805c;">Terbayar Sampah</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#12805c;">Rp {{ number_format($payingBilling->paid_garbage, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-lg p-2.5" style="background:rgba(18,128,92,0.05);border:1px solid rgba(18,128,92,0.2);">
                                <div class="text-xs" style="color:#12805c;">Terbayar Kas RT</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#12805c;">Rp {{ number_format($payingBilling->paid_kas_rt, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($errors->any() || session('modal_error'))
                    <div class="mx-6 mt-3 rounded-xl p-3 text-sm" style="background:rgba(199,125,26,0.1);border:1px solid rgba(199,125,26,0.3);color:#c77d1a;">
                        @if(session('modal_error'))
                            {{ session('modal_error') }}
                        @else
                            <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        @endif
                    </div>
                @endif

                <form wire:submit="savePayment" class="p-6 space-y-4">

                    {{-- Multi-month selector --}}
                    <div class="rounded-xl p-4 space-y-3" style="background:#ffffff;border:1px solid #e4e4e4;">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">Jumlah Bulan</p>
                            @if($paymentMonths > 1)
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(21,99,223,0.15);color:#161e2d;">Multi-bulan aktif</span>
                            @endif
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            @foreach([1, 3, 6, 12] as $n)
                                <button type="button" wire:click="$set('paymentMonths', {{ $n }})"
                                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                                    style="{{ $paymentMonths == $n ? 'background:#1563df;color:#ffffff;' : 'background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;' }}"
                                    @if($paymentMonths != $n) onmouseover="this.style.background='#e4e4e4';this.style.color='#1563df'" onmouseout="this.style.background='#f7f7f7';this.style.color='#a3abb0'" @endif>
                                    {{ $n == 1 ? '1 bulan' : $n . ' bln' }}
                                </button>
                            @endforeach
                            <div class="flex items-center gap-1.5">
                                <input type="number" wire:model.live="paymentMonths" min="1" max="12"
                                    class="w-16 text-center text-xs font-mono"
                                    style="background:#f7f7f7;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.5rem;padding:0.375rem 0.5rem;outline:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                <span class="text-xs" style="color:#a3abb0;">bln</span>
                            </div>
                        </div>

                        {{-- Direction — only when multi-month --}}
                        @if($paymentMonths > 1)
                        <div class="flex gap-2 pt-1">
                            <button type="button" wire:click="$set('paymentDirection', 'forward')"
                                class="flex-1 py-2 rounded-lg text-xs font-medium transition-colors text-center"
                                style="{{ $paymentDirection === 'forward' ? 'background:rgba(21,99,223,0.2);color:#161e2d;border:1px solid rgba(21,99,223,0.4);' : 'background:#ffffff;color:#a3abb0;border:1px solid #e4e4e4;' }}">
                                ➡ Ke Depan (Muka)
                            </button>
                            <button type="button" wire:click="$set('paymentDirection', 'backward')"
                                class="flex-1 py-2 rounded-lg text-xs font-medium transition-colors text-center"
                                style="{{ $paymentDirection === 'backward' ? 'background:rgba(192,69,59,0.15);color:#c0453b;border:1px solid rgba(192,69,59,0.35);' : 'background:#ffffff;color:#a3abb0;border:1px solid #e4e4e4;' }}">
                                ⬅ Ke Belakang (Tunggakan)
                            </button>
                        </div>
                        @endif
                    </div>

                    {{-- Multi-month preview table --}}
                    @if($paymentMonths > 1 && count($multiMonthPreview) > 0)
                    <div class="rounded-xl overflow-hidden" style="border:1px solid #e4e4e4;">
                        <div class="px-4 py-2" style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                            <span class="text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Rincian Bulan yang Dicakup</span>
                        </div>
                        <div>
                            @foreach($multiMonthPreview as $row)
                            <div class="flex items-center justify-between px-4 py-2" style="border-bottom:1px solid #f7f7f7;">
                                <div class="flex items-center gap-2">
                                    @if($row['status'] === 'paid')
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#12805c;"></span>
                                    @elseif($row['status'] === 'no_billing')
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#d9d9d9;"></span>
                                    @elseif($row['status'] === 'partial')
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#c77d1a;"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#1563df;"></span>
                                    @endif
                                    <span class="text-sm" style="color:#161e2d;">{{ $row['label'] }}</span>
                                    @if(!$row['period_exists'] && $row['status'] !== 'no_billing')
                                        <span class="text-xs px-1 py-0.5 rounded" style="background:rgba(21,99,223,0.1);color:#161e2d;">baru</span>
                                    @endif
                                </div>
                                <div class="text-right text-xs">
                                    @if($row['status'] === 'paid')
                                        <span style="color:#12805c;">Sudah lunas</span>
                                    @elseif($row['status'] === 'no_billing')
                                        <span style="color:#a3abb0;">Tidak ada tagihan</span>
                                    @else
                                        <span class="font-mono font-semibold" style="color:#161e2d;">Rp {{ number_format($row['outstanding'], 0, ',', '.') }}</span>
                                        @if($row['status'] === 'partial')
                                            <div style="color:#c77d1a;">Sebagian terbayar</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between px-4 py-3" style="background:#ffffff;">
                            <span class="text-sm font-semibold" style="color:#5c6368;">Total yang akan dibayar</span>
                            <span class="text-base font-bold font-mono" style="color:#161e2d;">Rp {{ number_format($multiMonthTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Tanggal Bayar <span style="color:#c0453b;">*</span></label>
                        <input type="date" wire:model="paymentDate"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;color-scheme:dark;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('paymentDate')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Single-month: individual amount fields --}}
                    @if($paymentMonths <= 1)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Security (Rp) <span style="color:#c0453b;">*</span></label>
                            <input type="number" wire:model="paymentAmountSecurity" min="0" step="1000"
                                style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            @error('paymentAmountSecurity')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Sampah (Rp) <span style="color:#c0453b;">*</span></label>
                            <input type="number" wire:model="paymentAmountGarbage" min="0" step="1000"
                                style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            @error('paymentAmountGarbage')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Kas RT (Rp) <span style="color:#c0453b;">*</span></label>
                            <input type="number" wire:model="paymentAmountKasRt" min="0" step="1000"
                                style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            @error('paymentAmountKasRt')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Extra charge payments --}}
                    @if(count($extraChargePayments) > 0)
                    <div class="rounded-xl p-4 space-y-3" style="background:#ffffff;border:1px solid #e4e4e4;">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">Biaya Tambahan</p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($extraChargePayments as $typeId => $amount)
                                @php $chargeItem = $payingBilling?->chargeItems->firstWhere('ipl_tariff_type_id', $typeId) @endphp
                                <div>
                                    <label class="block text-xs font-medium mb-1" style="color:#5c6368;">
                                        {{ $chargeItem?->tariffType?->name ?? 'Biaya #'.$typeId }} (Rp)
                                        @if($chargeItem)
                                            <span class="ml-1 text-xs" style="color:#a3abb0;">/ Rp {{ number_format($chargeItem->billed_amount, 0, ',', '.') }}</span>
                                        @endif
                                    </label>
                                    <input type="number" wire:model="extraChargePayments.{{ $typeId }}" min="0" step="1000"
                                        style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Metode Pembayaran</label>
                        <select wire:model="paymentMethod"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            <option value="cash">Cash / Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#5c6368;">No. Referensi</label>
                            <input type="text" wire:model="paymentReference" placeholder="Opsional"
                                style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Diterima Oleh</label>
                            <input type="text" wire:model="paymentReceivedBy" placeholder="Nama penerima"
                                style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Catatan</label>
                        <textarea wire:model="paymentNotes" rows="2"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closePaymentModal()"
                            class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                            style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;"
                            onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">Batal</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                            style="background:#12805c;color:#161e2d;"
                            onmouseover="this.style.background='#12805c'" onmouseout="this.style.background='#12805c'">
                            <span wire:loading.remove>
                                @if($paymentMonths > 1)
                                    Bayar {{ $paymentMonths }} Bulan
                                @else
                                    Simpan Pembayaran
                                @endif
                            </span>
                            <span wire:loading class="flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Checklist Payment Modal (Bayar per Penghuni) --}}
    @if($isChecklistPaymentOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data x-on:keydown.escape.window="$wire.closeChecklistPayment()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeChecklistPayment()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col" style="background:#ffffff;border:1px solid #d9d9d9;max-height:90vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#f7f7f7;border-bottom:1px solid #161e2d;">
                <h3 class="font-bold text-white" style="font-family:'Manrope',serif;">Bayar IPL per Penghuni</h3>
                <button wire:click="closeChecklistPayment()" class="p-1 rounded-lg transition-colors text-white/70 hover:text-white"
                    onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto">
                @if(session('modal_error'))
                    <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">{{ session('modal_error') }}</div>
                @endif

                <form wire:submit="saveChecklistPayment" class="p-6 space-y-4">
                    {{-- Resident selector --}}
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Pilih Penghuni <span style="color:#c0453b;">*</span></label>
                        <select wire:model.live="checklistResidentId"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            <option value="">— Pilih penghuni —</option>
                            @foreach($residentsList as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @error('checklistResidentId')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Billings checklist --}}
                    @if($checklistResidentId)
                        <div class="rounded-xl overflow-hidden" style="border:1px solid #e4e4e4;">
                            <div class="flex items-center gap-3 px-4 py-3" style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                                <input type="checkbox" wire:click="toggleChecklistAll"
                                    style="width:1rem;height:1rem;accent-color:#161e2d;"
                                    {{ count($checklistSelectedIds) > 0 && count($checklistSelectedIds) === $checklistBillings->count() ? 'checked' : '' }}>
                                <span class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">
                                    Pilih Semua
                                    <span class="ml-1 font-normal" style="color:#a3abb0;">({{ $checklistBillings->count() }} tagihan)</span>
                                </span>
                            </div>

                            @if($checklistBillings->isEmpty())
                                <div class="px-4 py-8 text-center">
                                    <p class="text-sm" style="color:#a3abb0;">Tidak ada tagihan yang perlu dibayar untuk penghuni ini.</p>
                                </div>
                            @else
                                <div class="divide-y" style="border-color:#f7f7f7;">
                                    @foreach($checklistBillings as $billing)
                                        @php
                                            $outstanding = $billing->outstanding;
                                        @endphp
                                        <label class="flex items-center gap-3 px-4 py-3 cursor-pointer transition-colors"
                                            style="background:{{ in_array($billing->id, $checklistSelectedIds) ? 'rgba(18,128,92,0.04)' : '' }};"
                                            onmouseover="this.style.backgroundColor='#f7f7f7'" onmouseout="this.style.backgroundColor='{{ in_array($billing->id, $checklistSelectedIds) ? 'rgba(18,128,92,0.04)' : '' }}'">
                                            <input type="checkbox" wire:model="checklistSelectedIds" value="{{ $billing->id }}"
                                                style="width:1rem;height:1rem;accent-color:#161e2d;">
                                            <div class="flex-1 min-w-0 flex items-center gap-3">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-bold shrink-0"
                                                    style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                                                    {{ $billing->houseBlock?->block_code ?? '—' }}
                                                </span>
                                                <span class="text-sm font-medium truncate" style="color:#161e2d;">{{ $billing->period?->period_label ?? '—' }}</span>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-sm font-semibold" style="color:#161e2d;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</div>
                                                @if($outstanding > 0)
                                                    <div class="text-xs" style="color:#c0453b;">Sisa: Rp {{ number_format($outstanding, 0, ',', '.') }}</div>
                                                @else
                                                    <div class="text-xs" style="color:#12805c;">Lunas</div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @error('checklistSelectedIds')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    @else
                        <div class="rounded-xl px-4 py-8 text-center" style="background:#ffffff;border:1px solid #e4e4e4;">
                            <p class="text-sm" style="color:#a3abb0;">Pilih penghuni terlebih dahulu untuk melihat tagihan yang perlu dibayar.</p>
                        </div>
                    @endif

                    {{-- Payment details --}}
                    @if(count($checklistSelectedIds) > 0)
                        <div class="rounded-xl p-4 space-y-3" style="background:#ffffff;border:1px solid #e4e4e4;">
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">Detail Pembayaran</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Tanggal Bayar <span style="color:#c0453b;">*</span></label>
                                    <input type="date" wire:model="checkPayDate"
                                        style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;color-scheme:dark;"
                                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                    @error('checkPayDate')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Metode <span style="color:#c0453b;">*</span></label>
                                    <select wire:model="checkPayMethod"
                                        style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                        <option value="cash">Cash / Tunai</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">No. Referensi</label>
                                    <input type="text" wire:model="checkPayReference" placeholder="Opsional"
                                        style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Diterima Oleh</label>
                                    <input type="text" wire:model="checkPayReceivedBy" placeholder="Nama penerima"
                                        style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Catatan</label>
                                <textarea wire:model="checkPayNotes" rows="2"
                                    style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'"></textarea>
                            </div>
                        </div>

                        @php
                            $selectedTotal = $checklistBillings->whereIn('id', $checklistSelectedIds)->sum('outstanding');
                        @endphp
                        <div class="flex items-center justify-between rounded-xl px-4 py-3" style="background:rgba(18,128,92,0.06);border:1px solid rgba(18,128,92,0.2);">
                            <span class="text-sm font-semibold" style="color:#12805c;">Total yang akan dibayar ({{ count($checklistSelectedIds) }} tagihan)</span>
                            <span class="text-lg font-bold font-mono" style="color:#12805c;">Rp {{ number_format($selectedTotal, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeChecklistPayment()"
                            class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                            style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;"
                            onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">Batal</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                            style="background:#161e2d;color:#ffffff;"
                            onmouseover="this.style.background='#1563df'" onmouseout="this.style.background='#161e2d'"
                            {{ count($checklistSelectedIds) === 0 ? 'disabled' : '' }}>
                            <span wire:loading.remove>
                                Bayar {{ count($checklistSelectedIds) }} Tagihan
                            </span>
                            <span wire:loading class="flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Pemutihan / Pembebasan Tunggakan --}}
    @if($isWaiveModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data x-on:keydown.escape.window="$wire.closeWaiveModal()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeWaiveModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #d9d9d9;max-height:90vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:linear-gradient(135deg,#f7f7f7,#f7f7f7);border-bottom:1px solid rgba(21,99,223,0.4);">
                <h3 class="font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Bebaskan Tunggakan (Pemutihan)</h3>
                <button wire:click="closeWaiveModal()" class="p-1 rounded-lg transition-colors" style="color:#161e2d;"
                    onmouseover="this.style.background='rgba(21,99,223,0.15)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto">
                @if($waivingBilling)
                    <div class="mx-6 mt-4 rounded-xl p-4 text-sm" style="background:#ffffff;border:1px solid #e4e4e4;">
                        <div class="font-semibold text-base" style="color:#161e2d;">{{ $waivingBilling->responsibleResident?->name ?? '(Tanpa Penanggung Jawab)' }}</div>
                        <div class="text-xs mt-0.5" style="color:#a3abb0;">
                            Blok: <span class="font-mono font-bold" style="color:#161e2d;">{{ $waivingBilling->houseBlock?->block_code ?? '—' }}</span>
                            &middot; Periode: <span class="font-medium" style="color:#161e2d;">{{ $waivingBilling->period?->period_label ?? '—' }}</span>
                        </div>
                    </div>
                @endif

                <div class="mx-6 mt-3 rounded-xl p-3 text-xs" style="background:rgba(21,99,223,0.08);border:1px solid rgba(21,99,223,0.3);color:#161e2d;">
                    Pembebasan menutup tunggakan tanpa dicatat sebagai pemasukan kas (piutang dihapus, bukan pendapatan). Wajib mencantumkan alasan untuk audit.
                </div>

                @if(session('modal_error'))
                    <div class="mx-6 mt-3 rounded-xl p-3 text-sm" style="background:rgba(199,125,26,0.1);border:1px solid rgba(199,125,26,0.3);color:#c77d1a;">
                        {{ session('modal_error') }}
                    </div>
                @endif

                <form wire:submit="saveWaive" class="p-6 space-y-4">
                    @if($waivingBilling)
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">Komponen yang Dibebaskan</p>
                        @php
                            $waiveRows = [
                                ['label' => 'Keamanan', 'model' => 'waiveSecurity', 'rem' => $waivingBilling->remainingSecurity()],
                                ['label' => 'Sampah',   'model' => 'waiveGarbage',  'rem' => $waivingBilling->remainingGarbage()],
                                ['label' => 'Kas RT',   'model' => 'waiveKasRt',    'rem' => $waivingBilling->remainingKasRt()],
                            ];
                        @endphp
                        @foreach($waiveRows as $row)
                            <label class="flex items-center justify-between gap-3 rounded-xl px-4 py-3 cursor-pointer {{ $row['rem'] <= 0 ? 'opacity-50' : '' }}"
                                style="background:#ffffff;border:1px solid #e4e4e4;">
                                <span class="flex items-center gap-2.5">
                                    <input type="checkbox" wire:model="{{ $row['model'] }}" @disabled($row['rem'] <= 0)
                                        style="width:1rem;height:1rem;accent-color:#161e2d;">
                                    <span class="text-sm font-medium" style="color:#161e2d;">{{ $row['label'] }}</span>
                                </span>
                                <span class="text-sm font-mono font-semibold" style="color:{{ $row['rem'] > 0 ? '#c0453b' : '#a3abb0' }};">
                                    @if($row['rem'] > 0) Rp {{ number_format($row['rem'], 0, ',', '.') }} @else lunas @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Alasan Pembebasan <span style="color:#c0453b;">*</span></label>
                        <textarea wire:model="waiverReason" rows="3" placeholder="mis. Warga kurang mampu, disetujui rapat RT 5 Juli 2026"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'"></textarea>
                        @error('waiverReason')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeWaiveModal()"
                            class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                            style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;"
                            onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">Batal</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                            style="background:#1563df;color:#ffffff;"
                            onmouseover="this.style.background='#5c6368'" onmouseout="this.style.background='#0e49a6'">
                            <span wire:loading.remove>Bebaskan Tunggakan</span>
                            <span wire:loading class="flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
