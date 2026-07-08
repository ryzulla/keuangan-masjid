<div>
    @php use Carbon\Carbon as CarbonAlias; @endphp

    @if(session('success'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold" style="color:#111827;font-family:'IBM Plex Sans',serif;">Kelola Penyewa</h2>
        <p class="text-sm mt-0.5" style="color:#667085;">Kelola penyewa di unit rumah Anda</p>
    </div>

    {{-- Owned Blocks --}}
    @if($ownedBlocks->isEmpty())
        <div class="rounded-2xl p-6 sm:p-8 text-center mb-6" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            <p class="text-sm" style="color:#98a2b3;">Anda tidak memiliki unit rumah sebagai pemilik.</p>
        </div>
    @else
        <div class="flex flex-wrap gap-3 mb-6">
            @foreach($ownedBlocks as $block)
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:#ffffff;border:1px solid rgba(16,24,40,0.25);">
                <div>
                    <p class="text-xs" style="color:#667085;">Unit Milik</p>
                    <p class="text-sm font-bold" style="color:#111827;">{{ $block->houseBlock?->block_code ?? '—' }}</p>
                </div>
                <button wire:click="openCreate({{ $block->house_block_id }})"
                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                    style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);"
                    onmouseover="this.style.background='rgba(16,24,40,0.2)'" onmouseout="this.style.background='rgba(16,24,40,0.1)'">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Penyewa
                </button>
            </div>
            @endforeach
        </div>
    @endif

    {{-- Tenants List --}}
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
        <div class="px-5 py-3.5" style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
            <h3 class="text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Daftar Penyewa Aktif</h3>
        </div>

        @if($tenants->isEmpty())
            <div class="p-6 sm:p-12 text-center">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <p class="text-sm" style="color:#98a2b3;">Belum ada penyewa aktif</p>
            </div>
        @else
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #eef0f3;">
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Nama Penyewa</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#98a2b3;">Unit</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#98a2b3;">Periode Kontrak</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#98a2b3;">Sewa/Bulan</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                        <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#eef0f3'" onmouseout="this.style.backgroundColor=''">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                        style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">
                                        {{ strtoupper(substr($tenant->resident->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium" style="color:#1d2939;">{{ $tenant->resident->name }}</p>
                                        @if($tenant->resident->phone)
                                            <p class="text-xs" style="color:#98a2b3;">{{ $tenant->resident->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 hidden sm:table-cell">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium"
                                    style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">
                                    {{ $tenant->houseBlock?->block_code ?? '—' }}
                                </span>
                                <span class="ml-1 text-xs" style="color:#98a2b3;">{{ ucfirst($tenant->ownership_type) }}</span>
                            </td>
                            <td class="px-5 py-3.5 hidden md:table-cell text-xs" style="color:#667085;">
                                @if($tenant->contract_start_date || $tenant->contract_end_date)
                                    {{ $tenant->contract_start_date?->format('d M Y') ?? '—' }}
                                    @if($tenant->contract_end_date)
                                        &ndash; {{ $tenant->contract_end_date->format('d M Y') }}
                                    @endif
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="px-5 py-3.5 hidden md:table-cell text-xs font-medium" style="color:#1d2939;">
                                @if($tenant->monthly_rent)
                                    Rp {{ number_format($tenant->monthly_rent, 0, ',', '.') }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="openEdit({{ $tenant->id }})"
                                        class="p-1.5 rounded-lg transition-colors" style="color:#111827;"
                                        onmouseover="this.style.background='rgba(16,24,40,0.1)'" onmouseout="this.style.background=''">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="endContract({{ $tenant->id }})"
                                        wire:confirm="Akhiri kontrak penyewa {{ $tenant->resident->name }}? Tindakan ini tidak dapat dibatalkan."
                                        class="p-1.5 rounded-lg transition-colors" style="color:#c0453b;"
                                        onmouseover="this.style.background='rgba(192,69,59,0.1)'" onmouseout="this.style.background=''">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="md:hidden divide-y" style="border-color:#eef0f3;">
                @forelse($tenants as $tenant)
                <div class="p-4" wire:key="tenant-mobile-{{ $tenant->id }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">
                                {{ strtoupper(substr($tenant->resident->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold" style="color:#1d2939;">{{ $tenant->resident->name }}</p>
                                @if($tenant->resident->phone)
                                    <p class="text-xs" style="color:#98a2b3;">{{ $tenant->resident->phone }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium"
                                style="background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);">
                                {{ $tenant->houseBlock?->block_code ?? '—' }}
                            </span>
                            <p class="text-xs mt-1" style="color:#98a2b3;">{{ ucfirst($tenant->ownership_type) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 mt-3">
                        <div>
                            <p class="text-xs" style="color:#98a2b3;">Periode Kontrak</p>
                            <p class="text-xs font-medium" style="color:#667085;">
                                @if($tenant->contract_start_date || $tenant->contract_end_date)
                                    {{ $tenant->contract_start_date?->format('d M Y') ?? '—' }}
                                    @if($tenant->contract_end_date)
                                        &ndash; {{ $tenant->contract_end_date->format('d M Y') }}
                                    @endif
                                @else
                                    &mdash;
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs" style="color:#98a2b3;">Sewa/Bulan</p>
                            <p class="text-sm font-medium" style="color:#1d2939;">
                                @if($tenant->monthly_rent)
                                    Rp {{ number_format($tenant->monthly_rent, 0, ',', '.') }}
                                @else
                                    &mdash;
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-1 mt-3 pt-3" style="border-top:1px solid #eef0f3;">
                        <button wire:click="openEdit({{ $tenant->id }})"
                            class="p-1.5 rounded-lg transition-colors" style="color:#111827;"
                            onmouseover="this.style.background='rgba(16,24,40,0.1)'" onmouseout="this.style.background=''">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button wire:click="endContract({{ $tenant->id }})"
                            wire:confirm="Akhiri kontrak penyewa {{ $tenant->resident->name }}? Tindakan ini tidak dapat dibatalkan."
                            class="p-1.5 rounded-lg transition-colors" style="color:#c0453b;"
                            onmouseover="this.style.background='rgba(192,69,59,0.1)'" onmouseout="this.style.background=''">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="p-6 sm:p-12 text-center">
                    <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-sm" style="color:#98a2b3;">Belum ada penyewa aktif</p>
                </div>
                @endforelse
            </div>
        @endif
    </div>

    {{-- ═══ Riwayat Penyewa ═══ --}}
    @if($pastTenants->isNotEmpty())
    <div class="rounded-2xl overflow-hidden mt-5" style="background:#ffffff;border:1px solid #e4e7ec;">
        <div class="px-5 py-3.5" style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
            <h3 class="text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Riwayat Penyewa</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#ffffff;border-bottom:1px solid #eef0f3;">
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Nama</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#98a2b3;">Unit</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#98a2b3;">Periode</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Berakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pastTenants as $pt)
                    <tr style="border-bottom:1px solid #f5f6f8;">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                    style="background:#eef0f3;color:#7c8698;">
                                    {{ strtoupper(substr($pt->resident->name, 0, 1)) }}
                                </div>
                                <span style="color:#475467;">{{ $pt->resident->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 hidden sm:table-cell">
                            <span class="text-xs px-2 py-0.5 rounded-lg" style="background:#eef0f3;color:#7c8698;">
                                {{ $pt->houseBlock?->block_code ?? '—' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 hidden md:table-cell text-xs" style="color:#667085;">
                            @if($pt->contract_start_date)
                                {{ CarbonAlias::parse($pt->contract_start_date)->format('d M Y') }}
                                @if($pt->contract_end_date) &ndash; {{ Carbon\Carbon::parse($pt->contract_end_date)->format('d M Y') }} @endif
                            @elseif($pt->resident_since)
                                {{ CarbonAlias::parse($pt->resident_since)->format('d M Y') }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs" style="color:#c0453b;">
                            {{ CarbonAlias::parse($pt->ended_at)->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);"
            wire:click="$set('isModalOpen', false)"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-md" style="background:#ffffff;border:1px solid #d0d5dd;">
            <div class="flex items-center justify-between px-6 py-4 rounded-t-2xl"
                style="background:#f2f4f7;border-bottom:1px solid rgba(16,24,40,0.3);">
                <h3 class="font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">
                    {{ $editingId ? 'Edit Data Penyewa' : 'Tambah Penyewa Baru' }}
                </h3>
                <button wire:click="$set('isModalOpen', false)" class="p-1 rounded-lg" style="color:#1d2939;"
                    onmouseover="this.style.background='rgba(16,24,40,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="save" class="px-6 py-5 space-y-4">
                @if(!$editingId)
                {{-- Jenis penyewa: baru vs penghuni terdaftar --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Jenis Penyewa</label>
                    <div class="flex gap-1.5 rounded-xl overflow-hidden" style="border:1px solid #e4e7ec;">
                        <button type="button" wire:click="$set('tenantMode','baru')"
                            class="flex-1 py-2 text-xs font-medium transition-all"
                            style="{{ $tenantMode === 'baru' ? 'background:#111827;color:#ffffff;' : 'background:#ffffff;color:#7c8698;' }}">
                            Penyewa Baru
                        </button>
                        <button type="button" wire:click="$set('tenantMode','terdaftar')"
                            class="flex-1 py-2 text-xs font-medium transition-all"
                            style="{{ $tenantMode === 'terdaftar' ? 'background:#111827;color:#ffffff;' : 'background:#ffffff;color:#7c8698;border-left:1px solid #e4e7ec;' }}">
                            Penghuni Terdaftar
                        </button>
                    </div>
                    <p class="text-xs mt-1.5" style="color:#98a2b3;">Pilih "Penghuni Terdaftar" bila penyewa adalah warga blok lain (mis. rumahnya sedang direnovasi).</p>
                </div>

                @if($tenantMode === 'terdaftar')
                {{-- Pilih penghuni yang sudah terdaftar --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Pilih Penghuni <span style="color:#c0453b;">*</span></label>
                    <select wire:model="existingResidentId"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        <option value="">-- Pilih penghuni terdaftar --</option>
                        @foreach($availableResidents as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('existingResidentId') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Nama Penyewa <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama lengkap penyewa"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    @error('name') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">No. Telepon</label>
                        <input type="tel" wire:model="phone" placeholder="08xx-xxxx-xxxx"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">WhatsApp</label>
                        <input type="tel" wire:model="whatsapp" placeholder="08xx-xxxx-xxxx"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    </div>
                </div>
                @endif
                @else
                {{-- Edit penyewa yang sudah terdaftar --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Nama Penyewa <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama lengkap penyewa"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    @error('name') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">No. Telepon</label>
                        <input type="tel" wire:model="phone" placeholder="08xx-xxxx-xxxx"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">WhatsApp</label>
                        <input type="tel" wire:model="whatsapp" placeholder="08xx-xxxx-xxxx"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    </div>
                </div>
                @endif
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Mulai Kontrak</label>
                        <input type="date" wire:model="contractStart"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        @error('contractStart') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Akhir Kontrak</label>
                        <input type="date" wire:model="contractEnd"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        @error('contractEnd') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Sewa / Bulan <span class="text-xs font-normal" style="color:#98a2b3;">(opsional)</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm" style="color:#7c8698;">Rp</span>
                        <input type="number" wire:model="monthlyRent" placeholder="0" min="0"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem 0.55rem 2.5rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                    </div>
                    @error('monthlyRent') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl cursor-pointer" style="background:#ffffff;border:1px solid #e4e7ec;">
                    <input type="checkbox" wire:model="paysIpl" style="accent-color:#111827;width:16px;height:16px;">
                    <div>
                        <span class="text-sm font-medium" style="color:#1d2939;">Penanggung IPL</span>
                        <p class="text-xs" style="color:#98a2b3;">Tagihan IPL dibebankan ke penyewa ini, bukan ke pemilik</p>
                    </div>
                </label>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Catatan <span class="text-xs font-normal" style="color:#98a2b3;">(opsional)</span></label>
                    <textarea wire:model="notes" rows="2" placeholder="Catatan tambahan..."
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.55rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #e4e7ec;">
                    <button type="button" wire:click="$set('isModalOpen', false)"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#f5f6f8;color:#344054;border:1px solid #d0d5dd;"
                        onmouseover="this.style.background='#e4e7ec'" onmouseout="this.style.background='#f5f6f8'">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 text-sm rounded-xl font-semibold"
                        style="background:#111827;color:#ffffff;"
                        onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Simpan</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
