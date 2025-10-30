<div> {{-- Div pembungkus utama komponen Livewire --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Tampilkan pesan error jika pengambilan data gagal --}}
            @if (session()->has('error'))
                <div role="alert" class="alert alert-error shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Grid Statistik (Komponen stats DaisyUI) --}}
            <div class="stats shadow w-full stats-vertical lg:stats-horizontal mb-6">
                <div class="stat">
                    <div class="stat-title">Total Saldo Kas</div>
                    {{-- Cek apakah $totalBalance numerik sebelum format --}}
                    <div class="stat-value">
                        @if(isset($totalBalance) && is_numeric($totalBalance))
                            Rp {{ number_format($totalBalance, 0, ',', '.') }}
                        @else
                            {{ $totalBalance ?? 'N/A' }} {{-- Tampilkan 'Error' atau default 'N/A' --}}
                        @endif
                    </div>
                    <div class="stat-desc">Di semua akun</div>
                </div>

                <div class="stat">
                    <div class="stat-title">Pemasukan Bulan Ini</div>
                    <div class="stat-value text-success">
                         @if(isset($monthlyIncome) && is_numeric($monthlyIncome))
                            Rp {{ number_format($monthlyIncome, 0, ',', '.') }}
                        @else
                            {{ $monthlyIncome ?? 'N/A' }}
                        @endif
                    </div>
                    <div class="stat-desc">Periode: {{ now()->format('F Y') }}</div>
                </div>

                <div class="stat">
                    <div class="stat-title">Pengeluaran Bulan Ini</div>
                    <div class="stat-value text-error">
                        @if(isset($monthlyExpense) && is_numeric($monthlyExpense))
                            Rp {{ number_format($monthlyExpense, 0, ',', '.') }}
                        @else
                            {{ $monthlyExpense ?? 'N/A' }}
                        @endif
                    </div>
                    <div class="stat-desc">Periode: {{ now()->format('F Y') }}</div>
                </div>
            </div>

            {{-- Tabel Transaksi Terakhir (Komponen card dan table DaisyUI) --}}
            <div class="card bg-base-100 shadow-xl mt-6">
                <div class="card-body">
                    <h2 class="card-title">Transaksi Terakhir</h2>
                    <div class="overflow-x-auto mt-4">
                        <table class="table table-sm table-zebra w-full">
                            {{-- Header Tabel --}}
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Akun</th>
                                    <th class="text-right">Jumlah</th>
                                </tr>
                            </thead>
                            {{-- Body Tabel --}}
                            <tbody>
                                {{-- Cek jika $recentTransactions ada dan tidak kosong --}}
                                @forelse($recentTransactions ?? [] as $tx)
                                    <tr class="hover">
                                        {{-- Tanggal Transaksi --}}
                                        <td>{{ optional($tx->transaction_date)->format('d/m/Y') ?? 'N/A' }}</td>
                                        {{-- Keterangan --}}
                                        <td>{{ $tx->description ?? '-' }}</td>
                                        {{-- Nama Kategori (pakai optional() untuk keamanan) --}}
                                        <td><div class="badge badge-ghost badge-sm">{{ optional($tx->category)->name ?? '-' }}</div></td>
                                        {{-- Nama Akun (pakai optional() untuk keamanan) --}}
                                        <td><div class="badge badge-outline badge-sm">{{ optional($tx->account)->name ?? '-' }}</div></td>
                                        {{-- Jumlah (Debit/Kredit) --}}
                                        <td class="text-right font-mono {{ ($tx->type ?? '') == 'debit' ? 'text-success' : 'text-error' }}">
                                            {{ ($tx->type ?? '') == 'debit' ? '+' : '-' }}
                                            Rp {{ number_format($tx->amount ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Pesan jika tidak ada transaksi --}}
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Belum ada transaksi terbaru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> {{-- Akhir Card Transaksi --}}

            <!-- ===== BAGIAN BARU: KAMPANYE AKTIF ===== -->
            <div class="card bg-base-100 shadow-xl mt-6">
                <div class="card-body">
                    <h2 class="card-title">Program/Kampanye Aktif</h2>
                    <div class="mt-4 space-y-4">
                         {{-- Cek jika $activeCampaigns ada dan bisa di-loop --}}
                        @forelse($activeCampaigns ?? [] as $campaign)
                            <div wire:key="dash-campaign-{{ $campaign->id }}">
                                {{-- Nama Kampanye dan Target --}}
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-semibold">{{ $campaign->name }}</h3>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        Target: Rp {{ number_format($campaign->target_amount ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                                {{-- Perhitungan Progress --}}
                                @php
                                    $target = (float)($campaign->target_amount ?? 0);
                                    // Akses properti virtual dari withSum
                                    $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                    // Hitung progress, tangani target 0
                                    $progress = ($target > 0) ? min(100, ($raised / $target) * 100) : ($raised > 0 ? 100 : 0);
                                @endphp
                                {{-- Progress Bar DaisyUI --}}

                                <progress class="progress progress-success w-full" value="{{ $progress }}" max="100"></progress>
                                {{-- Detail Progress --}}
                                <div class="flex justify-between text-xs mt-1">
                                    <span>Terkumpul: Rp {{ number_format($raised, 0, ',', '.') }}</span>
                                    <span>{{ number_format($progress, 1) }}%</span>
                                </div>
                            </div>
                            {{-- Tambahkan pemisah jika bukan item terakhir --}}
                            @if(!$loop->last)
                                <div class="divider my-1"></div>
                            @endif
                        @empty
                            {{-- Pesan jika tidak ada kampanye aktif --}}
                            <p class="text-center text-gray-500 dark:text-gray-400 py-4">Tidak ada program/kampanye yang sedang aktif.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- ===== AKHIR BAGIAN BARU ===== -->

        </div> {{-- Akhir Max Width Container --}}
    </div> {{-- Akhir Py-12 Padding --}}
</div> {{-- Akhir Div Pembungkus Utama --}}
