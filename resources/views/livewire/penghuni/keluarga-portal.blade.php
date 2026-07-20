<div>

    @if(session('family_login_name'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(107,91,149,0.08);border:1px solid rgba(107,91,149,0.25);color:#6B5B95;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Anda masuk sebagai <strong>{{ session('family_login_name') }}</strong> — berbagi akun rumah tangga {{ $resident->name }}.
        </div>
    @endif

    @if(session('success'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Anggota Keluarga</h2>
            <p class="text-sm mt-0.5" style="color:#586359;">Kelola data anggota keluarga yang tinggal bersama Anda</p>
        </div>
        <a href="{{ route('penghuni.keluarga.create') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
            style="background:#164A40;color:#ffffff;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Anggota
        </a>
    </div>

    @php
        $relLabels = [
            'istri'      => 'Istri',
            'suami'      => 'Suami',
            'anak'       => 'Anak',
            'orang_tua'  => 'Orang Tua',
            'mertua'     => 'Mertua',
            'saudara'    => 'Saudara',
            'lainnya'    => 'Lainnya',
        ];
        $relColors = [
            'istri'      => 'background:rgba(236,72,153,0.1);color:#db2777;border:1px solid rgba(236,72,153,0.25);',
            'suami'      => 'background:rgba(59,130,246,0.1);color:#2563eb;border:1px solid rgba(59,130,246,0.25);',
            'anak'       => 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);',
            'orang_tua'  => 'background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.25);',
            'mertua'     => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.25);',
            'saudara'    => 'background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.25);',
            'lainnya'    => 'background:rgba(107,114,128,0.1);color:#586359;border:1px solid rgba(107,114,128,0.25);',
        ];
    @endphp

    {{-- Kepala Keluarga (penghuni yang sedang login) --}}
    <div class="rounded-2xl p-5 flex items-start gap-4 mb-4" style="background:#ffffff;border:1px solid #164A40;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.1);">

        {{-- Avatar / Photo --}}
        @if($resident->photo)
            <img src="{{ Storage::disk('public')->url($resident->photo) }}" alt="{{ $resident->name }}"
                 class="w-12 h-12 rounded-xl object-cover shrink-0"
                 style="border:1px solid rgba(22,74,64,0.3);">
        @else
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-sm font-bold shrink-0"
                style="background:#164A40;color:#ffffff;">
                {{ strtoupper(substr($resident->name, 0, 1)) }}
            </div>
        @endif

        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="font-semibold text-sm" style="color:#17231E;">{{ $resident->name }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                    style="background:#164A40;color:#ffffff;">
                    Kepala Keluarga
                </span>
            </div>
            <div class="flex items-center gap-3 mt-1.5 text-xs flex-wrap" style="color:#909A8F;">
                @if($resident->gender)
                    <span style="color:{{ $resident->gender === 'laki-laki' ? '#2563eb' : '#db2777' }};">
                        {{ $resident->gender === 'laki-laki' ? 'Laki-laki' : 'Perempuan' }}
                    </span>
                @endif
                @if($resident->birth_date)
                    <span style="color:#909A8F;">@if($resident->gender)&middot; @endif{{ $resident->birth_date->format('d M Y') }}</span>
                @endif
                @if($resident->phone)
                    <span style="color:#909A8F;">&middot; {{ $resident->phone }}</span>
                @endif
                @if($resident->nik)
                    <span style="color:#909A8F;">&middot; NIK: {{ $resident->nik }}</span>
                @endif
            </div>
            @if($resident->notes)
                <p class="text-xs mt-1.5 italic" style="color:#909A8F;">{{ $resident->notes }}</p>
            @endif
            <p class="text-xs mt-1.5 italic" style="color:#909A8F;">Ini adalah data akun Anda</p>
        </div>

        <a href="{{ route('penghuni.keluarga.diri') }}" wire:navigate
            class="p-1.5 rounded-lg transition-colors shrink-0" style="color:#17231E;"
            title="Edit data diri">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </a>
    </div>

    {{-- Member List --}}
    @if($members->isEmpty())
        <div class="rounded-2xl p-6 sm:p-12 text-center" style="background:#ffffff;border:1px dashed #E0DFD4;">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#164A40"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-sm font-medium" style="color:#909A8F;">Belum ada anggota keluarga</p>
            <p class="text-xs mt-1" style="color:#909A8F;">Klik "Tambah Anggota" untuk mulai menambahkan data keluarga</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($members as $member)
            <div class="rounded-2xl p-5 flex items-start gap-4" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);"
                wire:key="member-{{ $member->id }}">

                {{-- Avatar / Photo --}}
                @if($member->photo)
                    <img src="{{ Storage::disk('public')->url($member->photo) }}" alt="{{ $member->name }}"
                         class="w-12 h-12 rounded-xl object-cover shrink-0"
                         style="border:1px solid rgba(22,74,64,0.3);">
                @else
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-sm font-bold shrink-0"
                        style="background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-sm" style="color:#17231E;">{{ $member->name }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                            style="{{ $relColors[$member->relationship] ?? 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                            {{ $relLabels[$member->relationship] ?? ucfirst($member->relationship) }}
                        </span>
                        @if($member->canLogin())
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium"
                            style="background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.25);"
                            title="Anggota ini punya akses login sendiri">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Bisa Login
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 mt-1.5 text-xs flex-wrap" style="color:#909A8F;">
                        <span style="color:{{ $member->gender === 'laki-laki' ? '#2563eb' : '#db2777' }};">
                            {{ $member->gender === 'laki-laki' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        @if($member->birth_date)
                            <span style="color:#909A8F;">&middot; {{ $member->birth_date->format('d M Y') }}</span>
                        @endif
                        @if($member->nik)
                            <span style="color:#909A8F;">&middot; NIK: {{ $member->nik }}</span>
                        @endif
                    </div>
                    @if($member->notes)
                        <p class="text-xs mt-1.5 italic" style="color:#909A8F;">{{ $member->notes }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('penghuni.keluarga.edit', $member->id) }}" wire:navigate
                        class="p-1.5 rounded-lg transition-colors" style="color:#17231E;"
                        title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <button wire:click="delete({{ $member->id }})"
                        wire:confirm="Hapus anggota keluarga {{ $member->name }}?"
                        class="p-1.5 rounded-lg transition-colors" style="color:#B0402C;"
                        title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
