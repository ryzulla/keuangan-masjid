<div>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(107,91,149,0.1);border:1px solid rgba(107,91,149,0.3);color:#6B5B95;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('info') }}
            </div>
        @endif

        {{-- Header Banner --}}
        <div class="rounded-2xl p-6 mb-6 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Pengaturan Akses Role</h3>
                    <p class="text-sm mt-1" style="color:#17231E;">Atur menu & fitur yang dapat diakses oleh setiap role pengguna</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button wire:click="resetToDefault" wire:confirm="Reset semua pengaturan ke default?"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm transition-colors"
                        style="background:#F1F3EC;color:#586359;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#F1F3EC'" onmouseout="this.style.background='#F1F3EC'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset Default
                    </button>
                    <button wire:click="save"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">
                            <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Simpan Perubahan
                        </span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Super Admin Note --}}
        <div class="rounded-xl p-4 mb-5 flex items-start gap-3" style="background:rgba(176,64,44,0.07);border:1px solid rgba(176,64,44,0.2);">
            <svg class="w-5 h-5 shrink-0 mt-0.5" style="color:#B0402C;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <div>
                <p class="text-sm font-semibold" style="color:#B0402C;">Super Admin — Akses Penuh (tidak bisa diubah)</p>
                <p class="text-xs mt-0.5" style="color:#909A8F;">Role Super Admin selalu memiliki akses ke semua menu dan fitur secara otomatis.</p>
            </div>
        </div>

        {{-- Role Summary Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            @foreach($roles as $role)
            <div class="rounded-xl p-3 text-center" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                <div class="text-2xl font-bold mb-0.5" style="color:#17231E;">{{ $summary[$role] }}</div>
                <div class="text-xs" style="color:#909A8F;">{{ $roleLabels[$role] }}</div>
                <div class="text-xs mt-1" style="color:#909A8F;">dari {{ count($gates) }} fitur</div>
            </div>
            @endforeach
        </div>

        {{-- Permission Matrix --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="min-width:820px;">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                            <th class="text-left px-5 py-4 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;min-width:220px;">
                                Menu / Fitur
                            </th>
                            @foreach($roles as $role)
                            <th class="text-center px-3 py-4" style="min-width:100px;">
                                <div class="text-xs font-semibold uppercase tracking-wider" style="color:#17231E;">{{ $roleLabels[$role] }}</div>
                                <div class="text-xs mt-0.5" style="color:#909A8F;">{{ $summary[$role] }}/{{ count($gates) }}</div>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $currentGroup = null;
                            $groupColors = [
                                'Administrasi' => '#6B5B95',
                                'DKM Masjid'   => '#0d9488',
                                'Perumahan'    => '#164A40',
                            ];
                        @endphp

                        @foreach($gates as $gateKey => $gateInfo)
                            @if($gateInfo['group'] !== $currentGroup)
                                @php $currentGroup = $gateInfo['group']; @endphp
                                <tr style="background:#ffffff;">
                                    <td colspan="{{ count($roles) + 1 }}" class="px-5 py-2">
                                        <span class="text-xs font-bold uppercase tracking-widest"
                                            style="color:{{ $groupColors[$gateInfo['group']] ?? '#586359' }};">
                                            {{ $gateInfo['group'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endif

                            <tr style="border-bottom:1px solid #ffffff;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        @php
                                            $iconSvg = match($gateInfo['icon']) {
                                                'users'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>',
                                                'cog'     => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>',
                                                'chart'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>',
                                                'book'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
                                                'star'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>',
                                                'home'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                                                'receipt' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>',
                                                'cash'    => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>',
                                                default   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>',
                                            };
                                            $iconColor = $groupColors[$gateInfo['group']] ?? '#586359';
                                        @endphp
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0"
                                            style="background:rgba({{ hexdec(substr($iconColor,1,2)) }},{{ hexdec(substr($iconColor,3,2)) }},{{ hexdec(substr($iconColor,5,2)) }},0.1);">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="{{ $iconColor }}">{!! $iconSvg !!}</svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-sm" style="color:#17231E;">{{ $gateInfo['label'] }}</div>
                                            <div class="text-xs" style="color:#909A8F;">{{ $gateKey }}</div>
                                        </div>
                                    </div>
                                </td>

                                @foreach($roles as $role)
                                <td class="px-3 py-3.5 text-center">
                                    <label class="inline-flex items-center justify-center cursor-pointer">
                                        <input type="checkbox"
                                            wire:model="matrix.{{ $role }}.{{ $gateKey }}"
                                            @if($gateKey === 'manage-admin' && $role !== 'admin') disabled @endif
                                            class="sr-only peer">
                                        <div class="relative w-10 h-5 rounded-full transition-colors duration-200 peer-checked:opacity-100"
                                            style="background:#F1F3EC;border:1px solid #E0DFD4;"
                                            :class="$wire.matrix['{{ $role }}']['{{ $gateKey }}'] ? '' : ''">
                                            {{-- Custom toggle UI --}}
                                            <div class="w-10 h-5 rounded-full transition-all duration-200 flex items-center {{ ($matrix[$role][$gateKey] ?? false) ? '' : '' }}"
                                                style="{{ ($matrix[$role][$gateKey] ?? false) ? 'background:rgba(22,74,64,0.2);border:1px solid rgba(22,74,64,0.4);' : 'background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);' }}
                                                    {{ ($gateKey === 'manage-admin' && $role !== 'admin') ? 'opacity:0.35;cursor:not-allowed;' : '' }}">
                                                @if($matrix[$role][$gateKey] ?? false)
                                                    <svg class="w-3.5 h-3.5 mx-auto" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                @else
                                                    <svg class="w-3 h-3 mx-auto" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="px-5 py-4 flex items-center justify-between" style="border-top:1px solid #F1F3EC;background:#ffffff;">
                <div class="flex items-center gap-4 text-xs" style="color:#909A8F;">
                    <span class="flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded flex items-center justify-center" style="background:rgba(22,74,64,0.2);border:1px solid rgba(22,74,64,0.4);">
                            <svg class="w-2.5 h-2.5" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        Punya Akses
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-4 h-4 rounded flex items-center justify-center" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
                            <svg class="w-2.5 h-2.5" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </span>
                        Tidak Punya Akses
                    </span>
                </div>
                <button wire:click="save"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Simpan Perubahan</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>

        {{-- Info: Dashboard always accessible --}}
        <div class="rounded-xl p-4 mt-4 flex items-start gap-3" style="background:rgba(22,74,64,0.05);border:1px solid rgba(22,74,64,0.15);">
            <svg class="w-4 h-4 shrink-0 mt-0.5" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs" style="color:#586359;">
                <span style="color:#17231E;">Dashboard</span> selalu dapat diakses oleh semua pengguna yang sudah login, tidak perlu pengaturan khusus.
                Perubahan akses berlaku setelah pengguna login ulang atau session cache habis (maks. 1 jam).
            </p>
        </div>
    </div>
</div>
