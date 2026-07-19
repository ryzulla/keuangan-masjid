<div>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session()->has('success'))
            <div class="mb-4 rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-4 rounded-xl p-3.5 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header Banner --}}
        <div class="rounded-2xl p-6 mb-5 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Manajemen Pengguna</h3>
                    <p class="text-sm mt-1" style="color:#17231E;">Kelola akun dan hak akses pengguna sistem</p>
                </div>
                <button wire:click="create()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Pengguna
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="overflow-x-auto hidden md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr style="background:#ffffff;border-bottom:1px solid #F1F3EC;">
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Nama</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Email</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Role</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold uppercase tracking-wider" style="color:#909A8F;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr style="border-bottom:1px solid #F1F3EC;" onmouseover="this.style.backgroundColor='#F1F3EC'" onmouseout="this.style.backgroundColor=''">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                            style="background:#F1F3EC;color:#17231E;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-medium" style="color:#17231E;">{{ $user->name }}</span>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                @if($user->resident_id)
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-medium" style="background:rgba(22,74,64,0.08);color:#164A40;">
                                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                                        Penghuni
                                                    </span>
                                                @endif
                                                @unless($user->is_active)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;">Nonaktif</span>
                                                @endunless
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-xs" style="color:#909A8F;">{{ $user->email }}</td>
                                <td class="px-5 py-3">
                                    @php $rc = $roleColors[$user->role] ?? '#586359'; @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                        style="background:{{ $rc }}1a;color:{{ $rc }};border:1px solid {{ $rc }}40;">
                                        {{ $roleLabels[$user->role] ?? ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        @if($user->resident_id && $user->id != auth()->id())
                                            <button wire:click="toggleActive({{ $user->id }})"
                                                wire:confirm="{{ $user->is_active ? 'Cabut akses admin penghuni ini?' : 'Aktifkan kembali akses admin penghuni ini?' }}"
                                                class="p-1.5 rounded-lg transition-colors" style="color:{{ $user->is_active ? '#A9741A' : '#12805c' }};"
                                                title="{{ $user->is_active ? 'Cabut akses admin' : 'Aktifkan akses admin' }}"
                                                onmouseover="this.style.background='rgba(22,74,64,0.08)'" onmouseout="this.style.background=''">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg>
                                            </button>
                                        @endif
                                        @php $isSuper = $user->role === 'super_admin'; @endphp
                                        @if(!$isSuper || $canManageSuper)
                                        <button wire:click="edit({{ $user->id }})"
                                            class="p-1.5 rounded-lg transition-colors" style="color:#17231E;"
                                            onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        @endif
                                        @if($user->id != auth()->id() && !$isSuper)
                                            <button wire:click="delete({{ $user->id }})" wire:confirm="Anda yakin ingin menghapus pengguna ini?"
                                                class="p-1.5 rounded-lg transition-colors" style="color:#B0402C;"
                                                onmouseover="this.style.background='rgba(176,64,44,0.1)'" onmouseout="this.style.background=''">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-sm" style="color:#909A8F;">Belum ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="md:hidden divide-y" style="border-color:#F1F3EC;">
                @forelse($users as $user)
                    @php $rc = $roleColors[$user->role] ?? '#586359'; @endphp
                    <div wire:key="user-card-{{ $user->id }}" class="p-4 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0"
                                style="background:#F1F3EC;color:#17231E;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold truncate" style="color:#17231E;">{{ $user->name }}</p>
                                <p class="text-xs truncate" style="color:#909A8F;">{{ $user->email }}</p>
                            </div>
                            @if($user->resident_id)
                                <span class="ml-auto inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium shrink-0" style="background:rgba(22,74,64,0.08);color:#164A40;">Penghuni</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                    style="background:{{ $rc }}1a;color:{{ $rc }};border:1px solid {{ $rc }}40;">
                                    {{ $roleLabels[$user->role] ?? ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                                @unless($user->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;">Nonaktif</span>
                                @endunless
                            </div>
                            <div class="flex items-center gap-1">
                                @if($user->resident_id && $user->id != auth()->id())
                                    <button wire:click="toggleActive({{ $user->id }})"
                                        wire:confirm="{{ $user->is_active ? 'Cabut akses admin penghuni ini?' : 'Aktifkan kembali akses admin penghuni ini?' }}"
                                        class="px-3 py-2 rounded-lg transition-colors" style="color:{{ $user->is_active ? '#A9741A' : '#12805c' }};"
                                        onmouseover="this.style.background='rgba(22,74,64,0.08)'" onmouseout="this.style.background=''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg>
                                    </button>
                                @endif
                                @php $isSuper = $user->role === 'super_admin'; @endphp
                                @if(!$isSuper || $canManageSuper)
                                <button wire:click="edit({{ $user->id }})"
                                    class="px-3 py-2 rounded-lg transition-colors" style="color:#17231E;"
                                    onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @endif
                                @if($user->id != auth()->id() && !$isSuper)
                                    <button wire:click="delete({{ $user->id }})" wire:confirm="Anda yakin ingin menghapus pengguna ini?"
                                        class="px-3 py-2 rounded-lg transition-colors" style="color:#B0402C;"
                                        onmouseover="this.style.background='rgba(176,64,44,0.1)'" onmouseout="this.style.background=''">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-sm" style="color:#909A8F;">Belum ada data pengguna.</div>
                @endforelse
            </div>

            <div class="px-5 py-3" style="border-top:1px solid #F1F3EC;">{{ $users->links() }}</div>
        </div>

        {{-- Modal Tambah / Edit Pengguna --}}
        @if($isModalOpen)
        @php $residentMode = !$selected_id && $createMode === 'resident'; @endphp
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeModal()"></div>
            <div class="relative rounded-2xl shadow-2xl w-full max-w-md" style="background:#ffffff;border:1px solid #D8D6C9;">
                <div class="flex items-center justify-between px-6 py-4 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                    <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">{{ $selected_id ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h3>
                    <button wire:click="closeModal()" class="p-1 rounded-lg transition-colors" style="color:#17231E;"
                        onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit="store" class="px-6 py-5 space-y-4">

                    {{-- Pemilih mode (hanya saat membuat, bukan edit) --}}
                    @unless($selected_id)
                    <div class="grid grid-cols-2 gap-1 p-1 rounded-xl" style="background:#F1F3EC;border:1px solid #E0DFD4;">
                        <button type="button" wire:click="setMode('new')"
                            class="py-2 rounded-lg text-sm font-medium transition-colors"
                            style="{{ !$residentMode ? 'background:#ffffff;color:#164A40;box-shadow:0 1px 2px rgba(22,74,64,0.08);' : 'background:transparent;color:#586359;' }}">
                            Akun Baru
                        </button>
                        <button type="button" wire:click="setMode('resident')"
                            class="py-2 rounded-lg text-sm font-medium transition-colors"
                            style="{{ $residentMode ? 'background:#ffffff;color:#164A40;box-shadow:0 1px 2px rgba(22,74,64,0.08);' : 'background:transparent;color:#586359;' }}">
                            Dari Penghuni
                        </button>
                    </div>
                    @endunless

                    @if($residentMode)
                        {{-- ── Mode: Dari Penghuni ── --}}
                        <p class="text-xs rounded-lg px-3 py-2" style="background:rgba(22,74,64,0.06);color:#586359;">
                            Penghuni terpilih akan mendapat menu <b>Admin</b> di portalnya untuk masuk ke panel pengurus, sesuai role yang dipilih. Akses bisa dicabut kapan saja.
                        </p>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">
                                Cari &amp; Pilih Penghuni
                                @if(count($selectedResidentIds)) <span style="color:#164A40;">({{ count($selectedResidentIds) }} dipilih)</span> @endif
                            </label>
                            <div wire:ignore id="residentSelectWrap" x-data x-init="
                                $nextTick(() => {
                                    const $sel = window.jQuery('#residentSelect');
                                    $sel.select2({
                                        placeholder: 'Ketik nama penghuni...',
                                        width: '100%',
                                        closeOnSelect: false,
                                        dropdownParent: window.jQuery('#residentSelectWrap'),
                                    });
                                    $sel.val(@js($selectedResidentIds)).trigger('change.select2');
                                    $sel.on('change', function () {
                                        $wire.set('selectedResidentIds', window.jQuery(this).val() || []);
                                    });
                                });
                            ">
                                <select id="residentSelect" multiple>
                                    @foreach($residents as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('selectedResidentIds') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Role / Hak Akses <span class="text-xs" style="color:#909A8F;">(untuk semua yang dipilih)</span></label>
                            <select wire:model="role"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                @include('livewire.admin.partials.role-options')
                            </select>
                            @error('role') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                        </div>
                    @elseif($selected_id && $editResidentLinked)
                        {{-- ── Edit akun dari penghuni: cukup ganti role ── --}}
                        <div class="flex items-center gap-2.5 rounded-xl px-3 py-2.5" style="border:1px solid #E0DFD4;background:#F5F7F1;">
                            <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0" style="background:#164A40;color:#fff;">{{ strtoupper(substr($name ?? '?', 0, 1)) }}</span>
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold truncate" style="color:#17231E;">{{ $name }}</span>
                                <span class="inline-flex items-center gap-1 text-xs" style="color:#164A40;">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Akun dari penghuni
                                </span>
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Role / Hak Akses</label>
                            <select wire:model="role"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                @include('livewire.admin.partials.role-options')
                            </select>
                            @error('role') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                        </div>
                        <p class="text-xs" style="color:#909A8F;">Penghuni ini masuk lewat tombol <b>Admin</b> di portalnya, jadi tidak perlu password. Untuk mencabut akses, gunakan tombol nonaktifkan di daftar.</p>
                    @else
                        {{-- ── Mode: Akun Baru / Edit akun biasa ── --}}
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Nama Lengkap</label>
                            <input type="text" wire:model="name"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @error('name') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Email</label>
                            <input type="email" wire:model="email"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @error('email') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">Role</label>
                            @if($editIsSuperAdmin)
                                <div class="flex items-center gap-2 text-sm px-3 py-2 rounded-xl" style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;">
                                    <svg class="w-4 h-4" style="color:#B0402C;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Super Admin — role terkunci
                                </div>
                                <p class="text-xs mt-1" style="color:#909A8F;">Role akun Super Admin tidak bisa diubah.</p>
                            @else
                                <select wire:model="role"
                                    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                                    @include('livewire.admin.partials.role-options')
                                </select>
                                @error('role') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color:#586359;">
                                Password
                                @if($selected_id)<span class="text-xs" style="color:#909A8F;">(kosongkan jika tidak ingin diubah)</span>@endif
                            </label>
                            <input type="password" wire:model="password"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @error('password') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #E0DFD4;">
                        <button type="button" wire:click="closeModal()"
                            class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                            style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                            onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 text-sm rounded-xl font-semibold transition-colors disabled:opacity-50"
                            style="background:#164A40;color:#ffffff;"
                            onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                            @disabled($residentMode && empty($selectedResidentIds)) wire:loading.attr="disabled" wire:target="store">
                            <span wire:loading.remove wire:target="store">{{ $residentMode ? 'Jadikan Admin' . (count($selectedResidentIds) ? ' (' . count($selectedResidentIds) . ')' : '') : 'Simpan' }}</span>
                            <span wire:loading wire:target="store" class="inline-flex items-center gap-1">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

@assets
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    /* Select2 — samakan dengan tema Denah Warga */
    #residentSelectWrap .select2-container--default .select2-selection--multiple{
        background:#ffffff;border:1px solid #E0DFD4;border-radius:0.75rem;min-height:42px;padding:2px 4px;
    }
    #residentSelectWrap .select2-container--default.select2-container--focus .select2-selection--multiple{ border-color:#164A40; }
    #residentSelectWrap .select2-selection__choice{
        background:#E1EDE8 !important;border:1px solid rgba(22,74,64,.3) !important;color:#164A40 !important;
        border-radius:8px !important;padding:2px 8px !important;font-size:.8rem;margin-top:5px !important;
    }
    #residentSelectWrap .select2-selection__choice__remove{ color:#164A40 !important;margin-right:5px !important; }
    #residentSelectWrap .select2-selection__choice__display{ padding-left:4px !important; }
    .select2-dropdown{ border:1px solid #E0DFD4;border-radius:0.75rem;overflow:hidden;box-shadow:0 8px 28px -12px rgba(22,74,64,.3); }
    .select2-container--default .select2-results__option--highlighted[aria-selected]{ background:#164A40 !important; }
    .select2-container--default .select2-results__option[aria-selected=true]{ background:#E1EDE8 !important;color:#164A40; }
    .select2-search--dropdown .select2-search__field{ border:1px solid #E0DFD4;border-radius:0.5rem;padding:6px 8px; }
    .select2-container{ z-index:60; }
</style>
@endassets
