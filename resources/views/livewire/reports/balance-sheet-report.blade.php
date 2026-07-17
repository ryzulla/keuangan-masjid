<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#161e2d;">Neraca Keuangan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
                <h1 class="text-2xl font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Neraca Keuangan</h1>
                <p class="text-sm mt-1" style="color:#161e2d;">Posisi aset dan ekuitas per hari ini, {{ now()->translatedFormat('d F Y') }}</p>
            </div>

            {{-- Org Tabs --}}
            <div class="flex gap-1 p-1 rounded-xl w-fit" style="background:#ffffff;border:1px solid #e4e4e4;">
                <button wire:click="$set('activeOrg', 'perumahan')"
                    class="px-5 py-2 rounded-lg text-sm font-medium transition-all"
                    style="{{ $activeOrg === 'perumahan' ? 'background:#1563df;color:#ffffff;' : 'color:#a3abb0;' }}"
                    @if($activeOrg !== 'perumahan') onmouseover="this.style.color='#1563df'" onmouseout="this.style.color='#a3abb0'" @endif>
                    Perumahan
                </button>
                <button wire:click="$set('activeOrg', 'dkm')"
                    class="px-5 py-2 rounded-lg text-sm font-medium transition-all"
                    style="{{ $activeOrg === 'dkm' ? 'background:#1563df;color:#ffffff;' : 'color:#a3abb0;' }}"
                    @if($activeOrg !== 'dkm') onmouseover="this.style.color='#1563df'" onmouseout="this.style.color='#a3abb0'" @endif>
                    DKM
                </button>
                <button wire:click="$set('activeOrg', 'semua')"
                    class="px-5 py-2 rounded-lg text-sm font-medium transition-all"
                    style="{{ $activeOrg === 'semua' ? 'background:#1563df;color:#ffffff;' : 'color:#a3abb0;' }}"
                    @if($activeOrg !== 'semua') onmouseover="this.style.color='#1563df'" onmouseout="this.style.color='#a3abb0'" @endif>
                    Semua
                </button>
            </div>

            @php
                $orgLabel = match($activeOrg) { 'perumahan' => 'Perumahan', 'dkm' => 'DKM', default => 'Semua Organisasi' };
            @endphp

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Total Aset</p>
                    <p class="text-2xl font-bold mt-1.5 font-mono" style="color:#161e2d;">Rp {{ number_format($totalAset, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Total Liabilitas</p>
                    <p class="text-2xl font-bold mt-1.5 font-mono" style="color:#c0453b;">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Total Ekuitas</p>
                    <p class="text-2xl font-bold mt-1.5 font-mono" style="color:#12805c;">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Balance Sheet Tables --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Aset --}}
                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                    <div class="px-5 py-4" style="background:linear-gradient(135deg,#e3f1ea,#e3f1ea);border-bottom:1px solid rgba(18,128,92,0.25);">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 rounded-full" style="background:#12805c;"></div>
                            <h3 class="font-bold text-base" style="color:#12805c;font-family:'Manrope',serif;">ASET (Harta)</h3>
                        </div>
                        <p class="text-xs mt-0.5 ml-3" style="color:#a3abb0;">Kas &amp; Setara Kas</p>
                    </div>
                    <div class="p-5 space-y-1">
                        @forelse($asetLancar as $account)
                            <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #f7f7f7;" onmouseover="this.style.backgroundColor='#f7f7f7'" onmouseout="this.style.backgroundColor=''">
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    <span class="text-sm truncate" style="color:#161e2d;">{{ $account->name }}</span>
                                    @if($activeOrg === 'semua')
                                        @php
                                            $orgType = $account->organization_type ?? 'umum';
                                        @endphp
                                        @if($orgType === 'perumahan')
                                            <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs" style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.15);">Perumahan</span>
                                        @elseif($orgType === 'dkm')
                                            <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.15);">DKM</span>
                                        @else
                                            <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">Umum</span>
                                        @endif
                                    @endif
                                </div>
                                <span class="font-mono text-sm font-semibold ml-3 shrink-0" style="color:{{ $account->balance >= 0 ? '#1563df' : '#c0453b' }};">
                                    Rp {{ number_format($account->balance, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-center py-6" style="color:#a3abb0;">Tidak ada akun terdaftar.</p>
                        @endforelse
                        <div class="flex justify-between items-center pt-4 mt-2" style="border-top:2px solid #e4e4e4;">
                            <span class="text-sm font-bold" style="color:#161e2d;">TOTAL ASET</span>
                            <span class="font-mono font-bold text-base" style="color:#161e2d;">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Liabilitas & Ekuitas --}}
                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                    <div class="px-5 py-4" style="background:#f7f7f7;border-bottom:1px solid rgba(21,99,223,0.25);">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 rounded-full" style="background:#1563df;"></div>
                            <h3 class="font-bold text-base" style="color:#161e2d;font-family:'Manrope',serif;">LIABILITAS &amp; EKUITAS</h3>
                        </div>
                        <p class="text-xs mt-0.5 ml-3" style="color:#a3abb0;">Sumber pendanaan — {{ $orgLabel }}</p>
                    </div>
                    <div class="p-5">
                        <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#a3abb0;">Liabilitas (Utang)</h4>
                        <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #f7f7f7;">
                            <span class="text-sm" style="color:#161e2d;">Utang Pihak Ketiga</span>
                            <span class="font-mono text-sm font-semibold" style="color:#c0453b;">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</span>
                        </div>

                        <h4 class="text-xs font-semibold uppercase tracking-wider mt-5 mb-3" style="color:#a3abb0;">Ekuitas</h4>
                        <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #f7f7f7;">
                            <span class="text-sm" style="color:#161e2d;">Dana {{ $orgLabel }} (Aset − Liabilitas)</span>
                            <span class="font-mono text-sm font-semibold" style="color:#12805c;">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center pt-4 mt-2" style="border-top:2px solid #e4e4e4;">
                            <span class="text-sm font-bold" style="color:#161e2d;">TOTAL LIABILITAS &amp; EKUITAS</span>
                            <span class="font-mono font-bold text-base" style="color:#161e2d;">Rp {{ number_format($totalLiabilitas + $totalEkuitas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
