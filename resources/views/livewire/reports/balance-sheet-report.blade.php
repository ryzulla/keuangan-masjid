<div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
                <h1 class="text-2xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Neraca Keuangan</h1>
                <p class="text-sm mt-1" style="color:#17231E;">Posisi aset dan ekuitas per hari ini, {{ now()->translatedFormat('d F Y') }}</p>
            </div>

            {{-- Org Tabs --}}
            <div class="flex gap-1 p-1 rounded-xl w-fit" style="background:#ffffff;border:1px solid #E0DFD4;">
                <button wire:click="$set('activeOrg', 'perumahan')"
                    class="px-5 py-2 rounded-lg text-sm font-medium transition-all"
                    style="{{ $activeOrg === 'perumahan' ? 'background:#164A40;color:#ffffff;' : 'color:#909A8F;' }}"
                    @if($activeOrg !== 'perumahan') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#909A8F'" @endif>
                    Perumahan
                </button>
                <button wire:click="$set('activeOrg', 'dkm')"
                    class="px-5 py-2 rounded-lg text-sm font-medium transition-all"
                    style="{{ $activeOrg === 'dkm' ? 'background:#164A40;color:#ffffff;' : 'color:#909A8F;' }}"
                    @if($activeOrg !== 'dkm') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#909A8F'" @endif>
                    DKM
                </button>
                <button wire:click="$set('activeOrg', 'semua')"
                    class="px-5 py-2 rounded-lg text-sm font-medium transition-all"
                    style="{{ $activeOrg === 'semua' ? 'background:#164A40;color:#ffffff;' : 'color:#909A8F;' }}"
                    @if($activeOrg !== 'semua') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#909A8F'" @endif>
                    Semua
                </button>
            </div>

            @php
                $orgLabel = match($activeOrg) { 'perumahan' => 'Perumahan', 'dkm' => 'DKM', default => 'Semua Organisasi' };
            @endphp

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Total Aset</p>
                    <p class="text-2xl font-bold mt-1.5 font-mono" style="color:#17231E;">Rp {{ number_format($totalAset, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Total Liabilitas</p>
                    <p class="text-2xl font-bold mt-1.5 font-mono" style="color:#B0402C;">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Total Ekuitas</p>
                    <p class="text-2xl font-bold mt-1.5 font-mono" style="color:#12805c;">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Balance Sheet Tables --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Aset --}}
                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <div class="px-5 py-4" style="background:linear-gradient(135deg,#E4F1EB,#E4F1EB);border-bottom:1px solid rgba(18,128,92,0.25);">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 rounded-full" style="background:#12805c;"></div>
                            <h3 class="font-bold text-base" style="color:#12805c;font-family:'Fraunces',Georgia,serif;">ASET (Harta)</h3>
                        </div>
                        <p class="text-xs mt-0.5 ml-3" style="color:#909A8F;">Kas &amp; Setara Kas</p>
                    </div>
                    <div class="p-5 space-y-1">
                        @forelse($asetLancar as $account)
                            <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #F1F3EC;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    <span class="text-sm truncate" style="color:#17231E;">{{ $account->name }}</span>
                                    @if($activeOrg === 'semua')
                                        @php
                                            $orgType = $account->organization_type ?? 'umum';
                                        @endphp
                                        @if($orgType === 'perumahan')
                                            <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.15);">Perumahan</span>
                                        @elseif($orgType === 'dkm')
                                            <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.15);">DKM</span>
                                        @else
                                            <span class="shrink-0 inline-flex items-center px-1.5 py-0.5 rounded text-xs" style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">Umum</span>
                                        @endif
                                    @endif
                                </div>
                                <span class="font-mono text-sm font-semibold ml-3 shrink-0" style="color:{{ $account->balance >= 0 ? '#164A40' : '#B0402C' }};">
                                    Rp {{ number_format($account->balance, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-center py-6" style="color:#909A8F;">Tidak ada akun terdaftar.</p>
                        @endforelse
                        <div class="flex justify-between items-center pt-4 mt-2" style="border-top:2px solid #E0DFD4;">
                            <span class="text-sm font-bold" style="color:#17231E;">TOTAL ASET</span>
                            <span class="font-mono font-bold text-base" style="color:#17231E;">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Liabilitas & Ekuitas --}}
                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <div class="px-5 py-4" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.25);">
                        <div class="flex items-center gap-2">
                            <div class="w-1 h-5 rounded-full" style="background:#164A40;"></div>
                            <h3 class="font-bold text-base" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">LIABILITAS &amp; EKUITAS</h3>
                        </div>
                        <p class="text-xs mt-0.5 ml-3" style="color:#909A8F;">Sumber pendanaan — {{ $orgLabel }}</p>
                    </div>
                    <div class="p-5">
                        <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#909A8F;">Liabilitas (Utang)</h4>
                        <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #F1F3EC;">
                            <span class="text-sm" style="color:#17231E;">Utang Pihak Ketiga</span>
                            <span class="font-mono text-sm font-semibold" style="color:#B0402C;">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</span>
                        </div>

                        <h4 class="text-xs font-semibold uppercase tracking-wider mt-5 mb-3" style="color:#909A8F;">Ekuitas</h4>
                        <div class="flex justify-between items-center py-2.5" style="border-bottom:1px solid #F1F3EC;">
                            <span class="text-sm" style="color:#17231E;">Dana {{ $orgLabel }} (Aset − Liabilitas)</span>
                            <span class="font-mono text-sm font-semibold" style="color:#12805c;">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-between items-center pt-4 mt-2" style="border-top:2px solid #E0DFD4;">
                            <span class="text-sm font-bold" style="color:#17231E;">TOTAL LIABILITAS &amp; EKUITAS</span>
                            <span class="font-mono font-bold text-base" style="color:#17231E;">Rp {{ number_format($totalLiabilitas + $totalEkuitas, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
