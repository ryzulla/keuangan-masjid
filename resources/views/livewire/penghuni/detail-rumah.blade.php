<div>
    @php use Carbon\Carbon as CarbonAlias; @endphp

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Back --}}
    <a href="{{ route('penghuni.rumah-saya') }}" wire:navigate
        class="inline-flex items-center gap-1.5 text-sm font-medium mb-5 transition-colors"
        style="color:#586359;"
        onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Rumah Saya
    </a>

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold" style="background:rgba(22,74,64,0.12);color:#164A40;">
            {{ $hb->block_letter }}{{ $hb->unit_number }}
        </div>
        <div>
            <h1 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Blok {{ $hb->block_code }}</h1>
            <p class="text-xs" style="color:#909A8F;">{{ $hb->block_letter }}-{{ $hb->unit_number }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 p-1 rounded-xl mb-6" style="background:#ffffff;border:1px solid #E0DFD4;">
        <button wire:click="switchTab('penyewa')"
            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all"
            style="{{ $activeTab === 'penyewa' ? 'background:#164A40;color:#ffffff;' : 'color:#586359;' }}">
            Penyewa
        </button>
        <button wire:click="switchTab('listing')"
            class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all"
            style="{{ $activeTab === 'listing' ? 'background:#164A40;color:#ffffff;' : 'color:#586359;' }}">
            Listing & Gallery
        </button>
    </div>

    {{-- ═══════════ TAB: PENYEWA ═══════════ --}}
    @if($activeTab === 'penyewa')
        {{-- Add Tenant Button --}}
        <div class="flex justify-end mb-4">
            <button wire:click="openCreateTenant"
                class="px-4 py-2 rounded-xl text-sm font-medium transition-colors inline-flex items-center gap-1.5"
                style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);"
                onmouseover="this.style.background='rgba(18,128,92,0.2)'" onmouseout="this.style.background='rgba(18,128,92,0.1)'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Penyewa
            </button>
        </div>

        {{-- Active Tenants --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;">
            <div class="px-5 py-3.5" style="border-bottom:1px solid #F1F3EC;">
                <h3 class="text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Penyewa Aktif</h3>
            </div>
            @if($tenants->isEmpty())
                <div class="p-6 sm:p-12 text-center">
                    <p class="text-sm" style="color:#909A8F;">Belum ada penyewa aktif</p>
                </div>
            @else
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom:1px solid #F1F3EC;">
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nama</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#909A8F;">Periode</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#909A8F;">Sewa/Bulan</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tenants as $tenant)
                            @php $endingSoon = $tenant->contract_end_date && $tenant->contract_end_date->diffInDays(now()) <= 30; @endphp
                            <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                            style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">
                                            {{ strtoupper(substr($tenant->resident->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium" style="color:#17231E;">{{ $tenant->resident->name }}</p>
                                            @if($tenant->resident->phone)
                                                <p class="text-xs" style="color:#909A8F;">{{ $tenant->resident->phone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 hidden sm:table-cell text-xs" style="color:#586359;">
                                    @if($tenant->contract_start_date || $tenant->contract_end_date)
                                        {{ $tenant->contract_start_date?->format('d M Y') ?? '—' }}
                                        @if($tenant->contract_end_date) &ndash; {{ $tenant->contract_end_date->format('d M Y') }} @endif
                                    @else &mdash; @endif
                                </td>
                                <td class="px-5 py-3.5 hidden md:table-cell text-xs font-medium" style="color:#17231E;">
                                    @if($tenant->monthly_rent) Rp {{ number_format($tenant->monthly_rent, 0, ',', '.') }} @else &mdash; @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button wire:click="openEditTenant({{ $tenant->id }})"
                                            class="p-1.5 rounded-lg transition-colors" style="color:#17231E;"
                                            onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button wire:click="endContract({{ $tenant->id }})"
                                            wire:confirm="Akhiri kontrak penyewa {{ $tenant->resident->name }}?"
                                            class="p-1.5 rounded-lg transition-colors" style="color:#B0402C;"
                                            onmouseover="this.style.background='rgba(176,64,44,0.1)'" onmouseout="this.style.background=''">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Mobile --}}
                <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                    @foreach($tenants as $tenant)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                    style="background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);">
                                    {{ strtoupper(substr($tenant->resident->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-sm" style="color:#17231E;">{{ $tenant->resident->name }}</p>
                                    @if($tenant->resident->phone) <p class="text-xs" style="color:#909A8F;">{{ $tenant->resident->phone }}</p> @endif
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button wire:click="openEditTenant({{ $tenant->id }})" class="p-1.5 rounded-lg" style="color:#17231E;">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="endContract({{ $tenant->id }})" wire:confirm="Akhiri kontrak?" class="p-1.5 rounded-lg" style="color:#B0402C;">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 mt-3">
                            <div>
                                <p class="text-[11px]" style="color:#909A8F;">Periode</p>
                                <p class="text-xs font-medium" style="color:#586359;">
                                    {{ $tenant->contract_start_date?->format('d M Y') ?? '—' }} – {{ $tenant->contract_end_date?->format('d M Y') ?? '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[11px]" style="color:#909A8F;">Sewa/Bulan</p>
                                <p class="text-xs font-medium" style="color:#17231E;">
                                    @if($tenant->monthly_rent) Rp {{ number_format($tenant->monthly_rent, 0, ',', '.') }} @else — @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Riwayat Penyewa --}}
        @if($pastTenants->isNotEmpty())
        <div class="rounded-2xl overflow-hidden mt-5" style="background:#ffffff;border:1px solid #E0DFD4;">
            <div class="px-5 py-3.5" style="border-bottom:1px solid #F1F3EC;">
                <h3 class="text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Riwayat Penyewa</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="border-bottom:1px solid #F1F3EC;">
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nama</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#909A8F;">Periode</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Berakhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pastTenants as $pt)
                        <tr style="border-bottom:1px solid #F1F3EC;">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0" style="background:#F1F3EC;color:#909A8F;">
                                        {{ strtoupper(substr($pt->resident->name, 0, 1)) }}
                                    </div>
                                    <span style="color:#586359;">{{ $pt->resident->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell text-xs" style="color:#586359;">
                                @if($pt->contract_start_date)
                                    {{ CarbonAlias::parse($pt->contract_start_date)->format('d M Y') }}
                                    @if($pt->contract_end_date) &ndash; {{ CarbonAlias::parse($pt->contract_end_date)->format('d M Y') }} @endif
                                @else &mdash; @endif
                            </td>
                            <td class="px-5 py-3 text-xs" style="color:#B0402C;">
                                {{ CarbonAlias::parse($pt->ended_at)->format('d M Y') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endif

    {{-- ═══════════ TAB: LISTING & GALLERY ═══════════ --}}
    @if($activeTab === 'listing')
        {{-- Listing Info --}}
        <section class="rounded-2xl p-5 sm:p-6 mb-5 space-y-5" style="background:#ffffff;border:1px solid #E0DFD4;">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold" style="color:#17231E;">Tampilkan di Halaman Depan</p>
                    <p class="text-xs" style="color:#909A8F;">Aktifkan agar rumah muncul di listing publik</p>
                </div>
                <label class="relative inline-flex shrink-0 cursor-pointer">
                    <input wire:model.live="editIsForRent" type="checkbox" class="sr-only peer">
                    <span class="w-11 h-6 rounded-full transition-colors peer-checked:bg-[#164A40]" style="background:#E0DFD4;"></span>
                    <span class="absolute left-0.5 top-0.5 w-5 h-5 rounded-full bg-white transition-transform peer-checked:translate-x-5"></span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Jenis Listing</label>
                <div class="flex gap-1.5 rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                    <button type="button" wire:click="$set('editListingType','sewa')"
                        class="flex-1 py-2 text-xs font-medium transition-all"
                        style="{{ $editListingType === 'sewa' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;' }}">
                        Sewa
                    </button>
                    <button type="button" wire:click="$set('editListingType','jual')"
                        class="flex-1 py-2 text-xs font-medium transition-all"
                        style="{{ $editListingType === 'jual' ? 'background:#12805c;color:#ffffff;' : 'background:#ffffff;color:#909A8F;border-left:1px solid #E0DFD4;' }}">
                        Jual
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium mb-1.5" style="color:#586359;">
                    {{ $editListingType === 'jual' ? 'Harga Jual' : 'Harga Sewa' }}
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-semibold" style="color:#909A8F;">Rp</span>
                    <input wire:model="editPrice" type="number" min="0" placeholder="Contoh: 1500000"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem 0.65rem 3rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>
                @error('editPrice') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
            </div>

            @if($editListingType === 'sewa')
            <div>
                <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Durasi Sewa</label>
                <div class="flex gap-1.5 rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                    <button type="button" wire:click="$set('editDuration','bulanan')"
                        class="flex-1 py-2 text-xs font-medium transition-all"
                        style="{{ $editDuration === 'bulanan' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;' }}">
                        Bulanan
                    </button>
                    <button type="button" wire:click="$set('editDuration','6bulan')"
                        class="flex-1 py-2 text-xs font-medium transition-all"
                        style="{{ $editDuration === '6bulan' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;border-left:1px solid #E0DFD4;' }}">
                        6 Bulan
                    </button>
                    <button type="button" wire:click="$set('editDuration','tahunan')"
                        class="flex-1 py-2 text-xs font-medium transition-all"
                        style="{{ $editDuration === 'tahunan' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;border-left:1px solid #E0DFD4;' }}">
                        Tahunan
                    </button>
                </div>
            </div>
            @endif

            <div>
                <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Deskripsi Singkat</label>
                <textarea wire:model="editDescription" rows="4" placeholder="Deskripsi rumah..."
                    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:vertical;"
                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'"></textarea>
                @error('editDescription') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
            </div>

            {{-- Property Specs --}}
            <div>
                <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Spesifikasi Properti</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] mb-1" style="color:#909A8F;">Luas Tanah (m²)</label>
                        <input wire:model="editLandArea" type="number" min="0" placeholder="72"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.8rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    </div>
                    <div>
                        <label class="block text-[11px] mb-1" style="color:#909A8F;">Luas Bangunan (m²)</label>
                        <input wire:model="editBuildingArea" type="number" min="0" placeholder="54"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.8rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-[11px] mb-1" style="color:#909A8F;">KT</label>
                    <input wire:model="editBedrooms" type="number" min="0" placeholder="3"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.8rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>
                <div>
                    <label class="block text-[11px] mb-1" style="color:#909A8F;">KM</label>
                    <input wire:model="editBathrooms" type="number" min="0" placeholder="2"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.8rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>
                <div>
                    <label class="block text-[11px] mb-1" style="color:#909A8F;">Listrik (W)</label>
                    <input wire:model="editElectricity" type="number" min="0" placeholder="2200"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.8rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] mb-1" style="color:#909A8F;">Sumber Air</label>
                    <div class="flex gap-1 rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                        <button type="button" wire:click="$set('editWaterSource','pdam')"
                            class="flex-1 py-2 text-[11px] font-medium transition-all"
                            style="{{ $editWaterSource === 'pdam' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;' }}">
                            PDAM
                        </button>
                        <button type="button" wire:click="$set('editWaterSource','tanah')"
                            class="flex-1 py-2 text-[11px] font-medium transition-all"
                            style="{{ $editWaterSource === 'tanah' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;border-left:1px solid #E0DFD4;' }}">
                            Tanah
                        </button>
                        <button type="button" wire:click="$set('editWaterSource','both')"
                            class="flex-1 py-2 text-[11px] font-medium transition-all"
                            style="{{ $editWaterSource === 'both' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;border-left:1px solid #E0DFD4;' }}">
                            Keduanya
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] mb-1" style="color:#909A8F;">Garasi (mobil)</label>
                    <input wire:model="editGarage" type="number" min="0" max="10" placeholder="0"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.8rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>
            </div>

            <button wire:click="saveRentalInfo"
                class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors"
                style="background:#164A40;color:#ffffff;"
                onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#164A40'"
                wire:loading.attr="disabled" wire:target="saveRentalInfo">
                <span wire:loading.remove wire:target="saveRentalInfo">Simpan</span>
                <span wire:loading wire:target="saveRentalInfo">Menyimpan...</span>
            </button>
        </section>

        {{-- Gallery --}}
        <section class="rounded-2xl p-5 sm:p-6 space-y-4" style="background:#ffffff;border:1px solid #E0DFD4;">
            <h3 class="text-sm font-semibold" style="color:#17231E;">Foto Gallery</h3>

            @if($hb->photos->count() > 0)
                <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                    @foreach($hb->photos as $photo)
                        <div class="relative group rounded-xl overflow-hidden aspect-square" style="border:1px solid #E0DFD4;">
                            <img src="{{ Storage::disk('public')->url($photo->photo_path) }}" class="w-full h-full object-cover">
                            @if($photo->is_primary)
                                <span class="absolute top-1.5 left-1.5 text-[9px] font-semibold px-1.5 py-0.5 rounded-md" style="background:rgba(22,74,64,0.9);color:#fff;">Utama</span>
                            @endif
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-1.5">
                                @if(!$photo->is_primary)
                                    <button wire:click="setPrimary({{ $photo->id }})"
                                        class="p-1.5 rounded-lg text-white" style="background:rgba(22,74,64,0.8);" title="Jadikan Foto Utama">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    </button>
                                @endif
                                <button wire:click="confirmDeletePhoto({{ $photo->id }})"
                                    class="p-1.5 rounded-lg text-white" style="background:rgba(176,64,44,0.8);" title="Hapus Foto">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Upload --}}
            <div>
                <label class="block w-full text-center px-4 py-4 rounded-xl text-sm font-medium cursor-pointer transition-colors"
                    style="background:#ffffff;border:2px dashed #E0DFD4;color:#586359;"
                    onmouseover="this.style.borderColor='#164A40'" onmouseout="this.style.borderColor='#E0DFD4'">
                    <div class="flex flex-col items-center gap-1">
                        <svg class="w-6 h-6" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                        </svg>
                        <span wire:loading.remove wire:target="newPhotos">Pilih Foto (maks. 2MB per file)</span>
                        <span wire:loading wire:target="newPhotos">Mengunggah...</span>
                    </div>
                    <input wire:model="newPhotos" type="file" accept="image/*" multiple class="hidden">
                </label>
                @error('newPhotos') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
                @error('newPhotos.*') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
            </div>

            @if(count($newPhotos) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($newPhotos as $photo)
                        <div class="w-20 h-20 rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
                <button wire:click="uploadPhotos"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#12805c;color:#ffffff;"
                    onmouseover="this.style.background='#0e6844'" onmouseout="this.style.background='#12805c'"
                    wire:loading.attr="disabled" wire:target="uploadPhotos">
                    <span wire:loading.remove wire:target="uploadPhotos">Unggah {{ count($newPhotos) }} Foto</span>
                    <span wire:loading wire:target="uploadPhotos">Mengunggah...</span>
                </button>
            @endif
        </section>
    @endif

    {{-- ═══════════ MODAL PENYEWA ═══════════ --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);"
            wire:click="$set('isModalOpen', false)"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto" style="background:#ffffff;border:1px solid #D8D6C9;">
            <div class="flex items-center justify-between px-6 py-4 rounded-t-2xl sticky top-0 z-10"
                style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.3);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                    {{ $editingId ? 'Edit Data Penyewa' : 'Tambah Penyewa Baru' }}
                </h3>
                <button wire:click="$set('isModalOpen', false)" class="p-1 rounded-lg" style="color:#17231E;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="saveTenant" class="px-6 py-5 space-y-4">
                @if(!$editingId)
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Jenis Penyewa</label>
                    <div class="flex gap-1.5 rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                        <button type="button" wire:click="$set('tenantMode','baru')"
                            class="flex-1 py-2 text-xs font-medium transition-all"
                            style="{{ $tenantMode === 'baru' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;' }}">
                            Penyewa Baru
                        </button>
                        <button type="button" wire:click="$set('tenantMode','terdaftar')"
                            class="flex-1 py-2 text-xs font-medium transition-all"
                            style="{{ $tenantMode === 'terdaftar' ? 'background:#164A40;color:#ffffff;' : 'background:#ffffff;color:#909A8F;border-left:1px solid #E0DFD4;' }}">
                            Penghuni Terdaftar
                        </button>
                    </div>
                </div>

                @if($tenantMode === 'terdaftar')
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Pilih Penghuni <span style="color:#B0402C;">*</span></label>
                    <select wire:model="existingResidentId"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                        <option value="">-- Pilih --</option>
                        @foreach($availableResidents as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('existingResidentId') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Nama Penyewa <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama lengkap"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    @error('name') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">No. Telepon</label>
                        <input type="tel" wire:model="phone" placeholder="08xx"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">WhatsApp</label>
                        <input type="tel" wire:model="whatsapp" placeholder="08xx"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                </div>
                @endif
                @else
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Nama Penyewa <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama lengkap"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    @error('name') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">No. Telepon</label>
                        <input type="tel" wire:model="phone" placeholder="08xx"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">WhatsApp</label>
                        <input type="tel" wire:model="whatsapp" placeholder="08xx"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                </div>
                @endif
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Mulai Kontrak</label>
                        <input type="date" wire:model="contractStart"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                        @error('contractStart') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Akhir Kontrak</label>
                        <input type="date" wire:model="contractEnd"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                        @error('contractEnd') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Sewa / Bulan</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color:#909A8F;">Rp</span>
                        <input type="number" wire:model="monthlyRent" placeholder="0" min="0"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem 0.55rem 2.5rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                    @error('monthlyRent') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl cursor-pointer" style="background:#ffffff;border:1px solid #E0DFD4;">
                    <input type="checkbox" wire:model="paysIpl" style="accent-color:#17231E;width:16px;height:16px;">
                    <div>
                        <span class="text-sm font-medium" style="color:#17231E;">Penanggung IPL</span>
                        <p class="text-xs" style="color:#909A8F;">Tagihan IPL dibebankan ke penyewa ini</p>
                    </div>
                </label>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Catatan</label>
                    <textarea wire:model="notes" rows="2" placeholder="Catatan tambahan..."
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:none;"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #E0DFD4;">
                    <button type="button" wire:click="$set('isModalOpen', false)"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 text-sm rounded-xl font-semibold"
                        style="background:#164A40;color:#ffffff;"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="saveTenant">Simpan</span>
                        <span wire:loading wire:target="saveTenant">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
