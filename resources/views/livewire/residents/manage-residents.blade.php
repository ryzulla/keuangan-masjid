<div>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-5 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Data Penghuni</h3>
                    <p class="text-sm mt-1" style="color:#17231E;">Kelola data penghuni dan kepemilikan rumah blok A-1 s/d P-9</p>
                </div>
                <a href="{{ route('residents.create') }}" wire:navigate
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Penghuni
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="rounded-2xl p-4 mb-5" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama penghuni..."
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem 0.5rem 2.25rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>
                <x-searchable-select model="filterBlock" :options="$houseBlocks->pluck('block_code', 'id')"
                    placeholder="Semua Blok" searchPlaceholder="Cari blok..." class="w-full" />
                <select wire:model.live="filterOwnership"
                    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    <option value="">Semua Kepemilikan</option>
                    <option value="pemilik">Pemilik</option>
                    <option value="kontrak">Kontrak / Sewa</option>
                    <option value="kos">Kos</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nama Penghuni</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Blok & Status</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#909A8F;">Kontak</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#909A8F;">Aktif</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($residents as $resident)
                        <tr style="border-bottom:1px solid #F1F3EC;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    @if($resident->photo)
                                        <img src="{{ Storage::disk('public')->url($resident->photo) }}"
                                            alt="{{ $resident->name }}"
                                            class="w-9 h-9 rounded-full object-cover shrink-0"
                                            style="border:1px solid rgba(22,74,64,0.3);">
                                    @else
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                                            style="background:#F1F3EC;color:#17231E;">
                                            {{ strtoupper(substr($resident->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold" style="color:#17231E;">{{ $resident->name }}</div>
                                        @if($resident->email)
                                            <div class="text-xs" style="color:#909A8F;">{{ $resident->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($resident->currentAssignments as $a)
                                        @php
                                            $ownerStyle = match($a->ownership_type) {
                                                'pemilik' => 'background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);',
                                                'kontrak' => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);',
                                                default   => 'background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);',
                                            };
                                            $ownerLabel = match($a->ownership_type) {
                                                'pemilik' => 'Pemilik',
                                                'kontrak' => 'Kontrak',
                                                default   => 'Kos',
                                            };
                                        @endphp
                                        <div class="flex items-center gap-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium" style="{{ $ownerStyle }}">
                                                {{ $a->houseBlock?->block_code ?? '?' }}
                                            </span>
                                            <span class="text-xs" style="color:#909A8F;">{{ $ownerLabel }}</span>
                                        </div>
                                    @empty
                                        <span class="text-xs italic" style="color:#909A8F;">Belum ditetapkan</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-3 hidden sm:table-cell text-xs" style="color:#909A8F;">
                                @if($resident->phone)
                                    <div>📞 {{ $resident->phone }}</div>
                                @endif
                                @if($resident->whatsapp && $resident->whatsapp !== $resident->phone)
                                    <div>💬 {{ $resident->whatsapp }}</div>
                                @endif
                                @if(!$resident->phone && !$resident->whatsapp)
                                    —
                                @endif
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell">
                                @if($resident->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('residents.show', $resident) }}" wire:navigate title="Detail"
                                        class="p-1.5 rounded-lg transition-colors inline-flex" style="color:#586359;"
                                        onmouseover="this.style.color='#164A40';this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.color='#586359';this.style.background=''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('residents.edit', $resident) }}" wire:navigate title="Edit"
                                        class="p-1.5 rounded-lg transition-colors inline-flex" style="color:#17231E;"
                                        onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button x-data x-on:click="if(confirm('Hapus penghuni {{ addslashes($resident->name) }}? Tindakan ini tidak dapat dibatalkan.')) $wire.delete({{ $resident->id }})"
                                        title="Hapus" wire:loading.attr="disabled"
                                        class="p-1.5 rounded-lg transition-colors" style="color:#B0402C;"
                                        onmouseover="this.style.background='rgba(176,64,44,0.1)'" onmouseout="this.style.background=''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-14 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="font-medium text-sm" style="color:#909A8F;">Belum ada data penghuni</p>
                                <a href="{{ route('residents.create') }}" wire:navigate class="mt-2 inline-block text-xs hover:underline" style="color:#17231E;">Tambah penghuni pertama &rarr;</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile stacked cards --}}
            <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                @forelse($residents as $resident)
                <div wire:key="res-card-{{ $resident->id }}" class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            @if($resident->photo)
                                <img src="{{ Storage::disk('public')->url($resident->photo) }}"
                                    alt="{{ $resident->name }}"
                                    class="w-9 h-9 rounded-full object-cover shrink-0"
                                    style="border:1px solid rgba(22,74,64,0.3);">
                            @else
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                                    style="background:#F1F3EC;color:#17231E;">
                                    {{ strtoupper(substr($resident->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="font-semibold truncate" style="color:#17231E;">{{ $resident->name }}</div>
                                @if($resident->email)
                                    <div class="text-xs truncate" style="color:#909A8F;">{{ $resident->email }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="shrink-0">
                            @if($resident->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1">
                        @forelse($resident->currentAssignments as $a)
                            @php
                                $ownerStyle = match($a->ownership_type) {
                                    'pemilik' => 'background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);',
                                    'kontrak' => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);',
                                    default   => 'background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);',
                                };
                                $ownerLabel = match($a->ownership_type) {
                                    'pemilik' => 'Pemilik',
                                    'kontrak' => 'Kontrak',
                                    default   => 'Kos',
                                };
                            @endphp
                            <div class="flex items-center gap-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium" style="{{ $ownerStyle }}">
                                    {{ $a->houseBlock?->block_code ?? '?' }}
                                </span>
                                <span class="text-xs" style="color:#909A8F;">{{ $ownerLabel }}</span>
                            </div>
                        @empty
                            <span class="text-xs italic" style="color:#909A8F;">Belum ditetapkan</span>
                        @endforelse
                    </div>

                    @if($resident->phone || $resident->whatsapp)
                        <div class="text-xs space-y-0.5" style="color:#909A8F;">
                            @if($resident->phone)
                                <div>📞 {{ $resident->phone }}</div>
                            @endif
                            @if($resident->whatsapp && $resident->whatsapp !== $resident->phone)
                                <div>💬 {{ $resident->whatsapp }}</div>
                            @endif
                        </div>
                    @endif

                    <div class="flex items-center gap-2 pt-1">
                        <a href="{{ route('residents.show', $resident) }}" wire:navigate title="Detail"
                            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors" style="color:#586359;background:rgba(255,255,255,0.03);border:1px solid #E0DFD4;"
                            onmouseover="this.style.color='#164A40';this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.color='#586359';this.style.background='rgba(255,255,255,0.03)'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail
                        </a>
                        <a href="{{ route('residents.edit', $resident) }}" wire:navigate title="Edit"
                            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors" style="color:#17231E;background:rgba(22,74,64,0.05);border:1px solid rgba(22,74,64,0.2);"
                            onmouseover="this.style.background='rgba(22,74,64,0.15)'" onmouseout="this.style.background='rgba(22,74,64,0.05)'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <button x-data x-on:click="if(confirm('Hapus penghuni {{ addslashes($resident->name) }}? Tindakan ini tidak dapat dibatalkan.')) $wire.delete({{ $resident->id }})"
                            title="Hapus" wire:loading.attr="disabled"
                            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors" style="color:#B0402C;background:rgba(176,64,44,0.05);border:1px solid rgba(176,64,44,0.2);"
                            onmouseover="this.style.background='rgba(176,64,44,0.15)'" onmouseout="this.style.background='rgba(176,64,44,0.05)'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </div>
                </div>
                @empty
                <div class="px-5 py-14 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="font-medium text-sm" style="color:#909A8F;">Belum ada data penghuni</p>
                    <a href="{{ route('residents.create') }}" wire:navigate class="mt-2 inline-block text-xs hover:underline" style="color:#17231E;">Tambah penghuni pertama &rarr;</a>
                </div>
                @endforelse
            </div>

            <div class="px-5 py-3" style="border-top:1px solid #F1F3EC;">{{ $residents->links() }}</div>
        </div>
    </div>
</div>
