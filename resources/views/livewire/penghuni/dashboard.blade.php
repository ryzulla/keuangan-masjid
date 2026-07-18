<div class="space-y-6">

    @if(session('success'))
        <div class="rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ═══ HERO — Denah Warga ═══ --}}
    <div class="pp-reveal relative overflow-hidden rounded-[22px] p-6 sm:p-7" style="background:linear-gradient(150deg,#0B2E28,#164A40 72%);color:#F4EFE2;">
        <div class="pp-denah"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
            <div class="min-w-0">
                <div class="flex items-center gap-3.5">
                    @if($resident->photo)
                        <img src="{{ Storage::disk('public')->url($resident->photo) }}" alt="{{ $resident->name }}"
                            class="w-14 h-14 rounded-2xl object-cover shrink-0" style="border:1.5px solid rgba(244,239,226,0.4);">
                    @else
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 pp-display"
                            style="background:rgba(244,239,226,0.14);color:#F4EFE2;font-weight:600;font-size:22px;border:1px solid rgba(244,239,226,0.25);">
                            {{ strtoupper(substr($resident->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p style="font-size:12.5px;color:rgba(244,239,226,0.78);">Assalamualaikum, selamat datang</p>
                        <h2 class="pp-display truncate" style="font-weight:500;font-size:26px;line-height:1.12;color:#F4EFE2;margin-top:2px;">{{ $resident->name }}</h2>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 mt-4">
                    @forelse($resident->currentAssignments as $assignment)
                        <span class="inline-flex items-center gap-2 rounded-lg" style="background:#1C5749;border:1px solid rgba(244,239,226,0.22);padding:5px 11px 5px 8px;">
                            <span class="w-5 h-5 rounded-md flex items-center justify-center shrink-0" style="background:rgba(244,239,226,0.16);">
                                <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="#F4EFE2" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5M5 9v11h14V9"/></svg>
                            </span>
                            <span class="leading-tight">
                                <span class="block pp-display" style="font-weight:600;font-size:14px;letter-spacing:.02em;color:#F4EFE2;">{{ $assignment->houseBlock?->block_code ?? '—' }}</span>
                                <span class="block" style="font-size:9.5px;text-transform:uppercase;letter-spacing:.1em;color:rgba(244,239,226,0.6);">{{ ucfirst($assignment->ownership_type) }}</span>
                            </span>
                        </span>
                    @empty
                        <span class="text-xs" style="color:rgba(244,239,226,0.7);">Belum ada blok terdaftar</span>
                    @endforelse
                </div>
                <p style="margin-top:13px;font-size:12.5px;color:rgba(244,239,226,0.68);">{{ $resident->familyMembers->count() }} anggota keluarga terdaftar</p>
            </div>

            {{-- Ringkasan IPL --}}
            <div class="rounded-2xl shrink-0 w-full sm:w-auto sm:min-w-[220px]" style="background:rgba(244,239,226,0.96);color:#17231E;padding:16px 18px;box-shadow:0 18px 34px -20px rgba(0,0,0,0.55);">
                @if($totalOutstanding > 0)
                    <p class="pp-eyebrow">Tagihan IPL Aktif</p>
                    <p class="pp-rp" style="font-weight:600;font-size:25px;color:#B0402C;margin:5px 0 2px;">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</p>
                    <p style="font-size:12px;color:#586359;">{{ $unpaidBillings->count() }} bulan belum lunas</p>
                    <a href="{{ route('penghuni.ipl') }}" wire:navigate class="mt-3 flex items-center justify-center gap-2 rounded-[10px] transition-colors"
                        style="background:#164A40;color:#F4EFE2;padding:10px;font-weight:600;font-size:13.5px;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#164A40'">
                        Bayar IPL Sekarang
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                @else
                    <p class="pp-eyebrow">Status IPL</p>
                    <div class="flex items-center gap-2.5 mt-2">
                        <span class="w-9 h-9 rounded-full flex items-center justify-center shrink-0" style="background:rgba(18,128,92,0.12);">
                            <svg class="w-5 h-5" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="pp-display" style="font-weight:600;font-size:16px;color:#12805c;">Semua Lunas</p>
                            <p style="font-size:11.5px;color:#586359;">Tidak ada tagihan aktif</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ YANG PERLU PERHATIAN ═══ --}}
    @php
        $hasAlerts = $totalOutstanding > 0 || $pendingRequests > 0 || $expiringContracts->isNotEmpty();
    @endphp
    @if($hasAlerts)
    <div class="rounded-2xl overflow-hidden" style="border:1px solid rgba(176,64,44,0.25);background:rgba(176,64,44,0.04);">
        <div class="px-5 py-3 flex items-center gap-2" style="background:rgba(176,64,44,0.08);border-bottom:1px solid rgba(176,64,44,0.15);">
            <svg class="w-4 h-4 shrink-0" style="color:#B0402C;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <h3 class="text-sm font-semibold" style="color:#B0402C;">Yang Perlu Perhatian</h3>
        </div>
        <div class="divide-y" style="border-color:rgba(176,64,44,0.1);">
            @if($totalOutstanding > 0)
            <a href="{{ route('penghuni.ipl') }}" wire:navigate class="flex items-center justify-between px-5 py-3 hover:bg-white/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(176,64,44,0.12);">
                        <svg class="w-4 h-4" style="color:#B0402C;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color:#17231E;">IPL belum lunas</p>
                        <p class="text-xs" style="color:#909A8F;">{{ $unpaidBillings->count() }} tagihan · Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</p>
                    </div>
                </div>
                <svg class="w-4 h-4 shrink-0" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif

            @if($pendingRequests > 0)
            <a href="{{ route('penghuni.ipl') }}" wire:navigate class="flex items-center justify-between px-5 py-3 hover:bg-white/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(169,116,26,0.12);">
                        <svg class="w-4 h-4" style="color:#A9741A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color:#17231E;">{{ $pendingRequests }} pembayaran menunggu konfirmasi</p>
                        <p class="text-xs" style="color:#909A8F;">Pengurus akan memverifikasi</p>
                    </div>
                </div>
                <svg class="w-4 h-4 shrink-0" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endif

            @foreach($expiringContracts as $ec)
            <a href="{{ route('penghuni.rumah-saya') }}" wire:navigate class="flex items-center justify-between px-5 py-3 hover:bg-white/50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(169,116,26,0.12);">
                        <svg class="w-4 h-4" style="color:#A9741A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium" style="color:#17231E;">Kontrak {{ $ec->houseBlock?->block_code ?? '—' }} berakhir {{ $ec->contract_end_date->diffInDays(now()) }} hari lagi</p>
                        <p class="text-xs" style="color:#909A8F;">{{ $ec->resident?->name ?? '—' }} · {{ $ec->contract_end_date->format('d M Y') }}</p>
                    </div>
                </div>
                <svg class="w-4 h-4 shrink-0" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ QUICK ACTIONS ═══ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('penghuni.ipl') }}" wire:navigate
            class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md"
            style="background:#ffffff;border:1px solid #E0DFD4;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(22,74,64,0.1);">
                <svg class="w-5 h-5" style="color:#164A40;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            </div>
            <span class="text-xs font-semibold text-center" style="color:#17231E;">Bayar IPL</span>
        </a>
        <a href="{{ route('penghuni.program') }}" wire:navigate
            class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md"
            style="background:#ffffff;border:1px solid #E0DFD4;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(18,128,92,0.1);">
                <svg class="w-5 h-5" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <span class="text-xs font-semibold text-center" style="color:#17231E;">Donasi</span>
        </a>
        @if($resident->isPemilik())
        <a href="{{ route('penghuni.rumah-saya') }}" wire:navigate
            class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md"
            style="background:#ffffff;border:1px solid #E0DFD4;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(169,116,26,0.1);">
                <svg class="w-5 h-5" style="color:#A9741A;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <span class="text-xs font-semibold text-center" style="color:#17231E;">Rumah Saya</span>
        </a>
        @endif
        <a href="{{ route('penghuni.keluarga') }}" wire:navigate
            class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md"
            style="background:#ffffff;border:1px solid #E0DFD4;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(107,91,149,0.1);">
                <svg class="w-5 h-5" style="color:#6B5B95;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-xs font-semibold text-center" style="color:#17231E;">Keluarga</span>
        </a>
        <button type="button" wire:click="openHealthModal"
            class="rounded-2xl p-4 flex flex-col items-center gap-2 transition-all hover:shadow-md cursor-pointer"
            style="background:#ffffff;border:1px solid #E0DFD4;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(176,64,44,0.1);">
                <svg class="w-5 h-5" style="color:#B0402C;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </div>
            <span class="text-xs font-semibold text-center" style="color:#17231E;">Lapor Warga</span>
        </button>
    </div>

    {{-- ═══ TOMBOL DARURAT ═══ --}}
    <button type="button" wire:click="triggerEmergency"
        wire:confirm="Anda yakin ingin mengaktifkan tombol darurat? Semua warga di blok Anda akan diberitahu."
        class="w-full rounded-2xl p-5 flex items-center gap-4 transition-all hover:shadow-lg cursor-pointer"
        style="background:linear-gradient(135deg,#B0402C,#8B2F1E);color:#ffffff;border:2px solid rgba(255,255,255,0.2);">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0" style="background:rgba(255,255,255,0.2);">
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="text-left">
            <p class="text-sm font-bold uppercase tracking-wider">Tombol Darurat</p>
            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.8);">Tekan untuk memberi tahu warga sekitar & admin</p>
        </div>
        <svg class="w-5 h-5 ml-auto shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
    </button>

    {{-- ═══ PENGUMUMAN ═══ --}}
    @if($notices->isNotEmpty())
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <div class="px-5 py-4 flex items-center gap-2" style="border-bottom:1px solid #F1F3EC;">
            <svg class="w-4 h-4" style="color:#164A40;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            <h3 class="text-sm font-semibold" style="color:#17231E;">Pengumuman</h3>
        </div>
        <div class="divide-y" style="border-color:#F1F3EC;">
            @foreach($notices as $notice)
            @php
                $pStyle = match($notice->priority) {
                    'warning' => 'background:rgba(169,116,26,0.1);color:#A9741A;',
                    'urgent'  => 'background:rgba(176,64,44,0.1);color:#B0402C;',
                    default   => 'background:rgba(22,74,64,0.08);color:#164A40;',
                };
                $liked = in_array($notice->id, $likedNoticeIds);
                $likeCount = $notice->likers_count ?? 0;
            @endphp
            <div class="px-5 py-3.5">
                <div class="flex items-start gap-3">
                    <span class="text-[10px] font-bold px-1.5 py-0.5 rounded mt-0.5 shrink-0 uppercase" style="{{ $pStyle }}">{{ $notice->priority }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium" style="color:#17231E;">{{ $notice->title }}</p>
                        <p class="text-xs mt-0.5 line-clamp-2" style="color:#586359;">{{ $notice->content }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <button type="button" wire:click="toggleLike({{ $notice->id }})" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-1.5 text-xs font-medium rounded-full px-2.5 py-1 transition-colors"
                                style="{{ $liked ? 'background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.25);' : 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <span>{{ $liked ? 'Disukai' : 'Suka' }}</span>
                                @if($likeCount > 0)<span style="opacity:.7;">· {{ $likeCount }}</span>@endif
                            </button>
                            <span class="text-[10px]" style="color:#909A8F;">{{ $notice->published_at?->diffForHumans() ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ INFO KEUANGAN (Ringkas) ═══ --}}
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #F1F3EC;">
            <h3 class="text-sm font-semibold" style="color:#17231E;">Saldo Rekening</h3>
            <a href="{{ route('penghuni.keuangan') }}" wire:navigate class="text-xs hover:underline" style="color:#586359;">Detail</a>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
            @forelse($this->accounts as $account)
                <div class="flex items-center justify-between rounded-xl px-4 py-3" style="background:#F1F3EC;">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider font-medium" style="color:#909A8F;">{{ $account->organization_type }}</p>
                        <p class="text-xs font-medium mt-0.5" style="color:#17231E;">{{ $account->name }}</p>
                    </div>
                    <p class="text-sm font-bold pp-rp" style="color:#12805c;">Rp {{ number_format($account->balance, 0, ',', '.') }}</p>
                </div>
            @empty
                <p class="text-sm text-center py-4" style="color:#909A8F;">Belum ada rekening.</p>
            @endforelse
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ═══ IPL TERBARU (Hanya Belum Lunas) ═══ --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #F1F3EC;">
                <h3 class="text-sm font-semibold" style="color:#17231E;">Tagihan IPL</h3>
                <a href="{{ route('penghuni.ipl') }}" wire:navigate class="text-xs hover:underline" style="color:#586359;">Bayar sekarang</a>
            </div>
            @forelse($recentBillings as $billing)
                @php
                    $statusStyle = match($billing->status) {
                        'partial' => 'background:rgba(169,116,26,0.12);color:#A9741A;border:1px solid rgba(169,116,26,0.25);',
                        default   => 'background:rgba(176,64,44,0.12);color:#B0402C;border:1px solid rgba(176,64,44,0.25);',
                    };
                    $statusLabel = match($billing->status) {
                        'partial' => 'Sebagian',
                        default   => 'Belum Bayar',
                    };
                @endphp
                <div class="px-5 py-3.5 flex items-center justify-between" style="border-bottom:1px solid #ffffff;">
                    <div>
                        <p class="text-sm font-medium" style="color:#17231E;">
                            {{ \Carbon\Carbon::create($billing->period->year, $billing->period->month)->translatedFormat('F Y') }}
                        </p>
                        <p class="text-xs mt-0.5" style="color:#909A8F;">
                            {{ $billing->houseBlock?->block_code ?? '-' }}
                            &middot; Sisa Rp {{ number_format($billing->outstanding, 0, ',', '.') }}
                        </p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium shrink-0" style="{{ $statusStyle }}">{{ $statusLabel }}</span>
                </div>
            @empty
                <div class="px-5 py-10 text-center">
                    <svg class="w-10 h-10 mx-auto mb-2" style="color:#12805c;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="text-sm font-medium" style="color:#12805c;">Semua tagihan lunas!</p>
                </div>
            @endforelse
        </div>

        {{-- ═══ RUMAH DITAWARKAN ═══ --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #F1F3EC;">
                <h3 class="text-sm font-semibold" style="color:#17231E;">Rumah Ditawarkan</h3>
                @if($resident->isPemilik())
                <a href="{{ route('penghuni.rumah-saya') }}" wire:navigate class="text-xs hover:underline" style="color:#586359;">Kelola</a>
                @endif
            </div>
            @if($listedHouses->isNotEmpty())
            <div class="p-4 space-y-2">
                @foreach($listedHouses as $lh)
                    @php
                        $photo = $lh->photos->firstWhere('is_primary') ?? $lh->photos->first();
                        $isForSale = ($lh->listing_type ?? 'sewa') === 'jual';
                    @endphp
                    <a href="{{ route('penghuni.detail-rumah', $lh->id) }}" wire:navigate
                        class="flex items-center gap-3 p-3 rounded-xl transition-colors"
                        style="background:#F1F3EC;border:1px solid #E0DFD4;"
                        onmouseover="this.style.borderColor='rgba(22,74,64,0.3)'" onmouseout="this.style.borderColor='#E0DFD4'">
                        <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0" style="background:#E0DFD4;">
                            @if($photo)
                                <img src="{{ Storage::disk('public')->url($photo->photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-5 h-5" style="color:#C9C7BA;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold" style="color:#17231E;">Blok {{ $lh->block_code }}</span>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                    style="background:{{ $isForSale ? 'rgba(22,74,64,0.1)' : 'rgba(169,116,26,0.1)' }};color:{{ $isForSale ? '#164A40' : '#A9741A' }};">
                                    {{ $isForSale ? 'Jual' : 'Sewa' }}
                                </span>
                            </div>
                            @if($lh->rental_price)
                                <p class="text-xs font-bold mt-0.5" style="color:#164A40;">
                                    Rp {{ number_format($lh->rental_price, 0, ',', '.') }}
                                    @if(!$isForSale)
                                        <span class="font-medium" style="color:#909A8F;">/ {{ match($lh->rental_duration ?? 'bulanan') { '6bulan' => '6bln', 'tahunan' => 'thn', default => 'bln' } }}</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            @else
            <div class="px-5 py-10 text-center">
                <p class="text-sm" style="color:#909A8F;">Belum ada rumah ditawarkan.</p>
            </div>
            @endif
        </div>

    </div>

    {{-- ═══ PROGRAM AKTIF ═══ --}}
    @if($campaigns->isNotEmpty())
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #F1F3EC;">
            <h3 class="text-sm font-semibold" style="color:#17231E;">Program Aktif</h3>
            <a href="{{ route('penghuni.program') }}" wire:navigate class="text-xs hover:underline" style="color:#586359;">Lihat semua</a>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($campaigns as $campaign)
                @php
                    $raised   = $campaign->donations->sum(fn($d) => optional($d->transaction)->amount ?? 0);
                    $progress = $campaign->target_amount > 0 ? min(100, ($raised / $campaign->target_amount) * 100) : 0;
                @endphp
                <a href="{{ route('penghuni.program.detail', $campaign->id) }}" wire:navigate
                    class="block p-4 rounded-xl transition-colors"
                    style="background:#F1F3EC;border:1px solid #E0DFD4;"
                    onmouseover="this.style.borderColor='rgba(22,74,64,0.3)'" onmouseout="this.style.borderColor='#E0DFD4'">
                    <p class="text-sm font-medium truncate" style="color:#17231E;">{{ $campaign->name }}</p>
                    <p class="text-xs mt-0.5" style="color:#909A8F;">
                        Target: Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}
                    </p>
                    <div class="mt-2.5 h-1.5 rounded-full" style="background:#E0DFD4;">
                        <div class="h-1.5 rounded-full" style="width:{{ $progress }}%;background:#164A40;"></div>
                    </div>
                    <div class="flex items-center justify-between mt-1.5">
                        <p class="text-[10px]" style="color:#909A8F;">{{ number_format($progress, 0) }}% terkumpul</p>
                        <span class="text-[10px] font-bold" style="color:#164A40;">Donasi →</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ PASAR WARGA — Rumah Dijual/Disewa oleh Warga Lain ═══ --}}
    @if($marketListings->isNotEmpty())
    <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
        <div class="px-5 py-4 flex items-center justify-between" style="border-bottom:1px solid #F1F3EC;">
            <div>
                <h3 class="text-sm font-semibold" style="color:#17231E;">Rumah Dijual &amp; Disewa Warga</h3>
                <p class="text-xs mt-0.5" style="color:#909A8F;">Ditawarkan oleh warga lain — hubungi langsung pemiliknya</p>
            </div>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($marketListings as $listing)
                @php
                    $photo = $listing->photos->firstWhere('is_primary') ?? $listing->photos->first();
                    $owner = $listing->owners->first();
                    $isForSale = ($listing->listing_type ?? 'sewa') === 'jual';
                    $wa = $owner ? ($owner->whatsapp ?: $owner->phone) : null;
                    $waDigits = $wa ? preg_replace('/[^0-9]/', '', $wa) : null;
                    if ($waDigits) {
                        $waDigits = str_starts_with($waDigits, '0')
                            ? '62' . substr($waDigits, 1)
                            : (str_starts_with($waDigits, '62') ? $waDigits : '62' . $waDigits);
                    }
                @endphp
                <div class="group rounded-xl overflow-hidden flex flex-col transition-shadow" style="position:relative;background:#F1F3EC;border:1px solid #E0DFD4;"
                    onmouseover="this.style.boxShadow='0 4px 18px -6px rgba(22,74,64,0.25)';this.style.borderColor='rgba(22,74,64,0.3)'"
                    onmouseout="this.style.boxShadow='none';this.style.borderColor='#E0DFD4'">
                    {{-- Link detail (stretched — tidak membungkus tombol kontak) --}}
                    <a href="{{ route('rental.detail', $listing->id) }}" wire:navigate
                        aria-label="Lihat detail rumah Blok {{ $listing->block_code }}"
                        style="position:absolute;inset:0;z-index:1;"></a>
                    {{-- Foto --}}
                    <div class="relative overflow-hidden" style="aspect-ratio:4/3;background:#E0DFD4;">
                        @if($photo)
                            <img src="{{ Storage::disk('public')->url($photo->photo_path) }}" class="w-full h-full object-cover" alt="Rumah Blok {{ $listing->block_code }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10" style="color:#C9C7BA;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                        @endif
                        <span class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-md"
                            style="background:{{ $isForSale ? 'rgba(22,74,64,0.92)' : 'rgba(169,116,26,0.92)' }};color:#fff;">
                            {{ $isForSale ? 'Dijual' : 'Disewa' }}
                        </span>
                    </div>

                    {{-- Info --}}
                    <div class="p-3 flex flex-col flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-bold" style="color:#17231E;">Blok {{ $listing->block_code }}</span>
                            <span class="text-[11px] font-semibold shrink-0" style="color:#164A40;">Detail &rarr;</span>
                        </div>
                        @if($listing->rental_price)
                            <p class="text-base font-extrabold mt-1" style="color:#164A40;font-family:'IBM Plex Mono',monospace;letter-spacing:-.01em;">
                                Rp {{ number_format($listing->rental_price, 0, ',', '.') }}
                                @if(!$isForSale)
                                    <span class="text-xs font-medium" style="color:#909A8F;font-family:'Plus Jakarta Sans',sans-serif;">/ {{ match($listing->rental_duration ?? 'bulanan') { '6bulan' => '6 bulan', 'tahunan' => 'tahun', default => 'bulan' } }}</span>
                                @endif
                            </p>
                        @endif
                        @if($listing->rental_description)
                            <p class="text-xs leading-relaxed mt-1.5 line-clamp-2" style="color:#586359;">{{ $listing->rental_description }}</p>
                        @endif

                        {{-- Kontak pemilik (lengkap — area login) --}}
                        @if($owner)
                            <div class="mt-3 pt-3" style="border-top:1px solid #E0DFD4;">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold shrink-0" style="background:rgba(22,74,64,0.12);color:#164A40;">
                                        {{ strtoupper(substr($owner->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-semibold truncate" style="color:#17231E;">{{ $owner->name }}</p>
                                        @if($owner->phone)
                                            <p class="text-xs" style="color:#586359;font-family:'IBM Plex Mono',monospace;">{{ $owner->phone }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 mt-auto">
                                    @if($owner->phone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $owner->phone) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-semibold py-2 rounded-lg transition-colors"
                                            style="position:relative;z-index:2;background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
                                            onmouseover="this.style.borderColor='rgba(22,74,64,0.35)'" onmouseout="this.style.borderColor='#E0DFD4'">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            Telepon
                                        </a>
                                    @endif
                                    @if($waDigits)
                                        <a href="https://wa.me/{{ $waDigits }}?text=Halo%2C+saya+tertarik+dengan+rumah+Blok+{{ $listing->block_code }}"
                                            target="_blank" rel="noopener"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-semibold py-2 rounded-lg transition-colors"
                                            style="position:relative;z-index:2;background:rgba(18,128,92,0.12);color:#12805c;"
                                            onmouseover="this.style.background='rgba(18,128,92,0.22)'" onmouseout="this.style.background='rgba(18,128,92,0.12)'">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ═══ MODAL LAPORAN KESEHATAN ═══ --}}
    @if($showHealthModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="dismissHealth"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg" style="background:#ffffff;border:1px solid #D8D6C9;">
            <div class="px-6 py-4 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Lapor Warga</h3>
                <p class="text-xs mt-1" style="color:#586359;">Bantu kami mengetahui kondisi Anda dan keluarga.</p>
            </div>
            <div class="px-6 py-5 space-y-4">
                {{-- Kategori --}}
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#586359;">Jenis Laporan</label>
                    <div class="flex flex-wrap gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="healthCategory" value="sakit" class="sr-only peer">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors peer-checked:ring-2"
                                style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;--tw-ring-color:#A9741A;"
                                :style="$wire.healthCategory === 'sakit' ? 'background:rgba(169,116,26,0.1);color:#A9741A;border-color:rgba(169,116,26,0.3);' : ''">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                Sakit
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="healthCategory" value="meninggal" class="sr-only peer">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors peer-checked:ring-2"
                                style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;--tw-ring-color:#B0402C;"
                                :style="$wire.healthCategory === 'meninggal' ? 'background:rgba(176,64,44,0.1);color:#B0402C;border-color:rgba(176,64,44,0.3);' : ''">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Berita Duka
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="healthCategory" value="lainnya" class="sr-only peer">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors peer-checked:ring-2"
                                style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;--tw-ring-color:#164A40;"
                                :style="$wire.healthCategory === 'lainnya' ? 'background:rgba(22,74,64,0.1);color:#164A40;border-color:rgba(22,74,64,0.3);' : ''">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Info Lainnya
                            </span>
                        </label>
                    </div>
                    @error('healthCategory') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>

                {{-- Report For --}}
                <div>
                    <label class="block text-sm font-medium mb-2" style="color:#586359;">Laporan Untuk</label>
                    <div class="flex flex-wrap gap-2">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="healthReportFor" value="diri_sendiri" class="sr-only peer">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors peer-checked:ring-2"
                                style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;--tw-ring-color:#164A40;"
                                :style="$wire.healthReportFor === 'diri_sendiri' ? 'background:rgba(22,74,64,0.1);color:#164A40;border-color:rgba(22,74,64,0.3);' : ''">
                                Diri Sendiri
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="healthReportFor" value="keluarga" class="sr-only peer">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors peer-checked:ring-2"
                                style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;--tw-ring-color:#6B5B95;"
                                :style="$wire.healthReportFor === 'keluarga' ? 'background:rgba(107,91,149,0.1);color:#6B5B95;border-color:rgba(107,91,149,0.3);' : ''">
                                Keluarga
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model="healthReportFor" value="warga_lain" class="sr-only peer">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors peer-checked:ring-2"
                                style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;--tw-ring-color:#A9741A;"
                                :style="$wire.healthReportFor === 'warga_lain' ? 'background:rgba(169,116,26,0.1);color:#A9741A;border-color:rgba(169,116,26,0.3);' : ''">
                                Warga Lain
                            </span>
                        </label>
                    </div>
                    @error('healthReportFor') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>

                {{-- Person Name --}}
                @if(in_array($healthReportFor, ['keluarga', 'warga_lain']))
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Nama</label>
                    <input type="text" wire:model="healthPersonName" placeholder="Nama yang dilaporkan"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;">
                    @error('healthPersonName') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                @endif

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5" style="color:#586359;">Deskripsi</label>
                    <textarea wire:model="healthDescription" rows="3" placeholder="Jelaskan kondisi yang terjadi..."
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.625rem 0.875rem;width:100%;font-size:0.875rem;outline:none;resize:none;"></textarea>
                    @error('healthDescription') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="px-6 py-4 flex justify-end gap-3" style="border-top:1px solid #E0DFD4;">
                <button type="button" wire:click="dismissHealth"
                    class="px-4 py-2 text-sm rounded-xl font-medium"
                    style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;">Nanti Saja</button>
                <button wire:click="submitHealthReport" wire:loading.attr="disabled"
                    class="px-5 py-2 text-sm rounded-xl font-semibold"
                    style="background:#164A40;color:#ffffff;">
                    <span wire:loading.remove wire:target="submitHealthReport">Kirim Laporan</span>
                    <span wire:loading wire:target="submitHealthReport">Mengirim...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
