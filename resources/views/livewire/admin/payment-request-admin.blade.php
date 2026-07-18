<div>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-5 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Konfirmasi Pembayaran</h3>
                    <p class="text-sm mt-1" style="color:#17231E;">Verifikasi dan proses permintaan pembayaran dari portal penghuni</p>
                </div>
                @if($pendingCount > 0)
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.25);">
                    <span class="text-2xl font-bold" style="color:#B0402C;">{{ $pendingCount }}</span>
                    <span class="text-sm" style="color:#B0402C;">menunggu konfirmasi</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Filters --}}
        <div class="rounded-2xl p-4 mb-5 flex flex-wrap gap-3" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <select wire:model.live="filterType" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;">
                <option value="">Semua Tipe</option>
                <option value="ipl">IPL</option>
                <option value="donation">Donasi</option>
            </select>
            <select wire:model.live="filterStatus" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;">
                <option value="pending">Menunggu</option>
                <option value="confirmed">Dikonfirmasi</option>
                <option value="rejected">Ditolak</option>
                <option value="">Semua Status</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Penghuni</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Tipe & Referensi</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Jumlah</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#909A8F;">Bukti</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Status</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr style="border-bottom:1px solid #F1F3EC;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                            <td class="px-5 py-3">
                                <div class="font-medium" style="color:#17231E;">{{ $req->resident->name }}</div>
                                <div class="text-xs" style="color:#909A8F;">{{ $req->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-5 py-3">
                                @if($req->type === 'ipl')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mb-1" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">IPL</span>
                                    @if($req->iplBilling?->period)
                                        <div class="text-xs" style="color:#586359;">
                                            {{ \Carbon\Carbon::create($req->iplBilling->period->year, $req->iplBilling->period->month)->translatedFormat('F Y') }}
                                            — {{ $req->iplBilling->houseBlock?->block_code }}
                                        </div>
                                    @elseif($req->period_year && $req->period_month)
                                        <div class="text-xs" style="color:#586359;">
                                            {{ \Carbon\Carbon::create($req->period_year, $req->period_month)->translatedFormat('F Y') }} <span style="color:#17231E;">(bayar di muka)</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mb-1" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Donasi</span>
                                    @if($req->campaign)
                                        <div class="text-xs" style="color:#586359;">{{ Str::limit($req->campaign->name, 30) }}</div>
                                    @endif
                                @endif
                                @if($req->payment_method)
                                    <div class="text-xs mt-0.5" style="color:#909A8F;">
                                        {{ ucfirst($req->payment_method) }}
                                        @if($req->bank_name) — {{ $req->bank_name }} @endif
                                        @if($req->reference_number)<br><span style="color:#909A8F;">Ref: {{ $req->reference_number }}</span>@endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-semibold" style="color:#17231E;">
                                Rp {{ number_format($req->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell">
                                @if($req->proof_photo)
                                    <a href="{{ Storage::disk('public')->url($req->proof_photo) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-lg transition-colors"
                                        style="background:rgba(22,74,64,0.08);color:#17231E;border:1px solid rgba(22,74,64,0.2);"
                                        onmouseover="this.style.background='rgba(22,74,64,0.15)'" onmouseout="this.style.background='rgba(22,74,64,0.08)'">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs" style="color:#909A8F;">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $statusStyle = match($req->status) {
                                        'pending'   => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);',
                                        'confirmed' => 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);',
                                        'rejected'  => 'background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);',
                                        default     => '',
                                    };
                                    $statusLabel = match($req->status) {
                                        'pending'   => 'Menunggu',
                                        'confirmed' => 'Dikonfirmasi',
                                        'rejected'  => 'Ditolak',
                                        default     => $req->status,
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="{{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                                @if($req->admin_notes)
                                    <div class="text-xs mt-1" style="color:#909A8F;">{{ $req->admin_notes }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($req->status === 'pending')
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="confirmModal({{ $req->id }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                        style="background:#164A40;color:#ffffff;"
                                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                                        Konfirmasi
                                    </button>
                                    <button wire:click="reject({{ $req->id }})" wire:confirm="Tolak permintaan ini?"
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                        style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);"
                                        onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
                                        Tolak
                                    </button>
                                </div>
                                @else
                                    <span class="text-xs" style="color:#909A8F;">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm" style="color:#909A8F;">Tidak ada permintaan pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                @forelse($requests as $req)
                <div wire:key="mobile-req-{{ $req->id }}" class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div>
                            <div class="font-medium" style="color:#17231E;">{{ $req->resident->name }}</div>
                            <div class="text-xs" style="color:#909A8F;">{{ $req->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold font-mono" style="color:#17231E;">Rp {{ number_format($req->amount, 0, ',', '.') }}</div>
                            @php
                                $statusStyleMobile = match($req->status) {
                                    'pending'   => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);',
                                    'confirmed' => 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);',
                                    'rejected'  => 'background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);',
                                    default     => '',
                                };
                                $statusLabelMobile = match($req->status) {
                                    'pending'   => 'Menunggu',
                                    'confirmed' => 'Dikonfirmasi',
                                    'rejected'  => 'Ditolak',
                                    default     => $req->status,
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1" style="{{ $statusStyleMobile }}">
                                {{ $statusLabelMobile }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div>
                            <div class="text-xs" style="color:#909A8F;">Tipe & Referensi</div>
                            <div class="mt-0.5">
                                @if($req->type === 'ipl')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mb-1" style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">IPL</span>
                                    @if($req->iplBilling?->period)
                                        <div class="text-xs" style="color:#586359;">
                                            {{ \Carbon\Carbon::create($req->iplBilling->period->year, $req->iplBilling->period->month)->translatedFormat('F Y') }}
                                            — {{ $req->iplBilling->houseBlock?->block_code }}
                                        </div>
                                    @elseif($req->period_year && $req->period_month)
                                        <div class="text-xs" style="color:#586359;">
                                            {{ \Carbon\Carbon::create($req->period_year, $req->period_month)->translatedFormat('F Y') }} <span style="color:#17231E;">(bayar di muka)</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mb-1" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Donasi</span>
                                    @if($req->campaign)
                                        <div class="text-xs" style="color:#586359;">{{ Str::limit($req->campaign->name, 30) }}</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-xs" style="color:#909A8F;">Metode</div>
                            <div class="mt-0.5">
                                @if($req->payment_method)
                                    <div class="text-xs" style="color:#586359;">
                                        {{ ucfirst($req->payment_method) }}
                                        @if($req->bank_name) — {{ $req->bank_name }} @endif
                                        @if($req->reference_number)<br><span style="color:#909A8F;">Ref: {{ $req->reference_number }}</span>@endif
                                    </div>
                                @else
                                    <span class="text-xs" style="color:#909A8F;">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="text-xs" style="color:#909A8F;">Bukti Transfer</div>
                            <div class="mt-0.5">
                                @if($req->proof_photo)
                                    <a href="{{ Storage::disk('public')->url($req->proof_photo) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-lg"
                                        style="background:rgba(22,74,64,0.08);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs" style="color:#909A8F;">—</span>
                                @endif
                            </div>
                        </div>
                        @if($req->admin_notes)
                        <div class="col-span-2">
                            <div class="text-xs" style="color:#909A8F;">Catatan Admin</div>
                            <div class="text-xs mt-0.5" style="color:#586359;">{{ $req->admin_notes }}</div>
                        </div>
                        @endif
                    </div>

                    @if($req->status === 'pending')
                    <div class="flex items-center gap-2">
                        <button wire:click="confirmModal({{ $req->id }})"
                            class="flex-1 px-3 py-2 rounded-lg text-xs font-medium text-center"
                            style="background:#164A40;color:#ffffff;">
                            Konfirmasi
                        </button>
                        <button wire:click="reject({{ $req->id }})" wire:confirm="Tolak permintaan ini?"
                            class="flex-1 px-3 py-2 rounded-lg text-xs font-medium text-center"
                            style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.2);">
                            Tolak
                        </button>
                    </div>
                    @endif
                </div>
                @empty
                <div class="px-5 py-12 text-center text-sm" style="color:#909A8F;">Tidak ada permintaan pembayaran.</div>
                @endforelse
            </div>

            <div class="px-5 py-3" style="border-top:1px solid #F1F3EC;">{{ $requests->links() }}</div>
        </div>

        {{-- Confirm Modal --}}
        @if($isConfirmModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isConfirmModalOpen',false)"></div>
            <div class="relative rounded-2xl shadow-2xl w-full max-w-md" style="background:#ffffff;border:1px solid #D8D6C9;">
                <div class="px-6 py-4 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                    <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                        {{ $confirmingType === 'donation' ? 'Konfirmasi Donasi' : 'Konfirmasi Pembayaran' }}
                    </h3>
                    <p class="text-xs mt-0.5" style="color:#17231E;">
                        {{ $confirmingType === 'donation'
                            ? 'Donasi akan dicatat otomatis ke kas ' . ($confirmingOrg === 'dkm' ? 'DKM' : 'Perumahan') . '.'
                            : 'Pembayaran IPL akan dicatat otomatis ke kas Perumahan.' }}
                    </p>
                </div>
                <div class="px-6 py-5 space-y-4">
                    @php $cr = $this->confirmingRequest; @endphp
                    @if($cr)
                    {{-- Detail pembayaran --}}
                    <div class="rounded-xl p-4 space-y-2.5" style="background:#ffffff;border:1px solid #E0DFD4;">
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#909A8F;">Penghuni</span>
                            <span class="font-medium text-right" style="color:#17231E;">{{ $cr->resident?->name ?? '—' }}</span>
                        </div>
                        @if($cr->type === 'ipl')
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#909A8F;">Periode</span>
                            <span class="font-medium text-right" style="color:#17231E;">
                                {{ $cr->iplBilling?->period?->period_label
                                    ?? (($cr->period_year && $cr->period_month) ? \Carbon\Carbon::create($cr->period_year,$cr->period_month)->translatedFormat('F Y') : '—') }}
                            </span>
                        </div>
                        @if($cr->iplBilling?->houseBlock)
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#909A8F;">Blok</span>
                            <span class="font-medium text-right" style="color:#17231E;">{{ $cr->iplBilling->houseBlock->block_code }}</span>
                        </div>
                        @endif
                        @else
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#909A8F;">Program</span>
                            <span class="font-medium text-right" style="color:#17231E;">{{ $cr->campaign?->name ?? 'Donasi umum' }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#909A8F;">Jumlah</span>
                            <span class="font-bold text-right" style="color:#17231E;">Rp {{ number_format((float)$cr->amount, 0, ',', '.') }}</span>
                        </div>
                        @if($cr->type === 'ipl' && ((float)$cr->amount_security > 0 || (float)$cr->amount_garbage > 0 || (float)$cr->amount_kas_rt > 0))
                        <div class="pt-2 space-y-1" style="border-top:1px solid #F1F3EC;">
                            @if((float)$cr->amount_security > 0)
                            <div class="flex justify-between gap-3 text-xs">
                                <span class="flex items-center gap-1.5" style="color:#586359;">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#1a7f37;"></span>
                                    Keamanan
                                </span>
                                <span class="font-medium" style="color:#17231E;">Rp {{ number_format((float)$cr->amount_security, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if((float)$cr->amount_garbage > 0)
                            <div class="flex justify-between gap-3 text-xs">
                                <span class="flex items-center gap-1.5" style="color:#586359;">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#c07a18;"></span>
                                    Sampah
                                </span>
                                <span class="font-medium" style="color:#17231E;">Rp {{ number_format((float)$cr->amount_garbage, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            @if((float)$cr->amount_kas_rt > 0)
                            <div class="flex justify-between gap-3 text-xs">
                                <span class="flex items-center gap-1.5" style="color:#586359;">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:#6B5B95;"></span>
                                    Kas RT
                                </span>
                                <span class="font-medium" style="color:#17231E;">Rp {{ number_format((float)$cr->amount_kas_rt, 0, ',', '.') }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#909A8F;">Metode</span>
                            <span class="font-medium text-right capitalize" style="color:#17231E;">
                                {{ $cr->payment_method }}{{ $cr->bank_name ? ' — '.$cr->bank_name : '' }}
                            </span>
                        </div>
                        @if($cr->reference_number)
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#909A8F;">No. Referensi</span>
                            <span class="font-medium text-right" style="color:#17231E;">{{ $cr->reference_number }}</span>
                        </div>
                        @endif
                        @if($cr->notes)
                        <div class="text-sm pt-1" style="border-top:1px dashed #E0DFD4;">
                            <span style="color:#909A8F;">Catatan penghuni:</span>
                            <span style="color:#17231E;">{{ $cr->notes }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Bukti pembayaran --}}
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Bukti Pembayaran</label>
                        @if($cr->proof_photo)
                            <a href="{{ Storage::disk('public')->url($cr->proof_photo) }}" target="_blank" class="block rounded-xl overflow-hidden" style="border:1px solid #E0DFD4;">
                                <img src="{{ Storage::disk('public')->url($cr->proof_photo) }}" alt="Bukti pembayaran"
                                    class="w-full max-h-64 object-contain" style="background:#ffffff;">
                            </a>
                            <p class="text-xs mt-1" style="color:#909A8F;">Klik untuk memperbesar di tab baru.</p>
                        @else
                            <div class="rounded-xl px-3 py-4 text-sm text-center" style="background:#ffffff;border:1px dashed #E0DFD4;color:#909A8F;">
                                Tidak ada bukti pembayaran diunggah.
                            </div>
                        @endif
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Catatan Admin (opsional)</label>
                        <textarea wire:model="adminNotes" rows="2" style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #E0DFD4;">
                        <button type="button" wire:click="$set('isConfirmModalOpen',false)"
                            class="px-4 py-2 text-sm rounded-xl font-medium"
                            style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;">Batal</button>
                        <button wire:click="confirm"
                            class="px-5 py-2 text-sm rounded-xl font-semibold"
                            style="background:#164A40;color:#ffffff;"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="confirm">Konfirmasi & Catat</span>
                            <span wire:loading wire:target="confirm">Memproses...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
