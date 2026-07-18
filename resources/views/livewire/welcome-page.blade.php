<div>
    {{-- Error alert --}}
    @if(session('page_error'))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-6 px-4">
            <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                <span>{{ session('page_error') }}</span>
            </div>
        </div>
    @endif

    {{-- Hero Section (FindHouse-style coral hero) --}}
    <div class="relative overflow-hidden" style="background:linear-gradient(120deg,#164A40 0%,#0F3A32 60%,#0F3A32 100%);">
        <div aria-hidden="true" style="position:absolute;inset:0;background-image:radial-gradient(circle at 20% 20%, rgba(255,255,255,.14) 0, transparent 45%),radial-gradient(circle at 85% 80%, rgba(255,255,255,.10) 0, transparent 40%);"></div>
        <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 py-20 md:py-28">
            <div class="text-center">
                {{-- Logo icon --}}
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-6"
                    style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.35);backdrop-filter:blur(4px);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="#ffffff" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold mb-4" style="color:#ffffff;font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:-.02em;">Portal Informasi Perumahan</h1>
                <p class="text-lg max-w-2xl mx-auto" style="color:rgba(255,255,255,.88);">Transparansi pengelolaan keuangan &amp; program perumahan dan DKM Masjid</p>
            </div>

            {{-- Quick Stats (floating white cards) --}}
            <div class="grid grid-cols-3 gap-4 mt-12 max-w-2xl mx-auto">
                <div class="text-center rounded-lg p-5" style="background:#ffffff;box-shadow:0 10px 40px rgba(0,0,0,.15);">
                    <div class="text-2xl sm:text-3xl font-extrabold" style="color:#164A40;font-family:'Plus Jakarta Sans',sans-serif;">{{ $totalBlocks }}</div>
                    <div class="text-xs mt-0.5" style="color:#586359;">Unit Rumah</div>
                </div>
                <div class="text-center rounded-lg p-5" style="background:#ffffff;box-shadow:0 10px 40px rgba(0,0,0,.15);">
                    <div class="text-2xl sm:text-3xl font-extrabold" style="color:#164A40;font-family:'Plus Jakarta Sans',sans-serif;">{{ $occupiedBlocks }}</div>
                    <div class="text-xs mt-0.5" style="color:#586359;">Unit Dihuni</div>
                </div>
                <div class="text-center rounded-lg p-5" style="background:#ffffff;box-shadow:0 10px 40px rgba(0,0,0,.15);">
                    <div class="text-2xl sm:text-3xl font-extrabold" style="color:#164A40;font-family:'Plus Jakarta Sans',sans-serif;">{{ $totalResidents }}</div>
                    <div class="text-xs mt-0.5" style="color:#586359;">Kepala Keluarga</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 py-10 space-y-10">

        {{-- IPL Status Section --}}
        @if($currentIplPeriod && !empty($iplSummary))
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-1 h-6 rounded-full" style="background:#164A40;"></div>
                <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Status IPL — {{ $currentIplPeriod->period_label }}</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Unit Lunas</p>
                    <p class="text-2xl font-bold mt-1" style="color:#12805c;">{{ $iplSummary['lunas'] ?? 0 }}</p>
                    <div class="mt-2 h-1.5 rounded-full overflow-hidden" style="background:#E0DFD4;">
                        @php $pctLunas = ($iplSummary['total_unit'] ?? 0) > 0 ? min(100, round(($iplSummary['lunas'] / $iplSummary['total_unit']) * 100)) : 0; @endphp
                        <div class="h-full rounded-full" style="width:{{ $pctLunas }}%;background:#12805c;"></div>
                    </div>
                    <p class="text-xs mt-1" style="color:#909A8F;">{{ $pctLunas }}% dari {{ $iplSummary['total_unit'] ?? 0 }} unit</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Belum Bayar</p>
                    <p class="text-2xl font-bold mt-1" style="color:#B0402C;">{{ $iplSummary['belum'] ?? 0 }}</p>
                    <p class="text-xs mt-3" style="color:#909A8F;">unit belum membayar</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Dana Terkumpul</p>
                    <p class="text-lg font-bold mt-1" style="color:#17231E;">Rp {{ number_format($iplSummary['terkumpul'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">periode {{ $currentIplPeriod->period_label }}</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Masih Tunggakan</p>
                    <p class="text-lg font-bold mt-1" style="color:#A9741A;">Rp {{ number_format(max(0, $iplSummary['tunggakan'] ?? 0), 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">perlu ditagih</p>
                </div>
            </div>
        </div>
        @endif

        {{-- DKM Finance Section --}}
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-1 h-6 rounded-full" style="background:#12805c;"></div>
                <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Keuangan DKM — {{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y') }}</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Saldo Kas DKM</p>
                    <p class="text-2xl font-bold mt-1" style="color:#17231E;">Rp {{ number_format($dkmBalance, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">total kas tersedia</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Pemasukan</p>
                    <p class="text-2xl font-bold mt-1" style="color:#12805c;">Rp {{ number_format($dkmMonthlyIncome, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">bulan ini</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Pengeluaran</p>
                    <p class="text-2xl font-bold mt-1" style="color:#B0402C;">Rp {{ number_format($dkmMonthlyExpense, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">bulan ini</p>
                </div>
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                    @php $dkmNet = $dkmMonthlyIncome - $dkmMonthlyExpense; @endphp
                    <p class="text-xs font-medium uppercase tracking-wide" style="color:#909A8F;">Selisih Bersih</p>
                    <p class="text-2xl font-bold mt-1" style="color:{{ $dkmNet >= 0 ? '#164A40' : '#B0402C' }};">{{ $dkmNet >= 0 ? '+' : '' }}Rp {{ number_format($dkmNet, 0, ',', '.') }}</p>
                    <p class="text-xs mt-1" style="color:#909A8F;">pemasukan - pengeluaran</p>
                </div>
            </div>
        </div>

        {{-- Rental Listings Section --}}
        @if($rentalListings->count() > 0)
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="w-1 h-6 rounded-full" style="background:#A9741A;"></div>
                <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Rumah Dijual &amp; Disewakan</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($rentalListings as $listing)
                    @php
                        $photo = $listing->primary_photo;
                        $owner = $listing->owners->first();
                        $isForSale = ($listing->listing_type ?? 'sewa') === 'jual';
                    @endphp
                    <a href="{{ route('rental.detail', $listing->id) }}" wire:navigate
                        class="group rounded-2xl overflow-hidden transition-all"
                        style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);"
                        onmouseover="this.style.boxShadow='0 4px 24px rgba(22,74,64,0.12)';this.style.borderColor='rgba(22,74,64,0.3)'"
                        onmouseout="this.style.boxShadow='0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06)';this.style.borderColor='#E0DFD4'">

                        {{-- Photo --}}
                        <div class="relative aspect-[4/3] overflow-hidden" style="background:#F1F3EC;">
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
                        <div class="p-4">
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
                                    <span class="text-lg font-extrabold" style="color:#164A40;">Rp {{ number_format($listing->rental_price, 0, ',', '.') }}</span>
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
                                <div class="pt-3 space-y-2" style="border-top:1px solid #F1F3EC;">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-[9px] font-bold shrink-0" style="background:rgba(22,74,64,0.12);color:#164A40;">
                                            {{ strtoupper(substr($owner->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-medium truncate" style="color:#17231E;">{{ $owner->name }}</p>
                                        </div>
                                    </div>
                                    @if($owner->phone)
                                        <div class="flex items-center gap-1.5 pl-0.5">
                                            <svg class="w-3 h-3 shrink-0" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span class="text-[11px] font-medium" style="color:#586359;">{{ $owner->phone }}</span>
                                        </div>
                                    @endif
                                    @if($owner->whatsapp || $owner->phone)
                                        @php $wa = $owner->whatsapp ?? $owner->phone; @endphp
                                        <a href="https://wa.me/{{ ltrim(preg_replace('/[^0-9]/', '', $wa), '0') }}?text=Halo%2C+saya+tertarik+untuk+menyewa+rumah+Blok+{{ $listing->block_code }}"
                                            target="_blank" rel="noopener"
                                            class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center transition-colors"
                                            style="background:rgba(18,128,92,0.12);color:#12805c;"
                                            onmouseover="this.style.background='rgba(18,128,92,0.25)'" onmouseout="this.style.background='rgba(18,128,92,0.12)'"
                                            onclick="event.stopPropagation()">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Programs / Campaigns --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Perumahan Programs --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1 h-6 rounded-full" style="background:#164A40;"></div>
                    <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Program Perumahan</h2>
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
                                    <span>Terkumpul: <span style="color:#17231E;font-weight:600;">Rp {{ number_format($raised, 0, ',', '.') }}</span></span>
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

            {{-- DKM Programs --}}
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1 h-6 rounded-full" style="background:#12805c;"></div>
                    <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Program DKM Masjid</h2>
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
                                    <span>Terkumpul: <span style="color:#17231E;font-weight:600;">Rp {{ number_format($raised, 0, ',', '.') }}</span></span>
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

        </div>

        {{-- Footer --}}
        <div class="text-center py-6" style="border-top:1px solid #F1F3EC;">
            <p class="text-sm" style="color:#909A8F;">Data diperbarui secara real-time &bull; Sistem Informasi Perumahan &copy; {{ date('Y') }}</p>
        </div>

    </div>
</div>
