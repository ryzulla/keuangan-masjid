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
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        <style>
            body { background-color:#E9ECE4; }
            [x-cloak] { display: none !important; }
        </style>
        @stack('styles')
        {{-- CKEditor loaded in head so wire:navigate won't re-execute it on each navigation --}}
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js" data-navigate-once></script>
    </head>
    <body class="font-sans antialiased" style="background-color:#E9ECE4; color:#17231E;">
        <livewire:penghuni.emergency-alert-banner />
        <div class="min-h-screen" style="background-color:#E9ECE4;">
            <livewire:layout.navigation />

            @if (isset($header))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    <header class="pp-admin-header relative overflow-hidden rounded-[22px]" style="background:linear-gradient(150deg,#0B2E28,#164A40 72%);">
                        <div style="position:absolute;inset:0;opacity:.65;pointer-events:none;background-image:linear-gradient(rgba(244,239,226,.12) 1px,transparent 1px),linear-gradient(90deg,rgba(244,239,226,.12) 1px,transparent 1px);background-size:34px 34px;-webkit-mask-image:radial-gradient(120% 120% at 88% -10%,#000,transparent 62%);mask-image:radial-gradient(120% 120% at 88% -10%,#000,transparent 62%);"></div>
                        <div class="relative py-5 px-6">
                            {{ $header }}
                        </div>
                    </header>
                </div>
            @endif

            <main class="pb-20 sm:pb-0">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" data-navigate-once></script>

        @stack('scripts')
        <x-pwa-install />
    </body>
</html>
