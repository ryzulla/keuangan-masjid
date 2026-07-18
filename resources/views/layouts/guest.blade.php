<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <x-pwa-meta />

        <title>{{ config('app.name', 'Sistem Perumahan') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Rubik:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased" style="background-color:#F1F3EC;">

        @if ($bare ?? false)
            {{-- Halaman menyediakan brand/card sendiri (mis. Portal Penghuni) --}}
            {{ $slot }}
        @else
            {{-- Background pattern --}}
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:radial-gradient(ellipse at top,rgba(22,74,64,0.08) 0%,transparent 60%),radial-gradient(ellipse at bottom-right,rgba(22,74,64,0.05) 0%,transparent 50%);"></div>
            </div>

            <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10 px-4">

                {{-- Logo / Brand --}}
                <div class="mb-8 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl mb-4"
                        style="background:#F1F3EC;border:1px solid rgba(22,74,64,0.4);">
                        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="#164A40" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-semibold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">
                        {{ config('app.name', 'Sistem Perumahan') }}
                    </h1>
                    <p class="text-xs mt-1" style="color:#909A8F;">Sistem Manajemen Perumahan &amp; DKM</p>
                </div>

                {{-- Card --}}
                <div class="w-full sm:max-w-md rounded-2xl px-7 py-7 shadow-2xl"
                    style="background-color:#ffffff;border:1px solid #E0DFD4;">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-xs" style="color:#909A8F;">&copy; {{ date('Y') }} Sistem Perumahan. All rights reserved.</p>
            </div>
        @endif

        @livewireScripts
        <x-pwa-install />
    </body>
</html>
