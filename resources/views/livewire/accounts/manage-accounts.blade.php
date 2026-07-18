<div>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Manajemen Akun/Kas</h1>
                        <p class="text-sm mt-1" style="color:#17231E;">Kelola rekening dan kas keuangan perumahan &amp; DKM</p>
                    </div>
                    <button wire:click="create()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Akun
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nama Akun</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Organisasi</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Saldo</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#909A8F;">Keterangan</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $account)
                                <tr style="border-bottom:1px solid #F1F3EC;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-4 py-3 font-medium" style="color:#17231E;">{{ $account->name }}</td>
                                    <td class="px-4 py-3">
                                        @if(($account->organization_type ?? '') === 'perumahan')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Perumahan</span>
                                        @elseif(($account->organization_type ?? '') === 'dkm')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">DKM</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;">Umum</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono font-semibold" style="color:#17231E;">Rp {{ number_format($account->balance, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-xs hidden md:table-cell" style="color:#909A8F;">{{ $account->description ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="edit({{ $account->id }})"
                                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);"
                                                onmouseover="this.style.background='rgba(22,74,64,0.2)'" onmouseout="this.style.background='rgba(22,74,64,0.1)'">
                                                Edit
                                            </button>
                                            <button wire:click="delete({{ $account->id }})"
                                                wire:confirm="Anda yakin? Menghapus akun tidak bisa dibatalkan."
                                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                                onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-14 text-center">
                                        <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        <p class="text-sm font-medium" style="color:#909A8F;">Belum ada akun/kas</p>
                                        <p class="text-xs mt-1" style="color:#909A8F;">Klik "+ Tambah Akun" untuk mulai.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                    @forelse($accounts as $account)
                        <div class="p-4" wire:key="acc-card-{{ $account->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-semibold min-w-0 break-words" style="color:#17231E;">{{ $account->name }}</p>
                                <p class="font-mono font-semibold text-sm shrink-0 text-right" style="color:#17231E;">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                            </div>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                @if(($account->organization_type ?? '') === 'perumahan')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Perumahan</span>
                                @elseif(($account->organization_type ?? '') === 'dkm')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">DKM</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;">Umum</span>
                                @endif
                            </div>
                            @if($account->description)
                                <p class="text-xs mt-2" style="color:#909A8F;">{{ $account->description }}</p>
                            @endif
                            <div class="mt-3 flex gap-2">
                                <button wire:click="edit({{ $account->id }})"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">Edit</button>
                                <button wire:click="delete({{ $account->id }})"
                                    wire:confirm="Anda yakin? Menghapus akun tidak bisa dibatalkan."
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">Hapus</button>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-14 text-center">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <p class="text-sm font-medium" style="color:#909A8F;">Belum ada akun/kas</p>
                            <p class="text-xs mt-1" style="color:#909A8F;">Klik "+ Tambah Akun" untuk mulai.</p>
                        </div>
                    @endforelse
                </div>

                @if($accounts->hasPages())
                    <div class="px-4 py-3" style="border-top:1px solid #E0DFD4;">{{ $accounts->links() }}</div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden" style="background:#ffffff;border:1px solid #D8D6C9;" @click.stop>
            <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">{{ $selected_id ? 'Edit Akun' : 'Buat Akun Baru' }}</h3>
                <button wire:click="closeModal()" class="p-1 rounded-lg transition-colors" style="color:#17231E;" onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="store" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Nama Akun <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="name"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                        onfocus="this.style.borderColor='#164A40';this.style.boxShadow='0 0 0 2px rgba(22,74,64,0.2)'"
                        onblur="this.style.borderColor='#E0DFD4';this.style.boxShadow=''"
                        placeholder="Contoh: Kas Iuran Perumahan">
                    @error('name')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Organisasi</label>
                    <select wire:model="organization_type"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                        onfocus="this.style.borderColor='#164A40';this.style.boxShadow='0 0 0 2px rgba(22,74,64,0.2)'"
                        onblur="this.style.borderColor='#E0DFD4';this.style.boxShadow=''">
                        <option value="perumahan">Perumahan</option>
                        <option value="dkm">DKM Masjid</option>
                        <option value="umum">Umum</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Saldo Awal (Rp)</label>
                    <input type="number" step="any" wire:model="balance"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors {{ $selected_id ? 'opacity-50 cursor-not-allowed' : '' }}"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                        onfocus="this.style.borderColor='#164A40';this.style.boxShadow='0 0 0 2px rgba(22,74,64,0.2)'"
                        onblur="this.style.borderColor='#E0DFD4';this.style.boxShadow=''"
                        {{ $selected_id ? 'disabled' : '' }}>
                    @if($selected_id)<p class="text-xs mt-1" style="color:#A9741A;">Saldo awal tidak bisa diubah saat edit.</p>@endif
                    @error('balance')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Keterangan</label>
                    <textarea wire:model="description" rows="2"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors resize-none"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                        onfocus="this.style.borderColor='#164A40';this.style.boxShadow='0 0 0 2px rgba(22,74,64,0.2)'"
                        onblur="this.style.borderColor='#E0DFD4';this.style.boxShadow=''"
                        placeholder="Deskripsi singkat akun ini"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closeModal()"
                        class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
