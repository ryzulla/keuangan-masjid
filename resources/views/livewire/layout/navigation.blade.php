<?php
use App\Livewire\Actions\Logout;
$logout = function (Logout $logout) {
    $logout();
    $this->redirect('/', navigate: true);
};
?>
<nav x-data="{ open: false }" style="background-color:#ffffff;border-bottom:1px solid #E0DFD4;box-shadow:0 2px 10px rgba(0,0,0,0.05);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Left: Logo + Nav --}}
            <div class="flex items-center">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" wire:navigate class="shrink-0 flex items-center gap-2.5 mr-6">
                    <span style="width:32px;height:32px;border-radius:9px;background:#164A40;display:grid;grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr;gap:2px;padding:6px;">
                        <span style="background:#F4EFE2;border-radius:1.5px;opacity:.9;"></span>
                        <span style="background:#F4EFE2;border-radius:1.5px;opacity:.5;"></span>
                        <span style="background:#F4EFE2;border-radius:1.5px;opacity:.5;"></span>
                        <span style="background:#F4EFE2;border-radius:1.5px;opacity:.9;"></span>
                    </span>
                    <span class="hidden sm:block leading-none">
                        <span class="block text-sm font-semibold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Sistem Perumahan</span>
                        <span class="block" style="font-size:10px;color:#909A8F;letter-spacing:.04em;margin-top:2px;">Panel Pengurus</span>
                    </span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden sm:flex sm:items-center sm:gap-1">

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="px-3 py-2 text-sm rounded-lg transition-colors duration-150"
                        style="{{ request()->routeIs('dashboard') ? 'color:#17231E;background:rgba(22,74,64,0.1);' : 'color:#586359;' }}"
                        onmouseover="if(!this.dataset.active)this.style.color='#164A40'"
                        onmouseout="if(!this.dataset.active)this.style.color='#586359'"
                        {{ request()->routeIs('dashboard') ? 'data-active=1' : '' }}>
                        Dashboard
                    </a>

                    {{-- Perumahan Dropdown --}}
                    @canany(['manage-residents', 'manage-ipl', 'manage-perumahan', 'manage-programs'])
                    <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                        <button @click="open=!open"
                            class="flex items-center gap-1 px-3 py-2 text-sm rounded-lg transition-colors"
                            style="color:#586359;"
                            :style="open ? 'color:#17231E;background:rgba(22,74,64,0.1);' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Perumahan
                            <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="none" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill="currentColor"/></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute left-0 top-full mt-1 w-52 rounded-xl py-1 z-50 shadow-2xl"
                            style="background-color:#F1F3EC;border:1px solid #E0DFD4;">
                            @can('manage-residents')
                            <a href="{{ route('residents.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Data Penghuni
                            </a>
                            <a href="{{ route('house-blocks.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Data Blok Rumah
                            </a>
                            @endcan
                            @can('manage-ipl')
                            <div style="height:1px;background:#F1F3EC;margin:2px 12px;"></div>
                            <a href="{{ route('ipl.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                                IPL Iuran Perumahan
                            </a>
                            <a href="{{ route('ipl.report') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Laporan IPL
                            </a>
                            <a href="{{ route('ipl.tariffs') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                Pengaturan Tarif
                            </a>
                            <a href="{{ route('payment-requests.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#A9741A';this.style.backgroundColor='rgba(169,116,26,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Konfirmasi Bayar Penghuni
                            </a>
                            @endcan
                            @canany(['manage-perumahan', 'manage-programs'])
                            <div style="height:1px;background:#F1F3EC;margin:2px 12px;"></div>
                            @can('manage-perumahan')
                            <a href="{{ route('perumahan.transaksi') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                Transaksi Perumahan
                            </a>
                            @endcan
                            @can('manage-programs')
                            <a href="{{ route('campaigns.index') . '?org=perumahan' }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Program Perumahan
                            </a>
                            @endcan
                            @endcanany
                        </div>
                    </div>
                    @endcanany

                    {{-- DKM Dropdown --}}
                    @canany(['manage-dkm', 'manage-programs'])
                    <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                        <button @click="open=!open"
                            class="flex items-center gap-1 px-3 py-2 text-sm rounded-lg transition-colors"
                            style="color:#586359;"
                            :style="open ? 'color:#17231E;background:rgba(22,74,64,0.1);' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            DKM Masjid
                            <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="none" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill="currentColor"/></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute left-0 top-full mt-1 w-48 rounded-xl py-1 z-50 shadow-2xl"
                            style="background-color:#F1F3EC;border:1px solid #E0DFD4;">
                            @can('manage-dkm')
                            <a href="{{ route('transactions.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                Transaksi DKM
                            </a>
                            <a href="{{ route('payment-requests.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#A9741A';this.style.backgroundColor='rgba(169,116,26,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Konfirmasi Donasi
                            </a>
                            @endcan
                            @can('manage-programs')
                            <a href="{{ route('campaigns.index') . '?org=dkm' }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Program DKM
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endcanany

                    {{-- Laporan Dropdown --}}
                    @can('view-reports')
                    <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                        <button @click="open=!open"
                            class="flex items-center gap-1 px-3 py-2 text-sm rounded-lg transition-colors"
                            style="color:#586359;"
                            :style="open ? 'color:#17231E;background:rgba(22,74,64,0.1);' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Laporan
                            <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="none" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill="currentColor"/></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute left-0 top-full mt-1 w-52 rounded-xl py-1 z-50 shadow-2xl"
                            style="background-color:#F1F3EC;border:1px solid #E0DFD4;">
                            <a href="{{ route('reports.cashflow') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                Laporan Arus Kas
                            </a>
                            <a href="{{ route('reports.balancesheet') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                                Neraca Keuangan
                            </a>
                        </div>
                    </div>
                    @endcan

                    {{-- Master Dropdown --}}
                    @can('manage-transactions')
                    <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                        <button @click="open=!open"
                            class="flex items-center gap-1 px-3 py-2 text-sm rounded-lg transition-colors"
                            style="color:#586359;"
                            :style="open ? 'color:#17231E;background:rgba(22,74,64,0.1);' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Master
                            <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="none" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill="currentColor"/></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute left-0 top-full mt-1 w-48 rounded-xl py-1 z-50 shadow-2xl"
                            style="background-color:#F1F3EC;border:1px solid #E0DFD4;">
                            <a href="{{ route('accounts.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                Akun &amp; Kas
                            </a>
                            <a href="{{ route('categories.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                Kategori
                            </a>
                        </div>
                    </div>
                    @endcan

                    {{-- Admin Dropdown --}}
                    @can('manage-admin')
                    <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                        <button @click="open=!open"
                            class="flex items-center gap-1 px-3 py-2 text-sm rounded-lg transition-colors"
                            style="color:#586359;"
                            :style="open ? 'color:#17231E;background:rgba(22,74,64,0.1);' : ''">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin
                            <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="none" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill="currentColor"/></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            class="absolute left-0 top-full mt-1 w-52 rounded-xl py-1 z-50 shadow-2xl"
                            style="background-color:#F1F3EC;border:1px solid #E0DFD4;">
                            <a href="{{ route('users.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Manajemen Pengguna
                            </a>
                            <a href="{{ route('role-access.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Pengaturan Akses Role
                            </a>
                            <a href="{{ route('notices.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                Pengumuman
                            </a>
                            <a href="{{ route('citizen-reports.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                Laporan Warga
                            </a>
                            <a href="{{ route('emergency-alerts.index') }}" wire:navigate @click="open=false"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                                style="color:#586359;"
                                onmouseover="this.style.color='#B0402C';this.style.backgroundColor='rgba(176,64,44,0.08)'"
                                onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Alert Darurat
                            </a>
                        </div>
                    </div>
                    @endcan
                </div>
            </div>

            {{-- Right: notifications + user + hamburger --}}
            <div class="flex items-center gap-1">
                {{-- Notification Bell (visible on all screen sizes) --}}
                <livewire:notification-bell guard="web" />

            <div class="flex items-center">
                <div x-data="{ open: false }" class="relative" @click.outside="open=false">
                    <button @click="open=!open"
                        class="flex items-center gap-2 px-2 sm:px-3 py-2 rounded-xl text-sm transition-colors"
                        style="border:1px solid #E0DFD4;background:#ffffff;"
                        :style="open ? 'border-color:rgba(22,74,64,0.4);' : ''"
                        onmouseover="this.style.borderColor='rgba(22,74,64,0.4)'"
                        onmouseout="if(!{{ 'false' }})this.style.borderColor='#E0DFD4'">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                            style="background:#F1F3EC;color:#17231E;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"
                            class="hidden sm:block text-sm font-medium max-w-24 truncate" style="color:#17231E;"></span>
                        <svg class="w-3 h-3 transition-transform" :class="open?'rotate-180':''" fill="none" viewBox="0 0 20 20" style="color:#909A8F;"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" fill="currentColor"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 top-full mt-1 w-48 rounded-xl py-1 z-50 shadow-2xl"
                        style="background-color:#F1F3EC;border:1px solid #E0DFD4;">
                        <div class="px-4 py-2.5 border-b" style="border-color:#F1F3EC;">
                            @php
                                $roleLabels = [
                                    'super_admin' => 'Super Admin', 'admin' => 'Admin',
                                    'bendahara' => 'Bendahara', 'ketua_dkm' => 'Ketua DKM',
                                    'dkm' => 'DKM', 'perumahan' => 'Perumahan',
                                    'pengurus_rt' => 'Pengurus RT',
                                ];
                            @endphp
                            <p class="text-xs font-semibold" style="color:#17231E;">{{ $roleLabels[auth()->user()->role] ?? ucfirst(auth()->user()->role) }}</p>
                            <p class="text-xs truncate" style="color:#909A8F;">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" wire:navigate @click="open=false"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                            style="color:#586359;"
                            onmouseover="this.style.color='#164A40';this.style.backgroundColor='rgba(22,74,64,0.08)'"
                            onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profil Saya
                        </a>
                        <div style="height:1px;background:#F1F3EC;margin:2px 0;"></div>
                        <button wire:click="logout" @click="open=false"
                            class="w-full flex items-center gap-2 px-4 py-2.5 text-sm transition-colors"
                            style="color:#586359;"
                            onmouseover="this.style.color='#B0402C';this.style.backgroundColor='rgba(176,64,44,0.08)'"
                            onmouseout="this.style.color='#586359';this.style.backgroundColor=''">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </div>
                </div>
            </div>

            </div>{{-- /right cluster --}}
        </div>
    </div>

    {{-- Mobile Menu — bottom sheet --}}
    <div x-show="open" x-cloak @click="open=false"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="sm:hidden fixed inset-0 z-40" style="background:rgba(0,0,0,0.1);"></div>
    <div x-show="open" x-cloak
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
        class="sm:hidden fixed inset-x-0 bottom-0 z-50 rounded-t-2xl max-h-[78vh] overflow-y-auto overscroll-contain"
        style="background-color:#F1F3EC;border-top:1px solid #E0DFD4;padding-bottom:calc(4rem + env(safe-area-inset-bottom,0px));">
        <div class="sticky top-0 z-10 flex items-center justify-between px-4 py-3" style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
            <span class="text-sm font-semibold" style="color:#17231E;">Menu</span>
            <button @click="open=false" type="button" class="p-1 rounded-lg" style="color:#909A8F;" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" wire:navigate @click="open=false"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Dashboard</a>
        </div>

        @canany(['manage-residents', 'manage-ipl', 'manage-perumahan', 'manage-programs'])
        <div class="px-4 py-3 border-t" style="border-color:#F1F3EC;">
            <p class="text-xs font-semibold px-3 mb-2" style="color:#17231E;">Perumahan</p>
            @can('manage-residents')
            <a href="{{ route('residents.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Data Penghuni</a>
            <a href="{{ route('house-blocks.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Data Blok Rumah</a>
            @endcan
            @can('manage-ipl')
            <a href="{{ route('ipl.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">IPL Iuran Perumahan</a>
            <a href="{{ route('ipl.report') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Laporan IPL</a>
            <a href="{{ route('ipl.tariffs') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Pengaturan Tarif</a>
            @endcan
            @can('manage-perumahan')
            <a href="{{ route('perumahan.transaksi') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Transaksi Perumahan</a>
            @endcan
            @can('manage-programs')
            <a href="{{ route('campaigns.index') . '?org=perumahan' }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Program Perumahan</a>
            @endcan
        </div>
        @endcanany

        @canany(['manage-dkm', 'manage-programs'])
        <div class="px-4 py-3 border-t" style="border-color:#F1F3EC;">
            <p class="text-xs font-semibold px-3 mb-2" style="color:#17231E;">DKM Masjid</p>
            @can('manage-dkm')
            <a href="{{ route('transactions.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Transaksi DKM</a>
            <a href="{{ route('payment-requests.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Konfirmasi Donasi</a>
            @endcan
            @can('manage-programs')
            <a href="{{ route('campaigns.index') . '?org=dkm' }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Program DKM</a>
            @endcan
        </div>
        @endcanany

        @can('view-reports')
        <div class="px-4 py-3 border-t" style="border-color:#F1F3EC;">
            <p class="text-xs font-semibold px-3 mb-2" style="color:#17231E;">Laporan</p>
            <a href="{{ route('reports.cashflow') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Laporan Arus Kas</a>
            <a href="{{ route('reports.balancesheet') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Neraca Keuangan</a>
        </div>
        @endcan

        @can('manage-transactions')
        <div class="px-4 py-3 border-t" style="border-color:#F1F3EC;">
            <p class="text-xs font-semibold px-3 mb-2" style="color:#17231E;">Master Data</p>
            <a href="{{ route('accounts.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Akun &amp; Kas</a>
            <a href="{{ route('categories.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Kategori</a>
        </div>
        @endcan

        @can('manage-admin')
        <div class="px-4 py-3 border-t" style="border-color:#F1F3EC;">
            <p class="text-xs font-semibold px-3 mb-2" style="color:#B0402C;">Admin</p>
            <a href="{{ route('users.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Manajemen Pengguna</a>
            <a href="{{ route('role-access.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Pengaturan Akses Role</a>
            <a href="{{ route('notices.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Pengumuman</a>
            <a href="{{ route('citizen-reports.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#586359;">Laporan Warga</a>
            <a href="{{ route('emergency-alerts.index') }}" wire:navigate @click="open=false" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm" style="color:#B0402C;">Alert Darurat</a>
        </div>
        @endcan

    </div>

    {{-- Mobile bottom navigation bar --}}
    <div class="sm:hidden fixed inset-x-0 bottom-0 z-30 flex"
        style="background:#ffffff;border-top:1px solid rgba(22,74,64,0.15);padding-bottom:env(safe-area-inset-bottom,0px);">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5"
            style="{{ request()->routeIs('dashboard') ? 'color:#17231E;' : 'color:#909A8F;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Home
        </a>
        @can('manage-ipl')
        <a href="{{ route('ipl.index') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5"
            style="{{ request()->routeIs('ipl.*') ? 'color:#17231E;' : 'color:#909A8F;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
            IPL
        </a>
        @endcan
        @can('manage-perumahan')
        <a href="{{ route('perumahan.transaksi') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5"
            style="{{ request()->routeIs('perumahan.*') ? 'color:#17231E;' : 'color:#909A8F;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Transaksi
        </a>
        @elsecan('manage-dkm')
        <a href="{{ route('transactions.index') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5"
            style="{{ request()->routeIs('transactions.*') ? 'color:#17231E;' : 'color:#909A8F;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Transaksi
        </a>
        @endcan
        @can('manage-programs')
        <a href="{{ route('campaigns.index') }}" wire:navigate class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5"
            style="{{ request()->routeIs('campaigns.*') ? 'color:#17231E;' : 'color:#909A8F;' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            Program
        </a>
        @endcan
        <button type="button" @click="open = !open" class="flex-1 flex flex-col items-center py-2 text-xs gap-0.5"
            :style="open ? 'color:#17231E;' : 'color:#909A8F;'" style="color:#909A8F;">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            Menu
        </button>
    </div>
</nav>
