<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:rgba(22,74,64,0.12);border:1px solid rgba(22,74,64,0.35);">
            <svg class="w-4.5 h-4.5" style="width:1.15rem;height:1.15rem;" fill="none" viewBox="0 0 24 24" stroke="#164A40" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-lg font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Pengaturan</h1>
            <p class="text-xs" style="color:#909A8F;">Kelola profil, keamanan, dan preferensi akun Anda</p>
        </div>
    </div>

    {{-- Grid: di desktop dua kolom, di HP satu kolom bertumpuk --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 items-start">

        {{-- ══════════ PROFIL ══════════ --}}
        <section class="rounded-2xl p-5 sm:p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <h2 class="text-sm font-semibold mb-1" style="color:#17231E;">Profil</h2>
            <p class="text-xs mb-4" style="color:#909A8F;">Data kontak Anda</p>

            @if(session('profile_success'))
                <div class="mb-4 rounded-lg px-3 py-2 text-xs" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">{{ session('profile_success') }}</div>
            @endif
            @if(session('profile_error'))
                <div class="mb-4 rounded-lg px-3 py-2 text-xs" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">{{ session('profile_error') }}</div>
            @endif

            <form wire:submit="saveProfile" class="space-y-4">
                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Nama <span style="color:#B0402C;">*</span></label>
                    <input wire:model="name" type="text" placeholder="Nama lengkap"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('name') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Email</label>
                    <input wire:model="email" type="email" placeholder="email@anda.com" autocomplete="email"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('email') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>

                {{-- Telepon & WhatsApp: sejajar di desktop --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Nomor Telepon</label>
                        <input wire:model="phone" type="tel" placeholder="08xxxx"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('phone') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Nomor WhatsApp</label>
                        <input wire:model="whatsapp" type="tel" placeholder="08xxxx"
                            style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                            onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                        @error('whatsapp') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit"
                    class="w-full sm:w-auto px-5 py-3 sm:py-2.5 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                    wire:loading.attr="disabled" wire:target="saveProfile">
                    <span wire:loading.remove wire:target="saveProfile">Simpan Profil</span>
                    <span wire:loading wire:target="saveProfile">Menyimpan...</span>
                </button>
            </form>
        </section>

        {{-- ══════════ GANTI PASSWORD ══════════ --}}
        <section class="rounded-2xl p-5 sm:p-6" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">
            <h2 class="text-sm font-semibold mb-1" style="color:#17231E;">Ganti Password</h2>
            <p class="text-xs mb-4" style="color:#909A8F;">Gunakan password yang kuat dan mudah Anda ingat</p>

            @if(session('password_success'))
                <div class="mb-4 rounded-lg px-3 py-2 text-xs" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">{{ session('password_success') }}</div>
            @endif
            @if(session('password_error'))
                <div class="mb-4 rounded-lg px-3 py-2 text-xs" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">{{ session('password_error') }}</div>
            @endif

            <form wire:submit="savePassword" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Password Saat Ini <span style="color:#B0402C;">*</span></label>
                    <input wire:model="currentPassword" type="password" placeholder="••••••••" autocomplete="current-password"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('currentPassword') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Password Baru <span style="color:#B0402C;">*</span></label>
                    <input wire:model="newPassword" type="password" placeholder="Minimal 6 karakter" autocomplete="new-password"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('newPassword') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Konfirmasi Password Baru <span style="color:#B0402C;">*</span></label>
                    <input wire:model="newPassword_confirmation" type="password" placeholder="Ulangi password baru" autocomplete="new-password"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.65rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>

                <button type="submit"
                    class="w-full sm:w-auto px-5 py-3 sm:py-2.5 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                    wire:loading.attr="disabled" wire:target="savePassword">
                    <span wire:loading.remove wire:target="savePassword">Ubah Password</span>
                    <span wire:loading wire:target="savePassword">Menyimpan...</span>
                </button>
            </form>
        </section>

        {{-- ══════════ FOTO PROFIL ══════════ --}}
        <section class="rounded-2xl p-5 sm:p-6" style="background:#ffffff;border:1px solid #F1F3EC;">
            <h2 class="text-sm font-semibold mb-1" style="color:#17231E;">Foto Profil</h2>
            <p class="text-xs mb-4" style="color:#909A8F;">Format gambar, maksimal 2MB</p>

            @if(session('photo_success'))
                <div class="mb-4 rounded-lg px-3 py-2 text-xs" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">{{ session('photo_success') }}</div>
            @endif
            @if(session('photo_error'))
                <div class="mb-4 rounded-lg px-3 py-2 text-xs" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">{{ session('photo_error') }}</div>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                {{-- Preview --}}
                <div class="flex justify-center sm:block">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="w-24 h-24 rounded-2xl object-cover" style="border:1px solid rgba(22,74,64,0.4);">
                    @elseif($existingPhoto)
                        <img src="{{ Storage::disk('public')->url($existingPhoto) }}" class="w-24 h-24 rounded-2xl object-cover" style="border:1px solid rgba(22,74,64,0.4);">
                    @else
                        <div class="w-24 h-24 rounded-2xl flex items-center justify-center text-3xl font-bold" style="background:rgba(22,74,64,0.15);color:#17231E;">
                            {{ strtoupper(substr($name ?: '?', 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Kontrol --}}
                <div class="flex-1 space-y-3">
                    <div>
                        <label class="block w-full sm:w-auto text-center px-4 py-3 sm:py-2 rounded-xl text-sm font-medium cursor-pointer transition-colors"
                            style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);color:#17231E;"
                            onmouseover="this.style.borderColor='#164A40'" onmouseout="this.style.borderColor='#E0DFD4'">
                            <span wire:loading.remove wire:target="photo">Pilih Foto</span>
                            <span wire:loading wire:target="photo">Memuat...</span>
                            <input wire:model="photo" type="file" accept="image/*" class="hidden">
                        </label>
                        @error('photo') <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        @if($photo)
                            <button type="button" wire:click="savePhoto"
                                class="w-full sm:w-auto px-5 py-3 sm:py-2 rounded-xl text-sm font-semibold transition-colors"
                                style="background:#164A40;color:#ffffff;"
                                onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                                wire:loading.attr="disabled" wire:target="savePhoto">
                                <span wire:loading.remove wire:target="savePhoto">Simpan Foto</span>
                                <span wire:loading wire:target="savePhoto">Menyimpan...</span>
                            </button>
                        @endif

                        @if($photo || $existingPhoto)
                            <button type="button" wire:click="removePhoto"
                                class="w-full sm:w-auto px-5 py-3 sm:py-2 rounded-xl text-sm font-medium transition-colors"
                                style="background:transparent;border:1px solid rgba(176,64,44,0.4);color:#B0402C;"
                                onmouseover="this.style.background='rgba(176,64,44,0.08)'" onmouseout="this.style.background='transparent'">
                                {{ $photo ? 'Batalkan' : 'Hapus Foto' }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ══════════ PREFERENSI NOTIFIKASI ══════════ --}}
        <section class="rounded-2xl p-5 sm:p-6" style="background:#ffffff;border:1px solid #F1F3EC;">
            <h2 class="text-sm font-semibold mb-1" style="color:#17231E;">Preferensi Notifikasi</h2>
            <p class="text-xs mb-4" style="color:#909A8F;">Pilih jenis pemberitahuan yang ingin Anda terima</p>

            @if(session('notif_success'))
                <div class="mb-4 rounded-lg px-3 py-2 text-xs" style="background:rgba(18,128,92,0.1);border:1px solid rgba(18,128,92,0.3);color:#12805c;">{{ session('notif_success') }}</div>
            @endif
            @if(session('notif_error'))
                <div class="mb-4 rounded-lg px-3 py-2 text-xs" style="background:rgba(176,64,44,0.1);border:1px solid rgba(176,64,44,0.3);color:#B0402C;">{{ session('notif_error') }}</div>
            @endif

            <form wire:submit="saveNotifications" class="space-y-3">
                @foreach($notificationTypes as $key => $label)
                    <label class="flex items-center justify-between gap-3 rounded-xl px-4 py-3.5 cursor-pointer transition-colors"
                        style="background:#ffffff;border:1px solid #E0DFD4;"
                        onmouseover="this.style.borderColor='#D8D6C9'" onmouseout="this.style.borderColor='#E0DFD4'">
                        <span class="text-sm" style="color:#17231E;">{{ $label }}</span>
                        <span class="relative inline-flex shrink-0">
                            <input wire:model="notifications.{{ $key }}" type="checkbox" class="sr-only peer">
                            <span class="w-11 h-6 rounded-full transition-colors peer-checked:bg-[#164A40]" style="background:#E0DFD4;"></span>
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 rounded-full bg-white transition-transform peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                @endforeach

                <button type="submit"
                    class="w-full sm:w-auto px-5 py-3 sm:py-2.5 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                    wire:loading.attr="disabled" wire:target="saveNotifications">
                    <span wire:loading.remove wire:target="saveNotifications">Simpan Preferensi</span>
                    <span wire:loading wire:target="saveNotifications">Menyimpan...</span>
                </button>
            </form>
        </section>

    </div>
</div>
