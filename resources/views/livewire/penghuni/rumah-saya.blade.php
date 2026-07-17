<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-5">
        <h2 class="text-xl font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Rumah Saya</h2>
        <p class="text-sm mt-0.5" style="color:#5c6368;">Daftar rumah yang Anda miliki</p>
    </div>

    @forelse($ownedBlocks as $hb)
        @php
            $photo = $hb->photos->firstWhere('is_primary') ?? $hb->photos->first();
            $tenant = $activeTenants->get($hb->id);
            $contractEnding = $tenant && $tenant->contract_end_date
                && $tenant->contract_end_date->diffInDays(now()) <= 30;
            $isForSale = ($hb->listing_type ?? 'sewa') === 'jual';
        @endphp
        <a href="{{ route('penghuni.detail-rumah', $hb->id) }}" wire:navigate
            class="block rounded-2xl p-5 sm:p-6 mb-4 transition-all"
            style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);{{ $contractEnding ? 'border-color:rgba(199,125,26,0.5);' : '' }}"
            onmouseover="this.style.boxShadow='0 4px 24px rgba(21,99,223,0.12)';this.style.borderColor='rgba(21,99,223,0.3)'"
            onmouseout="this.style.boxShadow='0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06)';this.style.borderColor='{{ $contractEnding ? 'rgba(199,125,26,0.5)' : '#e4e4e4' }}'">

            <div class="flex gap-4">
                {{-- Photo --}}
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden shrink-0" style="background:#f0f0f0;">
                    @if($photo)
                        <img src="{{ Storage::disk('public')->url($photo->photo_path) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-8 h-8" style="color:#d0d0d0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2 mb-1">
                        <div>
                            <h3 class="text-sm font-bold" style="color:#161e2d;">Blok {{ $hb->block_code }}</h3>
                            <p class="text-xs mt-0.5" style="color:#a3abb0;">{{ $hb->block_letter }}-{{ $hb->unit_number }}</p>
                        </div>
                        <svg class="w-4 h-4 shrink-0 mt-0.5" style="color:#d0d0d0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        {{-- Status badge --}}
                        @if($tenant)
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-0.5 rounded-md"
                                style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">
                                Disewa — {{ $tenant->resident->name }}
                            </span>
                            @if($contractEnding)
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md"
                                    style="background:rgba(192,69,59,0.1);color:#c0453b;">
                                    Berakhir {{ $tenant->contract_end_date->format('d M Y') }}
                                </span>
                            @endif
                        @elseif($hb->is_for_rent)
                            <span class="inline-flex items-center text-[11px] font-semibold px-2 py-0.5 rounded-md"
                                style="background:{{ $isForSale ? 'rgba(21,99,223,0.1)' : 'rgba(18,128,92,0.1)' }};color:{{ $isForSale ? '#1563df' : '#12805c' }};border:1px solid {{ $isForSale ? 'rgba(21,99,223,0.2)' : 'rgba(18,128,92,0.2)' }};">
                                {{ $isForSale ? 'Dijual' : 'Disewakan' }}
                            </span>
                            @if($hb->rental_price)
                                <span class="text-[11px] font-medium" style="color:#161e2d;">
                                    Rp {{ number_format($hb->rental_price, 0, ',', '.') }}
                                    @if(!$isForSale)
                                        /{{ match($hb->rental_duration ?? 'bulanan') { '6bulan' => '6bln', 'tahunan' => 'thn', default => 'bln' } }}
                                    @endif
                                </span>
                            @endif
                        @else
                            <span class="inline-flex items-center text-[11px] font-medium px-2 py-0.5 rounded-md"
                                style="background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.15);">
                                Dihuni
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="rounded-2xl p-8 text-center" style="background:#ffffff;border:1px dashed #e4e4e4;">
            <svg class="w-10 h-10 mx-auto mb-3" style="color:#a3abb0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <p class="text-sm font-medium" style="color:#5c6368;">Anda belum memiliki rumah sebagai pemilik.</p>
            <p class="text-xs mt-1" style="color:#a3abb0;">Hubungi admin untuk mendaftarkan rumah Anda.</p>
        </div>
    @endforelse
</div>
