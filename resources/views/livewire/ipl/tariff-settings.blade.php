<div>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="rounded-xl p-4 text-sm flex items-center gap-3" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-xl p-4 text-sm flex items-center gap-3" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Pengaturan Tarif IPL</h1>
                        <p class="text-sm mt-1" style="color:#17231E;">Kelola jenis tarif dan nominal default untuk setiap periode IPL</p>
                    </div>
                    <button wire:click="openCreate"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Tarif Baru
                    </button>
                </div>
            </div>

            {{-- Info Box --}}
            <div class="rounded-xl p-4 flex gap-3" style="background:rgba(22,74,64,0.06);border:1px solid rgba(22,74,64,0.2);">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#164A40" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm space-y-1" style="color:#17231E;">
                    <p><span style="color:#17231E;font-weight:600;">Tarif Sistem</span> (Keamanan &amp; Sampah) adalah tarif bawaan yang tidak bisa dihapus. Nominal defaultnya digunakan sebagai nilai awal saat membuat periode IPL baru.</p>
                    <p><span style="color:#17231E;font-weight:600;">Tarif Tambahan</span> adalah iuran lain di luar keamanan dan sampah (contoh: Kebersihan Taman, Parkir, dll). Tarif ini akan otomatis ditambahkan sebagai item tagihan terpisah pada setiap billing blok.</p>
                </div>
            </div>

            {{-- Tarif Sistem --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="flex items-center justify-between px-5 py-4" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.2);">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(22,74,64,0.15);border:1px solid rgba(22,74,64,0.3);">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#164A40" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm" style="color:#17231E;">Tarif Sistem (Bawaan)</h3>
                            <p class="text-xs" style="color:#909A8F;">Tarif wajib — tidak dapat dihapus</p>
                        </div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">{{ $systemTypes->count() }} tarif</span>
                </div>
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">No</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nama Tarif</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Keterangan</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nominal Default</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Sort</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Status</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($systemTypes as $i => $type)
                                <tr wire:key="sys-{{ $type->id }}" style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-5 py-3.5" style="color:#909A8F;">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold" style="color:#17231E;">{{ $type->name }}</span>
                                            @if($type->billing_key === 'security')
                                                <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(22,74,64,0.1);color:#164A40;border:1px solid rgba(22,74,64,0.2);">Keamanan</span>
                                            @elseif($type->billing_key === 'garbage')
                                                <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Sampah</span>
                                            @endif
                                        </div>
                                        <p class="text-xs mt-0.5" style="color:#909A8F;">
                                            @if($type->billing_key === 'security') Kolom: ipl_security_amount
                                            @elseif($type->billing_key === 'garbage') Kolom: ipl_garbage_amount
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm" style="color:#909A8F;">{{ $type->description ?? '—' }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="font-mono font-semibold text-base" style="color:#17231E;">Rp {{ number_format($type->default_amount, 0, ',', '.') }}</span>
                                        <p class="text-xs" style="color:#909A8F;">/blok/bulan</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-center text-sm" style="color:#909A8F;">{{ $type->sort_order }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        @if($type->is_active)
                                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#12805c;"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full" style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#D8D6C9;"></span>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button wire:click="openEdit({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);"
                                                onmouseover="this.style.background='rgba(22,74,64,0.2)'" onmouseout="this.style.background='rgba(22,74,64,0.1)'">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </button>
                                            <button wire:click="toggleActive({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="{{ $type->is_active ? 'background:rgba(176,64,44,0.08);color:#B0402C;border:1px solid rgba(176,64,44,0.2);' : 'background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.2);' }}"
                                                onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                                {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-sm" style="color:#909A8F;">Belum ada tarif sistem. Jalankan seeder.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                    @forelse($systemTypes as $type)
                        <div wire:key="sys-card-{{ $type->id }}" class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold" style="color:#17231E;">{{ $type->name }}</span>
                                        @if($type->billing_key === 'security')
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(22,74,64,0.1);color:#164A40;border:1px solid rgba(22,74,64,0.2);">Keamanan</span>
                                        @elseif($type->billing_key === 'garbage')
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Sampah</span>
                                        @endif
                                    </div>
                                    <p class="text-xs mt-0.5" style="color:#909A8F;">{{ $type->description ?? '—' }}</p>
                                </div>
                                @if($type->is_active)
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full shrink-0" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#12805c;"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full shrink-0" style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#D8D6C9;"></span>Nonaktif
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="font-mono font-semibold text-base" style="color:#17231E;">Rp {{ number_format($type->default_amount, 0, ',', '.') }}</span>
                                <p class="text-xs" style="color:#909A8F;">/blok/bulan &middot; Sort {{ $type->sort_order }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 pt-1 flex-wrap">
                                <button wire:click="openEdit({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <button wire:click="toggleActive({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="{{ $type->is_active ? 'background:rgba(176,64,44,0.08);color:#B0402C;border:1px solid rgba(176,64,44,0.2);' : 'background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.2);' }}">
                                    {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm" style="color:#909A8F;">Belum ada tarif sistem. Jalankan seeder.</div>
                    @endforelse
                </div>
            </div>

            {{-- Tarif Tambahan --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="flex items-center justify-between px-5 py-4" style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(107,91,149,0.12);border:1px solid rgba(107,91,149,0.25);">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#6B5B95" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm" style="color:#17231E;">Tarif Tambahan</h3>
                            <p class="text-xs" style="color:#909A8F;">Iuran lain di luar keamanan &amp; sampah (kebersihan taman, parkir, dll)</p>
                        </div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full" style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">{{ $extraTypes->count() }} tarif</span>
                </div>
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">No</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nama Tarif</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Keterangan</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nominal Default</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Sort</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Status</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($extraTypes as $i => $type)
                                <tr wire:key="extra-{{ $type->id }}" style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-5 py-3.5" style="color:#909A8F;">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold" style="color:#17231E;">{{ $type->name }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);">Extra</span>
                                        </div>
                                        <p class="text-xs mt-0.5" style="color:#909A8F;">ipl_billing_charge_items</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm" style="color:#909A8F;">{{ $type->description ?? '—' }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="font-mono font-semibold text-base" style="color:#6B5B95;">Rp {{ number_format($type->default_amount, 0, ',', '.') }}</span>
                                        <p class="text-xs" style="color:#909A8F;">/blok/bulan</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-center text-sm" style="color:#909A8F;">{{ $type->sort_order }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        @if($type->is_active)
                                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#12805c;"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full" style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#D8D6C9;"></span>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button wire:click="openEdit({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);"
                                                onmouseover="this.style.background='rgba(22,74,64,0.2)'" onmouseout="this.style.background='rgba(22,74,64,0.1)'">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </button>
                                            <button wire:click="toggleActive({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="{{ $type->is_active ? 'background:rgba(176,64,44,0.08);color:#B0402C;border:1px solid rgba(176,64,44,0.2);' : 'background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.2);' }}"
                                                onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                                {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                            <button wire:click="confirmDelete({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(176,64,44,0.08);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                                onmouseover="this.style.background='rgba(176,64,44,0.18)'" onmouseout="this.style.background='rgba(176,64,44,0.08)'">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center" style="color:#909A8F;">
                                        <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#6B5B95;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        <p class="text-sm">Belum ada tarif tambahan.</p>
                                        <p class="text-xs mt-1" style="color:#909A8F;">Klik "Tambah Tarif Baru" untuk menambahkan iuran selain keamanan &amp; sampah.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                    @forelse($extraTypes as $type)
                        <div wire:key="extra-card-{{ $type->id }}" class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold" style="color:#17231E;">{{ $type->name }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);">Extra</span>
                                    </div>
                                    <p class="text-xs mt-0.5" style="color:#909A8F;">{{ $type->description ?? '—' }}</p>
                                </div>
                                @if($type->is_active)
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full shrink-0" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#12805c;"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full shrink-0" style="background:#F1F3EC;color:#909A8F;border:1px solid #E0DFD4;">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#D8D6C9;"></span>Nonaktif
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="font-mono font-semibold text-base" style="color:#6B5B95;">Rp {{ number_format($type->default_amount, 0, ',', '.') }}</span>
                                <p class="text-xs" style="color:#909A8F;">/blok/bulan &middot; Sort {{ $type->sort_order }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 pt-1 flex-wrap">
                                <button wire:click="openEdit({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <button wire:click="toggleActive({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="{{ $type->is_active ? 'background:rgba(176,64,44,0.08);color:#B0402C;border:1px solid rgba(176,64,44,0.2);' : 'background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.2);' }}">
                                    {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                                <button wire:click="confirmDelete({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(176,64,44,0.08);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center" style="color:#909A8F;">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#6B5B95;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <p class="text-sm">Belum ada tarif tambahan.</p>
                            <p class="text-xs mt-1" style="color:#909A8F;">Klik "Tambah Tarif Baru" untuk menambahkan iuran selain keamanan &amp; sampah.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Akun Tujuan Pembayaran --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="flex items-center gap-3 px-5 py-4" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.2);">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(22,74,64,0.15);border:1px solid rgba(22,74,64,0.3);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#164A40" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h16a1 1 0 001-1V6a1 1 0 00-1-1H4a1 1 0 00-1 1v12a1 1 0 001 1z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm" style="color:#17231E;">Akun Tujuan Pembayaran</h3>
                        <p class="text-xs" style="color:#909A8F;">Tentukan akun kas/bank yang menerima dana untuk setiap jenis tarif &amp; donasi</p>
                    </div>
                </div>

                <form wire:submit="saveAccounts" class="p-5 space-y-5">
                    {{-- Tarif Sistem --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#909A8F;">Tarif Sistem</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($systemTypes as $type)
                                <div>
                                    <label class="block text-sm mb-1.5" style="color:#586359;">{{ $type->name }}</label>
                                    <select wire:model="tariffAccounts.{{ $type->id }}"
                                        class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                        <option value="">— pilih akun —</option>
                                        @foreach($perumahanAccounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tarif Tambahan Aktif --}}
                    @php $activeExtraTypes = $extraTypes->where('is_active', true); @endphp
                    @if($activeExtraTypes->count())
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#909A8F;">Tarif Tambahan (Aktif)</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($activeExtraTypes as $type)
                                    <div>
                                        <label class="block text-sm mb-1.5" style="color:#586359;">{{ $type->name }}</label>
                                        <select wire:model="tariffAccounts.{{ $type->id }}"
                                            class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                            <option value="">— pilih akun —</option>
                                            @foreach($perumahanAccounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Donasi --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#909A8F;">Donasi</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm mb-1.5" style="color:#586359;">Donasi Perumahan</label>
                                <select wire:model="donationAccountPerumahan"
                                    class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                    <option value="">— pilih akun —</option>
                                    @foreach($perumahanAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm mb-1.5" style="color:#586359;">Donasi DKM</label>
                                <select wire:model="donationAccountDkm"
                                    class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                    <option value="">— pilih akun —</option>
                                    @foreach($dkmAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end pt-2" style="border-top:1px solid #E0DFD4;">
                        <button type="submit"
                            class="px-5 py-2 text-sm rounded-xl font-semibold transition-colors"
                            style="background:#164A40;color:#ffffff;"
                            onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                            wire:loading.attr="disabled" wire:target="saveAccounts">
                            <span wire:loading.remove wire:target="saveAccounts">Simpan Akun</span>
                            <span wire:loading wire:target="saveAccounts" class="flex items-center gap-1.5">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Create / Edit Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeModal"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" style="background:#ffffff;border:1px solid #D8D6C9;" @click.stop>
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                    {{ $editingId ? 'Edit Tarif' : 'Tambah Tarif Baru' }}
                </h3>
                <button wire:click="closeModal" class="p-1 rounded-lg transition-colors" style="color:#17231E;" onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Error --}}
            @if(session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-xs" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                    {{ session('modal_error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="mx-6 mt-4 rounded-xl p-3" style="background:rgba(169,116,26,0.08);border:1px solid rgba(169,116,26,0.25);color:#A9741A;">
                    <p class="text-xs font-medium mb-1">Ada kesalahan input:</p>
                    <ul class="list-disc pl-4 text-xs space-y-0.5">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit="save" class="p-6 space-y-4">
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Nama Tarif <span style="color:#B0402C;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Contoh: Kebersihan Taman, Parkir, dll"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('name')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Keterangan <span class="text-xs" style="color:#909A8F;">(opsional)</span></label>
                    <input type="text" wire:model="description" placeholder="Penjelasan singkat tarif ini"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('description')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>

                {{-- Nominal Default --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Nominal Default (Rp) <span style="color:#B0402C;">*</span></label>
                    <input type="number" wire:model="defaultAmount" min="0" step="500" placeholder="Contoh: 50000"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none font-mono"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    <p class="text-xs mt-1" style="color:#909A8F;">Nilai ini digunakan sebagai default saat membuat periode IPL baru. Bisa diubah per-periode.</p>
                    @error('defaultAmount')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                </div>

                {{-- Sort Order + Aktif --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Urutan (Sort)</label>
                        <input type="number" wire:model="sortOrder" min="0"
                            class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('sortOrder')<p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" wire:model="isActive" class="w-4 h-4 rounded" style="accent-color:#17231E;">
                            <span class="text-sm" style="color:#586359;">Aktif</span>
                        </label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #E0DFD4;">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm rounded-xl font-semibold transition-colors"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>Simpan Tarif</span>
                        <span wire:loading class="flex items-center gap-1.5">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        (function () {
            let listenersAttached = false;
            function initListeners() {
                if (listenersAttached) return;
                Livewire.on('show-delete-confirmation', (event) => {
                    const id = event.id ?? event[0]?.id;
                    if (!window.Swal) return;
                    Swal.fire({
                        title: 'Hapus Tarif?',
                        text: 'Tarif ini akan dihapus permanen. Tagihan yang sudah dibuat tidak terpengaruh.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#B0402C',
                        cancelButtonColor: '#D8D6C9',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        color: '#17231E',
                    }).then((result) => {
                        if (result.isConfirmed && id !== undefined) {
                            @this.call('delete', id);
                        }
                    });
                });
                listenersAttached = true;
            }
            document.addEventListener('livewire:initialized', initListeners);
            document.addEventListener('livewire:navigated', () => { listenersAttached = false; initListeners(); });
        })();
    </script>
    @endpush
</div>
