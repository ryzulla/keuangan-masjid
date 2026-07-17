<div>

    @if(session('success'))
        <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold" style="color:#161e2d;font-family:'Manrope',serif;">Anggota Keluarga</h2>
            <p class="text-sm mt-0.5" style="color:#5c6368;">Kelola data anggota keluarga yang tinggal bersama Anda</p>
        </div>
        <a href="{{ route('penghuni.keluarga.create') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
            style="background:#1563df;color:#ffffff;">
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
            'orang_tua'  => 'background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.25);',
            'mertua'     => 'background:rgba(199,125,26,0.1);color:#c77d1a;border:1px solid rgba(199,125,26,0.25);',
            'saudara'    => 'background:rgba(99,102,241,0.1);color:#4f46e5;border:1px solid rgba(99,102,241,0.25);',
            'lainnya'    => 'background:rgba(107,114,128,0.1);color:#5c6368;border:1px solid rgba(107,114,128,0.25);',
        ];
    @endphp

    {{-- Member List --}}
    @if($members->isEmpty())
        <div class="rounded-2xl p-6 sm:p-12 text-center" style="background:#ffffff;border:1px dashed #e4e4e4;">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" viewBox="0 0 24 24" stroke="#1563df"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <p class="text-sm font-medium" style="color:#a3abb0;">Belum ada anggota keluarga</p>
            <p class="text-xs mt-1" style="color:#a3abb0;">Klik "Tambah Anggota" untuk mulai menambahkan data keluarga</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($members as $member)
            <div class="rounded-2xl p-5 flex items-start gap-4" style="background:#ffffff;border:1px solid #e4e4e4;box-shadow:0 1px 2px rgba(21,99,223,0.04),0 8px 20px -8px rgba(21,99,223,0.06);"
                wire:key="member-{{ $member->id }}">

                {{-- Avatar / Photo --}}
                @if($member->photo)
                    <img src="{{ Storage::disk('public')->url($member->photo) }}" alt="{{ $member->name }}"
                         class="w-12 h-12 rounded-xl object-cover shrink-0"
                         style="border:1px solid rgba(21,99,223,0.3);">
                @else
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-sm font-bold shrink-0"
                        style="background:rgba(21,99,223,0.1);color:#161e2d;border:1px solid rgba(21,99,223,0.2);">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-semibold text-sm" style="color:#161e2d;">{{ $member->name }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                            style="{{ $relColors[$member->relationship] ?? 'background:#f7f7f7;color:#5c6368;border:1px solid #e4e4e4;' }}">
                            {{ $relLabels[$member->relationship] ?? ucfirst($member->relationship) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-1.5 text-xs flex-wrap" style="color:#a3abb0;">
                        <span style="color:{{ $member->gender === 'laki-laki' ? '#2563eb' : '#db2777' }};">
                            {{ $member->gender === 'laki-laki' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        @if($member->birth_date)
                            <span style="color:#a3abb0;">&middot; {{ $member->birth_date->format('d M Y') }}</span>
                        @endif
                        @if($member->nik)
                            <span style="color:#a3abb0;">&middot; NIK: {{ $member->nik }}</span>
                        @endif
                    </div>
                    @if($member->notes)
                        <p class="text-xs mt-1.5 italic" style="color:#a3abb0;">{{ $member->notes }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('penghuni.keluarga.edit', $member->id) }}" wire:navigate
                        class="p-1.5 rounded-lg transition-colors" style="color:#161e2d;"
                        title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <button wire:click="delete({{ $member->id }})"
                        wire:confirm="Hapus anggota keluarga {{ $member->name }}?"
                        class="p-1.5 rounded-lg transition-colors" style="color:#c0453b;"
                        title="Hapus">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif

</div>
