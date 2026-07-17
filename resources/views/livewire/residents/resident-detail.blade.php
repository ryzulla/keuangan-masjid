<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#161e2d;">Detail Penghuni</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    @if($resident->photo)
                        <img src="{{ Storage::disk('public')->url($resident->photo) }}"
                            alt="{{ $resident->name }}"
                            class="w-14 h-14 rounded-2xl object-cover shrink-0"
                            style="border:2px solid rgba(21,99,223,0.4);">
                    @else
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-bold shrink-0"
                            style="background:rgba(21,99,223,0.15);color:#161e2d;border:1px solid rgba(21,99,223,0.3);">
                            {{ strtoupper(substr($resident->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-bold text-xl" style="color:#161e2d;font-family:'Manrope',serif;">{{ $resident->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            @if($resident->is_active)
                                <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(18,128,92,0.15);color:#12805c;border:1px solid rgba(18,128,92,0.25);">Aktif</span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full" style="background:#f7f7f7;color:#a3abb0;border:1px solid #e4e4e4;">Nonaktif</span>
                            @endif
                            <span class="text-xs" style="color:#a3abb0;">{{ $resident->currentAssignments->count() }} unit aktif</span>
                            <span class="text-xs" style="color:#a3abb0;">&middot; {{ $resident->familyMembers->count() }} anggota keluarga</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1.5 shrink-0">
                    <a href="{{ route('residents.edit', $resident) }}" wire:navigate
                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                        style="background:#1563df;color:#ffffff;">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <button wire:click="toggleActive" wire:confirm="{{ $resident->is_active ? 'Nonaktifkan penghuni ini?' : 'Aktifkan kembali penghuni ini?' }}"
                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium transition-colors"
                        style="{{ $resident->is_active ? 'background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.25);' : 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);' }}">
                        {{ $resident->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                    {{-- Portal Access --}}
                    @if($resident->email)
                    <button wire:click="openPasswordModal"
                        class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-xs transition-colors"
                        style="background:rgba(99,102,241,0.1);color:#4f46e5;border:1px solid rgba(99,102,241,0.25);">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        {{ $resident->password ? 'Reset Password' : 'Buat Akses' }}
                    </button>
                    @if($resident->password)
                    <button wire:click="resetPortalAccess" wire:confirm="Cabut akses portal penghuni ini?"
                        class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-xs transition-colors"
                        style="background:rgba(192,69,59,0.08);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Cabut Akses
                    </button>
                    @endif
                    @endif
                    <a href="{{ route('residents.index') }}" wire:navigate
                        class="inline-flex items-center gap-1 px-2 py-1.5 rounded-lg text-xs transition-colors"
                        style="background:#f7f7f7;color:#5c6368;border:1px solid #d9d9d9;">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        Semua
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Info + Contact --}}
            <div class="space-y-5">

                {{-- Kontak --}}
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                    <h4 class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:#161e2d;">Informasi Kontak</h4>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs" style="color:#a3abb0;">Telepon</p>
                            <p class="font-medium mt-0.5" style="color:#161e2d;">{{ $resident->phone ?: '—' }}</p>
                        </div>
                        <div style="border-top:1px solid #f7f7f7;padding-top:0.75rem;">
                            <p class="text-xs" style="color:#a3abb0;">WhatsApp</p>
                            @if($resident->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $resident->whatsapp) }}" target="_blank"
                                    class="font-medium mt-0.5 block hover:underline" style="color:#12805c;">{{ $resident->whatsapp }}</a>
                            @else
                                <p class="font-medium mt-0.5" style="color:#161e2d;">—</p>
                            @endif
                        </div>
                        @if($resident->email)
                        <div style="border-top:1px solid #f7f7f7;padding-top:0.75rem;">
                            <p class="text-xs" style="color:#a3abb0;">Email</p>
                            <p class="font-medium mt-0.5 break-all" style="color:#161e2d;">{{ $resident->email }}</p>
                        </div>
                        @endif
                        @if($resident->notes)
                        <div style="border-top:1px solid #f7f7f7;padding-top:0.75rem;">
                            <p class="text-xs" style="color:#a3abb0;">Catatan</p>
                            <p class="text-sm mt-0.5" style="color:#5c6368;">{{ $resident->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Right: Blok Rumah + Keluarga --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- ── Kepemilikan & Hunian Aktif ── --}}
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                    <h4 class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:#161e2d;">Kepemilikan & Hunian Saat Ini</h4>

                    @forelse($resident->currentAssignments as $a)
                    <div class="rounded-xl mb-3 overflow-hidden" style="border:1px solid #e4e4e4;" wire:key="ca-{{ $a->id }}">
                        <div class="flex items-center justify-between px-4 py-3" style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-xl" style="color:#161e2d;">{{ $a->houseBlock?->block_code ?? '?' }}</span>
                                @if($a->is_primary_residence)
                                    <span class="text-xs px-2 py-0.5 rounded-full" style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">★ Domisili Utama</span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @php
                                    $ownerStyle = match($a->ownership_type) {
                                        'pemilik' => 'background:rgba(59,130,246,0.1);color:#2563eb;border:1px solid rgba(59,130,246,0.2);',
                                        'kontrak' => 'background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);',
                                        default   => 'background:rgba(139,92,246,0.1);color:#7c3aed;border:1px solid rgba(139,92,246,0.2);',
                                    };
                                @endphp
                                <span class="text-xs px-2 py-0.5 rounded-full" style="{{ $ownerStyle }}">{{ $a->ownership_label }}</span>
                                @if($a->houseBlock)
                                    <a href="{{ route('house-blocks.show', $a->houseBlock) }}" wire:navigate
                                        class="text-xs px-2 py-0.5 rounded-full transition-colors"
                                        style="background:rgba(21,99,223,0.08);color:#161e2d;border:1px solid rgba(21,99,223,0.15);"
                                        onmouseover="this.style.color='#1563df'" onmouseout="this.style.color='#1563df'">
                                        Lihat Blok →
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="px-4 py-2.5 text-xs flex flex-wrap gap-x-5 gap-y-1" style="color:#a3abb0;">
                            @if($a->resident_since)
                                <span>Menghuni sejak: {{ $a->resident_since->locale('id')->isoFormat('D MMM Y') }}</span>
                            @endif
                            @if(in_array($a->ownership_type, ['kontrak', 'kos']) && $a->contract_start_date)
                                <span style="color:#c77d1a;">Kontrak: {{ $a->contract_start_date->format('M Y') }} – {{ $a->contract_end_date?->format('M Y') ?? 'sekarang' }}</span>
                            @endif
                            @if($a->monthly_rent)
                                <span style="color:#c77d1a;">Sewa: Rp {{ number_format($a->monthly_rent, 0, ',', '.') }}/bulan</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 rounded-xl" style="border:1px dashed #e4e4e4;">
                        <p class="text-sm" style="color:#a3abb0;">Belum ada kepemilikan atau hunian aktif.</p>
                        <a href="{{ route('residents.edit', $resident) }}" wire:navigate class="text-xs mt-1 inline-block hover:underline" style="color:#161e2d;">Tetapkan rumah →</a>
                    </div>
                    @endforelse

                    {{-- History section --}}
                    @php $historicalAssignments = $resident->assignments->whereNotNull('ended_at')->sortByDesc('ended_at'); @endphp
                    @if($historicalAssignments->count() > 0)
                    <div class="mt-4 pt-4" style="border-top:1px solid #e4e4e4;">
                        <h5 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#a3abb0;">Riwayat Hunian Sebelumnya</h5>
                        <div class="space-y-2">
                            @foreach($historicalAssignments as $ha)
                            <div class="flex items-center justify-between py-2 px-3 rounded-lg text-xs" style="background:#ffffff;border:1px solid #f7f7f7;">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold" style="color:#a3abb0;">{{ $ha->houseBlock?->block_code ?? '?' }}</span>
                                    <span style="color:#a3abb0;">{{ $ha->ownership_label }}</span>
                                </div>
                                <span style="color:#a3abb0;">
                                    {{ $ha->resident_since?->format('M Y') ?? '?' }} – {{ $ha->ended_at?->format('M Y') ?? '?' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- ── Anggota Keluarga ── --}}
                <div class="rounded-2xl p-5" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">
                            Anggota Keluarga
                            @if($resident->familyMembers->count() > 0)
                                <span class="ml-2 text-xs font-normal" style="color:#a3abb0;">{{ $resident->familyMembers->count() }} jiwa</span>
                            @endif
                        </h4>
                        <a href="{{ route('residents.edit', $resident) }}" wire:navigate
                            class="text-xs transition-colors" style="color:#161e2d;"
                            onmouseover="this.style.color='#1563df'" onmouseout="this.style.color='#1563df'">
                            Edit →
                        </a>
                    </div>

                    @forelse($resident->familyMembers as $fm)
                    <div class="flex items-center gap-3 py-2.5" style="border-bottom:1px solid #f7f7f7;" wire:key="fm-{{ $fm->id }}">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 font-bold text-sm"
                            style="{{ $fm->gender === 'laki-laki' ? 'background:rgba(56,189,248,0.1);color:#0284c7;' : 'background:rgba(236,72,153,0.1);color:#db2777;' }}">
                            {{ strtoupper(substr($fm->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate" style="color:#161e2d;">{{ $fm->name }}</p>
                            <p class="text-xs" style="color:#a3abb0;">
                                {{ $fm->relationship_label }}
                                @if($fm->birth_date)
                                    &bull; {{ $fm->birth_date->diffInYears(now()) }} tahun
                                @endif
                            </p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full shrink-0"
                            style="{{ $fm->gender === 'laki-laki' ? 'background:rgba(56,189,248,0.1);color:#0284c7;' : 'background:rgba(236,72,153,0.1);color:#db2777;' }}">
                            {{ $fm->gender === 'laki-laki' ? 'L' : 'P' }}
                        </span>
                    </div>
                    @empty
                    <p class="text-sm text-center py-6 italic" style="color:#a3abb0;">Belum ada anggota keluarga terdaftar.</p>
                    @endforelse
                </div>

            </div>

            {{-- Riwayat IPL — tabel di desktop, kartu di mobile --}}
            @if($resident->iplBillings->count() > 0)
            @php
                $iplHistory = $resident->iplBillings
                    ->sortByDesc(fn($b) => ($b->period?->year ?? 0) . str_pad((string)($b->period?->month ?? 0), 2, '0', STR_PAD_LEFT))
                    ->take(12);
                $iplStatusMeta = fn($s) => match($s ?? 'unpaid') {
                    'paid'    => ['background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);', 'Lunas'],
                    'partial' => ['background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);', 'Sebagian'],
                    default   => ['background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);', 'Belum Bayar'],
                };
            @endphp
            <div class="lg:col-span-3 rounded-2xl overflow-hidden mb-6" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
                <div class="px-5 py-4" style="border-bottom:1px solid #f7f7f7;">
                    <h4 class="text-xs font-semibold uppercase tracking-wider" style="color:#161e2d;">Riwayat IPL</h4>
                </div>

                {{-- Desktop: tabel --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                                <th class="text-left px-5 py-2.5 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Periode</th>
                                <th class="text-left px-5 py-2.5 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Blok</th>
                                <th class="text-right px-5 py-2.5 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Total</th>
                                <th class="text-right px-5 py-2.5 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Terbayar</th>
                                <th class="text-right px-5 py-2.5 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Sisa</th>
                                <th class="text-center px-5 py-2.5 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($iplHistory as $billing)
                                @php [$stStyle, $stLabel] = $iplStatusMeta($billing->status); @endphp
                                <tr style="border-bottom:1px solid #f7f7f7;">
                                    <td class="px-5 py-2.5" style="color:#161e2d;">{{ $billing->period?->period_label ?? '?' }}</td>
                                    <td class="px-5 py-2.5" style="color:#5c6368;">{{ $billing->houseBlock?->block_code ?? '—' }}</td>
                                    <td class="px-5 py-2.5 text-right font-mono" style="color:#161e2d;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-5 py-2.5 text-right font-mono" style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</td>
                                    <td class="px-5 py-2.5 text-right font-mono" style="{{ $billing->outstanding > 0 ? 'color:#c0453b;' : 'color:#a3abb0;' }}">Rp {{ number_format($billing->outstanding, 0, ',', '.') }}</td>
                                    <td class="px-5 py-2.5 text-center"><span class="text-xs px-2 py-0.5 rounded-full font-medium" style="{{ $stStyle }}">{{ $stLabel }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile: kartu --}}
                <div class="md:hidden divide-y" style="border-color:#f7f7f7;">
                    @foreach($iplHistory as $billing)
                        @php [$stStyle, $stLabel] = $iplStatusMeta($billing->status); @endphp
                        <div class="px-5 py-3">
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <span class="font-medium" style="color:#161e2d;">{{ $billing->period?->period_label ?? '?' }}</span>
                                    @if($billing->houseBlock)<span class="ml-1 text-xs" style="color:#a3abb0;">{{ $billing->houseBlock->block_code }}</span>@endif
                                </div>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium shrink-0" style="{{ $stStyle }}">{{ $stLabel }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1 mt-2 text-xs">
                                <div><span class="block" style="color:#a3abb0;">Total</span><span class="font-mono" style="color:#161e2d;">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</span></div>
                                <div><span class="block" style="color:#a3abb0;">Terbayar</span><span class="font-mono" style="color:#12805c;">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</span></div>
                                <div><span class="block" style="color:#a3abb0;">Sisa</span><span class="font-mono" style="{{ $billing->outstanding > 0 ? 'color:#c0453b;' : 'color:#a3abb0;' }}">Rp {{ number_format($billing->outstanding, 0, ',', '.') }}</span></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($resident->iplBillings->count() > 12)
                    <div class="px-5 py-2.5 text-center text-xs" style="color:#a3abb0;border-top:1px solid #f7f7f7;">+{{ $resident->iplBillings->count() - 12 }} tagihan lainnya</div>
                @endif
            </div>
            @endif

        {{-- Portal Status Card (selalu tampil untuk semua penghuni) --}}
        <div class="rounded-2xl p-5 mt-6" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <h4 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#161e2d;">Akses Portal Penghuni</h4>
            @if($resident->email)
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div>
                    <p class="text-sm" style="color:#161e2d;">Login: <span style="color:#5c6368;">{{ $resident->email }}</span></p>
                    <p class="text-xs mt-1" style="color:#a3abb0;">
                        @if($resident->password)
                            <span style="color:#12805c;">Aktif</span> — Penghuni dapat login ke portal
                        @else
                            <span style="color:#a3abb0;">Belum diatur</span> — Penghuni belum memiliki akses login
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @if($resident->password && $this->waLoginUrl)
                    <a href="{{ $this->waLoginUrl }}" target="_blank"
                        class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg font-medium transition-colors"
                        style="background:rgba(37,211,102,0.12);color:#12805c;border:1px solid rgba(37,211,102,0.3);">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.945C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-1.001zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.15-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.247-.694.247-1.289.173-1.413z"/></svg>
                        Kirim via WhatsApp
                    </a>
                    @endif
                    <a href="{{ route('penghuni.login') }}" target="_blank"
                        class="text-xs px-3 py-1.5 rounded-lg transition-colors"
                        style="background:rgba(21,99,223,0.08);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                        Buka Portal
                    </a>
                </div>
            </div>

            @if(session('portal_wa_credentials_url'))
            <div class="mt-4 rounded-xl p-3 flex items-center justify-between gap-3 flex-wrap" style="background:rgba(37,211,102,0.06);border:1px solid rgba(37,211,102,0.3);">
                <p class="text-xs" style="color:#12805c;">Password baru siap. Kirim <strong>email &amp; password</strong> ke penghuni sekarang — pesan ini hanya tersedia sekali.</p>
                <a href="{{ session('portal_wa_credentials_url') }}" target="_blank"
                    class="shrink-0 inline-flex items-center gap-1.5 text-xs px-3.5 py-2 rounded-lg font-semibold transition-colors"
                    style="background:#12805c;color:#ffffff;">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.945C.16 5.335 5.495 0 12.05 0a11.817 11.817 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-1.001zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.15-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.247-.694.247-1.289.173-1.413z"/></svg>
                    Kirim Email &amp; Password
                </a>
            </div>
            @endif
            @else
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <p class="text-sm" style="color:#5c6368;">Penghuni ini <span style="color:#c77d1a;">belum memiliki email</span>, sehingga belum bisa dibuatkan akses login portal. Tambahkan email penghuni terlebih dahulu.</p>
                <a href="{{ route('residents.edit', $resident) }}" wire:navigate
                    class="shrink-0 inline-flex items-center gap-1.5 text-sm px-4 py-2 rounded-xl font-semibold transition-colors"
                    style="background:#1563df;color:#ffffff;"
                    onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Tambah Email
                </a>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- Password Modal --}}
@if($isPasswordModalOpen)
<div class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isPasswordModalOpen',false)"></div>
    <div class="relative rounded-2xl shadow-2xl w-full max-w-sm" style="background:#ffffff;border:1px solid #d9d9d9;">
        <div class="px-6 py-4 rounded-t-2xl" style="background:#f7f7f7;border-bottom:1px solid rgba(21,99,223,0.35);">
            <h3 class="font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Atur Password Portal</h3>
            <p class="text-xs mt-1" style="color:#161e2d;">{{ $resident->name }} — {{ $resident->email }}</p>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Password Baru <span style="color:#c0453b;">*</span></label>
                <input type="password" wire:model="newPassword" placeholder="Min. 6 karakter"
                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                @error('newPassword') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Konfirmasi Password <span style="color:#c0453b;">*</span></label>
                <input type="password" wire:model="confirmPassword" placeholder="Ulangi password"
                    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                @error('confirmPassword') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #e4e4e4;">
                <button type="button" wire:click="$set('isPasswordModalOpen',false)"
                    class="px-4 py-2 text-sm rounded-xl font-medium"
                    style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;">Batal</button>
                <button wire:click="setPassword"
                    class="px-5 py-2 text-sm rounded-xl font-semibold"
                    style="background:#1563df;color:#ffffff;"
                    wire:loading.attr="disabled">Simpan Password</button>
            </div>
        </div>
    </div>
</div>
@endif
