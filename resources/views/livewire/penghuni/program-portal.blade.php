<div>
    @if(session('success'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="rounded-2xl p-6 mb-6" style="background:linear-gradient(135deg,#ffffff 0%,#ffffff 62%);border:1px solid rgba(16,24,40,0.35);">
        <h3 class="font-bold text-lg" style="color:#111827;font-family:'IBM Plex Sans',serif;">Program & Kampanye Aktif</h3>
        <p class="text-sm mt-1" style="color:#111827;">Dukung program perumahan dan masjid dengan berdonasi</p>
    </div>

    @forelse($campaigns as $campaign)
    @php
        $collected = $campaign->donations->sum(fn($d) => $d->transaction?->amount ?? 0)
                   + $campaign->residentPaymentRequests->sum('amount');
        $percent   = $campaign->target_amount > 0 ? min(100, ($collected / $campaign->target_amount) * 100) : 0;
        $orgStyle  = $campaign->organization_type === 'dkm'
            ? 'background:rgba(20,184,166,0.12);color:#0d9488;border:1px solid rgba(20,184,166,0.25);'
            : 'background:rgba(16,24,40,0.12);color:#111827;border:1px solid rgba(16,24,40,0.25);';
        $orgLabel  = $campaign->organization_type === 'dkm' ? 'DKM Masjid' : 'Perumahan';
    @endphp
    @if($loop->first)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @endif

        <div class="rounded-2xl overflow-hidden flex flex-col" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            {{-- Image (links to detail page) --}}
            <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
               class="block w-full h-40 shrink-0 overflow-hidden relative" style="background:#ffffff;">
                @if($campaign->image)
                    <img src="{{ Storage::disk('public')->url($campaign->image) }}" alt="{{ $campaign->name }}"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold" style="color:rgba(16,24,40,0.2);">
                        {{ strtoupper(substr($campaign->name, 0, 1)) }}
                    </div>
                @endif
            </a>

            {{-- Body --}}
            <div class="p-4 flex flex-col flex-1">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
                       class="font-semibold text-sm leading-snug hover:underline" style="color:#1d2939;">{{ $campaign->name }}</a>
                    <span class="shrink-0 text-xs px-2 py-0.5 rounded-full font-medium" style="{{ $orgStyle }}">{{ $orgLabel }}</span>
                </div>

                @if($campaign->description)
                    <p class="text-xs mb-3 line-clamp-2" style="color:#7c8698;">{{ strip_tags($campaign->description) }}</p>
                @endif

                {{-- Progress --}}
                <div class="mb-3 mt-auto">
                    <div class="flex justify-between text-xs mb-1" style="color:#7c8698;">
                        <span>Terkumpul</span>
                        <span>{{ number_format($percent, 0) }}%</span>
                    </div>
                    <div class="w-full rounded-full h-1.5" style="background:#e4e7ec;">
                        <div class="h-1.5 rounded-full" style="width:{{ $percent }}%;background:linear-gradient(90deg,#111827,#111827);"></div>
                    </div>
                    <div class="flex justify-between text-xs mt-1">
                        <span style="color:#111827;">Rp {{ number_format($collected, 0, ',', '.') }}</span>
                        <span style="color:#98a2b3;">dari Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
                        class="flex-1 py-2 rounded-xl text-xs font-medium text-center transition-colors"
                        style="background:rgba(16,24,40,0.1);color:#111827;border:1px solid rgba(16,24,40,0.2);">
                        Lihat Detail
                    </a>
                    <button wire:click="openDonate({{ $campaign->id }})"
                        class="flex-1 py-2 rounded-xl text-sm font-semibold transition-colors"
                        style="background:#111827;color:#ffffff;">
                        Donasi
                    </button>
                </div>
            </div>
        </div>

    @if($loop->last)
    </div>
    @endif
    @empty
        <div class="rounded-2xl p-6 sm:p-12 text-center" style="background:#ffffff;border:1px solid #e4e7ec;box-shadow:0 1px 2px rgba(16,24,40,0.04),0 8px 20px -8px rgba(16,24,40,0.06);">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            <p class="font-medium text-sm" style="color:#98a2b3;">Belum ada program aktif saat ini</p>
        </div>
    @endforelse


    {{-- ─── Donation Modal (quick donate from list, 2 columns) ─── --}}
    @if($isDonateModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-3">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="$set('isDonateModalOpen', false)"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-xl flex flex-col"
             style="background:#ffffff;border:1px solid #d0d5dd;max-height:92vh;">

            <div class="flex items-center justify-between px-6 py-4 shrink-0 rounded-t-2xl"
                 style="background:#f2f4f7;border-bottom:1px solid rgba(16,24,40,0.35);">
                <h3 class="font-bold" style="color:#1d2939;font-family:'IBM Plex Sans',serif;">Form Donasi</h3>
                <button wire:click="$set('isDonateModalOpen', false)" class="p-1 rounded-lg" style="color:#1d2939;">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="overflow-y-auto flex-1">
                <form wire:submit="submitDonation" id="quickDonateForm" class="px-6 py-5">

                    {{-- Asal donatur otomatis: Warga --}}
                    <div class="flex items-center gap-3 mb-4 px-4 py-3 rounded-xl" style="background:rgba(16,24,40,0.06);border:1px solid rgba(16,24,40,0.2);">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:rgba(16,24,40,0.15);">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#111827"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs" style="color:#7c8698;">Asal Donatur</p>
                            <p class="text-sm font-semibold truncate" style="color:#111827;">{{ auth('resident')->user()?->name }}</p>
                        </div>
                        <span class="shrink-0 text-xs px-2.5 py-1 rounded-full font-semibold" style="background:rgba(16,24,40,0.2);color:#111827;border:1px solid rgba(16,24,40,0.3);">Warga</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Nama Donatur <span style="color:#c0453b;">*</span></label>
                            <input type="text" wire:model="donorName"
                                style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                            @error('donorName') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Jumlah Donasi (Rp) <span style="color:#c0453b;">*</span></label>
                            <input type="number" wire:model="amount" placeholder="Min. 1.000" min="1000"
                                style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
                            @error('amount') <p class="text-xs mt-1" style="color:#c0453b;">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Metode Pembayaran</label>
                            <select wire:model.live="paymentMethod"
                                style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                                <option value="transfer">Transfer Bank</option>
                                <option value="cash">Tunai</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Nama Bank</label>
                            <input type="text" wire:model="bankName"
                                placeholder="{{ $paymentMethod === 'transfer' ? 'BCA, Mandiri...' : '—' }}"
                                {{ $paymentMethod !== 'transfer' ? 'disabled' : '' }}
                                style="background:{{ $paymentMethod !== 'transfer' ? '#ffffff' : '#ffffff' }};border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1.5" style="color:#475467;">No. Referensi</label>
                            <input type="text" wire:model="referenceNum" placeholder="No. transaksi"
                                style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Foto Bukti</label>
                            <label class="flex items-center gap-2 px-3 py-2.5 rounded-xl cursor-pointer"
                                style="background:#ffffff;border:1px dashed #d0d5dd;height:42px;">
                                <svg class="w-4 h-4 shrink-0" style="color:#98a2b3;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs truncate" style="color:#7c8698;">
                                    @if($proofPhoto) {{ $proofPhoto->getClientOriginalName() }} @else Pilih foto @endif
                                </span>
                                <input type="file" wire:model="proofPhoto" accept="image/*" class="hidden">
                            </label>
                            <div wire:loading wire:target="proofPhoto" class="text-xs mt-1" style="color:#111827;">Mengunggah...</div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-medium mb-1.5" style="color:#475467;">Catatan</label>
                        <textarea wire:model="notes" rows="2" placeholder="Pesan atau keterangan (opsional)"
                            style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:none;"></textarea>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 shrink-0 flex gap-3" style="border-top:1px solid #e4e7ec;">
                <button type="button" wire:click="$set('isDonateModalOpen', false)"
                    class="flex-1 py-2.5 rounded-xl text-sm font-medium"
                    style="background:#f5f6f8;color:#344054;border:1px solid #d0d5dd;">Batal</button>
                <button type="submit" form="quickDonateForm"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold"
                    style="background:#111827;color:#ffffff;" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="submitDonation">Kirim Donasi</span>
                    <span wire:loading wire:target="submitDonation" class="inline-flex items-center gap-1">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> Mengirim...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
