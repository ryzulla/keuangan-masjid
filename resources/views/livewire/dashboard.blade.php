<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session()->has('error'))
                <div role="alert" class="alert alert-error shadow-lg mb-4"><span>{{ session('error') }}</span></div>
            @endif

            {{-- Top Row: Two Finance Sections --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                {{-- Perumahan Section --}}
                <div class="card bg-base-100 shadow-xl border-l-4 border-blue-500">
                    <div class="card-body">
                        <h3 class="card-title text-blue-600 dark:text-blue-400">🏘️ Perumahan / RT</h3>
                        <div class="stats stats-vertical shadow-none bg-transparent w-full">
                            <div class="stat py-2 px-0">
                                <div class="stat-title text-xs">Total Kas Perumahan</div>
                                <div class="stat-value text-xl">
                                    @if(is_numeric($perumahanBalance))
                                        Rp {{ number_format($perumahanBalance, 0, ',', '.') }}
                                    @else N/A @endif
                                </div>
                            </div>
                        </div>

                        @if(!empty($iplSummary))
                        <div class="divider my-1 text-xs">IPL {{ $iplSummary['period'] }}</div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-base-200 rounded p-2">
                                <div class="text-xs text-gray-500">Total Tagihan</div>
                                <div class="text-sm font-bold">Rp {{ number_format($iplSummary['total_tagihan'], 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-success/10 rounded p-2">
                                <div class="text-xs text-gray-500">Terbayar</div>
                                <div class="text-sm font-bold text-success">Rp {{ number_format($iplSummary['total_terbayar'], 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-error/10 rounded p-2">
                                <div class="text-xs text-gray-500">Tunggakan</div>
                                <div class="text-sm font-bold text-error">Rp {{ number_format($iplSummary['tunggakan'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="text-xs text-gray-500">{{ $iplSummary['jumlah_lunas'] }}/{{ $iplSummary['jumlah_unit'] }} unit lunas</span>
                            @if($iplSummary['jumlah_unit'] > 0)
                                <progress class="progress progress-success w-full mt-1" value="{{ $iplSummary['jumlah_lunas'] }}" max="{{ $iplSummary['jumlah_unit'] }}"></progress>
                            @endif
                        </div>
                        @else
                            <p class="text-sm text-gray-400 mt-2">Belum ada data IPL. <a href="{{ route('ipl.index') }}" wire:navigate class="link link-primary">Buat periode IPL</a></p>
                        @endif

                        @if($activeCampaignsPerumahan->count() > 0)
                        <div class="divider my-1 text-xs">Program Perumahan Aktif</div>
                        @foreach($activeCampaignsPerumahan as $campaign)
                            @php
                                $target = (float)($campaign->target_amount ?? 0);
                                $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                $progress = ($target > 0) ? min(100, ($raised / $target) * 100) : ($raised > 0 ? 100 : 0);
                            @endphp
                            <div wire:key="dash-prum-{{ $campaign->id }}" class="text-xs">
                                <div class="flex justify-between"><span class="font-semibold">{{ $campaign->name }}</span><span>{{ number_format($progress, 0) }}%</span></div>
                                <progress class="progress progress-info w-full" value="{{ $progress }}" max="100"></progress>
                            </div>
                        @endforeach
                        @endif

                        <div class="card-actions mt-2">
                            @can('manage-ipl')
                            <a href="{{ route('ipl.index') }}" wire:navigate class="btn btn-sm btn-outline btn-primary">IPL</a>
                            @endcan
                            @can('manage-residents')
                            <a href="{{ route('residents.index') }}" wire:navigate class="btn btn-sm btn-outline">Penghuni</a>
                            @endcan
                        </div>
                    </div>
                </div>

                {{-- DKM Section --}}
                <div class="card bg-base-100 shadow-xl border-l-4 border-green-500">
                    <div class="card-body">
                        <h3 class="card-title text-green-600 dark:text-green-400">🕌 DKM Masjid</h3>
                        <div class="stats stats-vertical shadow-none bg-transparent w-full">
                            <div class="stat py-2 px-0">
                                <div class="stat-title text-xs">Total Kas DKM</div>
                                <div class="stat-value text-xl">
                                    @if(is_numeric($dkmBalance))
                                        Rp {{ number_format($dkmBalance, 0, ',', '.') }}
                                    @else N/A @endif
                                </div>
                                <div class="stat-desc">{{ now()->format('F Y') }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-center">
                            <div class="bg-success/10 rounded p-2">
                                <div class="text-xs text-gray-500">Pemasukan Bulan Ini</div>
                                <div class="text-sm font-bold text-success">Rp {{ number_format($monthlyIncomeDkm, 0, ',', '.') }}</div>
                            </div>
                            <div class="bg-error/10 rounded p-2">
                                <div class="text-xs text-gray-500">Pengeluaran Bulan Ini</div>
                                <div class="text-sm font-bold text-error">Rp {{ number_format($monthlyExpenseDkm, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        @if($activeCampaignsDkm->count() > 0)
                        <div class="divider my-1 text-xs">Program DKM Aktif</div>
                        @foreach($activeCampaignsDkm as $campaign)
                            @php
                                $target = (float)($campaign->target_amount ?? 0);
                                $raised = (float)($campaign->transactions_sum_amount ?? 0);
                                $progress = ($target > 0) ? min(100, ($raised / $target) * 100) : ($raised > 0 ? 100 : 0);
                            @endphp
                            <div wire:key="dash-dkm-{{ $campaign->id }}" class="text-xs">
                                <div class="flex justify-between"><span class="font-semibold">{{ $campaign->name }}</span><span>{{ number_format($progress, 0) }}%</span></div>
                                <progress class="progress progress-success w-full" value="{{ $progress }}" max="100"></progress>
                            </div>
                        @endforeach
                        @else
                            <p class="text-sm text-gray-400 mt-2">Tidak ada program DKM aktif.</p>
                        @endif

                        <div class="card-actions mt-2">
                            @can('manage-transactions')
                            <a href="{{ route('transactions.index') }}" wire:navigate class="btn btn-sm btn-outline btn-success">Transaksi</a>
                            @endcan
                            @can('view-reports')
                            <a href="{{ route('reports.cashflow') }}" wire:navigate class="btn btn-sm btn-outline">Laporan</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">Transaksi DKM Terbaru</h2>
                    <div class="overflow-x-auto mt-2">
                        <table class="table table-sm table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Kategori</th>
                                    <th>Akun</th>
                                    <th class="text-right">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions ?? [] as $tx)
                                    <tr class="hover">
                                        <td>{{ optional($tx->transaction_date)->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>{{ $tx->description ?? '-' }}</td>
                                        <td><div class="badge badge-ghost badge-sm">{{ optional($tx->category)->name ?? '-' }}</div></td>
                                        <td><div class="badge badge-outline badge-sm">{{ optional($tx->account)->name ?? '-' }}</div></td>
                                        <td class="text-right font-mono {{ ($tx->type ?? '') == 'debit' ? 'text-success' : 'text-error' }}">
                                            {{ ($tx->type ?? '') == 'debit' ? '+' : '-' }}
                                            Rp {{ number_format($tx->amount ?? 0, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-4 text-gray-400">Belum ada transaksi terbaru.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
