<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#111827;">Laporan Arus Kas</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if(session('report_error'))
                <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('report_error') }}</span>
                </div>
            @endif

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(16,24,40,0.35);">
                <h1 class="text-2xl font-bold" style="color:#111827;font-family:'IBM Plex Sans',serif;">Laporan Arus Kas</h1>
                <p class="text-sm mt-1" style="color:#111827;">Rekap pemasukan dan pengeluaran per periode</p>
            </div>

            {{-- Org Tabs --}}
            <div class="flex gap-1 p-1 rounded-xl w-fit" style="background:#ffffff;border:1px solid #e4e7ec;">
                <button wire:click="$set('activeOrg', 'perumahan')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    style="{{ $activeOrg === 'perumahan' ? 'background:#111827;color:#ffffff;' : 'color:#7c8698;' }}"
                    @if($activeOrg !== 'perumahan') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#7c8698'" @endif>
                    Perumahan
                </button>
                <button wire:click="$set('activeOrg', 'dkm')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    style="{{ $activeOrg === 'dkm' ? 'background:#111827;color:#ffffff;' : 'color:#7c8698;' }}"
                    @if($activeOrg !== 'dkm') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#7c8698'" @endif>
                    DKM Masjid
                </button>
                <button wire:click="$set('activeOrg', 'semua')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                    style="{{ $activeOrg === 'semua' ? 'background:#111827;color:#ffffff;' : 'color:#7c8698;' }}"
                    @if($activeOrg !== 'semua') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#7c8698'" @endif>
                    Semua
                </button>
            </div>

            {{-- Period Filter --}}
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <h3 class="font-semibold text-sm mb-4" style="color:#1d2939;">Pilih Periode</h3>
                <div class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#475467;">Bulan</label>
                        <select wire:model.live="month"
                            class="px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#475467;">Tahun</label>
                        <select wire:model.live="year"
                            class="px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div wire:loading wire:target="month,year,activeOrg" class="flex items-center gap-2 text-sm" style="color:#98a2b3;">
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
                    <p class="text-sm" style="color:#98a2b3;">
                        Periode: <span style="color:#475467;font-weight:500;">{{ \Carbon\Carbon::parse($rd['startDate'])->translatedFormat('d F Y') }}</span>
                        s/d <span style="color:#475467;font-weight:500;">{{ \Carbon\Carbon::parse($rd['endDate'])->translatedFormat('d F Y') }}</span>
                        &mdash; <span style="color:#111827;font-weight:500;">{{ $orgLabel }}</span>
                    </p>
                </div>

                {{-- Stat Cards --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#7c8698;">Saldo Awal</p>
                        <p class="text-xl font-bold mt-1" style="color:#1d2939;">Rp {{ number_format($rd['startingBalance'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#7c8698;">Total Pemasukan</p>
                        <p class="text-xl font-bold mt-1" style="color:#12805c;">Rp {{ number_format($rd['totalIncome'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#7c8698;">Total Pengeluaran</p>
                        <p class="text-xl font-bold mt-1" style="color:#c0453b;">Rp {{ number_format($rd['totalExpense'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <p class="text-xs font-medium uppercase tracking-wide" style="color:#7c8698;">Saldo Akhir</p>
                        <p class="text-xl font-bold mt-1" style="color:{{ $rd['endingBalance'] >= 0 ? '#111827' : '#c0453b' }};">
                            Rp {{ number_format($rd['endingBalance'], 0, ',', '.') }}
                        </p>
                        @if(abs($rd['discrepancy']) > 0.01)
                            <p class="text-xs mt-1 font-medium" style="color:#c77d1a;">⚠ Selisih: Rp {{ number_format($rd['discrepancy'], 0, ',', '.') }}</p>
                        @else
                            <p class="text-xs mt-1" style="color:#12805c;">✓ Terverifikasi</p>
                        @endif
                    </div>
                </div>

                {{-- Income & Expense Breakdown --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Pemasukan --}}
                    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <div class="px-5 py-4 rounded-t-2xl" style="background:linear-gradient(135deg,#e3f1ea,#e3f1ea);border-bottom:1px solid rgba(18,128,92,0.3);">
                            <h3 class="font-semibold" style="color:#12805c;">Rincian Pemasukan</h3>
                            <p class="text-sm mt-0.5" style="color:#0e6d4f;">Total: Rp {{ number_format($rd['totalIncome'], 0, ',', '.') }}</p>
                        </div>
                        <div class="p-5">
                            @forelse($rd['incomeSummary'] as $item)
                                <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #eef0f3;">
                                    <span class="text-sm" style="color:#475467;">{{ $item->name }}</span>
                                    <span class="font-mono text-sm font-semibold" style="color:#12805c;">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-center py-6" style="color:#98a2b3;">Tidak ada pemasukan pada periode ini.</p>
                            @endforelse
                            <div class="flex justify-between items-center pt-3 mt-2" style="border-top:2px solid #e4e7ec;">
                                <span class="text-sm font-bold" style="color:#1d2939;">TOTAL PEMASUKAN</span>
                                <span class="font-mono font-bold" style="color:#12805c;">Rp {{ number_format($rd['totalIncome'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Pengeluaran --}}
                    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <div class="px-5 py-4 rounded-t-2xl" style="background:linear-gradient(135deg,#f7e7e4,#f7e7e4);border-bottom:1px solid rgba(192,69,59,0.3);">
                            <h3 class="font-semibold" style="color:#c0453b;">Rincian Pengeluaran</h3>
                            <p class="text-sm mt-0.5" style="color:#a23a30;">Total: Rp {{ number_format($rd['totalExpense'], 0, ',', '.') }}</p>
                        </div>
                        <div class="p-5">
                            @forelse($rd['expenseSummary'] as $item)
                                <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #eef0f3;">
                                    <span class="text-sm" style="color:#475467;">{{ $item->name }}</span>
                                    <span class="font-mono text-sm font-semibold" style="color:#c0453b;">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-center py-6" style="color:#98a2b3;">Tidak ada pengeluaran pada periode ini.</p>
                            @endforelse
                            <div class="flex justify-between items-center pt-3 mt-2" style="border-top:2px solid #e4e7ec;">
                                <span class="text-sm font-bold" style="color:#1d2939;">TOTAL PENGELUARAN</span>
                                <span class="font-mono font-bold" style="color:#c0453b;">Rp {{ number_format($rd['totalExpense'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <div class="rounded-2xl p-6 sm:p-10 text-center" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);color:#98a2b3;">
                    Tidak dapat menampilkan data laporan saat ini.
                </div>
            @endisset

        </div>
    </div>
</div>
