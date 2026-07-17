<div>
    <x-slot name="header">
        <h2 class="font-semibold text-base" style="color:#161e2d;">Konfirmasi Pembayaran Penghuni</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.3);color:#c0453b;">
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-5" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg" style="color:#161e2d;font-family:'Manrope',serif;">Konfirmasi Pembayaran</h3>
                    <p class="text-sm mt-1" style="color:#161e2d;">Verifikasi dan proses permintaan pembayaran dari portal penghuni</p>
                </div>
                @if($pendingCount > 0)
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl" style="background:rgba(192,69,59,0.1);border:1px solid rgba(192,69,59,0.25);">
                    <span class="text-2xl font-bold" style="color:#c0453b;">{{ $pendingCount }}</span>
                    <span class="text-sm" style="color:#c0453b;">menunggu konfirmasi</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Filters --}}
        <div class="rounded-2xl p-4 mb-5 flex flex-wrap gap-3" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <select wire:model.live="filterType" style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;">
                <option value="">Semua Tipe</option>
                <option value="ipl">IPL</option>
                <option value="donation">Donasi</option>
            </select>
            <select wire:model.live="filterStatus" style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;font-size:0.875rem;outline:none;">
                <option value="pending">Menunggu</option>
                <option value="confirmed">Dikonfirmasi</option>
                <option value="rejected">Ditolak</option>
                <option value="">Semua Status</option>
            </select>
        </div>

        {{-- Table --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #f7f7f7;">
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Penghuni</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Tipe & Referensi</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Jumlah</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider hidden md:table-cell" style="color:#a3abb0;">Bukti</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Status</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#a3abb0;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr style="border-bottom:1px solid #f7f7f7;" onmouseover="this.style.backgroundColor='#f7f7f7'" onmouseout="this.style.backgroundColor=''">
                            <td class="px-5 py-3">
                                <div class="font-medium" style="color:#161e2d;">{{ $req->resident->name }}</div>
                                <div class="text-xs" style="color:#a3abb0;">{{ $req->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-5 py-3">
                                @if($req->type === 'ipl')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mb-1" style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">IPL</span>
                                    @if($req->iplBilling?->period)
                                        <div class="text-xs" style="color:#5c6368;">
                                            {{ \Carbon\Carbon::create($req->iplBilling->period->year, $req->iplBilling->period->month)->translatedFormat('F Y') }}
                                            — {{ $req->iplBilling->houseBlock?->block_code }}
                                        </div>
                                    @elseif($req->period_year && $req->period_month)
                                        <div class="text-xs" style="color:#5c6368;">
                                            {{ \Carbon\Carbon::create($req->period_year, $req->period_month)->translatedFormat('F Y') }} <span style="color:#161e2d;">(bayar di muka)</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mb-1" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Donasi</span>
                                    @if($req->campaign)
                                        <div class="text-xs" style="color:#5c6368;">{{ Str::limit($req->campaign->name, 30) }}</div>
                                    @endif
                                @endif
                                @if($req->payment_method)
                                    <div class="text-xs mt-0.5" style="color:#a3abb0;">
                                        {{ ucfirst($req->payment_method) }}
                                        @if($req->bank_name) — {{ $req->bank_name }} @endif
                                        @if($req->reference_number)<br><span style="color:#a3abb0;">Ref: {{ $req->reference_number }}</span>@endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-semibold" style="color:#161e2d;">
                                Rp {{ number_format($req->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 hidden md:table-cell">
                                @if($req->proof_photo)
                                    <a href="{{ Storage::disk('public')->url($req->proof_photo) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-lg transition-colors"
                                        style="background:rgba(21,99,223,0.08);color:#161e2d;border:1px solid rgba(21,99,223,0.2);"
                                        onmouseover="this.style.background='rgba(21,99,223,0.15)'" onmouseout="this.style.background='rgba(21,99,223,0.08)'">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs" style="color:#a3abb0;">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $statusStyle = match($req->status) {
                                        'pending'   => 'background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);',
                                        'confirmed' => 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);',
                                        'rejected'  => 'background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);',
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
                                    <div class="text-xs mt-1" style="color:#a3abb0;">{{ $req->admin_notes }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                @if($req->status === 'pending')
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="confirmModal({{ $req->id }})"
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                        style="background:#1563df;color:#ffffff;"
                                        onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
                                        Konfirmasi
                                    </button>
                                    <button wire:click="reject({{ $req->id }})" wire:confirm="Tolak permintaan ini?"
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium transition-colors"
                                        style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);"
                                        onmouseover="this.style.background='rgba(192,69,59,0.2)'" onmouseout="this.style.background='rgba(192,69,59,0.1)'">
                                        Tolak
                                    </button>
                                </div>
                                @else
                                    <span class="text-xs" style="color:#a3abb0;">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm" style="color:#a3abb0;">Tidak ada permintaan pembayaran.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Mobile cards --}}
            <div class="md:hidden divide-y" style="border-color:#f7f7f7;">
                @forelse($requests as $req)
                <div wire:key="mobile-req-{{ $req->id }}" class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div>
                            <div class="font-medium" style="color:#161e2d;">{{ $req->resident->name }}</div>
                            <div class="text-xs" style="color:#a3abb0;">{{ $req->created_at->diffForHumans() }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-semibold font-mono" style="color:#161e2d;">Rp {{ number_format($req->amount, 0, ',', '.') }}</div>
                            @php
                                $statusStyleMobile = match($req->status) {
                                    'pending'   => 'background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.2);',
                                    'confirmed' => 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);',
                                    'rejected'  => 'background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);',
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
                            <div class="text-xs" style="color:#a3abb0;">Tipe & Referensi</div>
                            <div class="mt-0.5">
                                @if($req->type === 'ipl')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mb-1" style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">IPL</span>
                                    @if($req->iplBilling?->period)
                                        <div class="text-xs" style="color:#5c6368;">
                                            {{ \Carbon\Carbon::create($req->iplBilling->period->year, $req->iplBilling->period->month)->translatedFormat('F Y') }}
                                            — {{ $req->iplBilling->houseBlock?->block_code }}
                                        </div>
                                    @elseif($req->period_year && $req->period_month)
                                        <div class="text-xs" style="color:#5c6368;">
                                            {{ \Carbon\Carbon::create($req->period_year, $req->period_month)->translatedFormat('F Y') }} <span style="color:#161e2d;">(bayar di muka)</span>
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mb-1" style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);">Donasi</span>
                                    @if($req->campaign)
                                        <div class="text-xs" style="color:#5c6368;">{{ Str::limit($req->campaign->name, 30) }}</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-xs" style="color:#a3abb0;">Metode</div>
                            <div class="mt-0.5">
                                @if($req->payment_method)
                                    <div class="text-xs" style="color:#5c6368;">
                                        {{ ucfirst($req->payment_method) }}
                                        @if($req->bank_name) — {{ $req->bank_name }} @endif
                                        @if($req->reference_number)<br><span style="color:#a3abb0;">Ref: {{ $req->reference_number }}</span>@endif
                                    </div>
                                @else
                                    <span class="text-xs" style="color:#a3abb0;">—</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-2">
                            <div class="text-xs" style="color:#a3abb0;">Bukti Transfer</div>
                            <div class="mt-0.5">
                                @if($req->proof_photo)
                                    <a href="{{ Storage::disk('public')->url($req->proof_photo) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-lg"
                                        style="background:rgba(21,99,223,0.08);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs" style="color:#a3abb0;">—</span>
                                @endif
                            </div>
                        </div>
                        @if($req->admin_notes)
                        <div class="col-span-2">
                            <div class="text-xs" style="color:#a3abb0;">Catatan Admin</div>
                            <div class="text-xs mt-0.5" style="color:#5c6368;">{{ $req->admin_notes }}</div>
                        </div>
                        @endif
                    </div>

                    @if($req->status === 'pending')
                    <div class="flex items-center gap-2">
                        <button wire:click="confirmModal({{ $req->id }})"
                            class="flex-1 px-3 py-2 rounded-lg text-xs font-medium text-center"
                            style="background:#1563df;color:#ffffff;">
                            Konfirmasi
                        </button>
                        <button wire:click="reject({{ $req->id }})" wire:confirm="Tolak permintaan ini?"
                            class="flex-1 px-3 py-2 rounded-lg text-xs font-medium text-center"
                            style="background:rgba(192,69,59,0.1);color:#c0453b;border:1px solid rgba(192,69,59,0.2);">
                            Tolak
                        </button>
                    </div>
                    @endif
                </div>
                @empty
                <div class="px-5 py-12 text-center text-sm" style="color:#a3abb0;">Tidak ada permintaan pembayaran.</div>
                @endforelse
            </div>

            <div class="px-5 py-3" style="border-top:1px solid #f7f7f7;">{{ $requests->links() }}</div>
        </div>

        {{-- Confirm Modal --}}
        @if($isConfirmModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isConfirmModalOpen',false)"></div>
            <div class="relative rounded-2xl shadow-2xl w-full max-w-md" style="background:#ffffff;border:1px solid #d9d9d9;">
                <div class="px-6 py-4 rounded-t-2xl" style="background:#f7f7f7;border-bottom:1px solid rgba(21,99,223,0.35);">
                    <h3 class="font-bold" style="color:#161e2d;font-family:'Manrope',serif;">
                        {{ $confirmingType === 'donation' ? 'Konfirmasi Donasi' : 'Konfirmasi Pembayaran' }}
                    </h3>
                    <p class="text-xs mt-0.5" style="color:#161e2d;">
                        {{ $confirmingType === 'donation'
                            ? 'Donasi akan dicatat otomatis ke kas ' . ($confirmingOrg === 'dkm' ? 'DKM' : 'Perumahan') . '.'
                            : 'Pembayaran IPL akan dicatat otomatis ke kas Perumahan.' }}
                    </p>
                </div>
                <div class="px-6 py-5 space-y-4">
                    @php $cr = $this->confirmingRequest; @endphp
                    @if($cr)
                    {{-- Detail pembayaran --}}
                    <div class="rounded-xl p-4 space-y-2.5" style="background:#ffffff;border:1px solid #e4e4e4;">
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#a3abb0;">Penghuni</span>
                            <span class="font-medium text-right" style="color:#161e2d;">{{ $cr->resident?->name ?? '—' }}</span>
                        </div>
                        @if($cr->type === 'ipl')
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#a3abb0;">Periode</span>
                            <span class="font-medium text-right" style="color:#161e2d;">
                                {{ $cr->iplBilling?->period?->period_label
                                    ?? (($cr->period_year && $cr->period_month) ? \Carbon\Carbon::create($cr->period_year,$cr->period_month)->translatedFormat('F Y') : '—') }}
                            </span>
                        </div>
                        @if($cr->iplBilling?->houseBlock)
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#a3abb0;">Blok</span>
                            <span class="font-medium text-right" style="color:#161e2d;">{{ $cr->iplBilling->houseBlock->block_code }}</span>
                        </div>
                        @endif
                        @else
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#a3abb0;">Program</span>
                            <span class="font-medium text-right" style="color:#161e2d;">{{ $cr->campaign?->name ?? 'Donasi umum' }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#a3abb0;">Jumlah</span>
                            <span class="font-bold text-right" style="color:#161e2d;">Rp {{ number_format((float)$cr->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#a3abb0;">Metode</span>
                            <span class="font-medium text-right capitalize" style="color:#161e2d;">
                                {{ $cr->payment_method }}{{ $cr->bank_name ? ' — '.$cr->bank_name : '' }}
                            </span>
                        </div>
                        @if($cr->reference_number)
                        <div class="flex justify-between gap-3 text-sm">
                            <span style="color:#a3abb0;">No. Referensi</span>
                            <span class="font-medium text-right" style="color:#161e2d;">{{ $cr->reference_number }}</span>
                        </div>
                        @endif
                        @if($cr->notes)
                        <div class="text-sm pt-1" style="border-top:1px dashed #e4e4e4;">
                            <span style="color:#a3abb0;">Catatan penghuni:</span>
                            <span style="color:#161e2d;">{{ $cr->notes }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Bukti pembayaran --}}
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Bukti Pembayaran</label>
                        @if($cr->proof_photo)
                            <a href="{{ Storage::disk('public')->url($cr->proof_photo) }}" target="_blank" class="block rounded-xl overflow-hidden" style="border:1px solid #e4e4e4;">
                                <img src="{{ Storage::disk('public')->url($cr->proof_photo) }}" alt="Bukti pembayaran"
                                    class="w-full max-h-64 object-contain" style="background:#ffffff;">
                            </a>
                            <p class="text-xs mt-1" style="color:#a3abb0;">Klik untuk memperbesar di tab baru.</p>
                        @else
                            <div class="rounded-xl px-3 py-4 text-sm text-center" style="background:#ffffff;border:1px dashed #e4e4e4;color:#a3abb0;">
                                Tidak ada bukti pembayaran diunggah.
                            </div>
                        @endif
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#5c6368;">Catatan Admin (opsional)</label>
                        <textarea wire:model="adminNotes" rows="2" style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #e4e4e4;">
                        <button type="button" wire:click="$set('isConfirmModalOpen',false)"
                            class="px-4 py-2 text-sm rounded-xl font-medium"
                            style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;">Batal</button>
                        <button wire:click="confirm"
                            class="px-5 py-2 text-sm rounded-xl font-semibold"
                            style="background:#1563df;color:#ffffff;"
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
