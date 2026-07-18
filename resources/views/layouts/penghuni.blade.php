<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-pwa-meta />
    <title>{{ isset($title) ? $title . ' — Portal Warga' : 'Portal Warga' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>
        /* ═══ Portal Warga — identitas "Denah Warga" ═══ */
        :root{
            --pp-canvas:#E9ECE4; --pp-surface:#FFFFFF; --pp-surface-2:#F2F5EE; --pp-inset:#F5F7F1;
            --pp-brand:#164A40; --pp-brand-600:#0F3A32; --pp-brand-700:#0B2E28;
            --pp-brand-tint:#E1EDE8; --pp-plate:#1C5749; --pp-cream:#F4EFE2;
            --pp-ink:#17231E; --pp-muted:#586359; --pp-faint:#909A8F;
            --pp-line:#E0DFD4; --pp-line-soft:#ECEBE1;
            --pp-income:#12805C; --pp-expense:#B0402C; --pp-warn:#A9741A;
            --pp-shadow-sm:0 1px 2px rgba(22,74,64,.05);
            --pp-shadow:0 2px 4px rgba(22,74,64,.04),0 12px 28px -14px rgba(22,74,64,.16);
            --pp-font-display:'Fraunces',Georgia,'Times New Roman',serif;
            --pp-font-body:'Plus Jakarta Sans',system-ui,-apple-system,'Segoe UI',sans-serif;
        }
        [x-cloak]{ display:none !important; }

        /* daisyUI memasang :root background putih (specificity tinggi) — override paksa */
        html, :root{ background-color:var(--pp-canvas) !important; }
        body{ background-color:var(--pp-canvas); color:var(--pp-ink); font-family:var(--pp-font-body); -webkit-font-smoothing:antialiased; min-height:100vh; }
        /* override app.css admin heading defaults inside the portal */
        h1,h2,h3,h4,h5,h6{ font-family:var(--pp-font-display); color:var(--pp-ink); font-optical-sizing:auto; letter-spacing:-.01em; }
        ::-webkit-scrollbar-thumb:hover{ background:var(--pp-brand); }

        .pp-display{ font-family:var(--pp-font-display); font-optical-sizing:auto; letter-spacing:-.01em; }
        .pp-rp{ font-family:var(--pp-font-display); font-optical-sizing:auto; font-variant-numeric:tabular-nums; letter-spacing:-.015em; }
        .pp-tnum{ font-variant-numeric:tabular-nums; }
        .pp-eyebrow{ font-size:10.5px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--pp-faint); }

        /* Block plate — plakat blok bergaya plat rumah */
        .pp-plate{ display:inline-flex; align-items:center; gap:6px; background:var(--pp-brand-tint); color:var(--pp-brand);
            border-radius:6px; padding:2px 8px; font-family:var(--pp-font-display); font-weight:600; font-size:12px; letter-spacing:.03em; }
        .pp-plate--solid{ background:var(--pp-plate); color:var(--pp-cream); }

        /* Hero denah grid texture */
        .pp-denah{ position:absolute; inset:0; opacity:.7; pointer-events:none;
            background-image:linear-gradient(rgba(244,239,226,.12) 1px,transparent 1px),linear-gradient(90deg,rgba(244,239,226,.12) 1px,transparent 1px);
            background-size:34px 34px; -webkit-mask-image:radial-gradient(120% 120% at 88% -10%,#000,transparent 62%); mask-image:radial-gradient(120% 120% at 88% -10%,#000,transparent 62%); }

        /* Nav */
        .pp-nav a{ padding:8px 13px; border-radius:9px; font-size:13.5px; font-weight:500; color:var(--pp-muted); transition:color .15s,background-color .15s; }
        .pp-nav a:hover{ color:var(--pp-ink); background:var(--pp-surface-2); }
        .pp-nav a.pp-on{ color:var(--pp-brand); background:var(--pp-brand-tint); font-weight:600; }

        /* Entrance motion */
        .pp-reveal{ opacity:0; transform:translateY(10px); animation:pp-rise .55s cubic-bezier(.2,.7,.3,1) forwards; }
        .pp-reveal:nth-child(2){animation-delay:.05s}.pp-reveal:nth-child(3){animation-delay:.1s}
        .pp-reveal:nth-child(4){animation-delay:.15s}.pp-reveal:nth-child(5){animation-delay:.2s}
        .pp-reveal:nth-child(6){animation-delay:.25s}.pp-reveal:nth-child(7){animation-delay:.3s}
        @keyframes pp-rise{ to{ opacity:1; transform:none; } }
        @media (prefers-reduced-motion:reduce){ .pp-reveal{ animation:none; opacity:1; transform:none; } }
    </style>
</head>
<body class="h-full">

<livewire:penghuni.emergency-alert-banner />

@php
    $resident = auth('resident')->user();
    $isPemilik = $resident?->isPemilik();
    $ppUnread = $resident?->unreadNotifications()->count() ?? 0;
    $ppBlocks = $resident?->currentAssignments?->map(fn($a)=>$a->houseBlock?->block_code)->filter()->join(', ');
    $ppLinks = [
        ['penghuni.dashboard', 'penghuni.dashboard', 'Beranda', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        ['penghuni.program', 'penghuni.program*', 'Program', 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
        ['penghuni.ipl', 'penghuni.ipl', 'IPL', 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
        ['penghuni.keluarga', 'penghuni.keluarga', 'Keluarga', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['penghuni.keuangan', 'penghuni.keuangan', 'Keuangan', 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
    ];
    if ($isPemilik) {
        $ppLinks[] = ['penghuni.rumah-saya', 'penghuni.rumah-saya*', 'Rumah Saya', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'];
    }
    // 4 tab utama di bar bawah mobile (sisanya di sheet Menu)
    $ppTabs = array_slice($ppLinks, 0, 4);
@endphp

{{-- ═══ Nav (desktop top bar + mobile bottom bar) ═══ --}}
<nav x-data="{ menuOpen: false }">
    {{-- Desktop / tablet top bar --}}
    <div class="hidden sm:block" style="background:rgba(255,255,255,.86);backdrop-filter:blur(10px);border-bottom:1px solid var(--pp-line);position:sticky;top:0;z-index:40;">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between" style="height:60px;">
            {{-- Brand --}}
            <a href="{{ route('penghuni.dashboard') }}" wire:navigate class="flex items-center gap-3">
                <span style="width:34px;height:34px;border-radius:9px;background:var(--pp-brand);display:grid;grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr;gap:2px;padding:7px;">
                    <span style="background:var(--pp-cream);border-radius:1.5px;opacity:.9;"></span>
                    <span style="background:var(--pp-cream);border-radius:1.5px;opacity:.5;"></span>
                    <span style="background:var(--pp-cream);border-radius:1.5px;opacity:.5;"></span>
                    <span style="background:var(--pp-cream);border-radius:1.5px;opacity:.9;"></span>
                </span>
                <span class="leading-none">
                    <span class="block pp-display" style="font-weight:600;font-size:16.5px;color:var(--pp-ink);">Portal Warga</span>
                    <span class="block" style="font-size:10.5px;color:var(--pp-faint);letter-spacing:.04em;margin-top:2px;">Sistem Perumahan</span>
                </span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden sm:flex items-center gap-1 pp-nav">
                <a href="{{ route('penghuni.dashboard') }}" wire:navigate class="{{ request()->routeIs('penghuni.dashboard') ? 'pp-on' : '' }}">Beranda</a>
                <a href="{{ route('penghuni.program') }}" wire:navigate class="{{ request()->routeIs('penghuni.program*') ? 'pp-on' : '' }}">Program</a>
                <a href="{{ route('penghuni.ipl') }}" wire:navigate class="{{ request()->routeIs('penghuni.ipl') ? 'pp-on' : '' }}">IPL</a>
                <a href="{{ route('penghuni.keluarga') }}" wire:navigate class="{{ request()->routeIs('penghuni.keluarga') ? 'pp-on' : '' }}">Keluarga</a>
                <a href="{{ route('penghuni.keuangan') }}" wire:navigate class="{{ request()->routeIs('penghuni.keuangan') ? 'pp-on' : '' }}">Keuangan</a>
                @if(auth('resident')->user()?->isPemilik())
                <a href="{{ route('penghuni.rumah-saya') }}" wire:navigate class="{{ request()->routeIs('penghuni.rumah-saya*') || request()->routeIs('penghuni.detail-rumah*') ? 'pp-on' : '' }}">Rumah Saya</a>
                @endif
            </div>

            {{-- Right: notifications + profile --}}
            <div class="flex items-center gap-2">
                <livewire:notification-bell guard="resident" />

                <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                    <button @click="open=!open" class="flex items-center gap-2 text-sm rounded-xl transition-colors" style="padding:3px 6px 3px 3px;">
                        @php $resident = auth('resident')->user() @endphp
                        @if($resident?->photo)
                            <img src="{{ Storage::disk('public')->url($resident->photo) }}" class="w-9 h-9 rounded-[10px] object-cover" style="border:1px solid var(--pp-line);">
                        @else
                            <span class="w-9 h-9 rounded-[10px] flex items-center justify-center text-sm font-bold" style="background:var(--pp-brand);color:var(--pp-cream);">
                                {{ strtoupper(substr($resident?->name ?? '?', 0, 1)) }}
                            </span>
                        @endif
                        <span class="hidden sm:block max-w-24 truncate font-medium" style="color:var(--pp-ink);">{{ $resident?->name }}</span>
                        <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="currentColor" viewBox="0 0 20 20" style="color:var(--pp-faint);"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 top-full mt-2 w-48 rounded-xl py-1 z-50" style="background:var(--pp-surface);border:1px solid var(--pp-line);box-shadow:var(--pp-shadow);">
                        <div class="px-4 py-2.5" style="border-bottom:1px solid var(--pp-line-soft);">
                            <p class="text-sm font-semibold truncate" style="color:var(--pp-ink);">{{ $resident?->name }}</p>
                            @if($resident?->currentAssignments?->isNotEmpty())
                            <p class="text-xs mt-0.5" style="color:var(--pp-faint);">Blok {{ $resident->currentAssignments->map(fn($a)=>$a->houseBlock?->block_code)->filter()->join(', ') }}</p>
                            @endif
                        </div>
                        <a href="{{ route('penghuni.settings') }}" wire:navigate class="w-full flex items-center gap-2 px-4 py-2.5 text-sm transition-colors text-left" style="color:var(--pp-muted);"
                            onmouseover="this.style.backgroundColor='var(--pp-surface-2)';this.style.color='var(--pp-brand)'" onmouseout="this.style.backgroundColor='';this.style.color='var(--pp-muted)'">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Pengaturan
                        </a>
                        <div style="height:1px;background:var(--pp-line-soft);margin:2px 0;"></div>
                        <form method="POST" action="{{ route('penghuni.logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm transition-colors text-left" style="color:var(--pp-expense);"
                                onmouseover="this.style.backgroundColor='rgba(176,64,44,.08)'" onmouseout="this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>{{-- /desktop top bar (hidden sm:block) --}}

    {{-- ═══ Mobile bottom bar (menu di bawah saja) ═══ --}}
    <div class="sm:hidden fixed inset-x-0 bottom-0 z-30 flex" style="background:var(--pp-surface);border-top:1px solid var(--pp-line);padding-bottom:env(safe-area-inset-bottom,0px);box-shadow:0 -2px 12px rgba(22,74,64,.06);">
        @foreach($ppTabs as [$route, $pattern, $label, $icon])
        <a href="{{ route($route) }}" wire:navigate class="flex-1 flex flex-col items-center gap-0.5 text-[11px] font-medium" style="padding:9px 0 8px;{{ request()->routeIs($pattern) ? 'color:var(--pp-brand);' : 'color:var(--pp-faint);' }}">
            <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs($pattern) ? '2' : '1.6' }}" d="{{ $icon }}"/></svg>
            {{ $label }}
        </a>
        @endforeach
        {{-- Menu (buka sheet) --}}
        <button type="button" @click="menuOpen=true" class="flex-1 flex flex-col items-center gap-0.5 text-[11px] font-medium" style="padding:9px 0 8px;color:var(--pp-faint);">
            <span class="relative">
                <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 6h16M4 12h16M4 18h16"/></svg>
                @if($ppUnread > 0)<span style="position:absolute;top:-2px;right:-4px;width:8px;height:8px;border-radius:50%;background:var(--pp-expense);border:1.5px solid var(--pp-surface);"></span>@endif
            </span>
            Menu
        </button>
    </div>

    {{-- ═══ Mobile menu sheet ═══ --}}
    <div x-show="menuOpen" x-cloak @click="menuOpen=false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="sm:hidden fixed inset-0 z-40" style="background:rgba(11,46,40,.30);"></div>
    <div x-show="menuOpen" x-cloak
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
        class="sm:hidden fixed inset-x-0 bottom-0 z-50 rounded-t-2xl overflow-y-auto overscroll-contain"
        style="background:var(--pp-surface);border-top:1px solid var(--pp-line);max-height:84vh;padding-bottom:calc(env(safe-area-inset-bottom,0px) + 14px);box-shadow:0 -12px 40px rgba(11,46,40,.18);">

        <div class="flex justify-center pt-2.5 pb-1"><span style="width:38px;height:4px;border-radius:2px;background:var(--pp-line);"></span></div>

        {{-- user header + notifikasi --}}
        <div class="px-5 py-3 flex items-center gap-3" style="border-bottom:1px solid var(--pp-line-soft);">
            @if($resident?->photo)
                <img src="{{ Storage::disk('public')->url($resident->photo) }}" class="w-11 h-11 rounded-xl object-cover shrink-0" style="border:1px solid var(--pp-line);">
            @else
                <span class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 pp-display" style="background:var(--pp-brand);color:var(--pp-cream);font-weight:600;font-size:17px;">{{ strtoupper(substr($resident?->name ?? '?', 0, 1)) }}</span>
            @endif
            <div class="min-w-0 flex-1">
                <p class="font-semibold truncate" style="color:var(--pp-ink);">{{ $resident?->name }}</p>
                @if($ppBlocks)<p class="text-xs mt-0.5" style="color:var(--pp-faint);">Blok {{ $ppBlocks }}</p>@endif
            </div>
            <livewire:notification-bell guard="resident" />
            <button type="button" @click="menuOpen=false" class="p-1.5 rounded-lg shrink-0" style="color:var(--pp-faint);" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- semua tujuan --}}
        <div class="px-4 pt-4">
            <p class="pp-eyebrow px-1 mb-2.5">Navigasi</p>
            <div class="grid grid-cols-3 gap-2.5">
                @foreach($ppLinks as [$route, $pattern, $label, $icon])
                @php $on = request()->routeIs($pattern); @endphp
                <a href="{{ route($route) }}" wire:navigate @click="menuOpen=false"
                    class="flex flex-col items-center gap-1.5 rounded-xl py-3.5 px-2 text-center transition-colors"
                    style="border:1px solid {{ $on ? 'rgba(22,74,64,.4)' : 'var(--pp-line)' }};background:{{ $on ? 'var(--pp-brand-tint)' : 'var(--pp-surface)' }};">
                    <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:{{ $on ? 'var(--pp-brand)' : 'var(--pp-muted)' }};"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $icon }}"/></svg>
                    <span class="text-[11.5px] font-medium leading-tight" style="color:var(--pp-ink);">{{ $label }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- akun --}}
        <div class="px-4 pt-4 pb-2 flex flex-col gap-2">
            <a href="{{ route('penghuni.settings') }}" wire:navigate @click="menuOpen=false"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium" style="border:1px solid var(--pp-line);color:var(--pp-muted);background:var(--pp-surface);">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Pengaturan
            </a>
            <form method="POST" action="{{ route('penghuni.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold" style="border:1px solid rgba(176,64,44,.3);color:var(--pp-expense);background:rgba(176,64,44,.05);">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-24 sm:pb-10">
    {{ $slot }}
</main>

@livewireScripts
<x-pwa-install />
</body>
</html>
