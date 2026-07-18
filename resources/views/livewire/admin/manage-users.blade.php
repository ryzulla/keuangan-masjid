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
                                        <span class="font-medium" style="color:#17231E;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-xs" style="color:#909A8F;">{{ $user->email }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $roleStyles = [
                                            'super_admin' => 'background:rgba(176,64,44,0.12);color:#B0402C;border:1px solid rgba(176,64,44,0.25);',
                                            'admin'       => 'background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);',
                                            'bendahara'   => 'background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);',
                                            'ketua_dkm'   => 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);',
                                            'dkm'         => 'background:rgba(20,184,166,0.1);color:#0d9488;border:1px solid rgba(20,184,166,0.2);',
                                            'perumahan'   => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);',
                                            'pengurus_rt' => 'background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);',
                                        ];
                                        $roleLabels = [
                                            'super_admin' => 'Super Admin',
                                            'admin'       => 'Admin',
                                            'bendahara'   => 'Bendahara',
                                            'ketua_dkm'   => 'Ketua DKM',
                                            'dkm'         => 'DKM (Input)',
                                            'perumahan'   => 'Perumahan',
                                            'pengurus_rt' => 'Pengurus RT',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                        style="{{ $roleStyles[$user->role] ?? 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                                        {{ $roleLabels[$user->role] ?? ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="p-1.5 rounded-lg transition-colors" style="color:#17231E;"
                                            onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        @if($user->id != auth()->id())
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
                    @php
                        $roleStyles = [
                            'super_admin' => 'background:rgba(176,64,44,0.12);color:#B0402C;border:1px solid rgba(176,64,44,0.25);',
                            'admin'       => 'background:rgba(22,74,64,0.1);color:#17231E;border:1px solid rgba(22,74,64,0.2);',
                            'bendahara'   => 'background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);',
                            'ketua_dkm'   => 'background:rgba(18,128,92,0.1);color:#12805c;border:1px solid rgba(18,128,92,0.2);',
                            'dkm'         => 'background:rgba(20,184,166,0.1);color:#0d9488;border:1px solid rgba(20,184,166,0.2);',
                            'perumahan'   => 'background:rgba(169,116,26,0.1);color:#A9741A;border:1px solid rgba(169,116,26,0.2);',
                            'pengurus_rt' => 'background:rgba(107,91,149,0.1);color:#6B5B95;border:1px solid rgba(107,91,149,0.2);',
                        ];
                        $roleLabels = [
                            'super_admin' => 'Super Admin',
                            'admin'       => 'Admin',
                            'bendahara'   => 'Bendahara',
                            'ketua_dkm'   => 'Ketua DKM',
                            'dkm'         => 'DKM (Input)',
                            'perumahan'   => 'Perumahan',
                            'pengurus_rt' => 'Pengurus RT',
                        ];
                    @endphp
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
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                style="{{ $roleStyles[$user->role] ?? 'background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;' }}">
                                {{ $roleLabels[$user->role] ?? ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                            <div class="flex items-center gap-1">
                                <button wire:click="edit({{ $user->id }})"
                                    class="px-3 py-2 rounded-lg transition-colors" style="color:#17231E;"
                                    onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if($user->id != auth()->id())
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

        {{-- Modal --}}
        @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeModal()"></div>
            <div class="relative rounded-2xl shadow-2xl w-full max-w-md" style="background:#ffffff;border:1px solid #D8D6C9;">
                <div class="flex items-center justify-between px-6 py-4 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                    <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">{{ $selected_id ? 'Edit Pengguna' : 'Buat Pengguna Baru' }}</h3>
                    <button wire:click="closeModal()" class="p-1 rounded-lg transition-colors" style="color:#17231E;"
                        onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form wire:submit="store" class="px-6 py-5 space-y-4">
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
                        <select wire:model="role"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            <optgroup label="── Perumahan ──" style="color:#17231E;">
                                <option value="perumahan">Perumahan (akses penuh perumahan)</option>
                                <option value="pengurus_rt">Pengurus RT (perumahan + laporan)</option>
                            </optgroup>
                            <optgroup label="── DKM Masjid ──" style="color:#17231E;">
                                <option value="dkm">DKM — Input Only</option>
                                <option value="ketua_dkm">Ketua DKM (hanya lihat laporan)</option>
                                <option value="bendahara">Bendahara DKM</option>
                            </optgroup>
                            <optgroup label="── Administrator ──" style="color:#17231E;">
                                <option value="admin">Admin</option>
                                <option value="super_admin">Super Admin (semua akses)</option>
                            </optgroup>
                        </select>
                        @error('role') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
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
                    <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #E0DFD4;">
                        <button type="button" wire:click="closeModal()"
                            class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                            style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                            onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 text-sm rounded-xl font-semibold transition-colors"
                            style="background:#164A40;color:#ffffff;"
                            onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Simpan</span>
                            <span wire:loading class="inline-flex items-center gap-1">
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
