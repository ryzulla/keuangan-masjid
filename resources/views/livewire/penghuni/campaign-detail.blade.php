<div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="fixed top-4 right-4 z-50 rounded-xl px-5 py-3 text-sm flex items-center gap-2 shadow-xl"
             style="background:rgba(18,128,92,0.15);border:1px solid rgba(18,128,92,0.35);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 mb-5">
        <a href="{{ route('penghuni.program') }}" wire:navigate
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
        <div class="relative w-full rounded-2xl overflow-hidden mb-6" style="max-height:360px;">
            <img src="{{ Storage::url($campaign->image) }}" alt="{{ $campaign->name }}"
                 class="w-full object-cover" style="max-height:360px;width:100%;object-fit:cover;">
            <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.1) 0%, transparent 50%);"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <div class="flex flex-wrap items-end gap-3">
                    <h1 class="text-2xl sm:text-3xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;text-shadow:0 2px 8px rgba(0,0,0,0.1);">
                        {{ $campaign->name }}
                    </h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                        style="{{ $campaign->organization_type === 'perumahan' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.4);' : 'background:rgba(20,184,166,0.15);color:#0d9488;border:1px solid rgba(20,184,166,0.3);' }}">
                        {{ $campaign->organization_type === 'perumahan' ? 'Perumahan' : 'DKM Masjid' }}
                    </span>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-2xl p-6 mb-6" style="background:#F1F3EC;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl sm:text-3xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">{{ $campaign->name }}</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold"
                    style="{{ $campaign->organization_type === 'perumahan' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.4);' : 'background:rgba(20,184,166,0.15);color:#0d9488;border:1px solid rgba(20,184,166,0.3);' }}">
                    {{ $campaign->organization_type === 'perumahan' ? 'Perumahan' : 'DKM Masjid' }}
                </span>
            </div>
        </div>
    @endif

    {{-- Stats calc --}}
    @php
        $collected   = $campaign->donations->sum(fn($d) => $d->transaction?->amount ?? 0);
        $target      = (float)($campaign->target_amount ?? 0);
        $progress    = $target > 0 ? min(100, round($collected / $target * 100)) : ($collected > 0 ? 100 : 0);
        $totalDonors = $stats['count_uang'] + $stats['count_barang'];
        $daysLeft    = $campaign->end_date ? now()->startOfDay()->diffInDays($campaign->end_date->copy()->startOfDay(), false) : null;
    @endphp

    {{-- 2-col layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: content 2/3 --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Description --}}
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

            {{-- Full content --}}
            @if($campaign->content)
                <div class="rounded-2xl p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <h2 class="text-base font-bold mb-4" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Deskripsi Program</h2>
                    <div class="prose-campaign" style="color:#17231E;line-height:1.8;font-size:0.9375rem;">
                        {!! $campaign->content !!}
                    </div>
                </div>
            @endif

            {{-- Video --}}
            @if($campaign->video_url)
                <div class="rounded-2xl overflow-hidden" style="border:1px solid #E0DFD4;">
                    @php
                        $embedUrl = $campaign->video_url;
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $embedUrl, $m)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                        }
                    @endphp
                    <div class="relative" style="padding-bottom:56.25%;">
                        <iframe src="{{ $embedUrl }}" class="absolute inset-0 w-full h-full rounded-2xl"
                            frameborder="0" allowfullscreen style="background:#ffffff;"></iframe>
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
                                 @click="lightbox = '{{ Storage::url($photo->photo_path) }}'">
                                <img src="{{ Storage::url($photo->photo_path) }}" alt="{{ $photo->caption ?? '' }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @if($photo->caption)
                                <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity flex items-end"
                                     style="background:linear-gradient(to top,rgba(0,0,0,0.1) 0%,transparent 60%);">
                                    <p class="text-xs p-2" style="color:#17231E;">{{ $photo->caption }}</p>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    {{-- Lightbox --}}
                    <div x-show="lightbox" x-transition @click="lightbox = null"
                         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                         style="background:rgba(0,0,0,0.1);">
                        <button @click="lightbox = null" class="absolute top-4 right-4 w-10 h-10 rounded-full flex items-center justify-center text-lg" style="background:rgba(255,255,255,0.1);color:#17231E;">✕</button>
                        <img :src="lightbox" class="rounded-2xl object-contain" style="max-width:90vw;max-height:85vh;" @click.stop>
                    </div>
                </div>
            @endif

            {{-- Donor List --}}
            <div>
                {{-- Header + filter row --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                    <h2 class="text-base font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                        Daftar Donasi
                        <span class="ml-2 text-sm font-normal" style="color:#909A8F;">({{ $totalDonors }} total)</span>
                    </h2>
                </div>

                {{-- Filters --}}
                <div class="flex flex-wrap gap-2 mb-4">
                    <button wire:click="$set('filterDonationForm', '')"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                        style="{{ $filterDonationForm === '' ? 'background:#164A40;color:#ffffff;' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                        Semua
                    </button>
                    <button wire:click="$set('filterDonationForm', 'uang')"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                        style="{{ $filterDonationForm === 'uang' ? 'background:#164A40;color:#ffffff;' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                        Uang ({{ $stats['count_uang'] }})
                    </button>
                    <button wire:click="$set('filterDonationForm', 'barang')"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                        style="{{ $filterDonationForm === 'barang' ? 'background:#164A40;color:#ffffff;' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                        Barang ({{ $stats['count_barang'] }})
                    </button>
                    <div style="width:1px;background:#E0DFD4;margin:0 4px;"></div>
                    <button wire:click="$set('filterDonorType', '')"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                        style="{{ $filterDonorType === '' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.3);' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                        Semua Asal
                    </button>
                    <button wire:click="$set('filterDonorType', 'warga')"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                        style="{{ $filterDonorType === 'warga' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.3);' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                        Warga ({{ $stats['count_warga'] }})
                    </button>
                    <button wire:click="$set('filterDonorType', 'luaran')"
                        class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                        style="{{ $filterDonorType === 'luaran' ? 'background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.3);' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                        Luaran ({{ $stats['count_luaran'] }})
                    </button>
                </div>

                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    @if($donors->isEmpty())
                        <div class="px-5 py-12 text-center">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <p class="text-sm" style="color:#909A8F;">Tidak ada donasi untuk filter ini</p>
                        </div>
                    @else
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Donatur</th>
                                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Asal</th>
                                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Jenis</th>
                                        <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#909A8F;">Tanggal</th>
                                        <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($donors as $donor)
                                    <tr style="border-bottom:1px solid #F1F3EC;"
                                        onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                        <td class="px-4 py-3.5">
                                            <div class="font-medium text-sm" style="color:#17231E;">{{ $donor['name'] }}</div>
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @if($donor['type'] === 'warga')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Warga</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);">Luaran</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            @if($donor['form'] === 'uang')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Uang</span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">Barang</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 hidden sm:table-cell text-xs whitespace-nowrap" style="color:#909A8F;">
                                            {{ $donor['date'] ? \Carbon\Carbon::parse($donor['date'])->format('d M Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right">
                                            @if($donor['form'] === 'uang')
                                                <span class="font-mono font-semibold text-sm" style="color:#12805c;">
                                                    Rp {{ number_format($donor['amount'], 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-xs" style="color:#586359;">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                            @forelse($donors as $donor)
                            <div wire:key="donor-mobile-{{ $donor['type'] }}-{{ $donor['form'] }}-{{ $loop->index }}" class="px-4 py-3.5">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="min-w-0">
                                        <div class="font-medium text-sm truncate" style="color:#17231E;">{{ $donor['name'] }}</div>
                                        <div class="text-xs mt-0.5" style="color:#909A8F;">
                                            {{ $donor['date'] ? \Carbon\Carbon::parse($donor['date'])->format('d M Y') : '—' }}
                                        </div>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        @if($donor['form'] === 'uang')
                                            <span class="font-mono font-semibold text-sm" style="color:#12805c;">
                                                Rp {{ number_format($donor['amount'], 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-xs" style="color:#586359;">—</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    @if($donor['type'] === 'warga')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Warga</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);">Luaran</span>
                                    @endif
                                    @if($donor['form'] === 'uang')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Uang</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">Barang</span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="px-5 py-12 text-center">
                                <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <p class="text-sm" style="color:#909A8F;">Tidak ada donasi untuk filter ini</p>
                            </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>

            {{-- Expense Report --}}
            <div>
                <h2 class="text-base font-bold mb-3" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                    Laporan Pengeluaran
                    <span class="ml-2 text-sm font-normal" style="color:#909A8F;">({{ $expenses->count() }} transaksi)</span>
                </h2>
                <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    @if($expenses->isEmpty())
                        <div class="px-5 py-12 text-center">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm" style="color:#909A8F;">Belum ada pengeluaran untuk program ini.</p>
                        </div>
                    @else
                        <div class="p-4" style="background:#ffffff;border-bottom:1px solid #E0DFD4;">
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
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                            @foreach($expenses as $exp)
                            <div class="px-4 py-3.5">
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
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">
                                        {{ $exp->category?->name ?? '—' }}
                                    </span>
                                    <span class="text-xs" style="color:#909A8F;">{{ $exp->account?->name ?? '—' }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- RIGHT: sidebar 1/3 --}}
        <div class="space-y-4">

            {{-- Progress Card --}}
            <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <h3 class="text-sm font-semibold mb-4" style="color:#17231E;">Progres Donasi</h3>
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
                    <div class="text-xl font-bold" style="color:#12805c;">Rp {{ number_format($collected, 0, ',', '.') }}</div>
                    @if($target > 0)
                        <div class="text-xs" style="color:#909A8F;">dari target Rp {{ number_format($target, 0, ',', '.') }}</div>
                    @endif
                    @if($daysLeft !== null)
                        <div class="text-xs px-3 py-2 rounded-lg text-center"
                             style="background:{{ $daysLeft < 0 ? 'rgba(176,64,44,0.08)' : 'rgba(22,74,64,0.08)' }};color:{{ $daysLeft < 0 ? '#B0402C' : '#164A40' }};">
                        @if($daysLeft < 0) Program telah berakhir
                             @elseif($daysLeft === 0) Hari terakhir
                             @else {{ $daysLeft }} hari lagi
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
                </div>
            </div>

            {{-- Tombol Donasi --}}
            <button wire:click="openDonate"
                class="w-full py-3.5 rounded-xl text-sm font-semibold transition-colors"
                style="background:#164A40;color:#ffffff;"
                onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                Donasi Sekarang
            </button>

        </div>
    </div>


    {{-- ─── Donation Modal (admin style) ─── --}}
    @if($isDonateModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-on:keydown.escape.window="$wire.set('isDonateModalOpen', false)">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isDonateModalOpen', false)"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #D8D6C9;max-height:92vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Form Donasi</h3>
                <button wire:click="$set('isDonateModalOpen', false)" class="p-1 rounded-lg" style="color:#17231E;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($errors->any())
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(169,116,26,0.1);border:1px solid rgba(169,116,26,0.3);color:#A9741A;">
                    <ul class="list-disc pl-4 space-y-0.5 text-xs">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form wire:submit="submitDonation" class="overflow-y-auto px-6 py-5 space-y-4">

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

                {{-- Asal Donatur --}}
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
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:rgba(22,74,64,0.06);border:1px solid rgba(22,74,64,0.2);">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:rgba(22,74,64,0.15);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs" style="color:#909A8F;">Asal Donatur</p>
                        <p class="text-sm font-semibold truncate" style="color:#17231E;">{{ auth('resident')->user()?->name }}</p>
                    </div>
                    <span class="shrink-0 text-xs px-2.5 py-1 rounded-full font-semibold" style="background:rgba(22,74,64,0.2);color:#17231E;border:1px solid rgba(22,74,64,0.3);">Warga</span>
                </div>
                @elseif($donorType === 'hamba_allah')
                <div class="px-3 py-2.5 rounded-xl text-xs" style="background:#ffffff;border:1px solid #F1F3EC;color:#909A8F;">
                    Donasi dicatat atas nama <strong style="color:#586359;">Hamba Allah</strong> (anonim).
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Nama Donatur <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="donorName" placeholder="Nama lengkap donatur"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('donorName')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Uang fields --}}
                @if($donationForm === 'uang')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Jumlah (Rp) <span style="color:#B0402C;">*</span></label>
                        <input type="number" wire:model="amount" min="1000" step="1000" placeholder="0"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('amount')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
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
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Metode Pembayaran</label>
                    <select wire:model.live="paymentMethod"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        <option value="transfer">Transfer Bank</option>
                        <option value="cash">Tunai</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Nama Bank</label>
                        <input type="text" wire:model="bankName"
                            placeholder="{{ $paymentMethod === 'transfer' ? 'BCA, Mandiri...' : '—' }}"
                            {{ $paymentMethod !== 'transfer' ? 'disabled' : '' }}
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">No. Referensi</label>
                        <input type="text" wire:model="referenceNum" placeholder="No. transaksi"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Foto Bukti (Opsional)</label>
                    <div class="rounded-xl p-3 flex flex-col gap-2" style="background:#ffffff;border:1px dashed #D8D6C9;">
                        <input type="file" wire:model="proofPhoto" accept="image/*"
                               class="block w-full text-xs" style="color:#909A8F;">
                        <div wire:loading wire:target="proofPhoto" class="text-xs" style="color:#17231E;">Mengunggah...</div>
                        @if($proofPhoto)
                            <img src="{{ $proofPhoto->temporaryUrl() }}"
                                 class="rounded-xl object-cover mt-1" style="max-height:140px;max-width:100%;border:1px solid #E0DFD4;">
                        @endif
                    </div>
                    @error('proofPhoto')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
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
                               class="block w-full text-xs" style="color:#909A8F;">
                        <div wire:loading wire:target="itemPhoto" class="text-xs" style="color:#17231E;">Mengunggah...</div>
                        <img x-show="previewItem" :src="previewItem" x-transition
                             class="rounded-xl object-cover mt-1" style="max-height:140px;max-width:100%;border:1px solid #E0DFD4;">
                    </div>
                    @error('itemPhoto')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Catatan (Opsional)</label>
                    <textarea wire:model="notes" rows="2" placeholder="Pesan atau keterangan tambahan"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="$set('isDonateModalOpen', false)"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold disabled:opacity-50"
                        style="background:#164A40;color:#ffffff;">
                        <span wire:loading.remove>Kirim Donasi</span>
                        <span wire:loading class="flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Mengirim...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <style>
    .prose-campaign h1,.prose-campaign h2,.prose-campaign h3,.prose-campaign h4 { color:#17231E; font-family:'Fraunces',Georgia,serif; margin:1.25em 0 0.5em; }
    .prose-campaign h1 { font-size:1.6rem; } .prose-campaign h2 { font-size:1.3rem; } .prose-campaign h3 { font-size:1.1rem; }
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
</div>
