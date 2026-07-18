<div>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

        @if(session('success') && !$isPaymentModalOpen && !$isPeriodModalOpen)
            <div class="rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header Banner --}}
        <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">IPL — Iuran Perumahan</h3>
                    <p class="text-sm mt-1" style="color:#17231E;">Kelola iuran bulanan penghuni perumahan</p>
                </div>
                <div class="flex gap-2">
                    @if($currentPeriod)
                    <button wire:click="generateBillings({{ $currentPeriod->id }})"
                            wire:confirm="Generate tagihan untuk semua unit aktif di periode {{ $currentPeriod->period_label }}?"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                            style="background:rgba(22,74,64,0.15);color:#17231E;border:1px solid rgba(22,74,64,0.3);"
                            onmouseover="this.style.background='rgba(22,74,64,0.25)'" onmouseout="this.style.background='rgba(22,74,64,0.15)'">
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
                        style="background:#17231E;color:#ffffff;"
                        onmouseover="this.style.background='#164A40'" onmouseout="this.style.background='#17231E'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Bayar per Penghuni
                    </button>
                    <button wire:click="openCreatePeriod()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Periode Baru
                    </button>
                </div>
            </div>
        </div>

        {{-- Period Selectors --}}
        <div class="rounded-2xl p-4" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="flex flex-wrap gap-3 items-center">
                <span class="text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Periode:</span>
                <select wire:model.live="periodFilterMonth" wire:change="selectByPeriod"
                    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;min-width:140px;"
                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @foreach($months as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select wire:model.live="periodFilterYear" wire:change="selectByPeriod"
                    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;min-width:110px;"
                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                @if($currentPeriod)
                    <span class="text-xs px-2 py-1 rounded-lg font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                        {{ $currentPeriod->period_label }}
                    </span>
                @else
                    <span class="text-xs italic" style="color:#909A8F;">Belum ada periode untuk bulan/tahun ini.</span>
                @endif
            </div>
        </div>

        @if($currentPeriod)
        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Total Tagihan</p>
                <p class="text-xl font-bold mt-1 truncate" style="color:#17231E;">Rp {{ number_format($summary['total_tagihan'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs mt-1" style="color:#909A8F;">{{ ($summary['jumlah_lunas'] ?? 0) + ($summary['jumlah_belum'] ?? 0) + ($summary['jumlah_sebagian'] ?? 0) }} unit</p>
            </div>
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Sudah Terbayar</p>
                <p class="text-xl font-bold mt-1 truncate" style="color:#12805c;">Rp {{ number_format($summary['total_terbayar'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs mt-1" style="color:#909A8F;">{{ $summary['jumlah_lunas'] ?? 0 }} unit lunas</p>
            </div>
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Tunggakan</p>
                <p class="text-xl font-bold mt-1 truncate" style="color:#B0402C;">Rp {{ number_format($summary['total_tunggakan'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs mt-1" style="color:#909A8F;">{{ $summary['jumlah_belum'] ?? 0 }} belum, {{ $summary['jumlah_sebagian'] ?? 0 }} sebagian</p>
                @if(($summary['total_dibebaskan'] ?? 0) > 0)
                    <p class="text-xs mt-0.5" style="color:#17231E;">Dibebaskan: Rp {{ number_format($summary['total_dibebaskan'], 0, ',', '.') }} (non-kas)</p>
                @endif
            </div>
            <div class="rounded-2xl p-5" style="background:rgba(22,74,64,0.05);border:1px solid rgba(22,74,64,0.2);">
                <p class="text-xs font-medium uppercase tracking-wide" style="color:#17231E;">Tarif Periode</p>
                <p class="text-sm font-semibold mt-1" style="color:#17231E;">Security: Rp {{ number_format($currentPeriod->ipl_security_amount, 0, ',', '.') }}</p>
                <p class="text-sm" style="color:#17231E;">Sampah: Rp {{ number_format($currentPeriod->ipl_garbage_amount, 0, ',', '.') }}</p>
                <p class="text-sm" style="color:#17231E;">Kas RT: Rp {{ number_format($currentPeriod->ipl_kas_rt_amount, 0, ',', '.') }}</p>
                <button wire:click="openEditPeriod({{ $currentPeriod->id }})" class="text-xs mt-1 hover:underline" style="color:#17231E;">Edit Tarif</button>
            </div>
        </div>

        {{-- Filters + Link --}}
        <div class="flex flex-wrap gap-3 items-center">
            <select wire:model.live="filterBillingStatus"
                style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;"
                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                <option value="">Semua Status</option>
                <option value="unpaid">Belum Bayar</option>
                <option value="partial">Sebagian</option>
                <option value="paid">Lunas</option>
            </select>
            <x-searchable-select model="filterBillingBlock" :options="$houseBlocks->pluck('block_code', 'id')"
                placeholder="Semua Blok" searchPlaceholder="Cari blok..." />
            <a href="{{ route('ipl.report') }}" wire:navigate
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;"
                onmouseover="this.style.color='#164A40';this.style.borderColor='rgba(22,74,64,0.3)'" onmouseout="this.style.color='#586359';this.style.borderColor='#E0DFD4'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Laporan IPL
            </a>
        </div>

        {{-- Billings Table --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Blok</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Penghuni</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#909A8F;">Security</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#909A8F;">Sampah</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#909A8F;">Kas RT</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Total</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#909A8F;">Terbayar</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#909A8F;">Sisa</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Status</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($billings as $billing)
                            <tr style="border-bottom:1px solid #F1F3EC;" wire:key="billing-{{ $billing->id }}"
                                onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                <td class="px-4 py-3">
                                    @if($billing->houseBlock)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-bold"
                                            style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                            {{ $billing->houseBlock->block_code }}
                                        </span>
                                    @else
                                        <span style="color:#909A8F;">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 font-medium" style="color:#17231E;">{{ $billing->responsibleResident?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden sm:table-cell" style="color:#909A8F;">Rp {{ number_format($billing->ipl_security_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden sm:table-cell" style="color:#909A8F;">Rp {{ number_format($billing->ipl_garbage_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden sm:table-cell" style="color:#909A8F;">Rp {{ number_format($billing->ipl_kas_rt_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-sm font-semibold" style="color:#17231E;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden md:table-cell" style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-mono text-xs hidden md:table-cell" style="{{ $billing->outstanding > 0 ? 'color:#B0402C;font-weight:600;' : 'color:#909A8F;' }}">
                                    Rp {{ number_format($billing->outstanding, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap items-center gap-1">
                                        @if($billing->status === 'paid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Lunas</span>
                                        @elseif($billing->status === 'partial')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">Sebagian</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">Belum Bayar</span>
                                        @endif
                                        @if($billing->is_waived)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" title="{{ $billing->waiver_reason }}" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.3);">Dibebaskan</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">
                                        @if($billing->status !== 'paid')
                                            <button wire:click="openPayment({{ $billing->id }})"
                                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold transition-colors"
                                                style="background:#164A40;color:#ffffff;"
                                                onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">Bayar</button>
                                            <button wire:click="openWaive({{ $billing->id }})"
                                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold transition-colors"
                                                style="background:#F1F3EC;color:#17231E;border:1px solid rgba(22,74,64,0.3);"
                                                onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Bebaskan</button>
                                        @elseif($billing->is_waived)
                                            <button wire:click="cancelWaive({{ $billing->id }})"
                                                wire:confirm="Batalkan pembebasan? Tunggakan akan kembali aktif."
                                                class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;"
                                                onmouseover="this.style.color='#B0402C'" onmouseout="this.style.color='#909A8F'">Batalkan</button>
                                        @else
                                            <span class="text-xs font-medium" style="color:#12805c;">✓ Lunas</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-14 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="font-medium text-sm" style="color:#909A8F;">Belum ada tagihan untuk periode ini</p>
                                    <p class="text-xs mt-1" style="color:#909A8F;">Klik "Generate Tagihan" untuk membuat tagihan otomatis.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                @forelse($billings as $billing)
                    <div class="px-4 py-4" wire:key="bill-card-{{ $billing->id }}">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                @if($billing->houseBlock)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-bold shrink-0"
                                        style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                        {{ $billing->houseBlock->block_code }}
                                    </span>
                                @else
                                    <span style="color:#909A8F;">—</span>
                                @endif
                                <span class="font-medium truncate" style="color:#17231E;">{{ $billing->responsibleResident?->name ?? '—' }}</span>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                @if($billing->status === 'paid')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Lunas</span>
                                @elseif($billing->status === 'partial')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">Sebagian</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">Belum Bayar</span>
                                @endif
                                @if($billing->is_waived)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.3);">Dibebaskan</span>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 mt-3 text-sm">
                            <div>
                                <span class="block text-xs" style="color:#909A8F;">Total</span>
                                <span style="color:#17231E;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-xs" style="color:#909A8F;">Terbayar</span>
                                <span style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-xs" style="color:#909A8F;">Sisa</span>
                                <span style="{{ $billing->outstanding > 0 ? 'color:#B0402C;font-weight:600;' : 'color:#909A8F;' }}">Rp {{ number_format($billing->outstanding, 0, ',', '.') }}</span>
                            </div>
                            <div>
                                <span class="block text-xs" style="color:#909A8F;">Security / Sampah / Kas RT</span>
                                <span class="font-mono text-xs" style="color:#909A8F;">
                                    Rp {{ number_format($billing->ipl_security_amount, 0, ',', '.') }} / Rp {{ number_format($billing->ipl_garbage_amount, 0, ',', '.') }} / Rp {{ number_format($billing->ipl_kas_rt_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            @if($billing->status !== 'paid')
                                <div class="flex gap-2">
                                    <button wire:click="openPayment({{ $billing->id }})"
                                        class="flex-1 inline-flex items-center justify-center py-2 rounded-lg text-sm font-semibold transition-colors"
                                        style="background:#164A40;color:#ffffff;"
                                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">Bayar</button>
                                    <button wire:click="openWaive({{ $billing->id }})"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                                        style="background:#F1F3EC;color:#17231E;border:1px solid rgba(22,74,64,0.3);"
                                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Bebaskan</button>
                                </div>
                            @elseif($billing->is_waived)
                                <button wire:click="cancelWaive({{ $billing->id }})"
                                    wire:confirm="Batalkan pembebasan? Tunggakan akan kembali aktif."
                                    class="w-full inline-flex items-center justify-center py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">Batalkan Pembebasan</button>
                            @else
                                <span class="block text-center text-xs font-medium" style="color:#12805c;">✓ Lunas</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-14 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="font-medium text-sm" style="color:#909A8F;">Belum ada tagihan untuk periode ini</p>
                            <p class="text-xs mt-1" style="color:#909A8F;">Klik "Generate Tagihan" untuk membuat tagihan otomatis.</p>
                    </div>
                @endforelse
            </div>
            @if($billings->hasPages())
                <div class="px-4 py-3" style="border-top:1px solid #F1F3EC;">{{ $billings->links() }}</div>
            @endif
        </div>
        @else
            <div class="rounded-2xl px-4 py-8 text-center" style="background:rgba(22,74,64,0.05);border:1px solid rgba(22,74,64,0.15);">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="font-medium" style="color:#17231E;">Pilih atau buat periode IPL terlebih dahulu</p>
            </div>
        @endif
    </div>

    {{-- Period Modal --}}
    @if($isPeriodModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-data x-on:keydown.escape.window="$wire.closePeriodModal()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closePeriodModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #D8D6C9;max-height:90vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">{{ $editingPeriodId ? 'Edit Periode IPL' : 'Buat Periode IPL Baru' }}</h3>
                <button wire:click="closePeriodModal()" class="p-1 rounded-lg transition-colors" style="color:#17231E;"
                    onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @if(session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">{{ session('modal_error') }}</div>
            @endif
            <form wire:submit="savePeriod" class="overflow-y-auto px-6 py-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Tahun <span style="color:#B0402C;">*</span></label>
                        <input type="number" wire:model="periodYear" min="2020" max="2100"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('periodYear')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Bulan <span style="color:#B0402C;">*</span></label>
                        <select wire:model="periodMonth"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @foreach(['1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus','9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $v => $l)
                                <option value="{{ $v }}">{{ $l }}</option>
                            @endforeach
                        </select>
                        @error('periodMonth')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                </div>
                @if(!$editingPeriodId)
                <div class="rounded-xl p-3 text-xs flex items-start gap-2" style="background:rgba(22,74,64,0.06);border:1px solid rgba(22,74,64,0.2);color:#17231E;">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Biaya utama mengikuti Pengaturan Tarif IPL. Ubah tarif di <strong>Pengaturan Tarif</strong> jika ingin mengganti.</span>
                </div>
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Iuran Security (Rp)</label>
                        <input type="number" wire:model="periodSecurityAmount" min="0" step="1000" {{ !$editingPeriodId ? 'readonly' : '' }}
                            style="background:{{ !$editingPeriodId ? '#F1F3EC' : '#ffffff' }};border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;{{ !$editingPeriodId ? 'cursor:not-allowed;opacity:0.8;' : '' }}">
                        @error('periodSecurityAmount')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Biaya Sampah (Rp)</label>
                        <input type="number" wire:model="periodGarbageAmount" min="0" step="1000" {{ !$editingPeriodId ? 'readonly' : '' }}
                            style="background:{{ !$editingPeriodId ? '#F1F3EC' : '#ffffff' }};border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;{{ !$editingPeriodId ? 'cursor:not-allowed;opacity:0.8;' : '' }}">
                        @error('periodGarbageAmount')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Iuran Kas RT (Rp)</label>
                        <input type="number" wire:model="periodKasRtAmount" min="0" step="1000" {{ !$editingPeriodId ? 'readonly' : '' }}
                            style="background:{{ !$editingPeriodId ? '#F1F3EC' : '#ffffff' }};border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;{{ !$editingPeriodId ? 'cursor:not-allowed;opacity:0.8;' : '' }}">
                        @error('periodKasRtAmount')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                </div>
                {{-- Extra tariff rates --}}
                @if(count($extraTariffRates) > 0)
                <div class="rounded-xl p-4 space-y-3" style="background:#ffffff;border:1px solid #E0DFD4;">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#17231E;">Biaya Tambahan</p>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($extraTariffRates as $typeId => $amount)
                            @php $tariffType = $extraTariffTypes->firstWhere('id', $typeId) @endphp
                            <div>
                                <label class="block text-xs font-medium mb-1" style="color:#586359;">{{ $tariffType?->name ?? 'Tarif #'.$typeId }} (Rp)</label>
                                <input type="number" wire:model="extraTariffRates.{{ $typeId }}" min="0" step="1000" {{ !$editingPeriodId ? 'readonly' : '' }}
                                    style="background:{{ !$editingPeriodId ? '#F1F3EC' : '#ffffff' }};border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;{{ !$editingPeriodId ? 'cursor:not-allowed;opacity:0.8;' : '' }}">
                                @error("extraTariffRates.{$typeId}")<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Catatan</label>
                    <textarea wire:model="periodNotes" rows="2"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closePeriodModal()"
                        class="px-4 py-2 text-sm rounded-xl transition-colors font-medium"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
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
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #D8D6C9;max-height:90vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:linear-gradient(135deg,#E4F1EB,#E4F1EB);border-bottom:1px solid rgba(18,128,92,0.35);">
                <h3 class="font-bold" style="color:#12805c;font-family:'Fraunces',Georgia,serif;">Catat Pembayaran IPL</h3>
                <button wire:click="closePaymentModal()" class="p-1 rounded-lg transition-colors" style="color:#12805c;"
                    onmouseover="this.style.background='rgba(18,128,92,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto">
                @if($payingBilling)
                    <div class="mx-6 mt-4 rounded-xl p-4 text-sm" style="background:#ffffff;border:1px solid #E0DFD4;">
                        <div class="font-semibold text-base" style="color:#17231E;">{{ $payingBilling->responsibleResident?->name ?? '(Tanpa Penanggung Jawab)' }}</div>
                        <div class="text-xs mt-0.5" style="color:#909A8F;">Blok: <span class="font-mono font-bold" style="color:#17231E;">{{ $payingBilling->houseBlock?->block_code ?? '—' }}</span></div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-3">
                            <div class="rounded-lg p-2.5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                                <div class="text-xs" style="color:#909A8F;">Tagihan Security</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#17231E;">Rp {{ number_format($payingBilling->ipl_security_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-lg p-2.5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                                <div class="text-xs" style="color:#909A8F;">Tagihan Sampah</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#17231E;">Rp {{ number_format($payingBilling->ipl_garbage_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="rounded-lg p-2.5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                                <div class="text-xs" style="color:#909A8F;">Tagihan Kas RT</div>
                                <div class="font-semibold text-sm mt-0.5" style="color:#17231E;">Rp {{ number_format($payingBilling->ipl_kas_rt_amount, 0, ',', '.') }}</div>
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
                    <div class="mx-6 mt-3 rounded-xl p-3 text-sm" style="background:rgba(169,116,26,0.1);border:1px solid rgba(169,116,26,0.3);color:#A9741A;">
                        @if(session('modal_error'))
                            {{ session('modal_error') }}
                        @else
                            <ul class="list-disc pl-4 space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        @endif
                    </div>
                @endif

                <form wire:submit="savePayment" class="p-6 space-y-4">

                    {{-- Multi-month selector --}}
                    <div class="rounded-xl p-4 space-y-3" style="background:#ffffff;border:1px solid #E0DFD4;">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color:#17231E;">Jumlah Bulan</p>
                            @if($paymentMonths > 1)
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:rgba(22,74,64,0.15);color:#17231E;">Multi-bulan aktif</span>
                            @endif
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            @foreach([1, 3, 6, 12] as $n)
                                <button type="button" wire:click="$set('paymentMonths', {{ $n }})"
                                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                                    style="{{ $paymentMonths == $n ? 'background:#164A40;color:#ffffff;' : 'background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;' }}"
                                    @if($paymentMonths != $n) onmouseover="this.style.background='#E0DFD4';this.style.color='#164A40'" onmouseout="this.style.background='#F1F3EC';this.style.color='#909A8F'" @endif>
                                    {{ $n == 1 ? '1 bulan' : $n . ' bln' }}
                                </button>
                            @endforeach
                            <div class="flex items-center gap-1.5">
                                <input type="number" wire:model.live="paymentMonths" min="1" max="12"
                                    class="w-16 text-center text-xs font-mono"
                                    style="background:#F1F3EC;border:1px solid #E0DFD4;color:#17231E;border-radius:0.5rem;padding:0.375rem 0.5rem;outline:none;"
                                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                <span class="text-xs" style="color:#909A8F;">bln</span>
                            </div>
                        </div>

                        {{-- Direction — only when multi-month --}}
                        @if($paymentMonths > 1)
                        <div class="flex gap-2 pt-1">
                            <button type="button" wire:click="$set('paymentDirection', 'forward')"
                                class="flex-1 py-2 rounded-lg text-xs font-medium transition-colors text-center"
                                style="{{ $paymentDirection === 'forward' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.4);' : 'background:#ffffff;color:#909A8F;border:1px solid #E0DFD4;' }}">
                                ➡ Ke Depan (Muka)
                            </button>
                            <button type="button" wire:click="$set('paymentDirection', 'backward')"
                                class="flex-1 py-2 rounded-lg text-xs font-medium transition-colors text-center"
                                style="{{ $paymentDirection === 'backward' ? 'background:rgba(176,64,44,0.15);color:#B0402C;border:1px solid rgba(176,64,44,0.35);' : 'background:#ffffff;color:#909A8F;border:1px solid #E0DFD4;' }}">
                                ⬅ Ke Belakang (Tunggakan)
                            </button>
                        </div>
                        @endif
                    </div>

                    {{-- Multi-month preview table --}}
                    @if($paymentMonths > 1 && count($multiMonthPreview) > 0)
                    <div class="rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                        <div class="px-4 py-2" style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                            <span class="text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Rincian Bulan yang Dicakup</span>
                        </div>
                        <div>
                            @foreach($multiMonthPreview as $row)
                            <div class="flex items-center justify-between px-4 py-2" style="border-bottom:1px solid #F1F3EC;">
                                <div class="flex items-center gap-2">
                                    @if($row['status'] === 'paid')
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#12805c;"></span>
                                    @elseif($row['status'] === 'no_billing')
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#D8D6C9;"></span>
                                    @elseif($row['status'] === 'partial')
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#A9741A;"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#164A40;"></span>
                                    @endif
                                    <span class="text-sm" style="color:#17231E;">{{ $row['label'] }}</span>
                                    @if(!$row['period_exists'] && $row['status'] !== 'no_billing')
                                        <span class="text-xs px-1 py-0.5 rounded" style="background:rgba(22,74,64,0.1);color:#17231E;">baru</span>
                                    @endif
                                </div>
                                <div class="text-right text-xs">
                                    @if($row['status'] === 'paid')
                                        <span style="color:#12805c;">Sudah lunas</span>
                                    @elseif($row['status'] === 'no_billing')
                                        <span style="color:#909A8F;">Tidak ada tagihan</span>
                                    @else
                                        <span class="font-mono font-semibold" style="color:#17231E;">Rp {{ number_format($row['outstanding'], 0, ',', '.') }}</span>
                                        @if($row['status'] === 'partial')
                                            <div style="color:#A9741A;">Sebagian terbayar</div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between px-4 py-3" style="background:#ffffff;">
                            <span class="text-sm font-semibold" style="color:#586359;">Total yang akan dibayar</span>
                            <span class="text-base font-bold font-mono" style="color:#17231E;">Rp {{ number_format($multiMonthTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Tanggal Bayar <span style="color:#B0402C;">*</span></label>
                        <input type="date" wire:model="paymentDate"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;color-scheme:dark;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('paymentDate')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Single-month: individual amount fields --}}
                    @if($paymentMonths <= 1)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Security (Rp) <span style="color:#B0402C;">*</span></label>
                            <input type="number" wire:model="paymentAmountSecurity" min="0" step="1000"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @error('paymentAmountSecurity')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Sampah (Rp) <span style="color:#B0402C;">*</span></label>
                            <input type="number" wire:model="paymentAmountGarbage" min="0" step="1000"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @error('paymentAmountGarbage')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Kas RT (Rp) <span style="color:#B0402C;">*</span></label>
                            <input type="number" wire:model="paymentAmountKasRt" min="0" step="1000"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @error('paymentAmountKasRt')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Extra charge payments --}}
                    @if(count($extraChargePayments) > 0)
                    <div class="rounded-xl p-4 space-y-3" style="background:#ffffff;border:1px solid #E0DFD4;">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color:#17231E;">Biaya Tambahan</p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($extraChargePayments as $typeId => $amount)
                                @php $chargeItem = $payingBilling?->chargeItems->firstWhere('ipl_tariff_type_id', $typeId) @endphp
                                <div>
                                    <label class="block text-xs font-medium mb-1" style="color:#586359;">
                                        {{ $chargeItem?->tariffType?->name ?? 'Biaya #'.$typeId }} (Rp)
                                        @if($chargeItem)
                                            <span class="ml-1 text-xs" style="color:#909A8F;">/ Rp {{ number_format($chargeItem->billed_amount, 0, ',', '.') }}</span>
                                        @endif
                                    </label>
                                    <input type="number" wire:model="extraChargePayments.{{ $typeId }}" min="0" step="1000"
                                        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Metode Pembayaran</label>
                        <select wire:model="paymentMethod"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            <option value="cash">Cash / Tunai</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">No. Referensi</label>
                            <input type="text" wire:model="paymentReference" placeholder="Opsional"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Diterima Oleh</label>
                            <input type="text" wire:model="paymentReceivedBy" placeholder="Nama penerima"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Catatan</label>
                        <textarea wire:model="paymentNotes" rows="2"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closePaymentModal()"
                            class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                            style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                            onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                            style="background:#12805c;color:#17231E;"
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
        <div class="relative rounded-2xl shadow-2xl w-full max-w-2xl flex flex-col" style="background:#ffffff;border:1px solid #D8D6C9;max-height:90vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid #17231E;">
                <h3 class="font-bold text-white" style="font-family:'Fraunces',Georgia,serif;">Bayar IPL per Penghuni</h3>
                <button wire:click="closeChecklistPayment()" class="p-1 rounded-lg transition-colors text-white/70 hover:text-white"
                    onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto">
                @if(session('modal_error'))
                    <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">{{ session('modal_error') }}</div>
                @endif

                <form wire:submit="saveChecklistPayment" class="p-6 space-y-4">
                    {{-- Resident selector --}}
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Pilih Penghuni <span style="color:#B0402C;">*</span></label>
                        <select wire:model.live="checklistResidentId"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            <option value="">— Pilih penghuni —</option>
                            @foreach($residentsList as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @error('checklistResidentId')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>

                    {{-- Billings checklist --}}
                    @if($checklistResidentId)
                        <div class="rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                            <div class="flex items-center gap-3 px-4 py-3" style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                                <input type="checkbox" wire:click="toggleChecklistAll"
                                    style="width:1rem;height:1rem;accent-color:#17231E;"
                                    {{ count($checklistSelectedIds) > 0 && count($checklistSelectedIds) === $checklistBillings->count() ? 'checked' : '' }}>
                                <span class="text-xs font-semibold uppercase tracking-wider" style="color:#17231E;">
                                    Pilih Semua
                                    <span class="ml-1 font-normal" style="color:#909A8F;">({{ $checklistBillings->count() }} tagihan)</span>
                                </span>
                            </div>

                            @if($checklistBillings->isEmpty())
                                <div class="px-4 py-8 text-center">
                                    <p class="text-sm" style="color:#909A8F;">Tidak ada tagihan yang perlu dibayar untuk penghuni ini.</p>
                                </div>
                            @else
                                <div class="divide-y" style="border-color:#F1F3EC;">
                                    @foreach($checklistBillings as $billing)
                                        @php
                                            $outstanding = $billing->outstanding;
                                            $remSec  = $billing->remainingSecurity();
                                            $remGarb = $billing->remainingGarbage();
                                            $remKrt  = $billing->remainingKasRt();
                                            $isPartial = isset($checkPartialMode[$billing->id]);
                                        @endphp
                                        <div class="px-4 py-3 transition-colors"
                                            style="background:{{ in_array($billing->id, $checklistSelectedIds) ? 'rgba(18,128,92,0.04)' : '' }};"
                                            onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor='{{ in_array($billing->id, $checklistSelectedIds) ? 'rgba(18,128,92,0.04)' : '' }}'">
                                            <div class="flex items-start gap-3">
                                                <input type="checkbox" wire:model.live="checklistSelectedIds" value="{{ $billing->id }}"
                                                    style="width:1rem;height:1rem;accent-color:#17231E;margin-top:2px;">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono font-bold shrink-0"
                                                            style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                                            {{ $billing->houseBlock?->block_code ?? '—' }}
                                                        </span>
                                                        <span class="text-sm font-medium truncate" style="color:#17231E;">{{ $billing->period?->period_label ?? '—' }}</span>
                                                    </div>
                                                    @if(!$isPartial)
                                                    <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-xs" style="color:#909A8F;">
                                                        @if($remSec > 0)
                                                            <span>Keamanan: <span class="font-mono font-medium" style="color:#17231E;">Rp {{ number_format($remSec, 0, ',', '.') }}</span></span>
                                                        @endif
                                                        @if($remGarb > 0)
                                                            <span>Sampah: <span class="font-mono font-medium" style="color:#17231E;">Rp {{ number_format($remGarb, 0, ',', '.') }}</span></span>
                                                        @endif
                                                        @if($remKrt > 0)
                                                            <span>Kas RT: <span class="font-mono font-medium" style="color:#17231E;">Rp {{ number_format($remKrt, 0, ',', '.') }}</span></span>
                                                        @endif
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="text-right shrink-0">
                                                    @if($isPartial)
                                                        <div class="text-xs font-semibold" style="color:#12805c;">Bayar Sebagian</div>
                                                    @else
                                                    <div class="text-sm font-semibold" style="color:#17231E;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</div>
                                                    @endif
                                                    @if($outstanding > 0)
                                                        <div class="text-xs" style="color:#B0402C;">Sisa: Rp {{ number_format($outstanding, 0, ',', '.') }}</div>
                                                    @else
                                                        <div class="text-xs" style="color:#12805c;">Lunas</div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(in_array($billing->id, $checklistSelectedIds) && $outstanding > 0)
                                            <div class="mt-2 ml-6">
                                                <button type="button" wire:click="toggleCheckPartialMode({{ $billing->id }})"
                                                    class="text-xs font-medium px-2 py-0.5 rounded-md transition-colors"
                                                    style="{{ $isPartial ? 'background:rgba(18,128,92,0.15);color:#12805c;border:1px solid rgba(18,128,92,0.3);' : 'background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;' }}">
                                                    {{ $isPartial ? 'Bayar Full' : 'Bayar Sebagian' }}
                                                </button>
                                            </div>
                                            @endif
                                            @if($isPartial && in_array($billing->id, $checklistSelectedIds))
                                            @php $period = $billing->period; @endphp
                                            <div class="mt-2 ml-6 flex flex-wrap gap-2">
                                                <label class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium cursor-pointer transition-colors"
                                                    style="{{ ($checkPartialAmounts[$billing->id]['security'] ?? false) ? 'background:rgba(18,128,92,0.12);color:#12805c;border:1px solid rgba(18,128,92,0.3);' : 'background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;' }}">
                                                    <input type="checkbox" wire:model.live="checkPartialAmounts.{{ $billing->id }}.security"
                                                        class="sr-only">
                                                    <span class="w-3.5 h-3.5 rounded border flex items-center justify-center shrink-0"
                                                        style="{{ ($checkPartialAmounts[$billing->id]['security'] ?? false) ? 'background:#12805c;border-color:#12805c;color:#fff;' : 'border-color:#D8D6C9;background:#fff;' }}">
                                                        @if($checkPartialAmounts[$billing->id]['security'] ?? false)
                                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        @endif
                                                    </span>
                                                    Keamanan · Rp {{ number_format($period->ipl_security_amount ?? 0, 0, ',', '.') }}
                                                </label>
                                                <label class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium cursor-pointer transition-colors"
                                                    style="{{ ($checkPartialAmounts[$billing->id]['garbage'] ?? false) ? 'background:rgba(18,128,92,0.12);color:#12805c;border:1px solid rgba(18,128,92,0.3);' : 'background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;' }}">
                                                    <input type="checkbox" wire:model.live="checkPartialAmounts.{{ $billing->id }}.garbage"
                                                        class="sr-only">
                                                    <span class="w-3.5 h-3.5 rounded border flex items-center justify-center shrink-0"
                                                        style="{{ ($checkPartialAmounts[$billing->id]['garbage'] ?? false) ? 'background:#12805c;border-color:#12805c;color:#fff;' : 'border-color:#D8D6C9;background:#fff;' }}">
                                                        @if($checkPartialAmounts[$billing->id]['garbage'] ?? false)
                                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        @endif
                                                    </span>
                                                    Sampah · Rp {{ number_format($period->ipl_garbage_amount ?? 0, 0, ',', '.') }}
                                                </label>
                                                <label class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium cursor-pointer transition-colors"
                                                    style="{{ ($checkPartialAmounts[$billing->id]['kas_rt'] ?? false) ? 'background:rgba(18,128,92,0.12);color:#12805c;border:1px solid rgba(18,128,92,0.3);' : 'background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;' }}">
                                                    <input type="checkbox" wire:model.live="checkPartialAmounts.{{ $billing->id }}.kas_rt"
                                                        class="sr-only">
                                                    <span class="w-3.5 h-3.5 rounded border flex items-center justify-center shrink-0"
                                                        style="{{ ($checkPartialAmounts[$billing->id]['kas_rt'] ?? false) ? 'background:#12805c;border-color:#12805c;color:#fff;' : 'border-color:#D8D6C9;background:#fff;' }}">
                                                        @if($checkPartialAmounts[$billing->id]['kas_rt'] ?? false)
                                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        @endif
                                                    </span>
                                                    Kas RT · Rp {{ number_format($period->ipl_kas_rt_amount ?? 0, 0, ',', '.') }}
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @error('checklistSelectedIds')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    @else
                        <div class="rounded-xl px-4 py-8 text-center" style="background:#ffffff;border:1px solid #E0DFD4;">
                            <p class="text-sm" style="color:#909A8F;">Pilih penghuni terlebih dahulu untuk melihat tagihan yang perlu dibayar.</p>
                        </div>
                    @endif

                    {{-- Payment details --}}
                    @if(count($checklistSelectedIds) > 0)
                        <div class="rounded-xl p-4 space-y-3" style="background:#ffffff;border:1px solid #E0DFD4;">
                            <p class="text-xs font-semibold uppercase tracking-wider" style="color:#17231E;">Detail Pembayaran</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Tanggal Bayar <span style="color:#B0402C;">*</span></label>
                                    <input type="date" wire:model="checkPayDate"
                                        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;color-scheme:dark;"
                                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                    @error('checkPayDate')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Metode <span style="color:#B0402C;">*</span></label>
                                    <select wire:model="checkPayMethod"
                                        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                        <option value="cash">Cash / Tunai</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" style="color:#586359;">No. Referensi</label>
                                    <input type="text" wire:model="checkPayReference" placeholder="Opsional"
                                        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Diterima Oleh</label>
                                    <input type="text" wire:model="checkPayReceivedBy" placeholder="Nama penerima"
                                        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color:#586359;">Catatan</label>
                                <textarea wire:model="checkPayNotes" rows="2"
                                    style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'"></textarea>
                            </div>
                        </div>

                        @php
                            $selectedTotal = 0;
                            foreach($checklistBillings->whereIn('id', $checklistSelectedIds) as $sb) {
                                if (isset($checkPartialAmounts[$sb->id]) && $sb->period) {
                                    $pa = $checkPartialAmounts[$sb->id];
                                    if (!empty($pa['security'])) $selectedTotal += min((float) $sb->period->ipl_security_amount, $sb->remainingSecurity());
                                    if (!empty($pa['garbage']))  $selectedTotal += min((float) $sb->period->ipl_garbage_amount, $sb->remainingGarbage());
                                    if (!empty($pa['kas_rt']))   $selectedTotal += min((float) $sb->period->ipl_kas_rt_amount, $sb->remainingKasRt());
                                } else {
                                    $selectedTotal += $sb->outstanding;
                                }
                            }
                        @endphp
                        <div class="flex items-center justify-between rounded-xl px-4 py-3" style="background:rgba(18,128,92,0.06);border:1px solid rgba(18,128,92,0.2);">
                            <span class="text-sm font-semibold" style="color:#12805c;">Total yang akan dibayar ({{ count($checklistSelectedIds) }} tagihan)</span>
                            <span class="text-lg font-bold font-mono" style="color:#12805c;">Rp {{ number_format($selectedTotal, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeChecklistPayment()"
                            class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                            style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                            onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                            style="background:#17231E;color:#ffffff;"
                            onmouseover="this.style.background='#164A40'" onmouseout="this.style.background='#17231E'"
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
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #D8D6C9;max-height:90vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:linear-gradient(135deg,#F1F3EC,#F1F3EC);border-bottom:1px solid rgba(22,74,64,0.4);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Bebaskan Tunggakan (Pemutihan)</h3>
                <button wire:click="closeWaiveModal()" class="p-1 rounded-lg transition-colors" style="color:#17231E;"
                    onmouseover="this.style.background='rgba(22,74,64,0.15)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto">
                @if($waivingBilling)
                    <div class="mx-6 mt-4 rounded-xl p-4 text-sm" style="background:#ffffff;border:1px solid #E0DFD4;">
                        <div class="font-semibold text-base" style="color:#17231E;">{{ $waivingBilling->responsibleResident?->name ?? '(Tanpa Penanggung Jawab)' }}</div>
                        <div class="text-xs mt-0.5" style="color:#909A8F;">
                            Blok: <span class="font-mono font-bold" style="color:#17231E;">{{ $waivingBilling->houseBlock?->block_code ?? '—' }}</span>
                            &middot; Periode: <span class="font-medium" style="color:#17231E;">{{ $waivingBilling->period?->period_label ?? '—' }}</span>
                        </div>
                    </div>
                @endif

                <div class="mx-6 mt-3 rounded-xl p-3 text-xs" style="background:rgba(22,74,64,0.08);border:1px solid rgba(22,74,64,0.3);color:#17231E;">
                    Pembebasan menutup tunggakan tanpa dicatat sebagai pemasukan kas (piutang dihapus, bukan pendapatan). Wajib mencantumkan alasan untuk audit.
                </div>

                @if(session('modal_error'))
                    <div class="mx-6 mt-3 rounded-xl p-3 text-sm" style="background:rgba(169,116,26,0.1);border:1px solid rgba(169,116,26,0.3);color:#A9741A;">
                        {{ session('modal_error') }}
                    </div>
                @endif

                <form wire:submit="saveWaive" class="p-6 space-y-4">
                    @if($waivingBilling)
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color:#17231E;">Komponen yang Dibebaskan</p>
                        @php
                            $waiveRows = [
                                ['label' => 'Keamanan', 'model' => 'waiveSecurity', 'rem' => $waivingBilling->remainingSecurity()],
                                ['label' => 'Sampah',   'model' => 'waiveGarbage',  'rem' => $waivingBilling->remainingGarbage()],
                                ['label' => 'Kas RT',   'model' => 'waiveKasRt',    'rem' => $waivingBilling->remainingKasRt()],
                            ];
                        @endphp
                        @foreach($waiveRows as $row)
                            <label class="flex items-center justify-between gap-3 rounded-xl px-4 py-3 cursor-pointer {{ $row['rem'] <= 0 ? 'opacity-50' : '' }}"
                                style="background:#ffffff;border:1px solid #E0DFD4;">
                                <span class="flex items-center gap-2.5">
                                    <input type="checkbox" wire:model="{{ $row['model'] }}" @disabled($row['rem'] <= 0)
                                        style="width:1rem;height:1rem;accent-color:#17231E;">
                                    <span class="text-sm font-medium" style="color:#17231E;">{{ $row['label'] }}</span>
                                </span>
                                <span class="text-sm font-mono font-semibold" style="color:{{ $row['rem'] > 0 ? '#B0402C' : '#909A8F' }};">
                                    @if($row['rem'] > 0) Rp {{ number_format($row['rem'], 0, ',', '.') }} @else lunas @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Alasan Pembebasan <span style="color:#B0402C;">*</span></label>
                        <textarea wire:model="waiverReason" rows="3" placeholder="mis. Warga kurang mampu, disetujui rapat RT 5 Juli 2026"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'"></textarea>
                        @error('waiverReason')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="closeWaiveModal()"
                            class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                            style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                            onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                            style="background:#164A40;color:#ffffff;"
                            onmouseover="this.style.background='#586359'" onmouseout="this.style.background='#0F3A32'">
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
