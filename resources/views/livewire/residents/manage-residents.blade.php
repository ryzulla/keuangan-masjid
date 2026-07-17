<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#161e2d;">Data Penghuni Perumahan</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-4 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-5" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg" style="color:#161e2d;font-family:'Manrope',serif;">Data Penghuni</h3>
                    <p class="text-sm mt-1" style="color:#161e2d;">Kelola data penghuni dan kepemilikan rumah blok A-1 s/d P-9</p>
                </div>
                <a href="{{ route('residents.create') }}" wire:navigate
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                    style="background:#1563df;color:#ffffff;"
                    onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Penghuni
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="rounded-2xl p-4 mb-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4" style="color:#a3abb0;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama penghuni..."
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem 0.5rem 2.25rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                </div>
                <select wire:model.live="filterBlock"
                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    <option value="">Semua Blok</option>
                    @foreach($houseBlocks as $blk)
                        <option value="{{ $blk->id }}">{{ $blk->block_code }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterOwnership"
                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    <option value="">Semua Kepemilikan</option>
                    <option value="pemilik">Pemilik</option>
                    <option value="kontrak">Kontrak / Sewa</option>
                    <option value="kos">Kos</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Nama Penghuni</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Blok & Status</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color:#a3abb0;">Kontak</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#a3abb0;">Aktif</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($residents as $resident)
                        <tr style="border-bottom:1px solid #f7f7f7;" onmouseover="this.style.backgroundColor='#f7f7f7'" onmouseout="this.style.backgroundColor=''">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    @if($resident->photo)
                                        <img src="{{ Storage::disk('public')->url($resident->photo) }}"
                                            alt="{{ $resident->name }}"
                                            class="w-9 h-9 rounded-full object-cover shrink-0"
                                            style="border:1px solid rgba(21,99,223,0.3);">
                                    @else
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                                            style="background:#f7f7f7;color:#161e2d;">
                                            {{ strtoupper(substr($resident->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-semibold" style="color:#161e2d;">{{ $resident->name }}</div>
                                        @if($resident->email)
                                            <div class="text-xs" style="color:#a3abb0;">{{ $resident->email }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($resident->currentAssignments as $a)
                                        @php
                                            $ownerStyle = match($a->ownership_type) {
                                                'pemilik' => 'background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);',
                                                'kontrak' => 'background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);',
                                                default   => 'background:rgba(139,92,246,0.1);color:#7c3aed;border:1px solid rgba(139,92,246,0.2);',
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
                                            <span class="text-xs" style="color:#a3abb0;">{{ $ownerLabel }}</span>
                                        </div>
                                    @empty
                                        <span class="text-xs italic" style="color:#a3abb0;">Belum ditetapkan</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-3 hidden sm:table-cell text-xs" style="color:#a3abb0;">
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
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('residents.show', $resident) }}" wire:navigate title="Detail"
                                        class="p-1.5 rounded-lg transition-colors inline-flex" style="color:#5c6368;"
                                        onmouseover="this.style.color='#1563df';this.style.background='rgba(21,99,223,0.1)'" onmouseout="this.style.color='#5c6368';this.style.background=''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('residents.edit', $resident) }}" wire:navigate title="Edit"
                                        class="p-1.5 rounded-lg transition-colors inline-flex" style="color:#161e2d;"
                                        onmouseover="this.style.background='rgba(21,99,223,0.1)'" onmouseout="this.style.background=''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <button x-data x-on:click="if(confirm('Hapus penghuni {{ addslashes($resident->name) }}? Tindakan ini tidak dapat dibatalkan.')) $wire.delete({{ $resident->id }})"
                                        title="Hapus" wire:loading.attr="disabled"
                                        class="p-1.5 rounded-lg transition-colors" style="color:#c0453b;"
                                        onmouseover="this.style.background='rgba(192,69,59,0.1)'" onmouseout="this.style.background=''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-14 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#1563df"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="font-medium text-sm" style="color:#a3abb0;">Belum ada data penghuni</p>
                                <a href="{{ route('residents.create') }}" wire:navigate class="mt-2 inline-block text-xs hover:underline" style="color:#161e2d;">Tambah penghuni pertama &rarr;</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile stacked cards --}}
            <div class="md:hidden divide-y" style="border-color:#f7f7f7;">
                @forelse($residents as $resident)
                <div wire:key="res-card-{{ $resident->id }}" class="p-4 space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            @if($resident->photo)
                                <img src="{{ Storage::disk('public')->url($resident->photo) }}"
                                    alt="{{ $resident->name }}"
                                    class="w-9 h-9 rounded-full object-cover shrink-0"
                                    style="border:1px solid rgba(21,99,223,0.3);">
                            @else
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold shrink-0"
                                    style="background:#f7f7f7;color:#161e2d;">
                                    {{ strtoupper(substr($resident->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <div class="font-semibold truncate" style="color:#161e2d;">{{ $resident->name }}</div>
                                @if($resident->email)
                                    <div class="text-xs truncate" style="color:#a3abb0;">{{ $resident->email }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="shrink-0">
                            @if($resident->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">Nonaktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-1">
                        @forelse($resident->currentAssignments as $a)
                            @php
                                $ownerStyle = match($a->ownership_type) {
                                    'pemilik' => 'background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);',
                                    'kontrak' => 'background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);',
                                    default   => 'background:rgba(139,92,246,0.1);color:#7c3aed;border:1px solid rgba(139,92,246,0.2);',
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
                                <span class="text-xs" style="color:#a3abb0;">{{ $ownerLabel }}</span>
                            </div>
                        @empty
                            <span class="text-xs italic" style="color:#a3abb0;">Belum ditetapkan</span>
                        @endforelse
                    </div>

                    @if($resident->phone || $resident->whatsapp)
                        <div class="text-xs space-y-0.5" style="color:#a3abb0;">
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
                            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors" style="color:#5c6368;background:rgba(255,255,255,0.03);border:1px solid #e4e4e4;"
                            onmouseover="this.style.color='#1563df';this.style.background='rgba(21,99,223,0.1)'" onmouseout="this.style.color='#5c6368';this.style.background='rgba(255,255,255,0.03)'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Detail
                        </a>
                        <a href="{{ route('residents.edit', $resident) }}" wire:navigate title="Edit"
                            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors" style="color:#161e2d;background:rgba(21,99,223,0.05);border:1px solid rgba(21,99,223,0.2);"
                            onmouseover="this.style.background='rgba(21,99,223,0.15)'" onmouseout="this.style.background='rgba(21,99,223,0.05)'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <button x-data x-on:click="if(confirm('Hapus penghuni {{ addslashes($resident->name) }}? Tindakan ini tidak dapat dibatalkan.')) $wire.delete({{ $resident->id }})"
                            title="Hapus" wire:loading.attr="disabled"
                            class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium transition-colors" style="color:#c0453b;background:rgba(192,69,59,0.05);border:1px solid rgba(192,69,59,0.2);"
                            onmouseover="this.style.background='rgba(192,69,59,0.15)'" onmouseout="this.style.background='rgba(192,69,59,0.05)'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </div>
                </div>
                @empty
                <div class="px-5 py-14 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#1563df"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="font-medium text-sm" style="color:#a3abb0;">Belum ada data penghuni</p>
                    <a href="{{ route('residents.create') }}" wire:navigate class="mt-2 inline-block text-xs hover:underline" style="color:#161e2d;">Tambah penghuni pertama &rarr;</a>
                </div>
                @endforelse
            </div>

            <div class="px-5 py-3" style="border-top:1px solid #f7f7f7;">{{ $residents->links() }}</div>
        </div>
    </div>
</div>
