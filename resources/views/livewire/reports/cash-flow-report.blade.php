<div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if(session('report_error'))
                <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('report_error') }}</span>
                </div>
            @endif

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
                <h1 class="text-2xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Laporan Arus Kas</h1>
                <p class="text-sm mt-1" style="color:#17231E;">Rekap pemasukan dan pengeluaran per periode</p>
            </div>

            {{-- Org Tabs --}}
            <div class="flex gap-1 p-1 rounded-xl w-fit" style="background:#ffffff;border:1px solid #E0DFD4;">
                <button wire:click="$set('activeOrg', 'perumahan')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    style="{{ $activeOrg === 'perumahan' ? 'background:#164A40;color:#ffffff;' : 'color:#909A8F;' }}"
                    @if($activeOrg !== 'perumahan') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#909A8F'" @endif>
                    Perumahan
                </button>
                <button wire:click="$set('activeOrg', 'dkm')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    style="{{ $activeOrg === 'dkm' ? 'background:#164A40;color:#ffffff;' : 'color:#909A8F;' }}"
                    @if($activeOrg !== 'dkm') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#909A8F'" @endif>
                    DKM Masjid
                </button>
                <button wire:click="$set('activeOrg', 'semua')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    style="{{ $activeOrg === 'semua' ? 'background:#164A40;color:#ffffff;' : 'color:#909A8F;' }}"
                    @if($activeOrg !== 'semua') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#909A8F'" @endif>
                    Semua
                </button>
            </div>

            {{-- Period Filter --}}
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <h3 class="font-semibold text-sm mb-4" style="color:#17231E;">Pilih Periode</h3>
                <div class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Bulan</label>
                        <select wire:model.live="month"
                            class="px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Tahun</label>
                        <select wire:model.live="year"
                            class="px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div wire:loading wire:target="month,year,activeOrg" class="flex items-center gap-2 text-sm" style="color:#909A8F;">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        Menghitung...
                    </div>
                </div>
            </div>

            @isset($this->reportData)
                @php
                    $rd = $this->reportData;
                    $orgLabel = match($activeOrg) { 'perumahan' => 'Perumahan', 'dkm' => 'DKM', default => 'Semua Organisasi' };
                @endphp

                <div class="text-center">
                    <p class="text-sm" style="color:#909A8F;">
                        Periode: <span style="color:#586359;font-weight:500;">{{ \Carbon\Carbon::parse($rd['startDate'])->translatedFormat('d F Y') }}</span>
                        s/d <span style="color:#586359;font-weight:500;">{{ \Carbon\Carbon::parse($rd['endDate'])->translatedFormat('d F Y') }}</span>
                        &mdash; <span style="color:#17231E;font-weight:500;">{{ $orgLabel }}</span>
                    </p>
                </div>

                {{-- Stat Cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Saldo Awal</p>
                        <p class="text-xl font-bold mt-1" style="color:#17231E;">Rp {{ number_format($rd['startingBalance'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Total Pemasukan</p>
                        <p class="text-xl font-bold mt-1" style="color:#12805c;">Rp {{ number_format($rd['totalIncome'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Total Pengeluaran</p>
                        <p class="text-xl font-bold mt-1" style="color:#B0402C;">Rp {{ number_format($rd['totalExpense'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Saldo Akhir</p>
                        <p class="text-xl font-bold mt-1" style="color:{{ $rd['endingBalance'] >= 0 ? '#164A40' : '#B0402C' }};">
                            Rp {{ number_format($rd['endingBalance'], 0, ',', '.') }}
                        </p>
                        @if(abs($rd['discrepancy']) > 0.01)
                            <p class="text-xs mt-1 font-medium" style="color:#A9741A;">⚠ Selisih: Rp {{ number_format($rd['discrepancy'], 0, ',', '.') }}</p>
                        @else
                            <p class="text-xs mt-1" style="color:#12805c;">✓ Terverifikasi</p>
                        @endif
                    </div>
                </div>

                {{-- Income & Expense Breakdown --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Pemasukan --}}
                    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <div class="px-5 py-4 rounded-t-2xl" style="background:linear-gradient(135deg,#E4F1EB,#E4F1EB);border-bottom:1px solid rgba(18,128,92,0.3);">
                            <h3 class="font-semibold" style="color:#12805c;">Rincian Pemasukan</h3>
                            <p class="text-sm mt-0.5" style="color:#0E6844;">Total: Rp {{ number_format($rd['totalIncome'], 0, ',', '.') }}</p>
                        </div>
                        <div class="p-5">
                            @forelse($rd['incomeSummary'] as $item)
                                <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #F1F3EC;">
                                    <span class="text-sm" style="color:#586359;">{{ $item->name }}</span>
                                    <span class="font-mono text-sm font-semibold" style="color:#12805c;">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-center py-6" style="color:#909A8F;">Tidak ada pemasukan pada periode ini.</p>
                            @endforelse
                            <div class="flex justify-between items-center pt-3 mt-2" style="border-top:2px solid #E0DFD4;">
                                <span class="text-sm font-bold" style="color:#17231E;">TOTAL PEMASUKAN</span>
                                <span class="font-mono font-bold" style="color:#12805c;">Rp {{ number_format($rd['totalIncome'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Pengeluaran --}}
                    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <div class="px-5 py-4 rounded-t-2xl" style="background:linear-gradient(135deg,#F6E7E2,#F6E7E2);border-bottom:1px solid rgba(176,64,44,0.3);">
                            <h3 class="font-semibold" style="color:#B0402C;">Rincian Pengeluaran</h3>
                            <p class="text-sm mt-0.5" style="color:#a23a30;">Total: Rp {{ number_format($rd['totalExpense'], 0, ',', '.') }}</p>
                        </div>
                        <div class="p-5">
                            @forelse($rd['expenseSummary'] as $item)
                                <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #F1F3EC;">
                                    <span class="text-sm" style="color:#586359;">{{ $item->name }}</span>
                                    <span class="font-mono text-sm font-semibold" style="color:#B0402C;">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-center py-6" style="color:#909A8F;">Tidak ada pengeluaran pada periode ini.</p>
                            @endforelse
                            <div class="flex justify-between items-center pt-3 mt-2" style="border-top:2px solid #E0DFD4;">
                                <span class="text-sm font-bold" style="color:#17231E;">TOTAL PENGELUARAN</span>
                                <span class="font-mono font-bold" style="color:#B0402C;">Rp {{ number_format($rd['totalExpense'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <div class="rounded-2xl p-6 sm:p-10 text-center" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#909A8F;">
                    Tidak dapat menampilkan data laporan saat ini.
                </div>
            @endisset

        </div>
    </div>
</div>
