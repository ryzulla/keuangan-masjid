<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#111827;">Manajemen Kategori</h2>
    </x-slot>

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
                <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(16,24,40,0.35);">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color:#111827;font-family:'IBM Plex Sans',serif;">Manajemen Kategori</h1>
                        <p class="text-sm mt-1" style="color:#111827;">Kategori pemasukan dan pengeluaran keuangan</p>
                    </div>
                    <button wire:click="create()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                        style="background:#111827;color:#ffffff;"
                        onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Kategori
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #f5f6f8;">
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Nama Kategori</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Tipe</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Organisasi</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Jenis Dana</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#98a2b3;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr style="border-bottom:1px solid #eef0f3;" onmouseover="this.style.backgroundColor='#f5f6f8'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-4 py-3 font-medium" style="color:#1d2939;">{{ $category->name }}</td>
                                    <td class="px-4 py-3">
                                        @if($category->type === 'income')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">
                                                ↑ Pemasukan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">
                                                ↓ Pengeluaran
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($category->organization_type ?? '') === 'perumahan')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">Perumahan</span>
                                        @elseif(($category->organization_type ?? '') === 'dkm')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">DKM</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#f5f6f8;color:#667085;border:1px solid #e4e7ec;">Umum</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($category->fund_type)
                                            @php
                                                $ftColors = ['zakat'=>'#c77d1a','infaq'=>'#12805c','sedekah'=>'#12805c','wakaf'=>'#2563eb','umum'=>'#667085'];
                                                $ftBg = ['zakat'=>'rgba(199,125,26,0.1)','infaq'=>'rgba(18,128,92,0.1)','sedekah'=>'rgba(52,211,153,0.1)','wakaf'=>'rgba(96,165,250,0.1)','umum'=>'rgba(136,136,136,0.1)'];
                                                $c = $ftColors[$category->fund_type] ?? '#667085';
                                                $b = $ftBg[$category->fund_type] ?? 'rgba(136,136,136,0.1)';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                                style="background:{{ $b }};color:{{ $c }};border:1px solid {{ $c }}40;">
                                                {{ ucfirst($category->fund_type) }}
                                            </span>
                                        @else
                                            <span style="color:#98a2b3;">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="edit({{ $category->id }})"
                                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);"
                                                onmouseover="this.style.background='rgba(16,24,40,0.2)'" onmouseout="this.style.background='rgba(16,24,40,0.1)'">
                                                Edit
                                            </button>
                                            <button wire:click="delete({{ $category->id }})"
                                                wire:confirm="Anda yakin? Menghapus kategori tidak bisa dibatalkan."
                                                class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);"
                                                onmouseover="this.style.background='rgba(192,69,59,0.2)'" onmouseout="this.style.background='rgba(192,69,59,0.1)'">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-14 text-center">
                                        <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        <p class="text-sm font-medium" style="color:#98a2b3;">Belum ada kategori</p>
                                        <p class="text-xs mt-1" style="color:#98a2b3;">Klik "+ Tambah Kategori" untuk mulai.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden divide-y" style="border-color:#eef0f3;">
                    @forelse($categories as $category)
                        <div class="p-4" wire:key="cat-card-{{ $category->id }}">
                            <div class="flex items-start justify-between gap-3">
                                <p class="font-semibold min-w-0 break-words" style="color:#1d2939;">{{ $category->name }}</p>
                                @if($category->type === 'income')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shrink-0" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">
                                        ↑ Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shrink-0" style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">
                                        ↓ Pengeluaran
                                    </span>
                                @endif
                            </div>
                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                @if(($category->organization_type ?? '') === 'perumahan')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">Perumahan</span>
                                @elseif(($category->organization_type ?? '') === 'dkm')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">DKM</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background:#f5f6f8;color:#667085;border:1px solid #e4e7ec;">Umum</span>
                                @endif
                                @if($category->fund_type)
                                    @php
                                        $ftColors = ['zakat'=>'#c77d1a','infaq'=>'#12805c','sedekah'=>'#12805c','wakaf'=>'#2563eb','umum'=>'#667085'];
                                        $ftBg = ['zakat'=>'rgba(199,125,26,0.1)','infaq'=>'rgba(18,128,92,0.1)','sedekah'=>'rgba(52,211,153,0.1)','wakaf'=>'rgba(96,165,250,0.1)','umum'=>'rgba(136,136,136,0.1)'];
                                        $c = $ftColors[$category->fund_type] ?? '#667085';
                                        $b = $ftBg[$category->fund_type] ?? 'rgba(136,136,136,0.1)';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize"
                                        style="background:{{ $b }};color:{{ $c }};border:1px solid {{ $c }}40;">
                                        {{ ucfirst($category->fund_type) }}
                                    </span>
                                @endif
                            </div>
                            <div class="mt-3 flex gap-2">
                                <button wire:click="edit({{ $category->id }})"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">Edit</button>
                                <button wire:click="delete({{ $category->id }})"
                                    wire:confirm="Anda yakin? Menghapus kategori tidak bisa dibatalkan."
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">Hapus</button>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-14 text-center">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <p class="text-sm font-medium" style="color:#98a2b3;">Belum ada kategori</p>
                            <p class="text-xs mt-1" style="color:#98a2b3;">Klik "+ Tambah Kategori" untuk mulai.</p>
                        </div>
                    @endforelse
                </div>

                @if($categories->hasPages())
                    <div class="px-4 py-3" style="border-top:1px solid #e4e7ec;">{{ $categories->links() }}</div>
                @endif
            </div>

        </div>
    </div>

    {{-- Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" style="background:#ffffff;border:1px solid #d0d5dd;" @click.stop>
            <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4" style="background:#f2f4f7;border-bottom:1px solid rgba(16,24,40,0.35);">
                <h3 class="font-bold text-lg" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">{{ $selected_id ? 'Edit Kategori' : 'Buat Kategori Baru' }}</h3>
                <button wire:click="closeModal()" class="p-1 rounded-lg transition-colors" style="color:#1d2939;" onmouseover="this.style.background='rgba(16,24,40,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="store" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Nama Kategori <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="name"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
                        onfocus="this.style.borderColor='#111827';this.style.boxShadow='0 0 0 2px rgba(16,24,40,0.2)'"
                        onblur="this.style.borderColor='#e4e7ec';this.style.boxShadow=''"
                        placeholder="Contoh: Iuran IPL Security">
                    @error('name')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Tipe Kategori <span style="color:#c0453b;">*</span></label>
                    <select wire:model="type"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
                        onfocus="this.style.borderColor='#111827';this.style.boxShadow='0 0 0 2px rgba(16,24,40,0.2)'"
                        onblur="this.style.borderColor='#e4e7ec';this.style.boxShadow=''">
                        <option value="income">↑ Pemasukan (Income)</option>
                        <option value="expense">↓ Pengeluaran (Expense)</option>
                    </select>
                    @error('type')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#475467;">Organisasi <span style="color:#c0453b;">*</span></label>
                    <select wire:model.live="organization_type"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
                        onfocus="this.style.borderColor='#111827';this.style.boxShadow='0 0 0 2px rgba(16,24,40,0.2)'"
                        onblur="this.style.borderColor='#e4e7ec';this.style.boxShadow=''">
                        <option value="perumahan">Perumahan</option>
                        <option value="dkm">DKM Masjid</option>
                        <option value="umum">Umum</option>
                    </select>
                    @error('organization_type')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @if($organization_type === 'dkm')
                <div class="rounded-xl p-4" style="background:rgba(18,128,92,0.04);border:1px solid rgba(18,128,92,0.2);">
                    <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Jenis Dana Syariat
                        <span class="text-xs font-normal ml-1" style="color:#98a2b3;">(opsional — panduan peruntukan)</span>
                    </label>
                    <select wire:model="fund_type"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors"
                        style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
                        onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                        <option value="">-- Tidak ada aturan khusus --</option>
                        <option value="zakat">Zakat (8 Asnaf)</option>
                        <option value="infaq">Infaq</option>
                        <option value="sedekah">Sedekah</option>
                        <option value="wakaf">Wakaf</option>
                        <option value="umum">Umum / Operasional</option>
                    </select>
                    @error('fund_type')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @endif
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="closeModal()"
                        class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                        style="background:#f5f6f8;color:#344054;border:1px solid #d0d5dd;"
                        onmouseover="this.style.background='#e4e7ec'" onmouseout="this.style.background='#f5f6f8'">Batal</button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                        style="background:#111827;color:#ffffff;"
                        onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
