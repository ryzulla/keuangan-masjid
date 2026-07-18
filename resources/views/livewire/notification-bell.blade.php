<div x-data="{ open: false }" class="relative" @click.outside="open = false" @keydown.escape.window="open = false">
    {{-- Bell button --}}
    <button @click="open = !open" type="button" class="relative p-2 rounded-lg transition-colors" style="color:#586359;"
        onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'" aria-label="Notifikasi">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if($this->badgeCount > 0)
            <span class="absolute -top-0.5 -right-0.5 flex h-4 min-w-4 px-0.5 items-center justify-center rounded-full text-xs font-bold"
                style="background:#A9741A;color:#17231E;font-size:9px;">{{ $this->badgeCount > 9 ? '9+' : $this->badgeCount }}</span>
        @endif
    </button>

    {{--
        Panel — DIFFERENT per breakpoint:
        · Mobile (< sm): full-screen page overlay (fixed inset-0), scroll di dalam.
        · Desktop (sm+): dropdown ringkas yang menempel di lonceng.
    --}}
    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 sm:scale-95"
        class="z-50 overflow-hidden shadow-2xl flex flex-col
               fixed inset-0 w-full h-full
               sm:absolute sm:inset-auto sm:right-0 sm:top-full sm:mt-1 sm:w-96 sm:h-auto sm:max-h-[80vh] sm:rounded-xl sm:origin-top-right"
        style="background:#ffffff;border:1px solid #E0DFD4;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 shrink-0" style="border-bottom:1px solid #F1F3EC;">
            <span class="text-base sm:text-sm font-semibold" style="color:#17231E;">Notifikasi</span>
            <button type="button" @click="open = false" class="p-1 -mr-1 rounded-lg transition-colors" style="color:#909A8F;"
                onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#909A8F'" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Actions bar --}}
        @if($this->notifications->isNotEmpty())
        <div class="flex items-center gap-4 px-4 py-2.5 shrink-0" style="border-bottom:1px solid #ffffff;">
            <button wire:click="markAllRead" wire:loading.attr="disabled"
                class="inline-flex items-center gap-1.5 text-xs transition-colors {{ $this->unreadCount > 0 ? '' : 'opacity-40 pointer-events-none' }}"
                style="color:#17231E;" onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#164A40'">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Tandai semua dibaca
            </button>
            <button wire:click="clearAll" wire:confirm="Hapus semua notifikasi? Tindakan ini tidak bisa dibatalkan."
                class="inline-flex items-center gap-1.5 text-xs ml-auto transition-colors"
                style="color:#909A8F;" onmouseover="this.style.color='#B0402C'" onmouseout="this.style.color='#909A8F'">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Bersihkan
            </button>
        </div>
        @endif

        {{-- Pending payment banner (admin only) --}}
        @if($this->pendingPayCount > 0)
            <a href="/payment-requests" wire:navigate @click="open=false"
                class="flex items-center gap-3 px-4 py-3.5 border-l-2 shrink-0" style="border-color:#A9741A;background:rgba(169,116,26,0.05);border-bottom:1px solid #ffffff;">
                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:rgba(169,116,26,0.15);">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="#A9741A"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold" style="color:#A9741A;">{{ $this->pendingPayCount }} Konfirmasi Menunggu</p>
                    <p class="text-xs" style="color:#909A8F;">Pembayaran penghuni perlu diverifikasi</p>
                </div>
            </a>
        @endif

        {{-- List --}}
        <div class="flex-1 overflow-y-auto overscroll-contain">
            @forelse($this->notifications as $notif)
                @php $data = $notif->data; @endphp
                <button type="button" wire:key="notif-{{ $notif->id }}"
                    wire:click="openNotification('{{ $notif->id }}')"
                    class="w-full text-left px-4 py-4 sm:py-3 transition-colors {{ $notif->read_at ? '' : 'border-l-2' }}"
                    style="{{ $notif->read_at ? '' : 'border-color:#17231E;background:rgba(22,74,64,0.04);' }}border-bottom:1px solid #ffffff;"
                    onmouseover="this.style.backgroundColor='rgba(255,255,255,0.03)'"
                    onmouseout="this.style.backgroundColor='{{ $notif->read_at ? 'transparent' : 'rgba(22,74,64,0.04)' }}'">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm sm:text-xs font-semibold" style="color:{{ $notif->read_at ? '#586359' : '#164A40' }};">{{ $data['title'] ?? 'Notifikasi' }}</p>
                            <p class="text-xs mt-0.5 leading-relaxed break-words" style="color:#909A8F;">{{ $data['message'] ?? '' }}</p>
                            <p class="text-xs mt-1" style="color:#909A8F;">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notif->read_at)
                            <span class="w-2 h-2 rounded-full shrink-0 mt-1.5" style="background:#164A40;"></span>
                        @endif
                    </div>
                </button>
            @empty
                @if($this->pendingPayCount === 0)
                    <div class="flex flex-col items-center justify-center h-full px-4 py-16 text-center">
                        <svg class="w-12 h-12 mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <p class="text-sm" style="color:#909A8F;">Belum ada notifikasi</p>
                    </div>
                @endif
            @endforelse
        </div>
    </div>
</div>
