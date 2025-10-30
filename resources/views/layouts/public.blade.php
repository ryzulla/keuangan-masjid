<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light"> {{-- Anda bisa ganti data-theme --}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Keuangan Masjid') }} - Selamat Datang</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        {{-- Latar belakang utama halaman --}}
        <div class="min-h-screen bg-gradient-to-br from-green-100 via-blue-50 to-green-100 dark:from-gray-800 dark:via-gray-900 dark:to-gray-800">

            {{-- Navbar Publik Sederhana --}}
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 shadow-sm sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        {{-- Logo dan Nama Aplikasi --}}
                        <div class="flex items-center">
                            <a href="{{ route('welcome') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                            </a>
                            <span class="ml-3 font-semibold text-xl text-gray-800 dark:text-gray-200 hidden sm:block">Keuangan Masjid</span>
                        </div>
                        {{-- Tombol Login --}}
                        <div class="flex items-center">
                            @auth
                                {{-- (Dashboard button styling) --}}
                                <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-ghost text-primary hover:bg-primary hover:text-primary-content">... Dashboard</a>
                            @else
                                {{-- Tombol Login Outline --}}
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                    Login Pengurus
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Konten Utama (Slot) --}}
            <main class="pt-8 pb-16">
                {{ $slot }}
            </main>

             {{-- Footer Sederhana (Opsional) --}}
             <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'Keuangan Masjid') }}. All rights reserved.
             </footer>

        </div> {{-- Akhir min-h-screen --}}

        @livewireScripts
        @stack('scripts')
    </body>
</html>
