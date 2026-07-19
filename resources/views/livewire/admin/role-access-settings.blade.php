<div>

    <div class="py-6 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 rounded-xl p-4 text-sm flex items-center gap-2" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="rounded-2xl p-6 mb-5 pp-hero" style="background:#ffffff;border:1px solid rgba(22,74,64,0.35);">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Pengaturan Akses Role</h3>
                    <p class="text-sm mt-1" style="color:#586359;">Pilih role, lalu atur menu &amp; fitur yang bisa diaksesnya.</p>
                </div>
                <button wire:click="addRole"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition-colors shrink-0"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#164A40'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Role
                </button>
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

        {{-- Daftar Role --}}
        <div class="rounded-2xl overflow-hidden" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <div class="divide-y" style="border-color:#F1F3EC;">
                @foreach($roles as $role)
                    @php $count = $summary[$role->key]; @endphp
                    <div class="flex items-center gap-3 px-5 py-4" style="{{ $role->is_active ? '' : 'background:#FBFBF7;opacity:.72;' }}" onmouseover="this.style.backgroundColor='#F8F9F5'" onmouseout="this.style.backgroundColor='{{ $role->is_active ? '' : '#FBFBF7' }}'">
                        <span class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:{{ $role->color }}1a;border:1px solid {{ $role->color }}40;">
                            <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $role->color }};"></span>
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="font-semibold" style="color:#17231E;">{{ $role->label }}</p>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium" style="background:#F1F3EC;color:#586359;">{{ $role->group }}</span>
                                @unless($role->is_system)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium" style="background:rgba(22,74,64,0.08);color:#164A40;">Kustom</span>
                                @endunless
                                @unless($role->is_active)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium" style="background:rgba(176,64,44,0.1);color:#B0402C;">Nonaktif</span>
                                @endunless
                            </div>
                            <p class="text-xs mt-0.5" style="color:#909A8F;">
                                <span class="font-mono">{{ $role->key }}</span> · {{ $count }} dari {{ count($gates) }} menu aktif
                            </p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <button wire:click="openAccess('{{ $role->key }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-colors"
                                style="background:#F1F3EC;color:#164A40;border:1px solid rgba(22,74,64,0.25);"
                                onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="hidden sm:inline">Atur Akses</span>
                            </button>
                            @if($role->key !== 'super_admin')
                            <button wire:click="toggleRoleActive({{ $role->id }})"
                                wire:confirm="{{ $role->is_active ? 'Nonaktifkan role '.$role->label.'? Pemegangnya kehilangan akses.' : 'Aktifkan kembali role '.$role->label.'?' }}"
                                title="{{ $role->is_active ? 'Nonaktifkan role' : 'Aktifkan role' }}"
                                class="p-2 rounded-lg transition-colors" style="color:{{ $role->is_active ? '#A9741A' : '#12805c' }};"
                                onmouseover="this.style.background='rgba(22,74,64,0.08)'" onmouseout="this.style.background=''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9"/></svg>
                            </button>
                            @endif
                            <button wire:click="editRole({{ $role->id }})" title="Edit role" class="p-2 rounded-lg transition-colors" style="color:#909A8F;"
                                onmouseover="this.style.background='rgba(22,74,64,0.08)';this.style.color='#164A40'" onmouseout="this.style.background='';this.style.color='#909A8F'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            @unless($role->is_system)
                            <button wire:click="deleteRole({{ $role->id }})" wire:confirm="Hapus role {{ $role->label }}?" title="Hapus role" class="p-2 rounded-lg transition-colors" style="color:#909A8F;"
                                onmouseover="this.style.background='rgba(176,64,44,0.1)';this.style.color='#B0402C'" onmouseout="this.style.background='';this.style.color='#909A8F'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endunless
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Info --}}
        <div class="rounded-xl p-4 mt-4 flex items-start gap-3" style="background:rgba(22,74,64,0.05);border:1px solid rgba(22,74,64,0.15);">
            <svg class="w-4 h-4 shrink-0 mt-0.5" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs" style="color:#586359;">
                <span style="color:#17231E;">Dashboard</span> selalu dapat diakses semua pengguna yang login.
                Perubahan akses berlaku setelah pengguna login ulang atau cache habis (maks. 1 jam).
            </p>
        </div>
    </div>

    {{-- ═══ Panel: Atur Akses Role ═══ --}}
    @if($isAccessModalOpen && $activeRole)
    @php
        $activeLabel = \App\Models\Role::labelFor($activeRole);
        $activeColor = \App\Models\Role::colorFor($activeRole);
        $gatesGrouped = collect($gates)->groupBy('group', true);
        $groupTint = ['Administrasi' => '#6B5B95', 'DKM Masjid' => '#0d9488', 'Perumahan' => '#164A40'];
    @endphp
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeAccess()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-lg flex flex-col" style="background:#ffffff;border:1px solid #D8D6C9;max-height:88vh;">
            <div class="flex items-center justify-between px-6 py-4 rounded-t-2xl shrink-0" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <div class="flex items-center gap-2.5 min-w-0">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:{{ $activeColor }}1a;border:1px solid {{ $activeColor }}40;">
                        <span class="w-2.5 h-2.5 rounded-full" style="background:{{ $activeColor }};"></span>
                    </span>
                    <div class="min-w-0">
                        <h3 class="font-bold truncate" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Atur Akses — {{ $activeLabel }}</h3>
                        <p class="text-xs" style="color:#909A8F;">Centang menu yang boleh diakses role ini</p>
                    </div>
                </div>
                <button wire:click="closeAccess()" class="p-1 rounded-lg transition-colors shrink-0" style="color:#17231E;"
                    onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="px-6 py-4 overflow-y-auto">
                <div class="flex items-center justify-end gap-3 mb-3 text-xs">
                    <button type="button" wire:click="toggleAll(true)" style="color:#164A40;font-weight:600;">Pilih semua</button>
                    <span style="color:#E0DFD4;">|</span>
                    <button type="button" wire:click="toggleAll(false)" style="color:#586359;">Kosongkan</button>
                    <span style="color:#E0DFD4;">|</span>
                    <button type="button" wire:click="resetActiveRole" style="color:#586359;">Reset default</button>
                </div>

                @foreach($gatesGrouped as $groupName => $groupGates)
                    <p class="text-xs font-bold uppercase tracking-widest mt-3 mb-1.5" style="color:{{ $groupTint[$groupName] ?? '#586359' }};">{{ $groupName }}</p>
                    <div class="space-y-1.5 mb-3">
                        @foreach($groupGates as $gateKey => $info)
                            @php $on = $matrix[$activeRole][$gateKey] ?? false; @endphp
                            <label class="flex items-center justify-between gap-3 rounded-xl px-3.5 py-2.5 cursor-pointer transition-colors"
                                style="border:1px solid {{ $on ? 'rgba(22,74,64,0.3)' : '#E0DFD4' }};background:{{ $on ? 'rgba(22,74,64,0.05)' : '#ffffff' }};">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium" style="color:#17231E;">{{ $info['label'] }}</p>
                                    <p class="text-xs font-mono" style="color:#909A8F;">{{ $gateKey }}</p>
                                </div>
                                <input type="checkbox" wire:model.live="matrix.{{ $activeRole }}.{{ $gateKey }}" class="sr-only peer">
                                <span class="shrink-0 w-10 h-5 rounded-full flex items-center transition-colors duration-150"
                                    style="{{ $on ? 'background:rgba(22,74,64,0.2);border:1px solid rgba(22,74,64,0.4);' : 'background:#ffffff;border:1px solid #E0DFD4;' }}">
                                    @if($on)
                                        <svg class="w-3.5 h-3.5 mx-auto" style="color:#17231E;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <svg class="w-3 h-3 mx-auto" style="color:#909A8F;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end gap-3 px-6 py-4 shrink-0" style="border-top:1px solid #E0DFD4;">
                <button type="button" wire:click="closeAccess()"
                    class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                    style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                    onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                <button type="button" wire:click="saveAccess"
                    class="px-5 py-2 text-sm rounded-xl font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#164A40'"
                    wire:loading.attr="disabled" wire:target="saveAccess">
                    <span wire:loading.remove wire:target="saveAccess">Simpan Akses</span>
                    <span wire:loading wire:target="saveAccess">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══ Modal: Tambah / Edit Role ═══ --}}
    @if($isRoleModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0" style="background:rgba(0,0,0,0.1);backdrop-filter:blur(4px);" wire:click="closeRoleModal()"></div>
        <div class="relative rounded-2xl shadow-2xl w-full max-w-md" style="background:#ffffff;border:1px solid #D8D6C9;">
            <div class="flex items-center justify-between px-6 py-4 rounded-t-2xl" style="background:#F1F3EC;border-bottom:1px solid rgba(22,74,64,0.35);">
                <h3 class="font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">{{ $roleId ? 'Edit Role' : 'Tambah Role' }}</h3>
                <button wire:click="closeRoleModal()" class="p-1 rounded-lg transition-colors" style="color:#17231E;"
                    onmouseover="this.style.background='rgba(22,74,64,0.1)'" onmouseout="this.style.background=''">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form wire:submit="saveRole" class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">Nama Role</label>
                    <input type="text" wire:model="roleLabel" placeholder="mis. Sekretaris"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('roleLabel') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1" style="color:#586359;">
                        Kode Role @if($roleId)<span class="text-xs" style="color:#909A8F;">(tidak bisa diubah)</span>@endif
                    </label>
                    @if($roleId)
                        <div class="text-sm px-3 py-2 rounded-xl" style="background:#F1F3EC;color:#586359;border:1px solid #E0DFD4;font-family:monospace;">{{ $roleKey }}</div>
                    @else
                        <input type="text" wire:model="roleKey" placeholder="mis. sekretaris"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;font-family:monospace;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        <p class="text-xs mt-1" style="color:#909A8F;">Huruf kecil, angka, garis bawah. Contoh: <code>sekretaris</code>. Tak bisa diubah nanti.</p>
                    @endif
                    @error('roleKey') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Grup</label>
                        <select wire:model="roleGroup"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                            @foreach($groups as $g)
                                <option value="{{ $g }}">{{ $g }}</option>
                            @endforeach
                        </select>
                        @error('roleGroup') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color:#586359;">Warna</label>
                        <div class="flex items-center gap-2">
                            <input type="color" wire:model="roleColor" style="width:42px;height:42px;border:1px solid #E0DFD4;border-radius:0.6rem;background:#fff;padding:2px;cursor:pointer;">
                            <input type="text" wire:model="roleColor" placeholder="#164A40"
                                style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.5rem 0.75rem;width:100%;font-size:0.875rem;outline:none;font-family:monospace;"
                                onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        </div>
                        @error('roleColor') <p class="text-xs mt-1" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-2" style="border-top:1px solid #E0DFD4;">
                    <button type="button" wire:click="closeRoleModal()"
                        class="px-4 py-2 text-sm rounded-xl font-medium transition-colors"
                        style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
                        onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 text-sm rounded-xl font-semibold transition-colors"
                        style="background:#164A40;color:#ffffff;"
                        onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#164A40'"
                        wire:loading.attr="disabled" wire:target="saveRole">
                        <span wire:loading.remove wire:target="saveRole">Simpan Role</span>
                        <span wire:loading wire:target="saveRole">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
