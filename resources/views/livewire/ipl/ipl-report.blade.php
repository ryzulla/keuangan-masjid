<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan IPL Perumahan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Period Selector --}}
            <div class="card bg-base-100 shadow mb-6">
                <div class="card-body py-3">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-sm font-semibold">Pilih Periode:</span>
                        @forelse($periods as $period)
                            <button wire:click="selectPeriod({{ $period->id }})"
                                class="btn btn-xs {{ $selectedPeriodId == $period->id ? 'btn-primary' : 'btn-outline' }}">
                                {{ $period->period_label }}
                            </button>
                        @empty
                            <span class="text-sm text-gray-400">Belum ada periode.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($currentPeriod)
                <div class="mb-4">
                    <h3 class="text-lg font-bold">Laporan Periode: {{ $currentPeriod->period_label }}</h3>
                    <p class="text-sm text-gray-500">
                        Tarif Security: Rp {{ number_format($currentPeriod->ipl_security_amount, 0, ',', '.') }} |
                        Tarif Sampah: Rp {{ number_format($currentPeriod->ipl_garbage_amount, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Summary Totals --}}
                @if(!empty($totals))
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                    <div class="stat bg-base-100 shadow rounded-box">
                        <div class="stat-title text-xs">Total Unit</div>
                        <div class="stat-value text-lg">{{ $totals['unit'] ?? 0 }}</div>
                    </div>
                    <div class="stat bg-base-100 shadow rounded-box">
                        <div class="stat-title text-xs">Total Tagihan</div>
                        <div class="stat-value text-sm">Rp {{ number_format($totals['tagihan'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat bg-base-100 shadow rounded-box">
                        <div class="stat-title text-xs">Terbayar</div>
                        <div class="stat-value text-sm text-success">Rp {{ number_format($totals['terbayar'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat bg-base-100 shadow rounded-box">
                        <div class="stat-title text-xs">Tunggakan</div>
                        <div class="stat-value text-sm text-error">Rp {{ number_format($totals['tunggakan'] ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat bg-base-100 shadow rounded-box">
                        <div class="stat-title text-xs">Unit Lunas</div>
                        <div class="stat-value text-sm text-success">{{ $totals['lunas'] ?? 0 }}</div>
                        <div class="stat-desc">dari {{ $totals['unit'] ?? 0 }} unit</div>
                    </div>
                </div>
                @endif

                {{-- Summary by Block --}}
                <div class="card bg-base-100 shadow-xl mb-6">
                    <div class="card-body">
                        <h3 class="card-title text-base">Rekapitulasi per Blok</h3>
                        <div class="overflow-x-auto">
                            <table class="table table-sm table-zebra w-full">
                                <thead>
                                    <tr>
                                        <th>Blok</th>
                                        <th class="text-center">Jumlah Unit</th>
                                        <th class="text-center">Lunas</th>
                                        <th class="text-center">Belum Bayar</th>
                                        <th class="text-center">Sebagian</th>
                                        <th class="text-right">Total Tagihan</th>
                                        <th class="text-right">Terbayar</th>
                                        <th class="text-right">Tunggakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($summaryByBlock as $row)
                                        @php $tunggakanRow = ($row->total_tagihan ?? 0) - ($row->total_terbayar ?? 0); @endphp
                                        <tr>
                                            <td class="font-bold">Blok {{ $row->block_letter ?? '?' }}</td>
                                            <td class="text-center">{{ $row->jumlah_unit }}</td>
                                            <td class="text-center text-success font-semibold">{{ $row->lunas }}</td>
                                            <td class="text-center text-error">{{ $row->belum_bayar }}</td>
                                            <td class="text-center text-warning">{{ $row->sebagian }}</td>
                                            <td class="text-right font-mono text-xs">Rp {{ number_format($row->total_tagihan ?? 0, 0, ',', '.') }}</td>
                                            <td class="text-right font-mono text-xs text-success">Rp {{ number_format($row->total_terbayar ?? 0, 0, ',', '.') }}</td>
                                            <td class="text-right font-mono text-xs {{ $tunggakanRow > 0 ? 'text-error' : '' }}">
                                                Rp {{ number_format($tunggakanRow, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center py-4 text-gray-400">Belum ada data tagihan untuk periode ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Unpaid Residents --}}
                @if($unpaidResidents->count() > 0)
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-base text-error">Daftar Tunggakan ({{ $unpaidResidents->count() }} unit)</h3>
                        <div class="overflow-x-auto">
                            <table class="table table-sm w-full">
                                <thead>
                                    <tr>
                                        <th>Blok</th>
                                        <th>Penghuni</th>
                                        <th class="text-right">Tagihan Security</th>
                                        <th class="text-right">Tagihan Sampah</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Terbayar</th>
                                        <th class="text-right">Sisa Tunggakan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unpaidResidents as $billing)
                                        <tr>
                                            <td class="font-mono font-semibold">{{ $billing->houseBlock?->block_code ?? '—' }}</td>
                                            <td>{{ $billing->resident?->name ?? '—' }}</td>
                                            <td class="text-right font-mono text-xs">Rp {{ number_format($billing->ipl_security_amount, 0, ',', '.') }}</td>
                                            <td class="text-right font-mono text-xs">Rp {{ number_format($billing->ipl_garbage_amount, 0, ',', '.') }}</td>
                                            <td class="text-right font-mono text-sm font-semibold">Rp {{ number_format($billing->total_amount, 0, ',', '.') }}</td>
                                            <td class="text-right font-mono text-xs text-success">Rp {{ number_format($billing->total_paid, 0, ',', '.') }}</td>
                                            <td class="text-right font-mono text-sm text-error font-semibold">Rp {{ number_format($billing->outstanding, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge badge-sm {{ $billing->status === 'partial' ? 'badge-warning' : 'badge-error' }}">
                                                    {{ $billing->status === 'partial' ? 'Sebagian' : 'Belum Bayar' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                    @if(!empty($totals) && ($totals['unit'] ?? 0) > 0)
                        <div class="alert alert-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Semua unit sudah melunasi IPL untuk periode ini!</span>
                        </div>
                    @endif
                @endif

            @else
                <div class="alert alert-info">
                    <span>Pilih periode IPL untuk melihat laporan.</span>
                </div>
            @endif

        </div>
    </div>
</div>
