<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#161e2d;">Pengaturan Tarif IPL</h2>
    </x-slot>

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
                <div class="rounded-xl p-4 text-sm flex items-center gap-3" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1zm0-4a1 1 0 011-1h.01a1 1 0 010 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Header Banner --}}
            <div class="rounded-2xl p-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Pengaturan Tarif IPL</h1>
                        <p class="text-sm mt-1" style="color:#161e2d;">Kelola jenis tarif dan nominal default untuk setiap periode IPL</p>
                    </div>
                    <button wire:click="openCreate"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                        style="background:#1563df;color:#ffffff;"
                        onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Tarif Baru
                    </button>
                </div>
            </div>

            {{-- Info Box --}}
            <div class="rounded-xl p-4 flex gap-3" style="background:rgba(21,99,223,0.06);border:1px solid rgba(21,99,223,0.2);">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="#1563df" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm space-y-1" style="color:#161e2d;">
                    <p><span style="color:#161e2d;font-weight:600;">Tarif Sistem</span> (Keamanan &amp; Sampah) adalah tarif bawaan yang tidak bisa dihapus. Nominal defaultnya digunakan sebagai nilai awal saat membuat periode IPL baru.</p>
                    <p><span style="color:#161e2d;font-weight:600;">Tarif Tambahan</span> adalah iuran lain di luar keamanan dan sampah (contoh: Kebersihan Taman, Parkir, dll). Tarif ini akan otomatis ditambahkan sebagai item tagihan terpisah pada setiap billing blok.</p>
                </div>
            </div>

            {{-- Tarif Sistem --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <div class="flex items-center justify-between px-5 py-4" style="background:#f7f7f7;border-bottom:1px solid rgba(21,99,223,0.2);">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(21,99,223,0.15);border:1px solid rgba(21,99,223,0.3);">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#1563df" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm" style="color:#161e2d;">Tarif Sistem (Bawaan)</h3>
                            <p class="text-xs" style="color:#a3abb0;">Tarif wajib — tidak dapat dihapus</p>
                        </div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full" style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">{{ $systemTypes->count() }} tarif</span>
                </div>
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">No</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Nama Tarif</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Keterangan</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Nominal Default</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Sort</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Status</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($systemTypes as $i => $type)
                                <tr wire:key="sys-{{ $type->id }}" style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#f7f7f7'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-5 py-3.5" style="color:#a3abb0;">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold" style="color:#161e2d;">{{ $type->name }}</span>
                                            @if($type->billing_key === 'security')
                                                <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(59,130,246,0.1);color:#2563eb;border:1px solid rgba(59,130,246,0.2);">Keamanan</span>
                                            @elseif($type->billing_key === 'garbage')
                                                <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Sampah</span>
                                            @endif
                                        </div>
                                        <p class="text-xs mt-0.5" style="color:#a3abb0;">
                                            @if($type->billing_key === 'security') Kolom: ipl_security_amount
                                            @elseif($type->billing_key === 'garbage') Kolom: ipl_garbage_amount
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm" style="color:#a3abb0;">{{ $type->description ?? '—' }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="font-mono font-semibold text-base" style="color:#161e2d;">Rp {{ number_format($type->default_amount, 0, ',', '.') }}</span>
                                        <p class="text-xs" style="color:#a3abb0;">/blok/bulan</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-center text-sm" style="color:#a3abb0;">{{ $type->sort_order }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        @if($type->is_active)
                                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#12805c;"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#d9d9d9;"></span>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button wire:click="openEdit({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);"
                                                onmouseover="this.style.background='rgba(21,99,223,0.2)'" onmouseout="this.style.background='rgba(21,99,223,0.1)'">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </button>
                                            <button wire:click="toggleActive({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="{{ $type->is_active ? 'background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);' : 'background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.2);' }}"
                                                onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                                {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-8 text-center text-sm" style="color:#a3abb0;">Belum ada tarif sistem. Jalankan seeder.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden divide-y" style="border-color:#f7f7f7;">
                    @forelse($systemTypes as $type)
                        <div wire:key="sys-card-{{ $type->id }}" class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold" style="color:#161e2d;">{{ $type->name }}</span>
                                        @if($type->billing_key === 'security')
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(59,130,246,0.1);color:#2563eb;border:1px solid rgba(59,130,246,0.2);">Keamanan</span>
                                        @elseif($type->billing_key === 'garbage')
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Sampah</span>
                                        @endif
                                    </div>
                                    <p class="text-xs mt-0.5" style="color:#a3abb0;">{{ $type->description ?? '—' }}</p>
                                </div>
                                @if($type->is_active)
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full shrink-0" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#12805c;"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full shrink-0" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#d9d9d9;"></span>Nonaktif
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="font-mono font-semibold text-base" style="color:#161e2d;">Rp {{ number_format($type->default_amount, 0, ',', '.') }}</span>
                                <p class="text-xs" style="color:#a3abb0;">/blok/bulan &middot; Sort {{ $type->sort_order }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 pt-1 flex-wrap">
                                <button wire:click="openEdit({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <button wire:click="toggleActive({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="{{ $type->is_active ? 'background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);' : 'background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.2);' }}">
                                    {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm" style="color:#a3abb0;">Belum ada tarif sistem. Jalankan seeder.</div>
                    @endforelse
                </div>
            </div>

            {{-- Tarif Tambahan --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <div class="flex items-center justify-between px-5 py-4" style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(139,92,246,0.12);border:1px solid rgba(139,92,246,0.25);">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm" style="color:#161e2d;">Tarif Tambahan</h3>
                            <p class="text-xs" style="color:#a3abb0;">Iuran lain di luar keamanan &amp; sampah (kebersihan taman, parkir, dll)</p>
                        </div>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">{{ $extraTypes->count() }} tarif</span>
                </div>
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">No</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Nama Tarif</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Keterangan</th>
                                <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Nominal Default</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Sort</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Status</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($extraTypes as $i => $type)
                                <tr wire:key="extra-{{ $type->id }}" style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#f7f7f7'" onmouseout="this.style.backgroundColor=''">
                                    <td class="px-5 py-3.5" style="color:#a3abb0;">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold" style="color:#161e2d;">{{ $type->name }}</span>
                                            <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(139,92,246,0.1);color:#7c3aed;border:1px solid rgba(139,92,246,0.2);">Extra</span>
                                        </div>
                                        <p class="text-xs mt-0.5" style="color:#a3abb0;">ipl_billing_charge_items</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm" style="color:#a3abb0;">{{ $type->description ?? '—' }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="font-mono font-semibold text-base" style="color:#7c3aed;">Rp {{ number_format($type->default_amount, 0, ',', '.') }}</span>
                                        <p class="text-xs" style="color:#a3abb0;">/blok/bulan</p>
                                    </td>
                                    <td class="px-5 py-3.5 text-center text-sm" style="color:#a3abb0;">{{ $type->sort_order }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        @if($type->is_active)
                                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#12805c;"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">
                                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#d9d9d9;"></span>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button wire:click="openEdit({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);"
                                                onmouseover="this.style.background='rgba(21,99,223,0.2)'" onmouseout="this.style.background='rgba(21,99,223,0.1)'">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </button>
                                            <button wire:click="toggleActive({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="{{ $type->is_active ? 'background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);' : 'background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.2);' }}"
                                                onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">
                                                {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                            <button wire:click="confirmDelete({{ $type->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                                style="background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);"
                                                onmouseover="this.style.background='rgba(192,69,59,0.18)'" onmouseout="this.style.background='rgba(192,69,59,0.08)'">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center" style="color:#a3abb0;">
                                        <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#7c3aed;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        <p class="text-sm">Belum ada tarif tambahan.</p>
                                        <p class="text-xs mt-1" style="color:#a3abb0;">Klik "Tambah Tarif Baru" untuk menambahkan iuran selain keamanan &amp; sampah.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="md:hidden divide-y" style="border-color:#f7f7f7;">
                    @forelse($extraTypes as $type)
                        <div wire:key="extra-card-{{ $type->id }}" class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold" style="color:#161e2d;">{{ $type->name }}</span>
                                        <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(139,92,246,0.1);color:#7c3aed;border:1px solid rgba(139,92,246,0.2);">Extra</span>
                                    </div>
                                    <p class="text-xs mt-0.5" style="color:#a3abb0;">{{ $type->description ?? '—' }}</p>
                                </div>
                                @if($type->is_active)
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full shrink-0" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#12805c;"></span>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs px-2.5 py-1 rounded-full shrink-0" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">
                                        <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#d9d9d9;"></span>Nonaktif
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="font-mono font-semibold text-base" style="color:#7c3aed;">Rp {{ number_format($type->default_amount, 0, ',', '.') }}</span>
                                <p class="text-xs" style="color:#a3abb0;">/blok/bulan &middot; Sort {{ $type->sort_order }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 pt-1 flex-wrap">
                                <button wire:click="openEdit({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </button>
                                <button wire:click="toggleActive({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="{{ $type->is_active ? 'background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);' : 'background:rgba(18,128,92,0.08);color:#12805c;border:1px solid rgba(18,128,92,0.2);' }}">
                                    {{ $type->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                                <button wire:click="confirmDelete({{ $type->id }})"
                                    class="inline-flex items-center gap-1 px-3 py-2 rounded-lg text-xs font-medium transition-colors"
                                    style="background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center" style="color:#a3abb0;">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#7c3aed;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <p class="text-sm">Belum ada tarif tambahan.</p>
                            <p class="text-xs mt-1" style="color:#a3abb0;">Klik "Tambah Tarif Baru" untuk menambahkan iuran selain keamanan &amp; sampah.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Akun Tujuan Pembayaran --}}
            <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <div class="flex items-center gap-3 px-5 py-4" style="background:#f7f7f7;border-bottom:1px solid rgba(21,99,223,0.2);">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(21,99,223,0.15);border:1px solid rgba(21,99,223,0.3);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#1563df" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h16a1 1 0 001-1V6a1 1 0 00-1-1H4a1 1 0 00-1 1v12a1 1 0 001 1z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-sm" style="color:#161e2d;">Akun Tujuan Pembayaran</h3>
                        <p class="text-xs" style="color:#a3abb0;">Tentukan akun kas/bank yang menerima dana untuk setiap jenis tarif &amp; donasi</p>
                    </div>
                </div>

                <form wire:submit="saveAccounts" class="p-5 space-y-5">
                    {{-- Tarif Sistem --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#a3abb0;">Tarif Sistem</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($systemTypes as $type)
                                <div>
                                    <label class="block text-sm mb-1.5" style="color:#5c6368;">{{ $type->name }}</label>
                                    <select wire:model="tariffAccounts.{{ $type->id }}"
                                        class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
                                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
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
                            <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#a3abb0;">Tarif Tambahan (Aktif)</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($activeExtraTypes as $type)
                                    <div>
                                        <label class="block text-sm mb-1.5" style="color:#5c6368;">{{ $type->name }}</label>
                                        <select wire:model="tariffAccounts.{{ $type->id }}"
                                            class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
                                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
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
                        <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#a3abb0;">Donasi</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm mb-1.5" style="color:#5c6368;">Donasi Perumahan</label>
                                <select wire:model="donationAccountPerumahan"
                                    class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                    <option value="">— pilih akun —</option>
                                    @foreach($perumahanAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm mb-1.5" style="color:#5c6368;">Donasi DKM</label>
                                <select wire:model="donationAccountDkm"
                                    class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
                                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                                    <option value="">— pilih akun —</option>
                                    @foreach($dkmAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-end pt-2" style="border-top:1px solid #e4e4e4;">
                        <button type="submit"
                            class="px-5 py-2 text-sm rounded-xl font-semibold transition-colors"
                            style="background:#1563df;color:#ffffff;"
                            onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'"
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
        <div class="relative rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" style="background:#ffffff;border:1px solid #d9d9d9;" @click.stop>
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4" style="background:#f7f7f7;border-bottom:1px solid rgba(21,99,223,0.35);">
                <h3 class="font-bold text-lg" style="color:#161e2d;font-family:'Manrope',serif;">
                    {{ $editingId ? 'Edit Tarif' : 'Tambah Tarif Baru' }}
                </h3>
                <button wire:click="closeModal" class="p-1 rounded-lg transition-colors" style="color:#161e2d;" onmouseover="this.style.background='rgba(21,99,223,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Error --}}
            @if(session('modal_error'))
                <div class="mx-6 mt-4 rounded-xl p-3 text-xs" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                    {{ session('modal_error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="mx-6 mt-4 rounded-xl p-3" style="background:rgba(199,125,26,0.08);border:1px solid rgba(199,125,26,0.25);color:#c77d1a;">
                    <p class="text-xs font-medium mb-1">Ada kesalahan input:</p>
                    <ul class="list-disc pl-4 text-xs space-y-0.5">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form wire:submit="save" class="p-6 space-y-4">
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Nama Tarif <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="name" placeholder="Contoh: Kebersihan Taman, Parkir, dll"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    @error('name')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Keterangan <span class="text-xs" style="color:#a3abb0;">(opsional)</span></label>
                    <input type="text" wire:model="description" placeholder="Penjelasan singkat tarif ini"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    @error('description')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>

                {{-- Nominal Default --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Nominal Default (Rp) <span style="color:#c0453b;">*</span></label>
                    <input type="number" wire:model="defaultAmount" min="0" step="500" placeholder="Contoh: 50000"
                        class="w-full px-3 py-2 text-sm rounded-xl outline-none font-mono"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    <p class="text-xs mt-1" style="color:#a3abb0;">Nilai ini digunakan sebagai default saat membuat periode IPL baru. Bisa diubah per-periode.</p>
                    @error('defaultAmount')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>

                {{-- Sort Order + Aktif --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Urutan (Sort)</label>
                        <input type="number" wire:model="sortOrder" min="0"
                            class="w-full px-3 py-2 text-sm rounded-xl outline-none"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('sortOrder')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" wire:model="isActive" class="w-4 h-4 rounded" style="accent-color:#161e2d;">
                            <span class="text-sm" style="color:#5c6368;">Aktif</span>
                        </label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #e4e4e4;">
                    <button type="button" wire:click="closeModal"
                        class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                        style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;"
                        onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm rounded-xl font-semibold transition-colors"
                        style="background:#1563df;color:#ffffff;"
                        onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'"
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
                        confirmButtonColor: '#c0453b',
                        cancelButtonColor: '#d9d9d9',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#ffffff',
                        color: '#161e2d',
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
