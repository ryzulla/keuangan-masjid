<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base truncate" style="color:#111827;">{{ $campaign->name }}</h2>
    </x-slot>

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
             style="background:rgba(192,69,59,0.15);border:1px solid rgba(192,69,59,0.35);color:#c0453b;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('campaigns.index') }}" wire:navigate
               class="inline-flex items-center gap-1.5 text-sm transition-colors"
               style="color:#111827;"
               onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#111827'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Program
            </a>
            <span style="color:#98a2b3;">/</span>
            <span class="text-sm truncate max-w-xs" style="color:#98a2b3;">{{ $campaign->name }}</span>
        </div>

        {{-- Hero Image --}}
        @if($campaign->image)
            <div class="relative w-full rounded-2xl overflow-hidden" style="max-height:360px;">
                <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->name }}"
                     class="w-full object-cover" style="max-height:360px;width:100%;object-fit:cover;">
                <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.1) 0%, transparent 50%);"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6">
                    <div class="flex flex-wrap items-end gap-3">
                        <h1 class="text-2xl sm:text-3xl font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;text-shadow:0 2px 8px rgba(0,0,0,0.1);">
                            {{ $campaign->name }}
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                            style="{{ $campaign->status === 'active' ? 'background:rgba(18,128,92,0.2);color:#12805c;border:1px solid rgba(18,128,92,0.4);' : ($campaign->status === 'completed' ? 'background:rgba(16,24,40,0.2);color:#111827;border:1px solid rgba(16,24,40,0.4);' : 'background:rgba(192,69,59,0.2);color:#c0453b;border:1px solid rgba(192,69,59,0.4);') }}">
                            {{ $campaign->status === 'active' ? 'Aktif' : ($campaign->status === 'completed' ? 'Selesai' : 'Dibatalkan') }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                            style="{{ $campaign->organization_type === 'perumahan' ? 'background:rgba(16,24,40,0.2);color:#111827;border:1px solid rgba(16,24,40,0.4);' : 'background:rgba(18,128,92,0.15);color:#12805c;border:1px solid rgba(18,128,92,0.3);' }}">
                            {{ $campaign->organization_type === 'perumahan' ? 'Perumahan' : 'DKM Masjid' }}
                        </span>
                    </div>
                </div>
            </div>
        @else
            <div class="rounded-2xl p-6" style="background:#f2f4f7;border:1px solid rgba(16,24,40,0.35);">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">{{ $campaign->name }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                        style="{{ $campaign->status === 'active' ? 'background:rgba(18,128,92,0.2);color:#12805c;border:1px solid rgba(18,128,92,0.4);' : ($campaign->status === 'completed' ? 'background:rgba(16,24,40,0.2);color:#1d2939;border:1px solid rgba(16,24,40,0.4);' : 'background:rgba(192,69,59,0.2);color:#c0453b;border:1px solid rgba(192,69,59,0.4);') }}">
                        {{ $campaign->status === 'active' ? 'Aktif' : ($campaign->status === 'completed' ? 'Selesai' : 'Dibatalkan') }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                        style="{{ $campaign->organization_type === 'perumahan' ? 'background:rgba(16,24,40,0.2);color:#1d2939;border:1px solid rgba(16,24,40,0.4);' : 'background:rgba(18,128,92,0.15);color:#12805c;border:1px solid rgba(18,128,92,0.3);' }}">
                        {{ $campaign->organization_type === 'perumahan' ? 'Perumahan' : 'DKM Masjid' }}
                    </span>
                </div>
                @if($campaign->description)
                    <p class="mt-2 text-sm" style="color:#1d2939;">{{ $campaign->description }}</p>
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
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        @if($descHasHtml)
                            <div class="prose-campaign text-sm leading-relaxed" style="color:#344054;">{!! $campaign->description !!}</div>
                        @else
                            <p class="text-sm leading-relaxed" style="color:#344054;">{!! nl2br(e($campaign->description)) !!}</p>
                        @endif
                    </div>
                @endif

                {{-- Location --}}
                @if($campaign->location)
                    <div class="flex items-center gap-2 px-4 py-3 rounded-xl text-sm" style="background:#ffffff;border:1px solid #e4e7ec;color:#475467;">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $campaign->location }}</span>
                    </div>
                @endif

                {{-- Full blog content --}}
                @if($campaign->content)
                    <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                        <h2 class="text-base font-bold mb-4" style="color:#111827;font-family:'IBM Plex Sans',serif;">Deskripsi Program</h2>
                        <div class="prose-campaign" style="color:#344054;line-height:1.8;font-size:0.9375rem;">
                            {!! $campaign->content !!}
                        </div>
                    </div>
                @endif

                {{-- Video embed --}}
                @if($campaign->video_url)
                    <div class="rounded-2xl overflow-hidden" style="border:1px solid #e4e7ec;">
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
                    <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);"
                         x-data="{ lightbox: null }">
                        <h2 class="text-base font-bold mb-4" style="color:#111827;font-family:'IBM Plex Sans',serif;">
                            Galeri Foto
                            <span class="ml-2 text-xs font-normal px-2 py-0.5 rounded-full" style="background:rgba(16,24,40,0.1);color:#111827;">{{ $campaign->photos->count() }} foto</span>
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
                                            <p class="text-xs p-2" style="color:#1d2939;">{{ $photo->caption }}</p>
                                        @endif
                                    </div>
                                    @can('manage-transactions')
                                    <button wire:click.stop="deletePhoto({{ $photo->id }})"
                                            wire:confirm="Hapus foto ini?"
                                            class="absolute top-2 right-2 w-6 h-6 rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity"
                                            style="background:rgba(192,69,59,0.9);color:#1d2939;">✕</button>
                                    @endcan
                                </div>
                            @endforeach
                        </div>

                        {{-- Lightbox --}}
                        <div x-show="lightbox" x-transition
                             @click="lightbox = null"
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                             style="background:rgba(0,0,0,0.1);">
                            <button @click="lightbox = null" class="absolute top-4 right-4 w-10 h-10 rounded-full flex items-center justify-center text-lg" style="background:rgba(255,255,255,0.1);color:#1d2939;">✕</button>
                            <img :src="lightbox" class="rounded-2xl object-contain" style="max-width:90vw;max-height:85vh;" @click.stop>
                        </div>
                    </div>
                @endif

            </div>

            {{-- RIGHT: Sidebar (1/3) --}}
            <div class="space-y-4">

                {{-- Progress Card --}}
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <h3 class="text-sm font-semibold mb-4" style="color:#111827;">Progres Donasi</h3>
                    @php
                        $target  = (float)($campaign->target_amount ?? 0);
                        $raised  = (float)($stats['total_uang'] ?? 0);
                        $progress = $target > 0 ? min(100, round($raised / $target * 100)) : ($raised > 0 ? 100 : 0);
                    @endphp
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs mb-1.5" style="color:#7c8698;">
                                <span>Terkumpul</span>
                                <span style="color:#111827;font-weight:600;">{{ $progress }}%</span>
                            </div>
                            <div class="w-full rounded-full h-2" style="background:#e4e7ec;">
                                <div class="h-2 rounded-full transition-all"
                                     style="width:{{ $progress }}%;background:{{ $progress >= 100 ? '#12805c' : 'linear-gradient(90deg,#111827,#111827)' }};"></div>
                            </div>
                        </div>
                        <div class="text-xl font-bold" style="color:#12805c;">Rp {{ number_format($raised, 0, ',', '.') }}</div>
                        @if($target > 0)
                            <div class="text-xs" style="color:#98a2b3;">dari target Rp {{ number_format($target, 0, ',', '.') }}</div>
                        @endif
                        @if($campaign->end_date)
                            @php
                                $daysLeft = now()->diffInDays($campaign->end_date, false);
                            @endphp
                            <div class="text-xs px-3 py-2 rounded-lg text-center"
                                 style="background:{{ $daysLeft < 0 ? 'rgba(192,69,59,0.08)' : 'rgba(16,24,40,0.08)' }};color:{{ $daysLeft < 0 ? '#c0453b' : '#111827' }};">
                                @if($daysLeft < 0)
                                    Program telah berakhir
                                @else
                                    {{ $daysLeft }} hari lagi
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="rounded-2xl p-5 space-y-3" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                    <h3 class="text-sm font-semibold" style="color:#111827;">Statistik Donasi</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #f5f6f8;">
                            <div class="text-xl font-bold" style="color:#1d2939;">{{ $stats['count_uang'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#98a2b3;">Donasi Uang</div>
                        </div>
                        <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #f5f6f8;">
                            <div class="text-xl font-bold" style="color:#1d2939;">{{ $stats['count_barang'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#98a2b3;">Donasi Barang</div>
                        </div>
                        <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #f5f6f8;">
                            <div class="text-xl font-bold" style="color:#111827;">{{ $stats['count_warga'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#98a2b3;">Dari Warga</div>
                        </div>
                        <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #f5f6f8;">
                            <div class="text-xl font-bold" style="color:#475467;">{{ $stats['count_luaran'] }}</div>
                            <div class="text-xs mt-0.5" style="color:#98a2b3;">Dari Luaran</div>
                        </div>
                    </div>
                </div>

                {{-- Periode --}}
                <div class="rounded-2xl p-4" style="background:#ffffff;border:1px solid #e4e7ec;">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span style="color:#98a2b3;">Mulai</span>
                            <span style="color:#344054;">{{ optional($campaign->start_date)->format('d M Y') ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span style="color:#98a2b3;">Selesai</span>
                            <span style="color:#344054;">{{ optional($campaign->end_date)->format('d M Y') ?? 'Tidak ditentukan' }}</span>
                        </div>
                        @if($campaign->sourceAccount)
                            <div class="flex justify-between">
                                <span style="color:#98a2b3;">Sumber Dana</span>
                                <span class="font-medium" style="color:#111827;">{{ $campaign->sourceAccount->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Admin actions --}}
                @can('manage-transactions')
                <div class="rounded-2xl p-4 space-y-2" style="background:#ffffff;border:1px solid #e4e7ec;">
                    <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#98a2b3;">Kelola Program</p>
                    <a href="{{ route('campaigns.edit', $campaign) }}" wire:navigate
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium w-full transition-colors"
                       style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);"
                       onmouseover="this.style.background='rgba(16,24,40,0.2)'" onmouseout="this.style.background='rgba(16,24,40,0.1)'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Program
                    </a>
                    <button wire:click="openGalleryUpload()"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium w-full transition-colors"
                        style="background:#ffffff;color:#475467;border:1px solid #e4e7ec;"
                        onmouseover="this.style.color='#111827';this.style.borderColor='rgba(16,24,40,0.3)'" onmouseout="this.style.color='#475467';this.style.borderColor='#e4e7ec'">
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
                <h2 class="text-lg font-bold" style="color:#111827;font-family:'IBM Plex Sans',serif;">
                    Daftar Donasi
                    <span class="ml-2 text-sm font-normal" style="color:#98a2b3;">({{ $stats['count_uang'] + $stats['count_barang'] }} total)</span>
                </h2>
                @can('manage-transactions')
                <button wire:click="openAddDonation()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold shrink-0"
                    style="background:#111827;color:#ffffff;"
                    onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Donasi
                </button>
                @endcan
            </div>

            {{-- Filters --}}
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('filterDonationForm', '')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonationForm === '' ? 'background:#111827;color:#ffffff;' : 'background:#f5f6f8;color:#667085;border:1px solid #e4e7ec;' }}"
                    @if($filterDonationForm !== '') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#667085'" @endif>
                    Semua
                </button>
                <button wire:click="$set('filterDonationForm', 'uang')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonationForm === 'uang' ? 'background:#111827;color:#ffffff;' : 'background:#f5f6f8;color:#667085;border:1px solid #e4e7ec;' }}"
                    @if($filterDonationForm !== 'uang') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#667085'" @endif>
                    Uang ({{ $stats['count_uang'] }})
                </button>
                <button wire:click="$set('filterDonationForm', 'barang')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonationForm === 'barang' ? 'background:#111827;color:#ffffff;' : 'background:#f5f6f8;color:#667085;border:1px solid #e4e7ec;' }}"
                    @if($filterDonationForm !== 'barang') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#667085'" @endif>
                    Barang ({{ $stats['count_barang'] }})
                </button>
                <div style="width:1px;background:#e4e7ec;margin:0 4px;"></div>
                <button wire:click="$set('filterDonorType', '')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonorType === '' ? 'background:rgba(16,24,40,0.2);color:#111827;border:1px solid rgba(16,24,40,0.3);' : 'background:#f5f6f8;color:#667085;border:1px solid #e4e7ec;' }}"
                    @if($filterDonorType !== '') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#667085'" @endif>
                    Semua Asal
                </button>
                <button wire:click="$set('filterDonorType', 'warga')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonorType === 'warga' ? 'background:rgba(16,24,40,0.2);color:#111827;border:1px solid rgba(16,24,40,0.3);' : 'background:#f5f6f8;color:#667085;border:1px solid #e4e7ec;' }}"
                    @if($filterDonorType !== 'warga') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#667085'" @endif>
                    Warga ({{ $stats['count_warga'] }})
                </button>
                <button wire:click="$set('filterDonorType', 'luaran')"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="{{ $filterDonorType === 'luaran' ? 'background:rgba(16,24,40,0.2);color:#111827;border:1px solid rgba(16,24,40,0.3);' : 'background:#f5f6f8;color:#667085;border:1px solid #e4e7ec;' }}"
                    @if($filterDonorType !== 'luaran') onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#667085'" @endif>
                    Luaran ({{ $stats['count_luaran'] }})
                </button>
            </div>

            {{-- Donation Table --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Donatur</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Asal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Jenis</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Nilai / Barang</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#98a2b3;">Foto</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#98a2b3;">Tanggal</th>
                                @can('manage-transactions')
                                <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Aksi</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($donations as $donation)
                                <tr style="border-bottom:1px solid #eef0f3;" wire:key="donation-{{ $donation->id }}"
                                    onmouseover="this.style.backgroundColor='#f5f6f8'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-sm" style="color:#1d2939;">{{ $donation->donor_name }}</div>
                                        @if($donation->resident)
                                            <div class="text-xs mt-0.5" style="color:#98a2b3;">{{ $donation->resident->name }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($donation->donor_type ?? 'luaran') === 'warga')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">Warga</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(99,102,241,0.1);color:#4f46e5;border:1px solid rgba(99,102,241,0.2);">Luaran</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($donation->donation_form ?? 'uang') === 'uang')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Uang</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">Barang</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($donation->donation_form ?? 'uang') === 'uang')
                                            <span class="font-mono font-semibold text-sm" style="color:#12805c;">Rp {{ number_format($donation->transaction?->amount ?? 0, 0, ',', '.') }}</span>
                                        @else
                                            <div class="text-sm" style="color:#344054;">{{ $donation->item_description }}</div>
                                            @if($donation->item_quantity)
                                                <div class="text-xs" style="color:#98a2b3;">{{ $donation->item_quantity }}</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 hidden md:table-cell">
                                        @if($donation->item_photo_path)
                                            <img src="{{ Storage::url($donation->item_photo_path) }}"
                                                 alt="Foto barang"
                                                 class="w-10 h-10 rounded-lg object-cover cursor-pointer"
                                                 style="border:1px solid #e4e7ec;"
                                                 onclick="window.open('{{ Storage::url($donation->item_photo_path) }}', '_blank')">
                                        @else
                                            <span style="color:#98a2b3;">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 hidden sm:table-cell text-xs whitespace-nowrap" style="color:#7c8698;">
                                        {{ optional($donation->created_at)->format('d M Y') }}
                                    </td>
                                    @can('manage-transactions')
                                    <td class="px-4 py-3 text-center">
                                        <button wire:click="confirmDeleteDonation({{ $donation->id }})"
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                            style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);"
                                            onmouseover="this.style.background='rgba(192,69,59,0.2)'" onmouseout="this.style.background='rgba(192,69,59,0.1)'">
                                            Hapus
                                        </button>
                                    </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-14 text-center">
                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        <p class="text-sm" style="color:#98a2b3;">Belum ada donasi untuk program ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden divide-y" style="border-color:#eef0f3;">
                    @forelse($donations as $donation)
                        <div class="p-4 space-y-2" wire:key="donation-mobile-{{ $donation->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="font-medium text-sm truncate" style="color:#1d2939;">{{ $donation->donor_name }}</div>
                                    @if($donation->resident)
                                        <div class="text-xs mt-0.5" style="color:#98a2b3;">{{ $donation->resident->name }}</div>
                                    @endif
                                    <div class="text-xs mt-0.5" style="color:#98a2b3;">{{ optional($donation->created_at)->format('d M Y') }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    @if(($donation->donation_form ?? 'uang') === 'uang')
                                        <span class="font-mono font-semibold text-sm" style="color:#12805c;">Rp {{ number_format($donation->transaction?->amount ?? 0, 0, ',', '.') }}</span>
                                    @else
                                        <div class="text-sm" style="color:#344054;">{{ $donation->item_description }}</div>
                                        @if($donation->item_quantity)
                                            <div class="text-xs" style="color:#98a2b3;">{{ $donation->item_quantity }}</div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                @if(($donation->donor_type ?? 'luaran') === 'warga')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">Warga</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(99,102,241,0.1);color:#4f46e5;border:1px solid rgba(99,102,241,0.2);">Luaran</span>
                                @endif
                                @if(($donation->donation_form ?? 'uang') === 'uang')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Uang</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">Barang</span>
                                @endif
                            </div>

                            @if($donation->item_photo_path)
                                <img src="{{ Storage::url($donation->item_photo_path) }}"
                                     alt="Foto barang"
                                     class="w-14 h-14 rounded-lg object-cover cursor-pointer"
                                     style="border:1px solid #e4e7ec;"
                                     onclick="window.open('{{ Storage::url($donation->item_photo_path) }}', '_blank')">
                            @endif

                            @can('manage-transactions')
                            <div class="pt-1">
                                <button wire:click="confirmDeleteDonation({{ $donation->id }})"
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);"
                                    onmouseover="this.style.background='rgba(192,69,59,0.2)'" onmouseout="this.style.background='rgba(192,69,59,0.1)'">
                                    Hapus
                                </button>
                            </div>
                            @endcan
                        </div>
                    @empty
                        <div class="px-4 py-14 text-center">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <p class="text-sm" style="color:#98a2b3;">Belum ada donasi untuk program ini.</p>
                        </div>
                    @endforelse
                </div>

                @if($donations->hasPages())
                    <div class="px-4 py-3" style="border-top:1px solid #eef0f3;">{{ $donations->links() }}</div>
                @endif
            </div>
        </div>

    </div>

    {{-- Donation Modal --}}
    @if($isDonationModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data x-on:keydown.escape.window="$wire.closeDonationModal()">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeDonationModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #d0d5dd;max-height:92vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#f2f4f7;border-bottom:1px solid rgba(16,24,40,0.35);">
                <h3 class="font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">Tambah Donasi</h3>
                <button wire:click="closeDonationModal()" class="p-1 rounded-lg" style="color:#1d2939;"
                    onmouseover="this.style.background='rgba(16,24,40,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($errors->any() || session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(199,125,26,0.1);border:1px solid rgba(199,125,26,0.3);color:#c77d1a;">
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
                    <label class="block text-sm font-medium mb-2" style="color:#475467;">Jenis Donasi <span style="color:#c0453b;">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 px-4 py-3 rounded-xl cursor-pointer transition-colors"
                               style="{{ $donationForm === 'uang' ? 'background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.4);' : 'background:#ffffff;border:1px solid #e4e7ec;' }}">
                            <input type="radio" wire:model.live="donationForm" value="uang" style="accent-color:#12805c;">
                            <span class="text-sm font-medium" style="{{ $donationForm === 'uang' ? 'color:#12805c;' : 'color:#667085;' }}">Uang</span>
                        </label>
                        <label class="flex items-center gap-2 px-4 py-3 rounded-xl cursor-pointer transition-colors"
                               style="{{ $donationForm === 'barang' ? 'background:rgba(199,125,26,0.1);border:1px solid rgba(199,125,26,0.4);' : 'background:#ffffff;border:1px solid #e4e7ec;' }}">
                            <input type="radio" wire:model.live="donationForm" value="barang" style="accent-color:#c77d1a;">
                            <span class="text-sm font-medium" style="{{ $donationForm === 'barang' ? 'color:#c77d1a;' : 'color:#667085;' }}">Barang</span>
                        </label>
                    </div>
                </div>

                {{-- Asal Donatur (default: Penghuni) --}}
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#475467;">Asal Donatur <span style="color:#c0453b;">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'penghuni' ? 'background:rgba(16,24,40,0.1);border:1px solid rgba(16,24,40,0.4);' : 'background:#ffffff;border:1px solid #e4e7ec;' }}">
                            <input type="radio" wire:model.live="donorType" value="penghuni" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'penghuni' ? 'color:#111827;' : 'color:#667085;' }}">Penghuni</span>
                        </label>
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'hamba_allah' ? 'background:rgba(16,24,40,0.1);border:1px solid rgba(16,24,40,0.4);' : 'background:#ffffff;border:1px solid #e4e7ec;' }}">
                            <input type="radio" wire:model.live="donorType" value="hamba_allah" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'hamba_allah' ? 'color:#111827;' : 'color:#667085;' }}">Hamba Allah</span>
                        </label>
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'luar' ? 'background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.4);' : 'background:#ffffff;border:1px solid #e4e7ec;' }}">
                            <input type="radio" wire:model.live="donorType" value="luar" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'luar' ? 'color:#4f46e5;' : 'color:#667085;' }}">Donatur Lain</span>
                        </label>
                    </div>
                </div>

                {{-- Detail sesuai asal donatur --}}
                @if($donorType === 'penghuni')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Pilih Penghuni <span style="color:#c0453b;">*</span></label>
                    <select wire:model.live="residentId"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        <option value="">-- Pilih penghuni --</option>
                        @foreach($residents as $resident)
                            <option value="{{ $resident->id }}">{{ $resident->name }}</option>
                        @endforeach
                    </select>
                    @error('residentId')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @elseif($donorType === 'luar')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Nama Donatur <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="donorName" placeholder="Nama lengkap donatur"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    @error('donorName')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @else
                <div class="px-3 py-2.5 rounded-xl text-xs" style="background:#ffffff;border:1px solid #eef0f3;color:#7c8698;">
                    Donasi dicatat atas nama <strong style="color:#667085;">Hamba Allah</strong> (anonim).
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Tanggal Donasi <span style="color:#c0453b;">*</span></label>
                    <input type="date" wire:model="donationDate"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;color-scheme:dark;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    @error('donationDate')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>

                {{-- Uang fields --}}
                @if($donationForm === 'uang')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#475467;">Jumlah (Rp) <span style="color:#c0453b;">*</span></label>
                        <input type="number" wire:model="donationAmount" min="1" step="1000" placeholder="0"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        @error('donationAmount')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#475467;">Tipe Donasi</label>
                        <select wire:model="donationType"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                            <option value="infaq">Infaq / Sedekah</option>
                            <option value="zakat">Zakat</option>
                            <option value="wakaf">Wakaf</option>
                            <option value="donasi">Donasi Umum</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Akun Penerimaan <span style="color:#c0453b;">*</span></label>
                    <select wire:model="accountId"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        <option value="">-- Pilih Akun --</option>
                        @foreach($orgAccounts as $acc)
                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                        @endforeach
                    </select>
                    @error('accountId')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Barang fields --}}
                @if($donationForm === 'barang')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Nama / Deskripsi Barang <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="itemDescription" placeholder="Contoh: Semen Portland, Kursi Plastik, dll"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    @error('itemDescription')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Jumlah / Satuan <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="itemQuantity" placeholder="Contoh: 10 karung, 5 buah, 2 meter"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    @error('itemQuantity')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                <div x-data="{ previewItem: null }">
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Foto Barang (Opsional)</label>
                    <div class="rounded-xl p-3 flex flex-col gap-2" style="background:#ffffff;border:1px dashed #d0d5dd;">
                        <input type="file" wire:model="itemPhoto" accept="image/*"
                               @change="previewItem = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                               class="block w-full text-xs"
                               style="color:#7c8698;"
                               x-ref="itemPhotoInput">
                        <div wire:loading wire:target="itemPhoto" class="text-xs" style="color:#111827;">Mengunggah...</div>
                        <img x-show="previewItem" :src="previewItem" x-transition
                             class="rounded-xl object-cover mt-1" style="max-height:140px;max-width:100%;border:1px solid #e4e7ec;">
                    </div>
                    @error('itemPhoto')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Catatan (Opsional)</label>
                    <textarea wire:model="donationNotes" rows="2" placeholder="Pesan atau keterangan tambahan"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closeDonationModal()"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#f5f6f8;color:#344054;border:1px solid #d0d5dd;"
                        onmouseover="this.style.background='#e4e7ec'" onmouseout="this.style.background='#f5f6f8'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold disabled:opacity-50"
                        style="background:#111827;color:#ffffff;"
                        onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'">
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
        <div class="relative rounded-2xl shadow-2xl w-full max-w-md" style="background:#ffffff;border:1px solid #d0d5dd;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 rounded-t-2xl" style="background:#f2f4f7;border-bottom:1px solid rgba(16,24,40,0.35);">
                <h3 class="font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">Tambah Foto Galeri</h3>
                <button wire:click="closeGalleryModal()" class="p-1 rounded-lg" style="color:#1d2939;"
                    onmouseover="this.style.background='rgba(16,24,40,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($errors->any() || session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(199,125,26,0.1);border:1px solid rgba(199,125,26,0.3);color:#c77d1a;">
                    @if(session('modal_error')){{ session('modal_error') }}
                    @else <ul class="list-disc pl-4 text-xs space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    @endif
                </div>
            @endif

            <form wire:submit="saveGalleryPhoto" class="p-6 space-y-4" x-data="{ previewGallery: null }">
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#475467;">Pilih Foto <span style="color:#c0453b;">*</span></label>
                    <div class="rounded-xl p-4 flex flex-col items-center gap-3" style="background:#ffffff;border:2px dashed #d0d5dd;">
                        <svg class="w-10 h-10 opacity-30" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <input type="file" wire:model="galleryPhoto" accept="image/*"
                               @change="previewGallery = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                               class="block w-full text-sm" style="color:#7c8698;">
                        <div wire:loading wire:target="galleryPhoto" class="text-xs" style="color:#111827;">Mengunggah...</div>
                        <img x-show="previewGallery" :src="previewGallery" x-transition
                             class="rounded-xl object-cover w-full" style="max-height:180px;border:1px solid #e4e7ec;">
                    </div>
                    @error('galleryPhoto')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Keterangan Foto (Opsional)</label>
                    <input type="text" wire:model="photoCaption" placeholder="Caption untuk foto ini"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                </div>
                <div class="flex justify-end gap-3 pt-1">
                    <button type="button" wire:click="closeGalleryModal()"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#f5f6f8;color:#344054;border:1px solid #d0d5dd;"
                        onmouseover="this.style.background='#e4e7ec'" onmouseout="this.style.background='#f5f6f8'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold disabled:opacity-50"
                        style="background:#111827;color:#ffffff;"
                        onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'">
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

    {{-- Inline styles for CKEditor content --}}
    <style>
    .prose-campaign h1,.prose-campaign h2,.prose-campaign h3,.prose-campaign h4 { color:#111827; font-family:'IBM Plex Sans',serif; margin:1.25em 0 0.5em; }
    .prose-campaign h1 { font-size:1.6rem; }
    .prose-campaign h2 { font-size:1.3rem; }
    .prose-campaign h3 { font-size:1.1rem; }
    .prose-campaign p { margin:0.75em 0; }
    .prose-campaign ul,.prose-campaign ol { padding-left:1.5rem; margin:0.75em 0; }
    .prose-campaign li { margin:0.25em 0; }
    .prose-campaign blockquote { border-left:3px solid #111827; padding-left:1rem; color:#111827; margin:1em 0; font-style:italic; }
    .prose-campaign a { color:#111827; text-decoration:underline; }
    .prose-campaign strong { color:#1d2939; }
    .prose-campaign img { max-width:100%; border-radius:0.75rem; margin:1em 0; border:1px solid #e4e7ec; }
    .prose-campaign table { width:100%; border-collapse:collapse; margin:1em 0; }
    .prose-campaign th,.prose-campaign td { border:1px solid #e4e7ec; padding:0.5rem 0.75rem; }
    .prose-campaign th { background:#ffffff; color:#111827; }
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
                        confirmButtonColor: '#c0453b',
                        cancelButtonColor: '#344054',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        color: '#1d2939',
                    }).then((result) => {
                        if (result.isConfirmed && id !== undefined) {
                            @this.call('deleteDonation', id);
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
