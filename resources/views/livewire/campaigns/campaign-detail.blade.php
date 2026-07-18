<div>

    {{-- Flash messages --}}
    @if(session('success') && !$isDonationModalOpen && !$isGalleryModalOpen)
        <div class="fixed top-4 right-4 z-50 rounded-xl px-5 py-3 text-sm flex items-center gap-2 shadow-xl"
             style="background:rgba(18,128,92,0.15);border:1px solid rgba(18,128,92,0.35);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fixed top-4 right-4 z-50 rounded-xl px-5 py-3 text-sm flex items-center gap-2 shadow-xl"
             style="background:rgba(176,64,44,0.15);border:1px solid rgba(176,64,44,0.35);color:#B0402C;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('campaigns.index') }}" wire:navigate
               class="inline-flex items-center gap-1.5 text-sm transition-colors"
               style="color:#17231E;"
               onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#164A40'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Program
            </a>
            <span style="color:#909A8F;">/</span>
            <span class="text-sm truncate max-w-xs" style="color:#909A8F;">{{ $campaign->name }}</span>
        </div>

        {{-- Hero Image --}}
        @if($campaign->image)
            <div class="relative w-full rounded-2xl overflow-hidden" style="max-height:360px;">
                <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->name }}"
                     class="w-full object-cover" style="max-height:360px;width:100%;object-fit:cover;">
                <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.1) 0%, transparent 50%);"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <div class="flex flex-wrap items-end gap-3">
                        <h1 class="text-2xl sm:text-3xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;text-shadow:0 2px 8px rgba(0,0,0,0.1);">
                            {{ $campaign->name }}
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                            style="{{ $campaign->status === 'active' ? 'background:rgba(18,128,92,0.2);color:#12805c;border:1px solid rgba(18,128,92,0.4);' : ($campaign->status === 'completed' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.4);' : 'background:rgba(176,64,44,0.2);color:#B0402C;border:1px solid rgba(176,64,44,0.4);') }}">
                            {{ $campaign->status === 'active' ? 'Aktif' : ($campaign->status === 'completed' ? 'Selesai' : 'Dibatalkan') }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                            style="{{ $campaign->organization_type === 'perumahan' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.4);' : 'background:rgba(18,128,92,0.15);color:#12805c;border:1px solid rgba(18,128,92,0.3);' }}">
                            {{ $campaign->organization_type === 'perumahan' ? 'Perumahan' : 'DKM Masjid' }}
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-2xl p-6 pp-hero" style="background:#F1F3EC;border:1px solid rgba(22,74,64,0.35);">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">{{ $campaign->name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                        style="{{ $campaign->status === 'active' ? 'background:rgba(18,128,92,0.2);color:#12805c;border:1px solid rgba(18,128,92,0.4);' : ($campaign->status === 'completed' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.4);' : 'background:rgba(176,64,44,0.2);color:#B0402C;border:1px solid rgba(176,64,44,0.4);') }}">
                        {{ $campaign->status === 'active' ? 'Aktif' : ($campaign->status === 'completed' ? 'Selesai' : 'Dibatalkan') }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                        style="{{ $campaign->organization_type === 'perumahan' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.4);' : 'background:rgba(18,128,92,0.15);color:#12805c;border:1px solid rgba(18,128,92,0.3);' }}">
                        {{ $campaign->organization_type === 'perumahan' ? 'Perumahan' : 'DKM Masjid' }}
                    </span>
                </div>
                @if($campaign->description)
                    <p class="mt-2 text-sm" style="color:#17231E;">{{ $campaign->description }}</p>
                @endif
            </div>
        @endif

        {{-- Main 2-col layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: Content (2/3) --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Short description --}}
                @if($campaign->description)
                    @php $descHasHtml = $campaign->description !== strip_tags($campaign->description); @endphp
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        @if($descHasHtml)
                            <div class="prose-campaign text-sm leading-relaxed" style="color:#17231E;">{!! $campaign->description !!}</div>
                        @else
                            <p class="text-sm leading-relaxed" style="color:#17231E;">{!! nl2br(e($campaign->description)) !!}</p>
                        @endif
                    </div>
                @endif

                {{-- Location --}}
                @if($campaign->location)
                    <div class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm" style="background:#ffffff;border:1px solid #E0DFD4;color:#586359;">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $campaign->location }}</span>
                    </div>
                @endif

                {{-- Full blog content --}}
                @if($campaign->content)
                    <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                        <h2 class="text-base font-bold mb-4" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Deskripsi Program</h2>
                        <div class="prose-campaign" style="color:#17231E;line-height:1.8;font-size:0.9375rem;">
                            {!! $campaign->content !!}
                        </div>
                    </div>
                @endif

                {{-- Video embed --}}
                @if($campaign->video_url)
                    <div class="rounded-2xl overflow-hidden" style="border:1px solid #E0DFD4;">
                        @php
                            $videoUrl = $campaign->video_url;
                            $embedUrl = $videoUrl;
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
                                $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                            }
                        @endphp
                        <div class="relative" style="padding-bottom:56.25%;">
                            <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full rounded-2xl"
                                frameborder="0" allowfullscreen
                                style="background:#ffffff;"></iframe>
                        </div>
                    </div>
                @endif

                {{-- Photo Gallery --}}
                @if($campaign->photos->count() > 0)
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);"
                         x-data="{ lightbox: null }">
                        <h2 class="text-base font-bold mb-4" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                            Galeri Foto
                            <span class="ml-2 text-xs font-normal px-2 py-0.5 rounded-full" style="background:rgba(22,74,64,0.1);color:#17231E;">{{ $campaign->photos->count() }} foto</span>
                        </h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($campaign->photos as $photo)
                                <div class="relative group rounded-xl overflow-hidden cursor-pointer"
                                     style="aspect-ratio:4/3;"
                                     wire:key="photo-{{ $photo->id }}"
                                     @click="lightbox = '{{ Storage::url($photo->photo_path) }}'">
                                    <img src="{{ Storage::url($photo->photo_path) }}"
                                         alt="{{ $photo->caption ?? '' }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity flex items-end"
                                         style="background:linear-gradient(to top,rgba(0,0,0,0.1) 0%,transparent 60%);">
                                        @if($photo->caption)
                                            <p class="text-xs p-2" style="color:#17231E;">{{ $photo->caption }}</p>
                                        @endif
                                    </div>
                                    @can('manage-transactions')
                                    <button wire:click.stop="deletePhoto({{ $photo->id }})"
                                            wire:confirm="Hapus foto ini?"
                                            class="absolute top-2 right-2 w-6 h-6 rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                            style="background:rgba(176,64,44,0.9);color:#17231E;">✕</button>
                                    @endcan
                                </div>
                            @endforeach
                        </div>

                        {{-- Lightbox --}}
                        <div x-show="lightbox" x-transition
                             @click="lightbox = null"
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                             style="background:rgba(0,0,0,0.1);">
                            <button @click="lightbox = null" class="absolute top-4 right-4 w-10 h-10 rounded-full flex items-center justify-center text-lg" style="background:rgba(255,255,255,0.1);color:#17231E;">✕</button>
                            <img :src="lightbox" class="rounded-2xl object-contain" style="max-width:90vw;max-height:85vh;" @click.stop>
                        </div>
                    </div>
                @endif

            </div>

            {{-- RIGHT: Sidebar (1/3) --}}
            <div class="space-y-4">

                {{-- Progress Card --}}
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <h3 class="text-sm font-semibold mb-4" style="color:#17231E;">Progres Donasi</h3>
                    @php
                        $target  = (float)($campaign->target_amount ?? 0);
                        $raised  = (float)($stats['total_uang'] ?? 0);
                        $progress = $target > 0 ? min(100, round($raised / $target * 100)) : ($raised > 0 ? 100 : 0);
                    @endphp
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs mb-1.5" style="color:#909A8F;">
                                <span>Terkumpul</span>
                                <span style="color:#17231E;font-weight:600;">{{ $progress }}%</span>
                            </div>
                            <div class="w-full rounded-full h-2" style="background:#E0DFD4;">
                                <div class="h-2 rounded-full transition-all"
                                     style="width:{{ $progress }}%;background:{{ $progress >= 100 ? '#12805c' : 'linear-gradient(90deg,#164A40,#164A40)' }};"></div>
                            </div>
                        </div>
                        <div class="text-xl font-bold" style="color:#12805c;">Rp {{ number_format($raised, 0, ',', '.') }}</div>
                        @if($target > 0)
                            <div class="text-xs" style="color:#909A8F;">dari target Rp {{ number_format($target, 0, ',', '.') }}</div>
                        @endif
                        @if($campaign->end_date)
                            @php
                                $daysLeft = now()->startOfDay()->diffInDays($campaign->end_date->copy()->startOfDay(), false);
                            @endphp
                            <div class="text-xs px-3 py-2 rounded-lg text-center"
                                 style="background:{{ $daysLeft < 0 ? 'rgba(176,64,44,0.08)' : 'rgba(22,74,64,0.08)' }};color:{{ $daysLeft < 0 ? '#B0402C' : '#164A40' }};">
                                @if($daysLeft < 0)
                                    Program telah berakhir
                                @elseif($daysLeft === 0)
                                    Hari terakhir
                                @else
                                    {{ $daysLeft }} hari lagi
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="rounded-2xl p-5 space-y-3" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <h3 class="text-sm font-semibold" style="color:#17231E;">Statistik Donasi</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #F1F3EC;">
                            <div class="text-xl font-bold" style="color:#17231E;">{{ $stats['count_uang'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#909A8F;">Donasi Uang</div>
                        </div>
                        <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #F1F3EC;">
                            <div class="text-xl font-bold" style="color:#17231E;">{{ $stats['count_barang'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#909A8F;">Donasi Barang</div>
                        </div>
                        <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #F1F3EC;">
                            <div class="text-xl font-bold" style="color:#17231E;">{{ $stats['count_warga'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#909A8F;">Dari Warga</div>
                        </div>
                        <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #F1F3EC;">
                            <div class="text-xl font-bold" style="color:#586359;">{{ $stats['count_luaran'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#909A8F;">Dari Luaran</div>
                        </div>
                    </div>
                </div>

                {{-- Periode --}}
                <div class="rounded-2xl p-4" style="background:#ffffff;border:1px solid #E0DFD4;">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span style="color:#909A8F;">Mulai</span>
                            <span style="color:#17231E;">{{ optional($campaign->start_date)->format('d M Y') ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color:#909A8F;">Selesai</span>
                            <span style="color:#17231E;">{{ optional($campaign->end_date)->format('d M Y') ?? 'Tidak ditentukan' }}</span>
                        </div>
                        @if($campaign->sourceAccount)
                            <div class="flex justify-between">
                                <span style="color:#909A8F;">Sumber Dana</span>
                                <span class="font-medium" style="color:#17231E;">{{ $campaign->sourceAccount->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Admin actions --}}
                @can('manage-transactions')
                <div class="rounded-2xl p-4 space-y-2" style="background:#ffffff;border:1px solid #E0DFD4;">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#909A8F;">Kelola Program</p>
                    <a href="{{ route('campaigns.edit', $campaign) }}" wire:navigate
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium w-full transition-colors"
                       style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);"
                       onmouseover="this.style.background='rgba(22,74,64,0.2)'" onmouseout="this.style.background='rgba(22,74,64,0.1)'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Program
                    </a>
                    <button wire:click="openGalleryUpload()"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium w-full transition-colors"
                        style="background:#ffffff;color:#586359;border:1px solid #E0DFD4;"
                        onmouseover="this.style.color='#164A40';this.style.borderColor='rgba(22,74,64,0.3)'" onmouseout="this.style.color='#586359';this.style.borderColor='#E0DFD4'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Tambah Foto Galeri
                    </button>
                </div>
                @endcan

            </div>
        </div>

        {{-- Donations Section --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h2 class="text-lg font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                    Daftar Donasi
                    <span class="ml-2 text-sm font-normal" style="color:#909A8F;">({{ $stats['count_uang'] + $stats['count_barang'] }} total)</span>
                </h2>
                @can('manage-transactions')
                <button wire:click="openAddDonation()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold shrink-0"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Donasi
                </button>
                @endcan
            </div>

            {{-- Filters --}}
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('filterDonationForm', '')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonationForm === '' ? 'background:#164A40;color:#ffffff;' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}"
                    @if($filterDonationForm !== '') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'" @endif>
                    Semua
                </button>
                <button wire:click="$set('filterDonationForm', 'uang')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonationForm === 'uang' ? 'background:#164A40;color:#ffffff;' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}"
                    @if($filterDonationForm !== 'uang') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'" @endif>
                    Uang ({{ $stats['count_uang'] }})
                </button>
                <button wire:click="$set('filterDonationForm', 'barang')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonationForm === 'barang' ? 'background:#164A40;color:#ffffff;' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}"
                    @if($filterDonationForm !== 'barang') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'" @endif>
                    Barang ({{ $stats['count_barang'] }})
                </button>
                <div style="width:1px;background:#E0DFD4;margin:0 4px;"></div>
                <button wire:click="$set('filterDonorType', '')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonorType === '' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.3);' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}"
                    @if($filterDonorType !== '') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'" @endif>
                    Semua Asal
                </button>
                <button wire:click="$set('filterDonorType', 'warga')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonorType === 'warga' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.3);' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}"
                    @if($filterDonorType !== 'warga') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'" @endif>
                    Warga ({{ $stats['count_warga'] }})
                </button>
                <button wire:click="$set('filterDonorType', 'luaran')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonorType === 'luaran' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.3);' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}"
                    @if($filterDonorType !== 'luaran') onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'" @endif>
                    Luaran ({{ $stats['count_luaran'] }})
                </button>
            </div>

            {{-- Donation Table --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Donatur</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Asal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Jenis</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nilai / Barang</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#909A8F;">Foto</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#909A8F;">Tanggal</th>
                                @can('manage-transactions')
                                <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donations as $donation)
                                <tr style="border-bottom:1px solid #F1F3EC;" wire:key="donation-{{ $donation->id }}"
                                    onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-sm" style="color:#17231E;">{{ $donation->donor_name }}</div>
                                        @if($donation->resident)
                                            <div class="text-xs mt-0.5" style="color:#909A8F;">{{ $donation->resident->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($donation->donor_type ?? 'luaran') === 'warga')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Warga</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);">Luaran</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($donation->donation_form ?? 'uang') === 'uang')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Uang</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">Barang</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($donation->donation_form ?? 'uang') === 'uang')
                                            <span class="font-mono font-semibold text-sm" style="color:#12805c;">Rp {{ number_format($donation->transaction?->amount ?? 0, 0, ',', '.') }}</span>
                                        @else
                                            <div class="text-sm" style="color:#17231E;">{{ $donation->item_description }}</div>
                                            @if($donation->item_quantity)
                                                <div class="text-xs" style="color:#909A8F;">{{ $donation->item_quantity }}</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 hidden md:table-cell">
                                        @if($donation->item_photo_path)
                                            <img src="{{ Storage::url($donation->item_photo_path) }}"
                                                 alt="Foto barang"
                                                 class="w-10 h-10 rounded-lg object-cover cursor-pointer"
                                                 style="border:1px solid #E0DFD4;"
                                                 onclick="window.open('{{ Storage::url($donation->item_photo_path) }}', '_blank')">
                                        @else
                                            <span style="color:#909A8F;">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 hidden sm:table-cell text-xs whitespace-nowrap" style="color:#909A8F;">
                                        {{ optional($donation->created_at)->format('d M Y') }}
                                    </td>
                                    @can('manage-transactions')
                                    <td class="px-4 py-3 text-center">
                                        <button wire:click="confirmDeleteDonation({{ $donation->id }})"
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                            style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                            onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                                            Hapus
                                        </button>
                                    </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-14 text-center">
                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        <p class="text-sm" style="color:#909A8F;">Belum ada donasi untuk program ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                    @forelse($donations as $donation)
                        <div class="p-4 space-y-2" wire:key="donation-mobile-{{ $donation->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="font-medium text-sm truncate" style="color:#17231E;">{{ $donation->donor_name }}</div>
                                    @if($donation->resident)
                                        <div class="text-xs mt-0.5" style="color:#909A8F;">{{ $donation->resident->name }}</div>
                                    @endif
                                    <div class="text-xs mt-0.5" style="color:#909A8F;">{{ optional($donation->created_at)->format('d M Y') }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    @if(($donation->donation_form ?? 'uang') === 'uang')
                                        <span class="font-mono font-semibold text-sm" style="color:#12805c;">Rp {{ number_format($donation->transaction?->amount ?? 0, 0, ',', '.') }}</span>
                                    @else
                                        <div class="text-sm" style="color:#17231E;">{{ $donation->item_description }}</div>
                                        @if($donation->item_quantity)
                                            <div class="text-xs" style="color:#909A8F;">{{ $donation->item_quantity }}</div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                @if(($donation->donor_type ?? 'luaran') === 'warga')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Warga</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);">Luaran</span>
                                @endif
                                @if(($donation->donation_form ?? 'uang') === 'uang')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Uang</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">Barang</span>
                                @endif
                            </div>

                            @if($donation->item_photo_path)
                                <img src="{{ Storage::url($donation->item_photo_path) }}"
                                     alt="Foto barang"
                                     class="w-14 h-14 rounded-lg object-cover cursor-pointer"
                                     style="border:1px solid #E0DFD4;"
                                     onclick="window.open('{{ Storage::url($donation->item_photo_path) }}', '_blank')">
                            @endif

                            @can('manage-transactions')
                            <div class="pt-1">
                                <button wire:click="confirmDeleteDonation({{ $donation->id }})"
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                    onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                                    Hapus
                                </button>
                            </div>
                            @endcan
                        </div>
                    @empty
                        <div class="px-4 py-14 text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <p class="text-sm" style="color:#909A8F;">Belum ada donasi untuk program ini.</p>
                        </div>
                    @endforelse
                </div>

                @if($donations->hasPages())
                    <div class="px-4 py-3" style="border-top:1px solid #F1F3EC;">{{ $donations->links() }}</div>
                @endif
            </div>
        </div>

        {{-- Expense Report --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <h2 class="text-lg font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                    Laporan Pengeluaran
                    <span class="ml-2 text-sm font-normal" style="color:#909A8F;">({{ $expenses->count() }} transaksi)</span>
                </h2>
                @can('manage-transactions')
                <button wire:click="openAddExpense()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold shrink-0"
                    style="background:#B0402C;color:#ffffff;"
                    onmouseover="this.style.background='#a3372e'" onmouseout="this.style.background='#B0402C'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pengeluaran
                </button>
                @endcan
            </div>
            <div class="rounded-2xl" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                @if($expenses->isEmpty())
                    <div class="px-5 py-12 text-center">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-sm" style="color:#909A8F;">Belum ada pengeluaran untuk program ini.</p>
                    </div>
                @else
                    <div class="p-4" style="border-bottom:1px solid #E0DFD4;">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium" style="color:#909A8F;">Total Pengeluaran</span>
                            <span class="text-lg font-bold" style="color:#B0402C;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr style="border-bottom:1px solid #F1F3EC;">
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Keterangan</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Kategori</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Akun</th>
                                    <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#909A8F;">Tanggal</th>
                                    <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Jumlah</th>
                                    @can('manage-transactions')
                                    <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $exp)
                                <tr style="border-bottom:1px solid #F1F3EC;"
                                    onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-4 py-3.5">
                                        <div class="font-medium text-sm" style="color:#17231E;">{{ $exp->description ?: '—' }}</div>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">
                                            {{ $exp->category?->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-xs" style="color:#909A8F;">{{ $exp->account?->name ?? '—' }}</td>
                                    <td class="px-4 py-3.5 hidden sm:table-cell text-xs whitespace-nowrap" style="color:#909A8F;">
                                        {{ $exp->transaction_date ? \Carbon\Carbon::parse($exp->transaction_date)->format('d M Y') : '—' }}
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        <span class="font-mono font-semibold text-sm" style="color:#B0402C;">
                                            Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    @can('manage-transactions')
                                    <td class="px-4 py-3.5 text-center">
                                        <button wire:click="confirmDeleteExpense({{ $exp->id }})"
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                            style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                            onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                                            Hapus
                                        </button>
                                    </td>
                                    @endcan
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                        @foreach($expenses as $exp)
                        <div class="px-4 py-3.5" wire:key="expense-{{ $exp->id }}">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="min-w-0 flex-1">
                                    <div class="font-medium text-sm" style="color:#17231E;">{{ $exp->description ?: '—' }}</div>
                                    <div class="text-xs mt-0.5" style="color:#909A8F;">
                                        {{ $exp->transaction_date ? \Carbon\Carbon::parse($exp->transaction_date)->format('d M Y') : '—' }}
                                    </div>
                                </div>
                                <div class="shrink-0 text-right">
                                    <span class="font-mono font-semibold text-sm" style="color:#B0402C;">
                                        Rp {{ number_format($exp->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">
                                    {{ $exp->category?->name ?? '—' }}
                                </span>
                                <span class="text-xs" style="color:#909A8F;">{{ $exp->account?->name ?? '—' }}</span>
                                @can('manage-transactions')
                                <button wire:click="confirmDeleteExpense({{ $exp->id }})"
                                    class="ml-auto inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium"
                                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                    onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                                    Hapus
                                </button>
                                @endcan
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Donation Modal --}}
    @if($isDonationModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-on:keydown.escape.window="$wire.closeDonationModal()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeDonationModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #D8D6C9;max-height:92vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Tambah Donasi</h3>
                <button wire:click="closeDonationModal()" class="p-1 rounded-lg" style="color:#17231E;"
                    onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($errors->any() || session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(169,116,26,0.1);border:1px solid rgba(169,116,26,0.3);color:#A9741A;">
                    @if(session('modal_error'))
                        {{ session('modal_error') }}
                    @else
                        <ul class="list-disc pl-4 space-y-0.5 text-xs">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    @endif
                </div>
            @endif

            <form wire:submit="saveDonation" class="overflow-y-auto px-6 py-5 space-y-4">

                {{-- Jenis Donasi (uang/barang) --}}
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#586359;">Jenis Donasi <span style="color:#B0402C;">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 px-4 py-3 rounded-xl cursor-pointer transition-colors"
                               style="{{ $donationForm === 'uang' ? 'background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.4);' : 'background:#ffffff;border:1px solid #E0DFD4;' }}">
                            <input type="radio" wire:model.live="donationForm" value="uang" style="accent-color:#12805c;">
                            <span class="text-sm font-medium" style="{{ $donationForm === 'uang' ? 'color:#12805c;' : 'color:#586359;' }}">Uang</span>
                        </label>
                        <label class="flex items-center gap-2 px-4 py-3 rounded-xl cursor-pointer transition-colors"
                               style="{{ $donationForm === 'barang' ? 'background:rgba(169,116,26,0.1);border:1px solid rgba(169,116,26,0.4);' : 'background:#ffffff;border:1px solid #E0DFD4;' }}">
                            <input type="radio" wire:model.live="donationForm" value="barang" style="accent-color:#A9741A;">
                            <span class="text-sm font-medium" style="{{ $donationForm === 'barang' ? 'color:#A9741A;' : 'color:#586359;' }}">Barang</span>
                        </label>
                    </div>
                </div>

                {{-- Asal Donatur (default: Penghuni) --}}
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#586359;">Asal Donatur <span style="color:#B0402C;">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'penghuni' ? 'background:rgba(22,74,64,0.1);border:1px solid rgba(22,74,64,0.4);' : 'background:#ffffff;border:1px solid #E0DFD4;' }}">
                            <input type="radio" wire:model.live="donorType" value="penghuni" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'penghuni' ? 'color:#17231E;' : 'color:#586359;' }}">Penghuni</span>
                        </label>
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'hamba_allah' ? 'background:rgba(22,74,64,0.1);border:1px solid rgba(22,74,64,0.4);' : 'background:#ffffff;border:1px solid #E0DFD4;' }}">
                            <input type="radio" wire:model.live="donorType" value="hamba_allah" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'hamba_allah' ? 'color:#17231E;' : 'color:#586359;' }}">Hamba Allah</span>
                        </label>
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'luar' ? 'background:rgba(107,91,149,0.1);border:1px solid rgba(107,91,149,0.4);' : 'background:#ffffff;border:1px solid #E0DFD4;' }}">
                            <input type="radio" wire:model.live="donorType" value="luar" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'luar' ? 'color:#6B5B95;' : 'color:#586359;' }}">Donatur Lain</span>
                        </label>
                    </div>
                </div>

                {{-- Detail sesuai asal donatur --}}
                @if($donorType === 'penghuni')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Pilih Penghuni <span style="color:#B0402C;">*</span></label>
                    <select wire:model.live="residentId"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        <option value="">-- Pilih penghuni --</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}">{{ $resident->name }}</option>
                        @endforeach
                    </select>
                    @error('residentId')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                @elseif($donorType === 'luar')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Nama Donatur <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="donorName" placeholder="Nama lengkap donatur"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('donorName')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                @else
                <div class="px-3 py-2.5 rounded-xl text-xs" style="background:#ffffff;border:1px solid #F1F3EC;color:#909A8F;">
                    Donasi dicatat atas nama <strong style="color:#586359;">Hamba Allah</strong> (anonim).
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Tanggal Donasi <span style="color:#B0402C;">*</span></label>
                    <input type="date" wire:model="donationDate"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;color-scheme:dark;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('donationDate')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>

                {{-- Uang fields --}}
                @if($donationForm === 'uang')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Jumlah (Rp) <span style="color:#B0402C;">*</span></label>
                        <input type="number" wire:model="donationAmount" min="1" step="1000" placeholder="0"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('donationAmount')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Tipe Donasi</label>
                        <select wire:model="donationType"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            <option value="infaq">Infaq / Sedekah</option>
                            <option value="zakat">Zakat</option>
                            <option value="wakaf">Wakaf</option>
                            <option value="donasi">Donasi Umum</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Akun Penerimaan <span style="color:#B0402C;">*</span></label>
                    <select wire:model="accountId"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        <option value="">-- Pilih Akun --</option>
                        @foreach($orgAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                    @error('accountId')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Barang fields --}}
                @if($donationForm === 'barang')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Nama / Deskripsi Barang <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="itemDescription" placeholder="Contoh: Semen Portland, Kursi Plastik, dll"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('itemDescription')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Jumlah / Satuan <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="itemQuantity" placeholder="Contoh: 10 karung, 5 buah, 2 meter"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('itemQuantity')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                <div x-data="{ previewItem: null }">
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Foto Barang (Opsional)</label>
                    <div class="rounded-xl p-3 flex flex-col gap-2" style="background:#ffffff;border:1px dashed #D8D6C9;">
                        <input type="file" wire:model="itemPhoto" accept="image/*"
                               @change="previewItem = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                               class="block w-full text-xs"
                               style="color:#909A8F;"
                               x-ref="itemPhotoInput">
                        <div wire:loading wire:target="itemPhoto" class="text-xs" style="color:#17231E;">Mengunggah...</div>
                        <img x-show="previewItem" :src="previewItem" x-transition
                             class="rounded-xl object-cover mt-1" style="max-height:140px;max-width:100%;border:1px solid #E0DFD4;">
                    </div>
                    @error('itemPhoto')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Catatan (Opsional)</label>
                    <textarea wire:model="donationNotes" rows="2" placeholder="Pesan atau keterangan tambahan"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closeDonationModal()"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold disabled:opacity-50"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                        <span wire:loading.remove>Simpan Donasi</span>
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

    {{-- Gallery Upload Modal --}}
    @if($isGalleryModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-on:keydown.escape.window="$wire.closeGalleryModal()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeGalleryModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-md" style="background:#ffffff;border:1px solid #D8D6C9;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Tambah Foto Galeri</h3>
                <button wire:click="closeGalleryModal()" class="p-1 rounded-lg" style="color:#17231E;"
                    onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($errors->any() || session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(169,116,26,0.1);border:1px solid rgba(169,116,26,0.3);color:#A9741A;">
                    @if(session('modal_error')){{ session('modal_error') }}
                    @else <ul class="list-disc pl-4 text-xs space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    @endif
                </div>
            @endif

            <form wire:submit="saveGalleryPhoto" class="p-6 space-y-4" x-data="{ previewGallery: null }">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#586359;">Pilih Foto <span style="color:#B0402C;">*</span></label>
                    <div class="rounded-xl p-4 flex flex-col items-center gap-3" style="background:#ffffff;border:2px dashed #D8D6C9;">
                        <svg class="w-10 h-10 opacity-30" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <input type="file" wire:model="galleryPhoto" accept="image/*"
                               @change="previewGallery = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                               class="block w-full text-sm" style="color:#909A8F;">
                        <div wire:loading wire:target="galleryPhoto" class="text-xs" style="color:#17231E;">Mengunggah...</div>
                        <img x-show="previewGallery" :src="previewGallery" x-transition
                             class="rounded-xl object-cover w-full" style="max-height:180px;border:1px solid #E0DFD4;">
                    </div>
                    @error('galleryPhoto')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Keterangan Foto (Opsional)</label>
                    <input type="text" wire:model="photoCaption" placeholder="Caption untuk foto ini"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>
                <div class="flex justify-end gap-3 pt-1">
                    <button type="button" wire:click="closeGalleryModal()"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold disabled:opacity-50"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                        <span wire:loading.remove>Upload Foto</span>
                        <span wire:loading class="flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Mengupload...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Expense Modal --}}
    @if($isExpenseModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-on:keydown.escape.window="$wire.closeExpenseModal()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeExpenseModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #D8D6C9;max-height:92vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Tambah Pengeluaran</h3>
                <button wire:click="closeExpenseModal()" class="p-1 rounded-lg" style="color:#17231E;"
                    onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($errors->any() || session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(169,116,26,0.1);border:1px solid rgba(169,116,26,0.3);color:#A9741A;">
                    @if(session('modal_error'))
                        {{ session('modal_error') }}
                    @else
                        <ul class="list-disc pl-4 space-y-0.5 text-xs">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    @endif
                </div>
            @endif

            <form wire:submit="saveExpense" class="overflow-y-auto px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Keterangan <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="expenseDescription" placeholder="Deskripsi pengeluaran"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('expenseDescription')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Jumlah (Rp) <span style="color:#B0402C;">*</span></label>
                        <input type="number" wire:model="expenseAmount" min="1" step="100" placeholder="0"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('expenseAmount')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Tanggal <span style="color:#B0402C;">*</span></label>
                        <input type="date" wire:model="expenseDate"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;color-scheme:dark;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('expenseDate')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Akun <span style="color:#B0402C;">*</span></label>
                    <select wire:model="expenseAccountId"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        <option value="">-- Pilih Akun --</option>
                        @foreach($expenseAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                    @error('expenseAccountId')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closeExpenseModal()"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold disabled:opacity-50"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                        <span wire:loading.remove>Simpan Pengeluaran</span>
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

    {{-- Inline styles for CKEditor content --}}
    <style>
    .prose-campaign h1,.prose-campaign h2,.prose-campaign h3,.prose-campaign h4 { color:#17231E; font-family:'Fraunces',Georgia,serif; margin:1.25em 0 0.5em; }
    .prose-campaign h1 { font-size:1.6rem; }
    .prose-campaign h2 { font-size:1.3rem; }
    .prose-campaign h3 { font-size:1.1rem; }
    .prose-campaign p { margin:0.75em 0; }
    .prose-campaign ul,.prose-campaign ol { padding-left:1.5rem; margin:0.75em 0; }
    .prose-campaign li { margin:0.25em 0; }
    .prose-campaign blockquote { border-left:3px solid #164A40; padding-left:1rem; color:#17231E; margin:1em 0; font-style:italic; }
    .prose-campaign a { color:#17231E; text-decoration:underline; }
    .prose-campaign strong { color:#17231E; }
    .prose-campaign img { max-width:100%; border-radius:0.75rem; margin:1em 0; border:1px solid #E0DFD4; }
    .prose-campaign table { width:100%; border-collapse:collapse; margin:1em 0; }
    .prose-campaign th,.prose-campaign td { border:1px solid #E0DFD4; padding:0.5rem 0.75rem; }
    .prose-campaign th { background:#ffffff; color:#17231E; }
    </style>

    @push('scripts')
    <script>
    (function() {
        function initDetailListeners() {
            if (window.Livewire && window.Swal) {
                Livewire.on('show-donation-delete', (event) => {
                    let id = event.id ?? (event[0]?.id);
                    Swal.fire({
                        title: 'Hapus Donasi?',
                        text: 'Data donasi ini akan dihapus permanen termasuk transaksi terkait.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B0402C',
                        cancelButtonColor: '#17231E',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        color: '#17231E',
                    }).then((result) => {
                        if (result.isConfirmed && id !== undefined) {
                            @this.call('deleteDonation', id);
                        }
                    });
                });
                Livewire.on('show-expense-delete', (event) => {
                    let id = event.id ?? (event[0]?.id);
                    Swal.fire({
                        title: 'Hapus Pengeluaran?',
                        text: 'Data pengeluaran ini akan dihapus permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B0402C',
                        cancelButtonColor: '#17231E',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        color: '#17231E',
                    }).then((result) => {
                        if (result.isConfirmed && id !== undefined) {
                            @this.call('deleteExpense', id);
                        }
                    });
                });
            }
        }
        document.addEventListener('livewire:navigated', initDetailListeners);
        document.addEventListener('livewire:initialized', initDetailListeners);
    })();
    </script>
    @endpush
</div>
