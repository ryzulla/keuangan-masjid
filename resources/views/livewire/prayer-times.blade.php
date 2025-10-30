<div class="p-4 bg-gradient-to-br from-teal-50 to-emerald-100 dark:from-gray-700 dark:to-gray-800 rounded-lg shadow">
    {{-- Notifikasi Error --}}
    @if (session()->has('prayer_time_error'))
        <div role="alert" class="alert alert-warning alert-sm mb-3">
             <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-4 w-4" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <span>{{ session('prayer_time_error') }} Menggunakan jadwal default.</span>
        </div>
    @endif
    <h3 class="font-semibold text-lg text-center text-gray-700 dark:text-gray-200 mb-2">Jadwal Sholat - {{ $lokasi }}</h3>
    <p class="text-sm text-center text-gray-500 dark:text-gray-400 mb-4">
        {{ $tanggalMasehi }} | {{ $tanggalHijriah }}
    </p>
    <div class="overflow-x-auto">
        <table class="table table-sm w-full">
            <tbody>
                <tr class="hover">
                    <td>Imsak</td>
                    <td class="font-mono text-right">{{ $imsak }}</td>
                </tr>
                <tr class="hover font-bold bg-emerald-200 dark:bg-emerald-700">
                    <td>Subuh</td>
                    <td class="font-mono text-right">{{ $subuh }}</td>
                </tr>
                <tr class="hover">
                    <td>Terbit</td>
                    <td class="font-mono text-right">{{ $terbit }}</td>
                </tr>
                 <tr class="hover">
                    <td>Dhuha</td>
                    <td class="font-mono text-right">{{ $dhuha }}</td>
                </tr>
                <tr class="hover font-bold bg-emerald-200 dark:bg-emerald-700">
                    <td>Dzuhur</td>
                    <td class="font-mono text-right">{{ $dzuhur }}</td>
                </tr>
                <tr class="hover font-bold bg-emerald-200 dark:bg-emerald-700">
                    <td>Ashar</td>
                    <td class="font-mono text-right">{{ $ashar }}</td>
                </tr>
                <tr class="hover font-bold bg-emerald-200 dark:bg-emerald-700">
                    <td>Maghrib</td>
                    <td class="font-mono text-right">{{ $maghrib }}</td>
                </tr>
                 <tr class="hover font-bold bg-emerald-200 dark:bg-emerald-700">
                    <td>Isya</td>
                    <td class="font-mono text-right">{{ $isya }}</td>
                </tr>
            </tbody>
        </table>
    </div>
     <p class="text-xs text-center text-gray-400 dark:text-gray-500 mt-3">
        *Waktu untuk wilayah {{ $lokasi }} dan sekitarnya. Jadwal dapat bervariasi.
    </p>
</div>
