<div>
    <style>
        /* Numeral buku-kas: angka keuangan pakai IBM Plex Mono + tabular figures */
        .pp-num{font-family:'IBM Plex Mono',monospace;font-feature-settings:'tnum' 1;letter-spacing:-.01em;}
        .pp-eyebrow{font-family:'IBM Plex Mono',monospace;letter-spacing:.2em;text-transform:uppercase;font-size:11px;font-weight:500;color:#A9741A;}
        /* Hero 2-kolom (pakai CSS langsung agar tak bergantung Tailwind arbitrary) */
        .pp-hero-grid{display:grid;grid-template-columns:1fr;gap:2.5rem;align-items:center;}
        @media(min-width:1024px){.pp-hero-grid{grid-template-columns:1.05fr .95fr;gap:3.5rem;}}
        /* Rail statistik hero */
        .pp-rail{display:flex;align-items:stretch;gap:1.75rem;margin-top:2.25rem;flex-wrap:wrap;}
        .pp-rail .lbl{font-family:'IBM Plex Mono',monospace;letter-spacing:.12em;text-transform:uppercase;font-size:11px;color:rgba(233,236,228,.6);margin-top:.35rem;white-space:nowrap;}
        .pp-rail .val{font-family:'IBM Plex Mono',monospace;letter-spacing:-.02em;font-weight:600;font-size:1.875rem;line-height:1;}
        .pp-rail .sep{width:1px;background:rgba(255,255,255,.14);align-self:stretch;}
    </style>

    {{-- Error alert --}}
    @if(session('page_error'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-6 px-4">
            <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ session('page_error') }}</span>
            </div>
        </div>
    @endif

    {{-- Hero — "Papan Warga": identitas + denah okupansi nyata --}}
    @php $occPct = $totalBlocks > 0 ? round($occupiedBlocks / $totalBlocks * 100) : 0; @endphp
    <div class="relative overflow-hidden" style="background:linear-gradient(150deg,#164A40 0%,#123E37 55%,#0F3A32 100%);">
        {{-- Motif blueprint (garis denah) --}}
        <div aria-hidden="true" style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.045) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.045) 1px,transparent 1px);background-size:34px 34px;"></div>
        <div aria-hidden="true" style="position:absolute;inset:0;background-image:radial-gradient(ellipse at 78% 8%,rgba(169,116,26,.18) 0,transparent 45%);"></div>

        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 py-14 md:py-20">
            <div class="pp-hero-grid">

                {{-- Kiri: identitas --}}
                <div>
                    <div class="inline-flex items-center gap-2 mb-6">
                        <span class="w-6 h-px" style="background:#A9741A;"></span>
                        <span class="text-[11px] font-medium uppercase" style="color:#C9A24B;font-family:'IBM Plex Mono',monospace;letter-spacing:.22em;">Perumahan · Transparansi Warga</span>
                    </div>

                    <h1 class="font-semibold mb-5" style="color:#ffffff;font-family:'Fraunces',Georgia,serif;font-size:clamp(2.3rem,5vw,3.6rem);line-height:1.05;letter-spacing:-.01em;">
                        {{ config('app.name', 'Denah Warga') }}
                    </h1>
                    <p class="text-base sm:text-lg max-w-xl" style="color:rgba(233,236,228,.82);line-height:1.6;">
                        Papan transparansi keuangan &amp; program perumahan dan DKM Masjid — terbuka untuk seluruh warga.
                    </p>

                    {{-- Rail statistik (angka mono, bukan kartu besar) --}}
                    <div class="pp-rail">
                        <div>
                            <div class="val" style="color:#ffffff;">{{ $totalBlocks }}</div>
                            <div class="lbl">Unit Rumah</div>
                        </div>
                        <div class="sep"></div>
                        <div>
                            <div class="val" style="color:#9FE7C4;">{{ $occupiedBlocks }}</div>
                            <div class="lbl">Unit Dihuni</div>
                        </div>
                        <div class="sep"></div>
                        <div>
                            <div class="val" style="color:#ffffff;">{{ $totalResidents }}</div>
                            <div class="lbl">Kepala Keluarga</div>
                        </div>
                    </div>

                    <p class="text-xs mt-8 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full" style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.18);color:rgba(233,236,228,.8);font-family:'IBM Plex Mono',monospace;">
                        <span class="w-1.5 h-1.5 rounded-full" style="background:#7ee2b8;box-shadow:0 0 0 3px rgba(126,226,184,.25);"></span>
                        Data per {{ $dataAsOf }} WIB
                    </p>
                </div>

                {{-- Kanan: denah okupansi (plakat) --}}
                @if(count($denah) > 0)
                <div class="rounded-2xl p-5 sm:p-6" style="background:rgba(255,255,255,.055);border:1px solid rgba(255,255,255,.14);backdrop-filter:blur(2px);position:relative;">
                    {{-- sudut kuningan (plakat) --}}
                    <span aria-hidden="true" style="position:absolute;top:10px;left:10px;width:14px;height:14px;border-top:2px solid #A9741A;border-left:2px solid #A9741A;"></span>
                    <span aria-hidden="true" style="position:absolute;top:10px;right:10px;width:14px;height:14px;border-top:2px solid #A9741A;border-right:2px solid #A9741A;"></span>
                    <span aria-hidden="true" style="position:absolute;bottom:10px;left:10px;width:14px;height:14px;border-bottom:2px solid #A9741A;border-left:2px solid #A9741A;"></span>
                    <span aria-hidden="true" style="position:absolute;bottom:10px;right:10px;width:14px;height:14px;border-bottom:2px solid #A9741A;border-right:2px solid #A9741A;"></span>

                    <div class="flex items-center justify-between mb-4 px-1">
                        <span class="text-[11px] font-medium uppercase" style="color:rgba(233,236,228,.75);font-family:'IBM Plex Mono',monospace;letter-spacing:.18em;">Denah Okupansi</span>
                        <span class="text-[11px] font-semibold" style="color:#9FE7C4;font-family:'IBM Plex Mono',monospace;">{{ $occPct }}% dihuni</span>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(14,1fr);gap:4px;">
                        @foreach($denah as $cell)
                            <div title="{{ $cell['code'] }} — {{ $cell['occupied'] ? 'Dihuni' : 'Kosong' }}"
                                style="aspect-ratio:1/1;border-radius:3px;{{ $cell['occupied']
                                    ? 'background:#9FE7C4;box-shadow:inset 0 0 0 1px rgba(22,74,64,.15);'
                                    : 'background:transparent;border:1px solid rgba(255,255,255,.22);' }}"></div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between mt-4 px-1">
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center gap-1.5 text-[11px]" style="color:rgba(233,236,228,.75);font-family:'IBM Plex Mono',monospace;">
                                <span style="width:10px;height:10px;border-radius:2px;background:#9FE7C4;display:inline-block;"></span> Dihuni
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-[11px]" style="color:rgba(233,236,228,.75);font-family:'IBM Plex Mono',monospace;">
                                <span style="width:10px;height:10px;border-radius:2px;border:1px solid rgba(255,255,255,.4);display:inline-block;"></span> Kosong
                            </span>
                        </div>
                        @if($denahExtra > 0)
                            <span class="text-[11px]" style="color:rgba(233,236,228,.55);font-family:'IBM Plex Mono',monospace;">+{{ $denahExtra }} unit</span>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 py-10 space-y-10">

        {{-- IPL Status Section --}}
        @if($currentIplPeriod && !empty($iplSummary))
        <div>
            <div class="mb-5">
                <p class="pp-eyebrow mb-2">Iuran Pemeliharaan Lingkungan</p>
                <div class="flex items-center gap-3">
                    <div class="w-1 h-6 rounded-full" style="background:#164A40;"></div>
                    <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Status IPL — {{ $currentIplPeriod->period_label }}</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Unit Lunas</p>
                    <p class="text-2xl font-bold mt-1 pp-num" style="color:#12805c;">{{ $iplSummary['lunas'] ?? 0 }}</p>
                    <div class="mt-2 h-1.5 rounded-full overflow-hidden" style="background:#E0DFD4;">
                        @php $pctLunas = ($iplSummary['total_unit'] ?? 0) > 0 ? min(100, round(($iplSummary['lunas'] / $iplSummary['total_unit']) * 100)) : 0; @endphp
                        <div class="h-full rounded-full" style="width:{{ $pctLunas }}%;background:#12805c;"></div>
                    </div>
                    <p class="text-xs mt-1" style="color:#909A8F;">{{ $pctLunas }}% dari {{ $iplSummary['total_unit'] ?? 0 }} unit</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Belum Bayar</p>
                    <p class="text-2xl font-bold mt-1 pp-num" style="color:#B0402C;">{{ $iplSummary['belum'] ?? 0 }}</p>
                    <p class="text-xs mt-3" style="color:#909A8F;">unit belum membayar</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Dana Terkumpul</p>
                    <p class="text-lg font-bold mt-1 pp-num" style="color:#17231E;">Rp {{ number_format($iplSummary['terkumpul'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">periode {{ $currentIplPeriod->period_label }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Masih Tunggakan</p>
                    <p class="text-lg font-bold mt-1 pp-num" style="color:#A9741A;">Rp {{ number_format(max(0, $iplSummary['tunggakan'] ?? 0), 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">perlu ditagih</p>
                </div>
            </div>

            {{-- Rincian dana terkumpul per komponen IPL --}}
            <div class="mt-4 rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <p class="text-xs font-medium uppercase tracking-wide mb-3" style="color:#909A8F;">Rincian Dana Terkumpul per Komponen</p>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" style="background:#164A40;"></span>
                            <p class="text-xs" style="color:#909A8F;">Keamanan</p>
                        </div>
                        <p class="text-base font-bold mt-1 pp-num" style="color:#17231E;">Rp {{ number_format($iplSummary['terkumpul_security'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" style="background:#12805c;"></span>
                            <p class="text-xs" style="color:#909A8F;">Kebersihan</p>
                        </div>
                        <p class="text-base font-bold mt-1 pp-num" style="color:#17231E;">Rp {{ number_format($iplSummary['terkumpul_garbage'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" style="background:#A9741A;"></span>
                            <p class="text-xs" style="color:#909A8F;">Kas RT</p>
                        </div>
                        <p class="text-base font-bold mt-1 pp-num" style="color:#17231E;">Rp {{ number_format($iplSummary['terkumpul_kas_rt'] ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(\App\Models\Setting::moduleEnabled('dkm'))
        {{-- DKM Finance Section --}}
        <div>
            <div class="mb-5">
                <p class="pp-eyebrow mb-2">Kas Masjid</p>
                <div class="flex items-center gap-3">
                    <div class="w-1 h-6 rounded-full" style="background:#12805c;"></div>
                    <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Keuangan DKM — {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y') }}</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Saldo Kas DKM</p>
                    <p class="text-2xl font-bold mt-1 pp-num" style="color:#17231E;">Rp {{ number_format($dkmBalance, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">total kas tersedia</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Pemasukan</p>
                    <p class="text-2xl font-bold mt-1 pp-num" style="color:#12805c;">Rp {{ number_format($dkmMonthlyIncome, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">bulan ini</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Pengeluaran</p>
                    <p class="text-2xl font-bold mt-1 pp-num" style="color:#B0402C;">Rp {{ number_format($dkmMonthlyExpense, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">bulan ini</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    @php $dkmNet = $dkmMonthlyIncome - $dkmMonthlyExpense; @endphp
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Selisih Bersih</p>
                    <p class="text-2xl font-bold mt-1 pp-num" style="color:{{ $dkmNet >= 0 ? '#164A40' : '#B0402C' }};">{{ $dkmNet >= 0 ? '+' : '' }}Rp {{ number_format($dkmNet, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">pemasukan - pengeluaran</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Rental Listings Section --}}
        @if($rentalListings->count() > 0)
        <div>
            <div class="mb-5">
                <p class="pp-eyebrow mb-2">Pasar Warga</p>
                <div class="flex items-center gap-3">
                    <div class="w-1 h-6 rounded-full" style="background:#A9741A;"></div>
                    <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Rumah Dijual &amp; Disewakan</h2>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($rentalListings as $listing)
                    @php
                        $photo = $listing->primary_photo;
                        $owner = $listing->owners->first();
                        $isForSale = ($listing->listing_type ?? 'sewa') === 'jual';
                        // Samarkan nomor HP di halaman publik demi privasi warga (WhatsApp tetap bisa dihubungi).
                        $maskedPhone = null;
                        if ($owner && $owner->phone) {
                            $digits = preg_replace('/[^0-9]/', '', $owner->phone);
                            $maskedPhone = strlen($digits) > 6
                                ? substr($digits, 0, 4) . str_repeat('•', max(3, strlen($digits) - 6)) . substr($digits, -2)
                                : $digits;
                        }
                    @endphp
                    <div class="group rounded-2xl overflow-hidden transition-all"
                        style="position:relative;display:flex;flex-direction:column;background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);"
                        onmouseover="this.style.boxShadow='0 4px 24px rgba(22,74,64,0.12)';this.style.borderColor='rgba(22,74,64,0.3)'"
                        onmouseout="this.style.boxShadow='0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06)';this.style.borderColor='#E0DFD4'">

                        {{-- Stretched link: seluruh kartu menuju detail (tanpa membungkus tombol WA) --}}
                        <a href="{{ route('rental.detail', $listing->id) }}" wire:navigate
                            aria-label="Lihat detail rumah Blok {{ $listing->block_code }}"
                            style="position:absolute;inset:0;z-index:1;"></a>

                        {{-- Photo --}}
                        <div class="relative overflow-hidden" style="aspect-ratio:4/3;background:#F1F3EC;">
                            @if($photo)
                                <img src="{{ Storage::disk('public')->url($photo->photo_path) }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    alt="Rumah Blok {{ $listing->block_code }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12" style="color:#C9C7BA;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                            @endif
                            @if($listing->photos->count() > 1)
                                <div class="absolute bottom-2 right-2 flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-semibold" style="background:rgba(0,0,0,0.6);color:#fff;backdrop-filter:blur(4px);">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $listing->photos->count() }}
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-4 flex flex-col flex-1">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div>
                                    <h3 class="text-sm font-bold" style="color:#17231E;">Blok {{ $listing->block_code }}</h3>
                                    <p class="text-xs mt-0.5" style="color:#909A8F;">{{ $listing->block_letter }}-{{ $listing->unit_number }}</p>
                                </div>
                                <span class="shrink-0 text-xs font-bold px-2.5 py-1 rounded-lg" style="background:{{ $isForSale ? 'rgba(22,74,64,0.1)' : 'rgba(169,116,26,0.1)' }};color:{{ $isForSale ? '#164A40' : '#A9741A' }};border:1px solid {{ $isForSale ? 'rgba(22,74,64,0.2)' : 'rgba(169,116,26,0.2)' }};">
                                    {{ $isForSale ? 'Dijual' : 'Disewakan' }}
                                </span>
                            </div>

                            @if($listing->rental_price)
                                <div class="flex items-baseline gap-1 mb-2">
                                    <span class="text-lg font-extrabold pp-num" style="color:#164A40;">Rp {{ number_format($listing->rental_price, 0, ',', '.') }}</span>
                                    @if(!$isForSale)
                                        <span class="text-xs" style="color:#909A8F;">/ {{ match($listing->rental_duration ?? 'bulanan') { '6bulan' => '6 bulan', 'tahunan' => 'tahun', default => 'bulan' } }}</span>
                                    @endif
                                </div>
                            @endif

                            @if($listing->rental_description)
                                <p class="text-xs leading-relaxed mb-3 line-clamp-2" style="color:#586359;">{{ $listing->rental_description }}</p>
                            @endif

                            {{-- Contact --}}
                            @if($owner)
                                @php
                                    $wa = $owner->whatsapp ?: $owner->phone;
                                    $waDigits = $wa ? preg_replace('/[^0-9]/', '', $wa) : null;
                                    if ($waDigits) {
                                        $waDigits = str_starts_with($waDigits, '0')
                                            ? '62' . substr($waDigits, 1)
                                            : (str_starts_with($waDigits, '62') ? $waDigits : '62' . $waDigits);
                                    }
                                @endphp
                                <div class="mt-auto pt-3" style="border-top:1px solid #F1F3EC;">
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0" style="background:rgba(22,74,64,0.12);color:#164A40;">
                                            {{ strtoupper(substr($owner->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold truncate" style="color:#17231E;">{{ $owner->name }}</p>
                                            @if($maskedPhone)
                                                <p class="text-[11px]" style="color:#909A8F;font-family:'IBM Plex Mono',monospace;">{{ $maskedPhone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($waDigits)
                                        <a href="https://wa.me/{{ $waDigits }}?text=Halo%2C+saya+tertarik+dengan+rumah+Blok+{{ $listing->block_code }}"
                                            target="_blank" rel="noopener"
                                            class="w-full inline-flex items-center justify-center gap-1.5 text-xs font-semibold py-2 rounded-lg transition-colors"
                                            style="position:relative;z-index:2;background:rgba(18,128,92,0.12);color:#12805c;"
                                            onmouseover="this.style.background='rgba(18,128,92,0.22)'" onmouseout="this.style.background='rgba(18,128,92,0.12)'">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            Hubungi via WhatsApp
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Programs / Campaigns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            @if(\App\Models\Setting::moduleEnabled('perumahan'))
            {{-- Perumahan Programs --}}
            <div>
                <div class="mb-5">
                    <p class="pp-eyebrow mb-2">Penggalangan · Perumahan</p>
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 rounded-full" style="background:#164A40;"></div>
                        <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Program Perumahan</h2>
                    </div>
                </div>
                <div class="space-y-4">
                    @forelse($activeCampaignsPerumahan as $campaign)
                        <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);" wire:key="p-campaign-{{ $campaign->id }}">
                            <div class="flex justify-between items-start gap-3 mb-3">
                                <h3 class="font-semibold leading-tight" style="color:#17231E;">{{ $campaign->name }}</h3>
                                <span class="text-xs font-medium px-2.5 py-1 rounded-lg whitespace-nowrap" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Aktif</span>
                            </div>
                            @php
                                $target = (float)($campaign->target_amount ?? 0);
                                $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                $progress = $target > 0 ? min(100, round($raised / $target * 100)) : ($raised > 0 ? 100 : 0);
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs mb-1.5" style="color:#909A8F;">
                                    <span>Terkumpul: <span class="pp-num" style="color:#17231E;font-weight:600;">Rp {{ number_format($raised, 0, ',', '.') }}</span></span>
                                    <span style="color:#17231E;">{{ $progress }}%</span>
                                </div>
                                <div class="h-2 rounded-full overflow-hidden" style="background:#E0DFD4;">
                                    <div class="h-full rounded-full transition-all" style="width:{{ $progress }}%;background:linear-gradient(to right,#164A40,#164A40);"></div>
                                </div>
                                @if($target > 0)
                                    <div class="text-xs mt-1" style="color:#909A8F;">Target: Rp {{ number_format($target, 0, ',', '.') }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl p-6 text-center" style="background:#ffffff;border:1px dashed #E0DFD4;">
                            <p class="text-sm" style="color:#909A8F;">Tidak ada program perumahan aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @endif

            @if(\App\Models\Setting::moduleEnabled('dkm'))
            {{-- DKM Programs --}}
            <div>
                <div class="mb-5">
                    <p class="pp-eyebrow mb-2">Penggalangan · DKM</p>
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-6 rounded-full" style="background:#12805c;"></div>
                        <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Program DKM Masjid</h2>
                    </div>
                </div>
                <div class="space-y-4">
                    @forelse($activeCampaignsDkm as $campaign)
                        <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);" wire:key="d-campaign-{{ $campaign->id }}">
                            <div class="flex justify-between items-start gap-3 mb-3">
                                <h3 class="font-semibold leading-tight" style="color:#17231E;">{{ $campaign->name }}</h3>
                                <span class="text-xs font-medium px-2.5 py-1 rounded-lg whitespace-nowrap" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Aktif</span>
                            </div>
                            @php
                                $target = (float)($campaign->target_amount ?? 0);
                                $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                $progress = $target > 0 ? min(100, round($raised / $target * 100)) : ($raised > 0 ? 100 : 0);
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs mb-1.5" style="color:#909A8F;">
                                    <span>Terkumpul: <span class="pp-num" style="color:#17231E;font-weight:600;">Rp {{ number_format($raised, 0, ',', '.') }}</span></span>
                                    <span style="color:#12805c;">{{ $progress }}%</span>
                                </div>
                                <div class="h-2 rounded-full overflow-hidden" style="background:#E0DFD4;">
                                    <div class="h-full rounded-full transition-all" style="width:{{ $progress }}%;background:linear-gradient(to right,#12805c,#12805c);"></div>
                                </div>
                                @if($target > 0)
                                    <div class="text-xs mt-1" style="color:#909A8F;">Target: Rp {{ number_format($target, 0, ',', '.') }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl p-6 text-center" style="background:#ffffff;border:1px dashed #E0DFD4;">
                            <p class="text-sm" style="color:#909A8F;">Tidak ada program DKM aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @endif

        </div>

        {{-- Footer --}}
        <div class="text-center py-6" style="border-top:1px solid #F1F3EC;">
            <p class="text-sm" style="color:#909A8F;">Data diperbarui secara real-time &bull; Sistem Informasi Perumahan &copy; {{ date('Y') }}</p>
        </div>

    </div>
</div>
