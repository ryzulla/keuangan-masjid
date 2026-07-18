<div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Flash Messages --}}
            @if(session('success') && !$isModalOpen)
                <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                    <button class="ml-auto transition-colors" style="color:#12805c;" @click="$el.closest('[style]').remove()">✕</button>
                </div>
            @endif
            @if((session('error') && !$isModalOpen) || session('render_error'))
                <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') ?? session('render_error') }}</span>
                </div>
            @endif

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Buku Besar Transaksi</h1>
                        <p class="text-sm mt-1" style="color:#17231E;">Riwayat seluruh pemasukan dan pengeluaran</p>
                    </div>
                    <button wire:click="create()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Transaksi
                    </button>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <h3 class="font-semibold text-sm mb-4" style="color:#17231E;">Filter Transaksi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Dari Tanggal</label>
                        <input type="date" wire:model.live.debounce.500ms="startDate"
                            class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Sampai Tanggal</label>
                        <input type="date" wire:model.live.debounce.500ms="endDate"
                            class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Kategori</label>
                        <select wire:model.live="selectedCategoryId"
                            class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            <option value="">Semua Kategori</option>
                            @foreach($this->filterCategories as $category)
                                <option value="{{ $category->id }}" wire:key="filter-cat-{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($showCampaignFilter)
                        <div>
                            <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Program/Kampanye</label>
                            <select wire:model.live="selectedCampaignId"
                                class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                <option value="">Semua Program</option>
                                @foreach($this->availableCampaigns as $campaign)
                                    <option value="{{ $campaign->id }}" wire:key="filter-camp-{{ $campaign->id }}">{{ $campaign->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="hidden lg:block"></div>
                    @endif
                </div>
                <div class="flex justify-end gap-2 mt-4 pt-4" style="border-top:1px solid #E0DFD4;">
                    <span wire:loading wire:target="exportExcel,exportPdf" class="text-sm self-center flex items-center gap-1" style="color:#909A8F;">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Mengekspor...
                    </span>
                    <button wire:click="exportExcel" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                        style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.3);"
                        onmouseover="this.style.background='rgba(18,128,92,0.2)'" onmouseout="this.style.background='rgba(18,128,92,0.1)'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span wire:loading.remove wire:target="exportExcel">Excel</span>
                        <span wire:loading wire:target="exportExcel">...</span>
                    </button>
                    <button wire:click="exportPdf" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                        style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.3);"
                        onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span wire:loading.remove wire:target="exportPdf">PDF</span>
                        <span wire:loading wire:target="exportPdf">...</span>
                    </button>
                </div>
            </div>

            {{-- Summary Stats --}}
            @isset($totalDebit, $totalCredit)
                @php
                    $safeStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Awal';
                    $safeEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Hari ini';
                    $netFlow = $totalDebit - $totalCredit;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Total Pemasukan</p>
                        <p class="text-xl font-bold mt-1" style="color:#12805c;">Rp {{ number_format($totalDebit, 0, ',', '.') }}</p>
                        <p class="text-xs mt-1" style="color:#909A8F;">{{ $safeStartDate }} — {{ $safeEndDate }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Total Pengeluaran</p>
                        <p class="text-xl font-bold mt-1" style="color:#B0402C;">Rp {{ number_format($totalCredit, 0, ',', '.') }}</p>
                        <p class="text-xs mt-1" style="color:#909A8F;">{{ $safeStartDate }} — {{ $safeEndDate }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Selisih (Net Flow)</p>
                        <p class="text-xl font-bold mt-1" style="color:{{ $netFlow >= 0 ? '#164A40' : '#B0402C' }};">
                            Rp {{ number_format($netFlow, 0, ',', '.') }}
                        </p>
                        <p class="text-xs mt-1" style="color:{{ $netFlow >= 0 ? '#164A40' : '#B0402C' }};">{{ $netFlow >= 0 ? 'Surplus' : 'Defisit' }}</p>
                    </div>
                </div>
            @endisset

            {{-- Transactions Table --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider whitespace-nowrap" style="color:#909A8F;">Tanggal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Keterangan</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Kategori</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Akun/Kas</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Program</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Donatur</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Bukti</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Debit (+)</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Kredit (-)</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $transactionPaginator = ($transactions instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) ? $transactions : null; @endphp
                            @if($transactionPaginator)
                                @forelse($transactionPaginator->items() as $tx)
                                    <tr wire:key="tx-{{ $tx->id }}" style="border-bottom:1px solid #F1F3EC;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                        <td class="px-4 py-3 text-xs whitespace-nowrap" style="color:#909A8F;">{{ optional($tx->transaction_date)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 max-w-xs truncate" style="color:#17231E;">{{ $tx->description }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs" style="background:#F1F3EC;color:#586359;">{{ optional($tx->category)->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs" style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;">{{ optional($tx->account)->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if(optional($tx->campaign)->name)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">{{ $tx->campaign->name }}</span>
                                            @else
                                                <span style="color:#909A8F;">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs" style="color:#909A8F;">{{ optional($tx->donation)->donor_name ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            @if($tx->attachment)
                                                <a href="{{ Storage::url($tx->attachment) }}" target="_blank" class="text-xs hover:underline" style="color:#17231E;">Lihat</a>
                                            @else
                                                <span style="color:#909A8F;">—</span>
                                            @endif
                                        </td>
                                        @if($tx->type === 'debit')
                                            <td class="px-4 py-3 text-right font-mono text-sm font-semibold" style="color:#12805c;">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-right" style="color:#909A8F;">—</td>
                                        @else
                                            <td class="px-4 py-3 text-right" style="color:#909A8F;">—</td>
                                            <td class="px-4 py-3 text-right font-mono text-sm font-semibold" style="color:#B0402C;">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                                        @endif
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button wire:click="edit({{ $tx->id }})"
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                    style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);"
                                                    onmouseover="this.style.background='rgba(22,74,64,0.2)'" onmouseout="this.style.background='rgba(22,74,64,0.1)'">
                                                    Edit
                                                </button>
                                                <button wire:click.prevent="confirmDelete({{ $tx->id }})"
                                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                                    onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-12 text-center" style="color:#909A8F;">
                                            <svg class="w-10 h-10 mx-auto mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            <p>Belum ada transaksi pada periode/filter ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            @else
                                <tr>
                                    <td colspan="10" class="px-4 py-8 text-center" style="color:#B0402C;">Gagal memuat data transaksi.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card List --}}
                <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                    @if($transactionPaginator)
                        @forelse($transactionPaginator->items() as $tx)
                            <div wire:key="tx-card-{{ $tx->id }}" class="px-4 py-3 space-y-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-medium break-words" style="color:#17231E;">{{ $tx->description }}</p>
                                        <p class="text-xs mt-0.5" style="color:#909A8F;">{{ optional($tx->transaction_date)->format('d/m/Y') }}</p>
                                    </div>
                                    @if($tx->type === 'debit')
                                        <span class="font-mono font-semibold shrink-0" style="color:#12805c;">+Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="font-mono font-semibold shrink-0" style="color:#B0402C;">-Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs" style="background:#F1F3EC;color:#586359;">{{ optional($tx->category)->name ?? '-' }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs" style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;">{{ optional($tx->account)->name ?? '-' }}</span>
                                    @if(optional($tx->campaign)->name)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">{{ $tx->campaign->name }}</span>
                                    @endif
                                    @if(optional($tx->donation)->donor_name)
                                        <span class="text-xs" style="color:#909A8F;">{{ $tx->donation->donor_name }}</span>
                                    @endif
                                    @if($tx->attachment)
                                        <a href="{{ Storage::url($tx->attachment) }}" target="_blank" class="text-xs hover:underline" style="color:#17231E;">Lihat</a>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5 pt-1">
                                    <button wire:click="edit({{ $tx->id }})"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                        style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                        Edit
                                    </button>
                                    <button wire:click.prevent="confirmDelete({{ $tx->id }})"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                        style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-12 text-center" style="color:#909A8F;">
                                <svg class="w-10 h-10 mx-auto mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p>Belum ada transaksi pada periode/filter ini.</p>
                            </div>
                        @endforelse
                    @else
                        <div class="px-4 py-8 text-center" style="color:#B0402C;">Gagal memuat data transaksi.</div>
                    @endif
                </div>

                @if($transactionPaginator && $transactionPaginator->hasPages())
                    <div class="px-4 py-3" style="border-top:1px solid #F1F3EC;">{{ $transactionPaginator->links() }}</div>
                @endif
            </div>

        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" id="transaction-modal" style="background:rgba(0,0,0,0.1);" wire:click.self="closeModal()">
        <div class="w-full max-w-lg rounded-2xl shadow-2xl flex flex-col" style="background:#ffffff;border:1px solid #E0DFD4;max-height:90vh;">

            <div class="flex items-center justify-between px-6 py-4 shrink-0" style="border-bottom:1px solid #F1F3EC;">
                <h3 class="text-base font-semibold" style="color:#17231E;">
                    {{ $selected_id ? 'Edit Transaksi DKM' : 'Tambah Transaksi DKM' }}
                </h3>
                <button wire:click="closeModal()" class="p-1.5 rounded-lg transition-colors" style="color:#909A8F;"
                    onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#909A8F'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1">
            <form wire:submit="store" class="px-6 py-5 space-y-4">

                @if(session('modal_error'))
                <div class="rounded-lg px-3 py-2 text-xs" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                    {{ session('modal_error') }}
                </div>
                @endif
                @if($errors->any())
                <div class="rounded-lg px-3 py-2 text-xs" style="background:rgba(169,116,26,0.08);border:1px solid rgba(169,116,26,0.3);color:#A9741A;">
                    <ul class="space-y-0.5 list-disc pl-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                {{-- Jenis Transaksi (toggle) --}}
                <div>
                    <label class="text-xs font-medium block mb-2" style="color:#586359;">Jenis Transaksi</label>
                    <div class="flex rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                        <button type="button" wire:click="$set('type','debit')"
                            class="flex-1 py-2 text-sm font-medium transition-all"
                            style="{{ $type === 'debit' ? 'background:rgba(74,222,128,0.15);color:#12805c;border-right:1px solid rgba(74,222,128,0.2);' : 'background:#ffffff;color:#909A8F;border-right:1px solid #E0DFD4;' }}">
                            Pemasukan
                        </button>
                        <button type="button" wire:click="$set('type','credit')"
                            class="flex-1 py-2 text-sm font-medium transition-all"
                            style="{{ $type === 'credit' ? 'background:rgba(176,64,44,0.15);color:#B0402C;' : 'background:#ffffff;color:#909A8F;' }}">
                            Pengeluaran
                        </button>
                    </div>
                </div>

                {{-- Akun DKM --}}
                <div>
                    <label class="text-xs font-medium block mb-1.5" style="color:#586359;">Akun Kas DKM</label>
                    <select wire:model.live="account_id" class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;">
                        <option value="">-- Pilih Akun --</option>
                        @foreach($this->accounts->where('organization_type', 'dkm') as $account)
                            <option value="{{ $account->id }}" wire:key="modal-account-{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                    @if($this->accounts->where('organization_type','dkm')->isEmpty())
                        <p class="text-xs mt-1" style="color:#A9741A;">Belum ada akun DKM. Tambah di menu Master → Akun.</p>
                    @endif
                    @error('account_id')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="text-xs font-medium block mb-1.5" style="color:#586359;">Kategori</label>
                    <select wire:model.live="category_id" class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;" wire:loading.attr="disabled" wire:target="type">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($this->categories as $category)
                            <option value="{{ $category->id }}" wire:key="modal-category-{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                @if($type === 'credit' && $modalOrg === 'dkm' && $category_id)
                    @php
                        $selectedCatForSyariat = collect($categories)->firstWhere('id', $category_id);
                        $syariatFundType = $selectedCatForSyariat?->fund_type;
                    @endphp
                    @if($syariatFundType === 'zakat')
                        <div class="rounded-xl px-4 py-3 flex gap-3" style="background:rgba(169,116,26,0.08);border:1px solid rgba(169,116,26,0.4);">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#A9741A"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                            <div>
                                <p class="text-xs font-semibold mb-0.5" style="color:#A9741A;">Ketentuan Syariat — Dana Zakat</p>
                                <p class="text-xs leading-relaxed" style="color:#A9741A;">Dana Zakat <strong>hanya boleh</strong> didistribusikan kepada 8 Asnaf: <em>Fakir, Miskin, Amil Zakat, Muallaf, Riqab, Gharimin, Fi Sabilillah,</em> dan <em>Ibnu Sabil.</em></p>
                                <p class="text-xs mt-1" style="color:#A9741A;">Dalil: QS. At-Taubah: 60</p>
                            </div>
                        </div>
                    @elseif($syariatFundType === 'wakaf')
                        <div class="rounded-xl px-4 py-3 flex gap-3" style="background:rgba(22,74,64,0.08);border:1px solid rgba(22,74,64,0.3);">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="text-xs font-semibold mb-0.5" style="color:#164A40;">Ketentuan Syariat — Dana Wakaf</p>
                                <p class="text-xs leading-relaxed" style="color:#164A40;">Dana Wakaf bersifat <strong>permanen</strong>. Hanya boleh digunakan sesuai peruntukan asal yang telah ditetapkan. Aset wakaf tidak boleh dijual, dihibahkan, atau dipindahtangankan.</p>
                            </div>
                        </div>
                    @elseif($syariatFundType === 'infaq')
                        <div class="rounded-xl px-4 py-3 flex gap-3" style="background:rgba(18,128,92,0.06);border:1px solid rgba(18,128,92,0.25);">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#12805c"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="text-xs font-semibold mb-0.5" style="color:#12805c;">Ketentuan Syariat — Dana Infaq</p>
                                <p class="text-xs leading-relaxed" style="color:#0E6844;">Dana Infaq boleh digunakan untuk keperluan masjid, pendidikan, sosial, kegiatan keagamaan, dan kebutuhan umum umat.</p>
                            </div>
                        </div>
                    @elseif($syariatFundType === 'sedekah')
                        <div class="rounded-xl px-4 py-3 flex gap-3" style="background:rgba(52,211,153,0.06);border:1px solid rgba(52,211,153,0.25);">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#12805c"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="text-xs font-semibold mb-0.5" style="color:#12805c;">Ketentuan Syariat — Dana Sedekah</p>
                                <p class="text-xs leading-relaxed" style="color:#0E6844;">Dana Sedekah bersifat fleksibel. Dapat digunakan untuk berbagai kebutuhan sosial, keagamaan, dan kemanusiaan sesuai kemaslahatan.</p>
                            </div>
                        </div>
                    @endif
                @endif
                @if($showCampaignDropdown)
                    <div class="rounded-xl p-3" style="background:rgba(22,74,64,0.06);border:1px solid rgba(22,74,64,0.15);">
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Untuk Program/Kampanye</label>
                        <select wire:model="campaign_id" class="w-full px-3 py-2 text-sm rounded-xl outline-none" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;" onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            <option value="">-- Pilih Program --</option>
                            @foreach($this->availableCampaigns as $campaign)
                                <option value="{{ $campaign->id }}" wire:key="modal-campaign-{{ $campaign->id }}">{{ $campaign->name }}</option>
                            @endforeach
                        </select>
                        @error('campaign_id')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                @endif
                @if($type === 'debit')
                    {{-- === SEKSI DONATUR (perumahan & masjid) === --}}
                    <div class="rounded-xl p-4 space-y-3" style="background:rgba(22,74,64,0.04);border:1px solid rgba(22,74,64,0.2);">
                        <p class="text-xs font-semibold uppercase tracking-wider" style="color:#17231E;">Informasi Donatur{{ $modalOrg === 'dkm' ? ' & Dana' : '' }}</p>

                        @if($modalOrg === 'dkm')
                        {{-- Jenis Dana (khusus masjid/DKM) --}}
                        <div>
                            <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Jenis Dana</label>
                            <select wire:model="donationType" class="w-full px-3 py-2 text-sm rounded-xl outline-none" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;" onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                <option value="infaq">Infaq</option>
                                <option value="sedekah">Sedekah</option>
                                <option value="zakat">Zakat</option>
                                <option value="wakaf">Wakaf</option>
                                <option value="umum">Umum / Lain-lain</option>
                            </select>
                        </div>
                        @endif

                        {{-- Donatur Type Selector --}}
                        <div>
                            <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Donatur</label>
                            <div class="flex gap-1.5 mb-2.5 rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                                <button type="button" wire:click="$set('donorType','hamba_allah')"
                                    class="flex-1 py-2 text-xs font-medium transition-all"
                                    style="{{ $donorType === 'hamba_allah' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;' }}">
                                    Hamba Allah
                                </button>
                                <button type="button" wire:click="$set('donorType','penghuni')"
                                    class="flex-1 py-2 text-xs font-medium transition-all"
                                    style="{{ $donorType === 'penghuni' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;border-left:1px solid #E0DFD4;' }}">
                                    Penghuni
                                </button>
                                <button type="button" wire:click="$set('donorType','luar')"
                                    class="flex-1 py-2 text-xs font-medium transition-all"
                                    style="{{ $donorType === 'luar' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;border-left:1px solid #E0DFD4;' }}">
                                    Donatur Lain
                                </button>
                            </div>
                            @if($donorType === 'penghuni')
                                <select wire:model="donorResidentId" class="w-full px-3 py-2 text-sm rounded-xl outline-none" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;" onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                    <option value="">-- Pilih Penghuni --</option>
                                    @foreach($residents as $r)
                                        <option value="{{ $r->id }}" wire:key="res-{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            @elseif($donorType === 'luar')
                                <input type="text" wire:model="donor_name" placeholder="Nama donatur..." class="w-full px-3 py-2 text-sm rounded-xl outline-none" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;" onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @else
                                <div class="px-3 py-2 rounded-xl text-xs" style="background:#ffffff;border:1px solid #F1F3EC;color:#909A8F;">
                                    Transaksi akan dicatat atas nama <strong style="color:#586359;">Hamba Allah</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                {{-- Jumlah + Tanggal --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium block mb-1.5" style="color:#586359;">Jumlah (Rp)</label>
                        <input type="number" wire:model="amount" placeholder="0" min="1"
                            class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none"
                            style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;">
                        @error('amount')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-medium block mb-1.5" style="color:#586359;">Tanggal</label>
                        <input type="date" wire:model="transaction_date"
                            class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none"
                            style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;">
                        @error('transaction_date')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="text-xs font-medium block mb-1.5" style="color:#586359;">Keterangan</label>
                    <textarea wire:model="description" rows="2" placeholder="Deskripsi transaksi..."
                        class="w-full rounded-xl px-3 py-2.5 text-sm focus:outline-none resize-none"
                        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;"></textarea>
                    @error('description')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>

                {{-- Bukti --}}
                <div>
                    <label class="text-xs font-medium block mb-1.5" style="color:#586359;">Bukti Transaksi <span style="color:#909A8F;">(PDF/JPG/PNG, maks 2MB)</span></label>
                    <input type="file" wire:model="attachmentFile" class="block w-full text-sm" style="color:#586359;">
                    <div wire:loading wire:target="attachmentFile" class="text-xs mt-1" style="color:#17231E;">Mengunggah...</div>
                    @error('attachmentFile')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    @if($existingAttachment && !$attachmentFile)
                        <div class="mt-2 text-xs flex items-center gap-2" style="color:#586359;">
                            <a href="{{ Storage::url($existingAttachment) }}" target="_blank" class="hover:underline" style="color:#17231E;">{{ basename($existingAttachment) }}</a>
                            <button type="button" wire:click="removeAttachment" class="hover:underline" style="color:#B0402C;">✕ Hapus</button>
                        </div>
                    @elseif($attachmentFile && !$errors->has('attachmentFile'))
                        <div class="mt-2 text-xs" style="color:#12805c;">{{ $attachmentFile->getClientOriginalName() }}
                            <button type="button" wire:click="$set('attachmentFile',null)" class="ml-2 hover:underline" style="color:#A9741A;">✕ Batal</button>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all"
                        style="background:linear-gradient(135deg,#164A40,#164A40);color:#ffffff;"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="store">{{ $selected_id ? 'Simpan Perubahan' : 'Tambah Transaksi' }}</span>
                        <span wire:loading wire:target="store">Menyimpan...</span>
                    </button>
                    <button type="button" wire:click="closeModal()"
                        class="px-4 py-2.5 rounded-xl text-sm transition-colors"
                        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#586359;">Batal</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        if (typeof window.sweetAlertListenersAttached === 'undefined') {
            window.sweetAlertListenersAttached = false;
        }

        function initSweetAlertListeners() {
            if (!window.sweetAlertListenersAttached) {
                Livewire.on('show-delete-confirmation', (event) => {
                    let componentId = event.id ?? (event[0]?.id);
                    if (!window.Swal) { console.error('Swal is not defined!'); return; }
                    Swal.fire({
                        title: 'Anda Yakin?',
                        text: "Transaksi ini akan dihapus permanen! Saldo akun akan disesuaikan.",
                        icon: 'warning', showCancelButton: true,
                        confirmButtonColor: '#164A40', cancelButtonColor: '#D8D6C9',
                        confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                        background: '#ffffff', color: '#17231E',
                    }).then((result) => {
                        if (result.isConfirmed && componentId !== undefined) {
                            @this.call('delete', componentId);
                        }
                    });
                });

                Livewire.on('transactionDeleted', () => {
                    Swal.fire({ title: 'Berhasil!', text: 'Transaksi berhasil dihapus.', icon: 'success', timer: 2000, showConfirmButton: false, background: '#ffffff', color: '#17231E' });
                });

                Livewire.on('deleteFailed', (event) => {
                    let message = event.message ?? (event[0]?.message ?? 'Gagal menghapus transaksi.');
                    Swal.fire('Gagal!', message, 'error', { background: '#ffffff', color: '#17231E' });
                });

                window.sweetAlertListenersAttached = true;
            }
        }

        document.addEventListener('livewire:navigated', () => {
            window.sweetAlertListenersAttached = false;
            initSweetAlertListeners();
        });
        document.addEventListener('livewire:initialized', initSweetAlertListeners);
    </script>
    @endpush
</div>
