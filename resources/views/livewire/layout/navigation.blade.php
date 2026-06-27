<?php
use App\Livewire\Actions\Logout;
$logout = function (Logout $logout) {
    $logout();
    $this->redirect('/', navigate: true);
};
?>
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Dashboard -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                <!-- Perumahan Dropdown -->
                @canany(['manage-residents', 'manage-ipl'])
                <div class="hidden sm:flex sm:items-center sm:ms-4">
                    <x-dropdown align="left" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>🏘️ Perumahan</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @can('manage-residents')
                            <x-dropdown-link :href="route('residents.index')" wire:navigate>
                                👥 Data Penghuni
                            </x-dropdown-link>
                            @endcan
                            @can('manage-ipl')
                            <x-dropdown-link :href="route('ipl.index')" wire:navigate>
                                💰 IPL Security &amp; Sampah
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('ipl.report')" wire:navigate>
                                📊 Laporan IPL
                            </x-dropdown-link>
                            @endcan
                            @can('manage-transactions')
                            <x-dropdown-link :href="route('campaigns.index') . '?org=perumahan'" wire:navigate>
                                🏗️ Program Perumahan
                            </x-dropdown-link>
                            @endcan
                        </x-slot>
                    </x-dropdown>
                </div>
                @endcanany

                <!-- DKM Masjid Dropdown -->
                @can('manage-transactions')
                <div class="hidden sm:flex sm:items-center sm:ms-4">
                    <x-dropdown align="left" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>🕌 DKM Masjid</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('transactions.index')" wire:navigate>
                                📖 Transaksi DKM
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('campaigns.index') . '?org=dkm'" wire:navigate>
                                🌙 Program DKM
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endcan

                <!-- Laporan Dropdown -->
                @canany(['view-reports', 'manage-ipl'])
                <div class="hidden sm:flex sm:items-center sm:ms-4">
                    <x-dropdown align="left" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>📋 Laporan</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            @can('view-reports')
                            <x-dropdown-link :href="route('reports.cashflow')" wire:navigate>
                                💵 Laporan Arus Kas DKM
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('reports.balancesheet')" wire:navigate>
                                📊 Neraca DKM
                            </x-dropdown-link>
                            @endcan
                        </x-slot>
                    </x-dropdown>
                </div>
                @endcanany

                <!-- Data Master Dropdown -->
                @can('manage-transactions')
                <div class="hidden sm:flex sm:items-center sm:ms-4">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>⚙️ Master</div>
                                <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('accounts.index')" wire:navigate>
                                🏦 Manajemen Akun/Kas
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('categories.index')" wire:navigate>
                                🏷️ Manajemen Kategori
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
                @endcan

                <!-- Admin -->
                @can('manage-admin')
                <div class="hidden space-x-8 sm:-my-px sm:ms-4 sm:flex">
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')" wire:navigate>
                        👤 Admin
                    </x-nav-link>
                </div>
                @endcan
            </div>

            <!-- User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="ms-1"><svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-2 text-xs text-gray-400 border-b border-gray-100 dark:border-gray-700">
                            {{ auth()->user()->role ?? 'user' }}
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>{{ __('Log Out') }}</x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                Dashboard
            </x-responsive-nav-link>
        </div>

        @canany(['manage-residents', 'manage-ipl'])
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4"><div class="font-medium text-base text-gray-800 dark:text-gray-200">🏘️ Perumahan</div></div>
            <div class="mt-3 space-y-1">
                @can('manage-residents')
                <x-responsive-nav-link :href="route('residents.index')" wire:navigate>Data Penghuni</x-responsive-nav-link>
                @endcan
                @can('manage-ipl')
                <x-responsive-nav-link :href="route('ipl.index')" wire:navigate>IPL Security &amp; Sampah</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('ipl.report')" wire:navigate>Laporan IPL</x-responsive-nav-link>
                @endcan
                @can('manage-transactions')
                <x-responsive-nav-link :href="route('campaigns.index') . '?org=perumahan'" wire:navigate>Program Perumahan</x-responsive-nav-link>
                @endcan
            </div>
        </div>
        @endcanany

        @can('manage-transactions')
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4"><div class="font-medium text-base text-gray-800 dark:text-gray-200">🕌 DKM Masjid</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('transactions.index')" wire:navigate>Transaksi DKM</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('campaigns.index') . '?org=dkm'" wire:navigate>Program DKM</x-responsive-nav-link>
            </div>
        </div>
        @endcan

        @can('view-reports')
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4"><div class="font-medium text-base text-gray-800 dark:text-gray-200">📋 Laporan</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('reports.cashflow')" wire:navigate>Laporan Arus Kas DKM</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.balancesheet')" wire:navigate>Neraca DKM</x-responsive-nav-link>
            </div>
        </div>
        @endcan

        @can('manage-transactions')
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4"><div class="font-medium text-base text-gray-800 dark:text-gray-200">⚙️ Master</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('accounts.index')" wire:navigate>Manajemen Akun/Kas</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('categories.index')" wire:navigate>Manajemen Kategori</x-responsive-nav-link>
            </div>
        </div>
        @endcan

        @can('manage-admin')
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4"><div class="font-medium text-base text-gray-800 dark:text-gray-200">👤 Admin</div></div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('users.index')" wire:navigate>Manajemen Pengguna</x-responsive-nav-link>
            </div>
        </div>
        @endcan

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" wire:navigate>Profile</x-responsive-nav-link>
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>Log Out</x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
