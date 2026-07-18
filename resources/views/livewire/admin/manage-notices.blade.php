<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
    {{-- Header — pine hero --}}
    <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Pengumuman</h3>
                <p class="text-sm mt-1" style="color:#17231E;">Kelola pengumuman untuk penghuni perumahan.</p>
            </div>
            <button wire:click="openModal"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                style="background:#164A40;color:#ffffff;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Pengumuman
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <div class="overflow-x-auto hidden md:block">
            <table class="w-full text-sm">
                <thead>
                    <tr style="border-bottom:1px solid #F1F3EC;">
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Judul</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Prioritas</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Status</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Kadaluarsa</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notices as $notice)
                    <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                        <td class="px-5 py-3.5">
                            <span class="font-medium" style="color:#17231E;">{{ Str::limit($notice->title, 50) }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $pStyle = match($notice->priority) {
                                    'info'    => 'background:rgba(22,74,64,0.1);color:#164A40;border:1px solid rgba(22,74,64,0.25);',
                                    'warning' => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.25);',
                                    'urgent'  => 'background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.25);',
                                };
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="{{ $pStyle }}">{{ ucfirst($notice->priority) }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <button wire:click="togglePublish({{ $notice->id }})"
                                class="text-xs px-2 py-0.5 rounded-full font-medium cursor-pointer"
                                style="{{ $notice->is_published ? 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);' : 'background:rgba(144,154,143,0.15);color:#909A8F;border:1px solid rgba(144,154,143,0.3);' }}">
                                {{ $notice->is_published ? 'Aktif' : 'Draft' }}
                            </button>
                        </td>
                        <td class="px-5 py-3.5 text-xs" style="color:#586359;">
                            {{ $notice->expires_at ? $notice->expires_at->format('d M Y') : '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $notice->id }})" class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Edit</button>
                                <button wire:click="delete({{ $notice->id }})" wire:confirm="Yakin hapus pengumuman ini?"
                                    class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center">
                            <p class="text-sm" style="color:#909A8F;">Belum ada pengumuman.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
            @forelse($notices as $notice)
            <div class="px-4 py-3 space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-medium" style="color:#17231E;">{{ $notice->title }}</p>
                        <p class="text-xs mt-0.5" style="color:#909A8F;">
                            {{ $notice->priority }} · {{ $notice->is_published ? 'Aktif' : 'Draft' }}
                            @if($notice->expires_at) · Kadaluarsa {{ $notice->expires_at->format('d M Y') }} @endif
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click="edit({{ $notice->id }})" class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Edit</button>
                    <button wire:click="delete({{ $notice->id }})" wire:confirm="Yakin hapus?"
                        class="text-xs px-2.5 py-1 rounded-lg font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">Hapus</button>
                </div>
            </div>
            @empty
            <div class="px-4 py-8 text-center">
                <p class="text-sm" style="color:#909A8F;">Belum ada pengumuman.</p>
            </div>
            @endforelse
        </div>

        <div class="px-5 py-3" style="border-top:1px solid #F1F3EC;">
            {{ $notices->links() }}
        </div>
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isModalOpen', false)"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg" style="background:#ffffff;border:1px solid #D8D6C9;">
            <div class="px-6 py-4 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                    {{ $editingId ? 'Edit Pengumuman' : 'Buat Pengumuman' }}
                </h3>
            </div>
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Judul</label>
                    <input type="text" wire:model="title" placeholder="Judul pengumuman"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    @error('title') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Isi Pengumuman</label>
                    <textarea wire:model="content" rows="5" placeholder="Tulis isi pengumuman..."
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:none;"></textarea>
                    @error('content') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Prioritas</label>
                        <select wire:model="priority"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                            <option value="info">Info</option>
                            <option value="warning">Peringatan</option>
                            <option value="urgent">Mendesak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Kadaluarsa (opsional)</label>
                        <input type="date" wire:model="expires_at"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                        @error('expires_at') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_published" style="accent-color:#17231E;">
                        <span class="text-sm" style="color:#586359;">Publikasikan sekarang</span>
                    </label>
                </div>
            </div>
            <div class="px-6 py-4 flex justify-end gap-3" style="border-top:1px solid #E0DFD4;">
                <button type="button" wire:click="$set('isModalOpen', false)"
                    class="px-4 py-2 text-sm rounded-xl font-medium"
                    style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;">Batal</button>
                <button wire:click="save" wire:loading.attr="disabled"
                    class="px-5 py-2 text-sm rounded-xl font-semibold"
                    style="background:#164A40;color:#ffffff;">
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Simpan' : 'Publikasikan' }}</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
