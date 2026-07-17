<div>
    @if(session('success'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="rounded-2xl p-6 mb-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(21,99,223,0.35);">
        <h3 class="font-bold text-lg" style="color:#161e2d;font-family:'Manrope',serif;">Program & Kampanye Aktif</h3>
        <p class="text-sm mt-1" style="color:#161e2d;">Dukung program perumahan dan masjid dengan berdonasi</p>
    </div>

    @forelse($campaigns as $campaign)
    @php
        $collected = $campaign->donations->sum(fn($d) => $d->transaction?->amount ?? 0);
        $percent   = $campaign->target_amount > 0 ? min(100, ($collected / $campaign->target_amount) * 100) : 0;
        $orgStyle  = $campaign->organization_type === 'dkm'
            ? 'background:rgba(20,184,166,0.12);color:#0d9488;border:1px solid rgba(20,184,166,0.25);'
            : 'background:rgba(21,99,223,0.12);color:#161e2d;border:1px solid rgba(21,99,223,0.25);';
        $orgLabel  = $campaign->organization_type === 'dkm' ? 'DKM Masjid' : 'Perumahan';
    @endphp
    @if($loop->first)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @endif

        <div class="rounded-2xl overflow-hidden flex flex-col" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            {{-- Image (links to detail page) --}}
            <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
               class="block w-full h-40 shrink-0 overflow-hidden relative" style="background:#ffffff;">
                @if($campaign->image)
                    <img src="{{ Storage::disk('public')->url($campaign->image) }}" alt="{{ $campaign->name }}"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold" style="color:rgba(21,99,223,0.2);">
                        {{ strtoupper(substr($campaign->name, 0, 1)) }}
                    </div>
                @endif
            </a>

            {{-- Body --}}
            <div class="p-4 flex flex-col flex-1">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
                       class="font-semibold text-sm leading-snug hover:underline" style="color:#161e2d;">{{ $campaign->name }}</a>
                    <span class="shrink-0 text-xs px-2 py-0.5 rounded-full font-medium" style="{{ $orgStyle }}">{{ $orgLabel }}</span>
                </div>

                @if($campaign->description)
                    <p class="text-xs mb-3 line-clamp-2" style="color:#a3abb0;">{{ strip_tags($campaign->description) }}</p>
                @endif

                {{-- Progress --}}
                <div class="mb-3 mt-auto">
                    <div class="flex justify-between text-xs mb-1" style="color:#a3abb0;">
                        <span>Terkumpul</span>
                        <span>{{ number_format($percent, 0) }}%</span>
                    </div>
                    <div class="w-full rounded-full h-1.5" style="background:#e4e4e4;">
                        <div class="h-1.5 rounded-full" style="width:{{ $percent }}%;background:linear-gradient(90deg,#1563df,#1563df);"></div>
                    </div>
                    <div class="flex justify-between text-xs mt-1">
                        <span style="color:#161e2d;">Rp {{ number_format($collected, 0, ',', '.') }}</span>
                        <span style="color:#a3abb0;">dari Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
                        class="flex-1 py-2 rounded-xl text-xs font-medium text-center transition-colors"
                        style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                        Lihat Detail
                    </a>
                    <button wire:click="openDonate({{ $campaign->id }})"
                        class="flex-1 py-2 rounded-xl text-sm font-semibold transition-colors"
                        style="background:#1563df;color:#ffffff;">
                        Donasi
                    </button>
                </div>
            </div>
        </div>

    @if($loop->last)
    </div>
    @endif
    @empty
        <div class="rounded-2xl p-6 sm:p-12 text-center" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#1563df"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <p class="font-medium text-sm" style="color:#a3abb0;">Belum ada program aktif saat ini</p>
        </div>
    @endforelse


    {{-- ─── Donation Modal (full form: uang/barang, penghuni/hamba allah/donatur lain) ─── --}}
    @if($isDonateModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data x-on:keydown.escape.window="$wire.set('isDonateModalOpen', false)">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isDonateModalOpen', false)"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #d9d9d9;max-height:92vh;" @click.stop>
            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl" style="background:#f7f7f7;border-bottom:1px solid rgba(21,99,223,0.35);">
                <h3 class="font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Form Donasi</h3>
                <button wire:click="$set('isDonateModalOpen', false)" class="p-1 rounded-lg" style="color:#161e2d;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if($errors->any())
                <div class="mx-6 mt-4 rounded-xl p-3 text-sm" style="background:rgba(199,125,26,0.1);border:1px solid rgba(199,125,26,0.3);color:#c77d1a;">
                    <ul class="list-disc pl-4 space-y-0.5 text-xs">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form wire:submit="submitDonation" class="overflow-y-auto px-6 py-5 space-y-4">

                {{-- Jenis Donasi (uang/barang) --}}
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#5c6368;">Jenis Donasi <span style="color:#c0453b;">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 px-4 py-3 rounded-xl cursor-pointer transition-colors"
                               style="{{ $donationForm === 'uang' ? 'background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.4);' : 'background:#ffffff;border:1px solid #e4e4e4;' }}">
                            <input type="radio" wire:model.live="donationForm" value="uang" style="accent-color:#12805c;">
                            <span class="text-sm font-medium" style="{{ $donationForm === 'uang' ? 'color:#12805c;' : 'color:#5c6368;' }}">Uang</span>
                        </label>
                        <label class="flex items-center gap-2 px-4 py-3 rounded-xl cursor-pointer transition-colors"
                               style="{{ $donationForm === 'barang' ? 'background:rgba(199,125,26,0.1);border:1px solid rgba(199,125,26,0.4);' : 'background:#ffffff;border:1px solid #e4e4e4;' }}">
                            <input type="radio" wire:model.live="donationForm" value="barang" style="accent-color:#c77d1a;">
                            <span class="text-sm font-medium" style="{{ $donationForm === 'barang' ? 'color:#c77d1a;' : 'color:#5c6368;' }}">Barang</span>
                        </label>
                    </div>
                </div>

                {{-- Asal Donatur --}}
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#5c6368;">Asal Donatur <span style="color:#c0453b;">*</span></label>
                    <div class="grid grid-cols-3 gap-2">
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'penghuni' ? 'background:rgba(21,99,223,0.1);border:1px solid rgba(21,99,223,0.4);' : 'background:#ffffff;border:1px solid #e4e4e4;' }}">
                            <input type="radio" wire:model.live="donorType" value="penghuni" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'penghuni' ? 'color:#161e2d;' : 'color:#5c6368;' }}">Penghuni</span>
                        </label>
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'hamba_allah' ? 'background:rgba(21,99,223,0.1);border:1px solid rgba(21,99,223,0.4);' : 'background:#ffffff;border:1px solid #e4e4e4;' }}">
                            <input type="radio" wire:model.live="donorType" value="hamba_allah" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'hamba_allah' ? 'color:#161e2d;' : 'color:#5c6368;' }}">Hamba Allah</span>
                        </label>
                        <label class="flex items-center justify-center px-3 py-2.5 rounded-xl cursor-pointer text-center"
                               style="{{ $donorType === 'luar' ? 'background:rgba(99,102,241,0.1);border:1px solid rgba(99,102,241,0.4);' : 'background:#ffffff;border:1px solid #e4e4e4;' }}">
                            <input type="radio" wire:model.live="donorType" value="luar" class="sr-only">
                            <span class="text-sm" style="{{ $donorType === 'luar' ? 'color:#4f46e5;' : 'color:#5c6368;' }}">Donatur Lain</span>
                        </label>
                    </div>
                </div>

                {{-- Detail sesuai asal donatur --}}
                @if($donorType === 'penghuni')
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl" style="background:rgba(21,99,223,0.06);border:1px solid rgba(21,99,223,0.2);">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:rgba(21,99,223,0.15);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#1563df"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs" style="color:#a3abb0;">Asal Donatur</p>
                        <p class="text-sm font-semibold truncate" style="color:#161e2d;">{{ auth('resident')->user()?->name }}</p>
                    </div>
                    <span class="shrink-0 text-xs px-2.5 py-1 rounded-full font-semibold" style="background:rgba(21,99,223,0.2);color:#161e2d;border:1px solid rgba(21,99,223,0.3);">Warga</span>
                </div>
                @elseif($donorType === 'hamba_allah')
                <div class="px-3 py-2.5 rounded-xl text-xs" style="background:#ffffff;border:1px solid #f7f7f7;color:#a3abb0;">
                    Donasi dicatat atas nama <strong style="color:#5c6368;">Hamba Allah</strong> (anonim).
                </div>
                @else
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Nama Donatur <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="donorName" placeholder="Nama lengkap donatur"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    @error('donorName')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Uang fields --}}
                @if($donationForm === 'uang')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Jumlah (Rp) <span style="color:#c0453b;">*</span></label>
                        <input type="number" wire:model="amount" min="1000" step="1000" placeholder="0"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        @error('amount')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Tipe Donasi</label>
                        <select wire:model="donationType"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                            <option value="infaq">Infaq / Sedekah</option>
                            <option value="zakat">Zakat</option>
                            <option value="wakaf">Wakaf</option>
                            <option value="donasi">Donasi Umum</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Metode Pembayaran</label>
                    <select wire:model.live="paymentMethod"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                        <option value="transfer">Transfer Bank</option>
                        <option value="cash">Tunai</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Nama Bank</label>
                        <input type="text" wire:model="bankName"
                            placeholder="{{ $paymentMethod === 'transfer' ? 'BCA, Mandiri...' : '—' }}"
                            {{ $paymentMethod !== 'transfer' ? 'disabled' : '' }}
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#5c6368;">No. Referensi</label>
                        <input type="text" wire:model="referenceNum" placeholder="No. transaksi"
                            style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Foto Bukti (Opsional)</label>
                    <div class="rounded-xl p-3 flex flex-col gap-2" style="background:#ffffff;border:1px dashed #d9d9d9;">
                        <input type="file" wire:model="proofPhoto" accept="image/*"
                               class="block w-full text-xs" style="color:#a3abb0;">
                        <div wire:loading wire:target="proofPhoto" class="text-xs" style="color:#161e2d;">Mengunggah...</div>
                        @if($proofPhoto)
                            <img src="{{ $proofPhoto->temporaryUrl() }}"
                                 class="rounded-xl object-cover mt-1" style="max-height:140px;max-width:100%;border:1px solid #e4e4e4;">
                        @endif
                    </div>
                    @error('proofPhoto')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @endif

                {{-- Barang fields --}}
                @if($donationForm === 'barang')
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Nama / Deskripsi Barang <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="itemDescription" placeholder="Contoh: Semen Portland, Kursi Plastik, dll"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    @error('itemDescription')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Jumlah / Satuan <span style="color:#c0453b;">*</span></label>
                    <input type="text" wire:model="itemQuantity" placeholder="Contoh: 10 karung, 5 buah, 2 meter"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
                    @error('itemQuantity')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                <div x-data="{ previewItem: null }">
                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Foto Barang (Opsional)</label>
                    <div class="rounded-xl p-3 flex flex-col gap-2" style="background:#ffffff;border:1px dashed #d9d9d9;">
                        <input type="file" wire:model="itemPhoto" accept="image/*"
                               @change="previewItem = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                               class="block w-full text-xs" style="color:#a3abb0;">
                        <div wire:loading wire:target="itemPhoto" class="text-xs" style="color:#161e2d;">Mengunggah...</div>
                        <img x-show="previewItem" :src="previewItem" x-transition
                             class="rounded-xl object-cover mt-1" style="max-height:140px;max-width:100%;border:1px solid #e4e4e4;">
                    </div>
                    @error('itemPhoto')<p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p>@enderror
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#5c6368;">Catatan (Opsional)</label>
                    <textarea wire:model="notes" rows="2" placeholder="Pesan atau keterangan tambahan"
                        style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;resize:none;"
                        onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" wire:click="$set('isDonateModalOpen', false)"
                        class="px-4 py-2 text-sm rounded-xl font-medium"
                        style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;"
                        onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">Batal</button>
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm rounded-xl font-semibold disabled:opacity-50"
                        style="background:#1563df;color:#ffffff;">
                        <span wire:loading.remove>Kirim Donasi</span>
                        <span wire:loading class="flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            Mengirim...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
