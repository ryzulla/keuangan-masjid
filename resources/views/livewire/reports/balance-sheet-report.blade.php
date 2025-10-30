<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Laporan Neraca Keuangan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold">NERACA KEUANGAN</h3>
                <p class="text-lg">Posisi Keuangan per: {{ now()->format('d F Y') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class->
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title border-b-2 pb-2">ASET (Harta)</h2>
                            <div class="mt-2">
                                <h3 class="font-bold text-lg">Aset Lancar</h3>
                                <p class="text-sm mb-2">(Kas & Setara Kas)</p>
                                <ul class="space-y-1">
                                    @foreach($asetLancar as $account)
                                        <li class="flex justify-between items-center text-sm">
                                            <span>{{ $account->name }}</span>
                                            <span class="font-mono">Rp {{ number_format($account->balance, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="flex justify-between font-bold text-lg border-t-2 pt-2 mt-4">
                                <span>TOTAL ASET</span>
                                <span class="font-mono">Rp {{ number_format($totalAset, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class->
                     <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h2 class="card-title border-b-2 pb-2">LIABILITAS & EKUITAS</h2>
                            <div class="mt-2">
                                <h3 class="font-bold text-lg">Liabilitas (Utang)</h3>
                                <ul class="space-y-1">
                                    <li class="flex justify-between items-center text-sm">
                                        <span>Utang Pihak Ketiga</span>
                                        <span class="font-mono">Rp {{ number_format($totalLiabilitas, 0, ',', '.') }}</span>
                                    </li>
                                </ul>

                                <h3 class="font-bold text-lg mt-6">Ekuitas (Dana Umat)</h3>
                                <ul class="space-y-1">
                                    <li class="flex justify-between items-center text-sm">
                                        <span>Dana Umat (Aset - Liabilitas)</span>
                                        <span class="font-mono">Rp {{ number_format($totalEkuitas, 0, ',', '.') }}</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="flex justify-between font-bold text-lg border-t-2 pt-2 mt-4">
                                <span>TOTAL LIABILITAS & EKUITAS</span>
                                <span class="font-mono">Rp {{ number_format($totalLiabilitas + $totalEkuitas, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
