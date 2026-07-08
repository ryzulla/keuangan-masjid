<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ isset($title) ? $title . ' — Portal Penghuni' : 'Portal Penghuni' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500&family=IBM+Plex+Sans:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="h-full" style="background-color:#f5f6f8;color:#1d2939;font-family:'IBM Plex Sans',sans-serif;">

{{-- Nav --}}
<nav style="background:#ffffff;border-bottom:1px solid rgba(16,24,40,0.2);position:sticky;top:0;z-index:40;">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-14">
            {{-- Brand --}}
            <a href="{{ route('penghuni.dashboard') }}" wire:navigate class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#f2f4f7;border:1px solid rgba(16,24,40,0.5);">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="#111827" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span class="text-sm font-semibold" style="color:#111827;font-family:'IBM Plex Sans',serif;">Portal Penghuni</span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden sm:flex items-center gap-1 text-sm">
                @php $r = request()->routeIs('penghuni.*') @endphp
                <a href="{{ route('penghuni.dashboard') }}" wire:navigate class="px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('penghuni.dashboard') ? 'text-amber-400' : '' }}" style="{{ request()->routeIs('penghuni.dashboard') ? 'color:#111827;background:rgba(16,24,40,0.1);' : 'color:#667085;' }}">Dashboard</a>
                <a href="{{ route('penghuni.program') }}" wire:navigate class="px-3 py-1.5 rounded-lg transition-colors" style="{{ request()->routeIs('penghuni.program*') ? 'color:#111827;background:rgba(16,24,40,0.1);' : 'color:#667085;' }}">Program</a>
                <a href="{{ route('penghuni.ipl') }}" wire:navigate class="px-3 py-1.5 rounded-lg transition-colors" style="{{ request()->routeIs('penghuni.ipl') ? 'color:#111827;background:rgba(16,24,40,0.1);' : 'color:#667085;' }}">IPL</a>
                <a href="{{ route('penghuni.keluarga') }}" wire:navigate class="px-3 py-1.5 rounded-lg transition-colors" style="{{ request()->routeIs('penghuni.keluarga') ? 'color:#111827;background:rgba(16,24,40,0.1);' : 'color:#667085;' }}">Keluarga</a>
                <a href="{{ route('penghuni.keuangan') }}" wire:navigate class="px-3 py-1.5 rounded-lg transition-colors" style="{{ request()->routeIs('penghuni.keuangan') ? 'color:#111827;background:rgba(16,24,40,0.1);' : 'color:#667085;' }}">Keuangan</a>
                @if(auth('resident')->user()?->isPemilik())
                <a href="{{ route('penghuni.penyewa') }}" wire:navigate class="px-3 py-1.5 rounded-lg transition-colors" style="{{ request()->routeIs('penghuni.penyewa') ? 'color:#111827;background:rgba(16,24,40,0.1);' : 'color:#667085;' }}">Penyewa</a>
                @endif
            </div>

            {{-- Right: notifications + profile (dikelompokkan agar berdampingan) --}}
            <div class="flex items-center gap-1">
            {{-- Notification Bell --}}
            <livewire:notification-bell guard="resident" />

            {{-- User --}}
            <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                <button @click="open=!open" class="flex items-center gap-2 text-sm">
                    @php $resident = auth('resident')->user() @endphp
                    @if($resident?->photo)
                        <img src="{{ Storage::disk('public')->url($resident->photo) }}" class="w-7 h-7 rounded-full object-cover" style="border:1px solid rgba(16,24,40,0.4);">
                    @else
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold" style="background:rgba(16,24,40,0.15);color:#111827;">
                            {{ strtoupper(substr($resident?->name ?? '?', 0, 1)) }}
                        </div>
                    @endif
                    <span class="hidden sm:block max-w-24 truncate" style="color:#344054;">{{ $resident?->name }}</span>
                    <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="currentColor" viewBox="0 0 20 20" style="color:#7c8698;"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute right-0 top-full mt-1 w-44 rounded-xl py-1 z-50 shadow-2xl" style="background:#ffffff;border:1px solid #e4e7ec;">
                    <a href="{{ route('penghuni.settings') }}" wire:navigate class="w-full flex items-center gap-2 px-4 py-2.5 text-sm transition-colors text-left" style="color:#475467;"
                        onmouseover="this.style.backgroundColor='rgba(16,24,40,0.08)';this.style.color='#111827'" onmouseout="this.style.backgroundColor='';this.style.color='#475467'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Pengaturan
                    </a>
                    <form method="POST" action="{{ route('penghuni.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm transition-colors text-left" style="color:#c0453b;"
                            onmouseover="this.style.backgroundColor='rgba(192,69,59,0.08)'" onmouseout="this.style.backgroundColor=''">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
            </div>{{-- /right group --}}
        </div>
    </div>

    {{-- Mobile bottom nav --}}
    <div class="sm:hidden fixed inset-x-0 bottom-0 z-30 flex" style="background:#ffffff;border-top:1px solid rgba(16,24,40,0.15);padding-bottom:env(safe-area-inset-bottom,0px);">
        <a href="{{ route('penghuni.dashboard') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5" style="{{ request()->routeIs('penghuni.dashboard') ? 'color:#111827;' : 'color:#7c8698;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Home
        </a>
        <a href="{{ route('penghuni.program') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5" style="{{ request()->routeIs('penghuni.program*') ? 'color:#111827;' : 'color:#7c8698;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Program
        </a>
        <a href="{{ route('penghuni.ipl') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5" style="{{ request()->routeIs('penghuni.ipl') ? 'color:#111827;' : 'color:#7c8698;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            IPL
        </a>
        <a href="{{ route('penghuni.keluarga') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5" style="{{ request()->routeIs('penghuni.keluarga') ? 'color:#111827;' : 'color:#7c8698;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Keluarga
        </a>
        <a href="{{ route('penghuni.keuangan') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5" style="{{ request()->routeIs('penghuni.keuangan') ? 'color:#111827;' : 'color:#7c8698;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            Keuangan
        </a>
        @if(auth('resident')->user()?->isPemilik())
        <a href="{{ route('penghuni.penyewa') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5" style="{{ request()->routeIs('penghuni.penyewa') ? 'color:#111827;' : 'color:#7c8698;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Penyewa
        </a>
        @endif
    </div>
</nav>

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-24 sm:pb-6">
    {{ $slot }}
</main>

@livewireScripts
</body>
</html>
