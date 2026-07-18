<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Perumahan') }} - Portal Informasi</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased" style="background-color:#F1F3EC;color:#17231E;">
        <div class="min-h-screen" style="background-color:#F1F3EC;">

            {{-- Public Navbar --}}
            <nav class="sticky top-0 z-50" style="background-color:#ffffff;border-bottom:1px solid #E0DFD4;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                                style="background:rgba(22,74,64,0.15);border:1px solid rgba(22,74,64,0.3);">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="#164A40" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <span class="font-bold text-lg hidden sm:block" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">{{ config('app.name', 'Sistem Perumahan') }}</span>
                        </a>
                        <div class="flex items-center gap-2">
                            @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                                    style="background:#164A40;color:#ffffff;"
                                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('penghuni.login') }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                                    style="color:#586359;border:1px solid #E0DFD4;"
                                    onmouseover="this.style.color='#164A40';this.style.borderColor='rgba(22,74,64,0.4)'" onmouseout="this.style.color='#586359';this.style.borderColor='#E0DFD4'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    Masuk
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <main>
                {{ $slot }}
            </main>

            <footer class="py-5 text-center text-sm" style="border-top:1px solid #F1F3EC;color:#909A8F;">
                &copy; {{ date('Y') }} {{ config('app.name', 'Sistem Perumahan') }}. Semua hak dilindungi.
            </footer>

        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
