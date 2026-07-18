<div>
    {{-- Back link --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <a href="{{ route('welcome') }}" wire:navigate
            class="inline-flex items-center gap-1.5 text-sm font-medium transition-colors"
            style="color:#586359;"
            onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

            {{-- ═══════════ LEFT: Gallery ═══════════ --}}
            <div class="lg:col-span-3 space-y-3">

                {{-- Main Photo --}}
                <div class="relative rounded-2xl overflow-hidden aspect-[4/3]" style="background:#F1F3EC;border:1px solid #E0DFD4;">
                    @if($houseBlock->photos->count() > 0)
                        @php $currentPhoto = $houseBlock->photos[$activePhotoIndex] ?? $houseBlock->photos->first(); @endphp
                        <img src="{{ Storage::disk('public')->url($currentPhoto->photo_path) }}"
                            class="w-full h-full object-cover"
                            alt="Rumah Blok {{ $houseBlock->block_code }}">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2">
                            <svg class="w-16 h-16" style="color:#C9C7BA;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <p class="text-sm" style="color:#909A8F;">Belum ada foto</p>
                        </div>
                    @endif

                    {{-- Overlay: arrows + counter --}}
                    @if($houseBlock->photos->count() > 1)
                        <div class="absolute inset-0 z-20 flex items-center justify-between px-3">
                            <button wire:click="prevPhoto"
                                class="w-9 h-9 rounded-full flex items-center justify-center transition-colors shrink-0"
                                style="background:rgba(0,0,0,0.45);color:#fff;backdrop-filter:blur(4px);"
                                onmouseover="this.style.background='rgba(0,0,0,0.7)'" onmouseout="this.style.background='rgba(0,0,0,0.45)'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            </button>
                            <button wire:click="nextPhoto"
                                class="w-9 h-9 rounded-full flex items-center justify-center transition-colors shrink-0"
                                style="background:rgba(0,0,0,0.45);color:#fff;backdrop-filter:blur(4px);"
                                onmouseover="this.style.background='rgba(0,0,0,0.7)'" onmouseout="this.style.background='rgba(0,0,0,0.45)'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        </div>
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 z-20 px-3 py-1 rounded-full text-[11px] font-semibold" style="background:rgba(0,0,0,0.5);color:#fff;backdrop-filter:blur(4px);">
                            {{ $activePhotoIndex + 1 }} / {{ $houseBlock->photos->count() }}
                        </div>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($houseBlock->photos->count() > 1)
                    <div class="flex gap-2 overflow-x-auto pb-1">
                        @foreach($houseBlock->photos as $idx => $photo)
                            <button wire:click="setActivePhoto({{ $idx }})"
                                class="shrink-0 w-16 h-16 rounded-xl overflow-hidden transition-all"
                                style="border:{{ $activePhotoIndex === $idx ? '2px solid #164A40' : '2px solid #E0DFD4' }};opacity:{{ $activePhotoIndex === $idx ? '1' : '0.7' }};">
                                <img src="{{ Storage::disk('public')->url($photo->photo_path) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ═══════════ RIGHT: Info ═══════════ --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Title & Badge --}}
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-lg" style="background:{{ ($houseBlock->listing_type ?? 'sewa') === 'jual' ? 'rgba(22,74,64,0.1)' : 'rgba(18,128,92,0.1)' }};color:{{ ($houseBlock->listing_type ?? 'sewa') === 'jual' ? '#164A40' : '#12805c' }};border:1px solid {{ ($houseBlock->listing_type ?? 'sewa') === 'jual' ? 'rgba(22,74,64,0.2)' : 'rgba(18,128,92,0.2)' }};">
                            {{ ($houseBlock->listing_type ?? 'sewa') === 'jual' ? 'Dijual' : 'Disewakan' }}
                        </span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold" style="color:#17231E;font-family:'Plus Jakarta Sans',sans-serif;">
                        Blok {{ $houseBlock->block_code }}
                    </h1>
                    <p class="text-sm mt-1" style="color:#909A8F;">{{ $houseBlock->block_letter }}-{{ $houseBlock->unit_number }}</p>
                </div>

                {{-- Price --}}
                @if($houseBlock->rental_price)
                    <div class="rounded-2xl p-5" style="background:linear-gradient(135deg,rgba(22,74,64,0.06),rgba(22,74,64,0.02));border:1px solid rgba(22,74,64,0.15);">
                        <p class="text-xs font-medium uppercase tracking-wide mb-1" style="color:#909A8F;">Harga {{ ($houseBlock->listing_type ?? 'sewa') === 'jual' ? 'Jual' : 'Sewa' }}</p>
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-2xl sm:text-3xl font-extrabold" style="color:#164A40;">Rp {{ number_format($houseBlock->rental_price, 0, ',', '.') }}</span>
                            @if(($houseBlock->listing_type ?? 'sewa') === 'sewa')
                                <span class="text-sm font-medium" style="color:#909A8F;">/ {{ match($houseBlock->rental_duration ?? 'bulanan') { '6bulan' => '6 bulan', 'tahunan' => 'tahun', default => 'bulan' } }}</span>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Description --}}
                @if($houseBlock->rental_description)
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;">
                        <h3 class="text-sm font-semibold mb-2" style="color:#17231E;">Deskripsi</h3>
                        <p class="text-sm leading-relaxed whitespace-pre-line" style="color:#586359;">{{ $houseBlock->rental_description }}</p>
                    </div>
                @endif

                {{-- Property Specs --}}
                @if($houseBlock->land_area || $houseBlock->building_area || $houseBlock->bedrooms || $houseBlock->bathrooms || $houseBlock->electricity || $houseBlock->water_source || $houseBlock->garage)
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;">
                        <h3 class="text-sm font-semibold mb-3" style="color:#17231E;">Spesifikasi Properti</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @if($houseBlock->land_area)
                                <div class="rounded-xl p-3" style="background:#F1F3EC;">
                                    <p class="text-[11px]" style="color:#909A8F;">Luas Tanah</p>
                                    <p class="text-sm font-semibold" style="color:#17231E;">{{ $houseBlock->land_area }} m²</p>
                                </div>
                            @endif
                            @if($houseBlock->building_area)
                                <div class="rounded-xl p-3" style="background:#F1F3EC;">
                                    <p class="text-[11px]" style="color:#909A8F;">Luas Bangunan</p>
                                    <p class="text-sm font-semibold" style="color:#17231E;">{{ $houseBlock->building_area }} m²</p>
                                </div>
                            @endif
                            @if($houseBlock->bedrooms)
                                <div class="rounded-xl p-3" style="background:#F1F3EC;">
                                    <p class="text-[11px]" style="color:#909A8F;">Kamar Tidur</p>
                                    <p class="text-sm font-semibold" style="color:#17231E;">{{ $houseBlock->bedrooms }} KT</p>
                                </div>
                            @endif
                            @if($houseBlock->bathrooms)
                                <div class="rounded-xl p-3" style="background:#F1F3EC;">
                                    <p class="text-[11px]" style="color:#909A8F;">Kamar Mandi</p>
                                    <p class="text-sm font-semibold" style="color:#17231E;">{{ $houseBlock->bathrooms }} KM</p>
                                </div>
                            @endif
                            @if($houseBlock->electricity)
                                <div class="rounded-xl p-3" style="background:#F1F3EC;">
                                    <p class="text-[11px]" style="color:#909A8F;">Listrik</p>
                                    <p class="text-sm font-semibold" style="color:#17231E;">{{ number_format($houseBlock->electricity) }} W</p>
                                </div>
                            @endif
                            @if($houseBlock->water_source)
                                <div class="rounded-xl p-3" style="background:#F1F3EC;">
                                    <p class="text-[11px]" style="color:#909A8F;">Sumber Air</p>
                                    <p class="text-sm font-semibold" style="color:#17231E;">{{ $houseBlock->water_source_label }}</p>
                                </div>
                            @endif
                            @if($houseBlock->garage)
                                <div class="rounded-xl p-3" style="background:#F1F3EC;">
                                    <p class="text-[11px]" style="color:#909A8F;">Garasi</p>
                                    <p class="text-sm font-semibold" style="color:#17231E;">{{ $houseBlock->garage }} Mobil</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Contact Owner --}}
                @if($owner)
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;">
                        <h3 class="text-sm font-semibold mb-3" style="color:#17231E;">Kontak Pemilik</h3>
                        <div class="flex items-center gap-3 mb-4">
                            @if($owner->photo)
                                <img src="{{ Storage::disk('public')->url($owner->photo) }}" class="w-11 h-11 rounded-full object-cover" style="border:2px solid rgba(22,74,64,0.3);">
                            @else
                                <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-bold" style="background:rgba(22,74,64,0.15);color:#164A40;">
                                    {{ strtoupper(substr($owner->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold" style="color:#17231E;">{{ $owner->name }}</p>
                                @if($owner->phone)
                                    <p class="text-xs" style="color:#909A8F;">{{ $owner->phone }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2">
                            @if($owner->whatsapp || $owner->phone)
                                @php $wa = $owner->whatsapp ?? $owner->phone; @endphp
                                <a href="https://wa.me/{{ ltrim(preg_replace('/[^0-9]/', '', $wa), '0') }}?text=Halo%2C+saya+tertarik+untuk+menyewa+rumah+Blok+{{ $houseBlock->block_code }}"
                                    target="_blank" rel="noopener"
                                    class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl text-sm font-semibold transition-colors"
                                    style="background:#12805c;color:#ffffff;"
                                    onmouseover="this.style.background='#0e6844'" onmouseout="this.style.background='#12805c'">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    Hubungi via WhatsApp
                                </a>
                            @endif

                            @if($owner->phone)
                                <a href="tel:{{ $owner->phone }}"
                                    class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl text-sm font-medium transition-colors"
                                    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                                    onmouseover="this.style.borderColor='#164A40'" onmouseout="this.style.borderColor='#E0DFD4'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    Telepon Langsung
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Info Tambahan --}}
                <div class="rounded-2xl p-5 space-y-3" style="background:#ffffff;border:1px solid #E0DFD4;">
                    <h3 class="text-sm font-semibold" style="color:#17231E;">Informasi Rumah</h3>
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs" style="color:#909A8F;">Kode Unit</span>
                            <span class="text-xs font-semibold" style="color:#17231E;">{{ $houseBlock->block_code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs" style="color:#909A8F;">Blok</span>
                            <span class="text-xs font-semibold" style="color:#17231E;">{{ $houseBlock->block_letter }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs" style="color:#909A8F;">Nomor Unit</span>
                            <span class="text-xs font-semibold" style="color:#17231E;">{{ $houseBlock->unit_number }}</span>
                        </div>
                        @if($houseBlock->notes)
                            <div class="pt-2" style="border-top:1px solid #F1F3EC;">
                                <p class="text-xs" style="color:#909A8F;">Catatan:</p>
                                <p class="text-xs mt-0.5" style="color:#586359;">{{ $houseBlock->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
