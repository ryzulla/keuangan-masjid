<div> {{-- Wrapper utama komponen Livewire --}}
    {{-- Layout public.blade.php akan membungkus ini --}}

    {{-- Konten Utama --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12 md:py-16 space-y-10 md:space-y-12"> {{-- Padding & Spacing --}}

         {{-- Pesan Error Jika Gagal Load Data / Download --}}
        @if (session()->has('page_error'))
            <div role="alert" class="alert alert-error shadow-lg">
                 <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('page_error') }}</span>
                 <button class="btn btn-sm btn-ghost" @click="$el.closest('.alert').remove()">✕</button>
            </div>
        @endif

        {{-- 1. Section Hero/Judul --}}
        <div class="text-center pb-8">
            <div class="inline-block p-4 bg-gradient-to-br from-green-500 to-teal-600 rounded-full shadow-lg mb-4">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-gray-100 mb-3">Transparansi Keuangan Masjid</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Lihat ringkasan pemasukan, pengeluaran, program berjalan, dan jadwal sholat hari ini.</p>
        </div>

        {{-- Grid untuk Konten Utama --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- Kolom Kiri: Ringkasan & Jadwal Sholat --}}
            <div class="lg:col-span-1 space-y-8"> {{-- Kolom kiri --}}

                {{-- Card Ringkasan Keuangan --}}
                <div class="card bg-base-100 shadow-xl border border-gray-200 dark:border-gray-700">
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4 border-b pb-2">📊 Ringkasan Keuangan</h2>
                        <div class="space-y-4">
                            {{-- Bulan Lalu --}}
                            <div>
                                <h3 class="font-medium text-gray-700 dark:text-gray-300 text-base">Bulan Lalu ({{ \Carbon\Carbon::now()->subMonthNoOverflow()->isoFormat('MMMM YYYY') }})</h3>
                                <div class="flex justify-between items-center mt-1 text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Pemasukan:</span>
                                    <span class="font-semibold text-success">Rp {{ (isset($lastMonthIncome) && is_numeric($lastMonthIncome)) ? number_format($lastMonthIncome, 0, ',', '.') : ($lastMonthIncome ?? 'N/A') }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-1 text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Pengeluaran:</span>
                                    <span class="font-semibold text-error">Rp {{ (isset($lastMonthExpense) && is_numeric($lastMonthExpense)) ? number_format($lastMonthExpense, 0, ',', '.') : ($lastMonthExpense ?? 'N/A') }}</span>
                                </div>
                            </div>
                            <div class="divider my-2"></div>
                            {{-- Bulan Ini --}}
                            <div>
                                 <h3 class="font-medium text-gray-700 dark:text-gray-300 text-base">Bulan Ini ({{ \Carbon\Carbon::now()->isoFormat('MMMM YYYY') }})</h3>
                                <div class="flex justify-between items-center mt-1 text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Pemasukan:</span>
                                    <span class="font-semibold text-success">Rp {{ (isset($currentMonthIncome) && is_numeric($currentMonthIncome)) ? number_format($currentMonthIncome, 0, ',', '.') : ($currentMonthIncome ?? 'N/A') }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-1 text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Pengeluaran:</span>
                                    <span class="font-semibold text-error">Rp {{ (isset($currentMonthExpense) && is_numeric($currentMonthExpense)) ? number_format($currentMonthExpense, 0, ',', '.') : ($currentMonthExpense ?? 'N/A') }}</span>
                                </div>
                                 <span class="text-xs text-gray-500 italic block mt-1">(Data berjalan hingga hari ini)</span>
                            </div>
                        </div>

                         {{-- === Tombol Download Laporan === --}}
                        <div class="divider mt-6">Download Laporan Detail</div>
                        <div class="flex flex-col sm:flex-row gap-2 justify-center pt-2">
                            {{-- Tombol Bulan Ini --}}
                            <button wire:click="downloadMonthlyReport('current')" wire:loading.attr="disabled" wire:target="downloadMonthlyReport('current')" class="btn btn-sm btn-outline btn-info">
                                <span wire:loading wire:target="downloadMonthlyReport('current')" class="loading loading-spinner loading-xs"></span>
                                <span wire:loading.remove wire:target="downloadMonthlyReport('current')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                </span>
                                Laporan Bulan Ini (PDF)
                            </button>
                            {{-- Tombol Bulan Lalu --}}
                            <button wire:click="downloadMonthlyReport('last')" wire:loading.attr="disabled" wire:target="downloadMonthlyReport('last')" class="btn btn-sm btn-outline btn-secondary">
                                 <span wire:loading wire:target="downloadMonthlyReport('last')" class="loading loading-spinner loading-xs"></span>
                                 <span wire:loading.remove wire:target="downloadMonthlyReport('last')">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                 </span>
                                Laporan Bulan Lalu (PDF)
                            </button>
                        </div>
                         {{-- Indikator loading umum --}}
                         <div wire:loading wire:target="downloadMonthlyReport" class="text-xs text-center mt-2 text-gray-500">Memproses file PDF...</div>
                        {{-- === Akhir Tombol Download === --}}

                    </div>
                </div> {{-- Akhir Card Ringkasan --}}

                {{-- Card Jadwal Sholat --}}
                <div class="card bg-base-100 shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    {{-- Embed komponen jadwal sholat tanpa padding card-body --}}
                    @livewire('prayer-times')
                </div> {{-- Akhir Card Jadwal Sholat --}}

            </div> {{-- Akhir Kolom Kiri --}}


            {{-- Kolom Kanan: Program Berjalan --}}
            <div class="lg:col-span-2"> {{-- Kolom kanan --}}
                <div class="card bg-base-100 shadow-xl border border-gray-200 dark:border-gray-700 h-full"> {{-- h-full --}}
                    <div class="card-body">
                        <h2 class="card-title text-lg mb-4 border-b pb-2">🚀 Program & Kampanye Berjalan</h2>
                        <div class="space-y-6"> {{-- Tambah jarak antar item --}}
                            @forelse($activeCampaigns ?? [] as $campaign)
                                <div wire:key="welcome-campaign-{{ $campaign->id }}">
                                    <div class="flex justify-between items-start mb-2 flex-wrap gap-2"> {{-- items-start --}}
                                        <h3 class="font-semibold text-lg leading-tight">{{ $campaign->name }}</h3>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap text-right">
                                            Target:<br class="sm:hidden"/> <span class="font-medium">Rp {{ number_format($campaign->target_amount ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    @php
                                        $target = (float)($campaign->target_amount ?? 0);
                                        $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                        $progress = ($target > 0) ? min(100, ($raised / $target) * 100) : ($raised > 0 ? 100 : 0);
                                    @endphp
                                    {{-- Progress Bar dengan tooltip --}}
                                    <div class="tooltip tooltip-bottom w-full" data-tip="{{ number_format($progress, 1) }}% Tercapai">
                                        <progress class="progress progress-accent w-full" value="{{ $progress }}" max="100"></progress>
                                    </div>
                                    <div class="flex justify-between text-xs mt-1 text-gray-600 dark:text-gray-400">
                                        <span>Terkumpul: <span class="font-semibold text-gray-800 dark:text-gray-200">Rp {{ number_format($raised, 0, ',', '.') }}</span></span>
                                        {{-- Sisa Hari (Opsional) --}}
                                        @if($campaign->end_date)
                                            @php $daysLeft = now()->diffInDays($campaign->end_date, false); @endphp
                                            @if($daysLeft >= 0)
                                                <span>Sisa: {{ $daysLeft }} hari</span>
                                            @else
                                                 <span class="text-error">Telah Berakhir</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @if(!$loop->last) <div class="divider my-3"></div> @endif
                            @empty
                                <div class="text-center py-10">
                                    <p class="text-gray-500">Tidak ada program/kampanye yang sedang berjalan saat ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div> {{-- Akhir Kolom Kanan --}}

        </div> {{-- Akhir Grid Utama --}}

        {{-- Kalkulator Zakat Dihapus --}}

    </div> {{-- Akhir Max Width Container --}}
</div> {{-- Akhir Wrapper Utama --}}
