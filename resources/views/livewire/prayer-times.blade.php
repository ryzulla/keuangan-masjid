<div class="rounded-2xl p-4" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
    @if(session()->has('prayer_time_error'))
        <div class="mb-3 rounded-xl p-3 text-xs flex items-center gap-2" style="background:rgba(169,116,26,0.1);border:1px solid rgba(169,116,26,0.2);color:#A9741A;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>{{ session('prayer_time_error') }} Menggunakan jadwal default.</span>
        </div>
    @endif

    <div class="flex items-center gap-2 mb-3">
        <div class="w-1 h-5 rounded-full" style="background:#12805c;"></div>
        <h3 class="font-bold text-sm" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Jadwal Sholat — {{ $lokasi }}</h3>
    </div>
    <p class="text-xs text-center mb-4" style="color:#909A8F;">{{ $tanggalMasehi }} | {{ $tanggalHijriah }}</p>

    <div class="space-y-1">
        @php
            $prayers = [
                ['Imsak', $imsak, false],
                ['Subuh', $subuh, true],
                ['Terbit', $terbit, false],
                ['Dhuha', $dhuha, false],
                ['Dzuhur', $dzuhur, true],
                ['Ashar', $ashar, true],
                ['Maghrib', $maghrib, true],
                ['Isya', $isya, true],
            ];
        @endphp
        @foreach($prayers as [$name, $time, $highlight])
            <div class="flex justify-between items-center px-3 py-1.5 rounded-lg"
                style="{{ $highlight ? 'background:rgba(18,128,92,0.07);border:1px solid rgba(18,128,92,0.15);' : 'background:#ffffff;border:1px solid #F1F3EC;' }}">
                <span class="text-sm {{ $highlight ? 'font-semibold' : '' }}" style="{{ $highlight ? 'color:#12805c;' : 'color:#586359;' }}">{{ $name }}</span>
                <span class="font-mono text-sm {{ $highlight ? 'font-bold' : '' }}" style="{{ $highlight ? 'color:#12805c;' : 'color:#909A8F;' }}">{{ $time }}</span>
            </div>
        @endforeach
    </div>

    <p class="text-xs text-center mt-3" style="color:#909A8F;">*Waktu wilayah {{ $lokasi }}. Dapat bervariasi.</p>
</div>
