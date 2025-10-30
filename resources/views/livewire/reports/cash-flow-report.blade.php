<div> {{-- Div pembungkus utama --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Arus Kas (Cash Flow)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

             {{-- Tampilkan pesan error jika perhitungan gagal --}}
            @if (session()->has('report_error'))
                <div role="alert" class="alert alert-error shadow-lg mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Error: {{ session('report_error') }}</span>
                </div>
            @endif

            <div class="card bg-base-100 shadow-xl mb-6">
                <div class="card-body flex-row items-center gap-4">
                    <div class="form-control">
                        <label class="label-text">Pilih Bulan:</label>
                        {{-- wire:model.live akan memicu re-render saat filter diubah --}}
                        <select wire:model.live="month" class="select select-bordered select-sm">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 10)) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label-text">Pilih Tahun:</label>
                        <select wire:model.live="year" class="select select-bordered select-sm">
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Muncul otomatis saat Livewire sedang memproses (misal ganti filter) --}}
            <div wire:loading.flex class="w-full justify-center items-center p-4">
                <span class="loading loading-lg loading-spinner text-primary mr-3"></span>
                <span>Sedang menghitung laporan...</span>
            </div>

            {{-- Konten ini disembunyikan saat loading --}}
            <div wire:loading.remove>
                <div class="text-center mb-4">
                    <h3 class="text-lg font-semibold">Laporan Arus Kas</h3>
                    {{-- Pastikan reportData ada sebelum mengakses index --}}
                    @isset($this->reportData)
                        <p>Periode: {{ \Carbon\Carbon::parse($this->reportData['startDate'])->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($this->reportData['endDate'])->format('d F Y') }}</p>
                    @endisset
                </div>

                {{-- Hanya tampilkan jika reportData ada --}}
                @isset($this->reportData)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">
                        <div class="stat bg-base-100 shadow rounded-lg">
                            <div class="stat-title">Saldo Awal Periode</div>
                            <div class="stat-value text-gray-600">Rp {{ number_format($this->reportData['startingBalance'], 0, ',', '.') }}</div>
                        </div>
                        <div class="stat bg-base-100 shadow rounded-lg">
                            <div class="stat-title">Total Pemasukan</div>
                            <div class="stat-value text-success">Rp {{ number_format($this->reportData['totalIncome'], 0, ',', '.') }}</div>
                        </div>
                        <div class="stat bg-base-100 shadow rounded-lg">
                            <div class="stat-title">Total Pengeluaran</div>
                            <div class="stat-value text-error">Rp {{ number_format($this->reportData['totalExpense'], 0, ',', '.') }}</div>
                        </div>
                        <div class="stat bg-base-100 shadow rounded-lg col-span-1 md:col-span-3">
                            <div class="stat-title">Saldo Akhir Periode (Perhitungan)</div>
                            <div class="stat-value">Rp {{ number_format($this->reportData['endingBalance'], 0, ',', '.') }}</div>
                            {{-- Validasi Saldo --}}
                            @if(abs($this->reportData['discrepancy']) > 0.01)
                                <div class="stat-desc text-error font-bold">
                                    Peringatan! Saldo akhir perhitungan tidak sesuai (Selisih: Rp {{ number_format($this->reportData['discrepancy'], 0, ',', '.') }}). Periksa transaksi.
                                </div>
                            @else
                                <div class="stat-desc text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-4 h-4 stroke-current mr-1" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Saldo terverifikasi.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="card bg-base-100 shadow">
                            <div class="card-body">
                                <h3 class="card-title text-success border-b pb-2">Rincian Pemasukan</h3>
                                <ul class="list-disc pl-5 mt-2 space-y-1 text-sm">
                                    @forelse($this->reportData['incomeSummary'] as $item)
                                        <li class="flex justify-between">
                                            <span>{{ $item->name }}</span>
                                            <span class="font-mono font-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                        </li>
                                    @empty
                                        <li>Tidak ada pemasukan pada periode ini.</li>
                                    @endforelse
                                    <li class="flex justify-between font-bold border-t pt-2 mt-2">
                                        <span>TOTAL PEMASUKAN</span>
                                        <span class="font-mono">Rp {{ number_format($this->reportData['totalIncome'], 0, ',', '.') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card bg-base-100 shadow">
                            <div class="card-body">
                                <h3 class="card-title text-error border-b pb-2">Rincian Pengeluaran</h3>
                                <ul class="list-disc pl-5 mt-2 space-y-1 text-sm">
                                    @forelse($this->reportData['expenseSummary'] as $item)
                                        <li class="flex justify-between">
                                            <span>{{ $item->name }}</span>
                                            <span class="font-mono font-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                        </li>
                                    @empty
                                        <li>Tidak ada pengeluaran pada periode ini.</li>
                                    @endforelse
                                    <li class="flex justify-between font-bold border-t pt-2 mt-2">
                                        <span>TOTAL PENGELUARAN</span>
                                        <span class="font-mono">Rp {{ number_format($this->reportData['totalExpense'], 0, ',', '.') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                 {{-- Tampilkan pesan jika data tidak tersedia karena error --}}
                 <p class="text-center text-error">Tidak dapat menampilkan data laporan saat ini.</p>
                @endisset
            </div> {{-- Akhir wire:loading.remove --}}

        </div> {{-- Akhir Max Width Container --}}
    </div> {{-- Akhir Py-12 Padding --}}
</div> {{-- Akhir Div Pembungkus Utama --}}
