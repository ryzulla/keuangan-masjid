<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Perumahan') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        <style>
            body { background-color:#f7f7f7; }
            [x-cloak] { display: none !important; }
        </style>
        @stack('styles')
        {{-- CKEditor loaded in head so wire:navigate won't re-execute it on each navigation --}}
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js" data-navigate-once></script>
    </head>
    <body class="font-sans antialiased" style="background-color:#f7f7f7; color:#161e2d;">
        <div class="min-h-screen" style="background-color:#f7f7f7;">
            <livewire:layout.navigation />

            @if (isset($header))
                <header style="background-color:#ffffff; border-bottom:1px solid #e4e4e4;">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="pb-20 sm:pb-0">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" data-navigate-once></script>

        @stack('scripts')
    </body>
</html>
