<div class="min-h-screen flex flex-col items-center justify-center px-4" style="background-color:#F1F3EC;">

    {{-- Logo --}}
    <div class="mb-8 text-center">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4"
            style="background:#F1F3EC;border:1px solid rgba(22,74,64,0.5);">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="#164A40" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </div>
        <h1 class="text-xl font-bold" style="color:#17231E;font-family:'Fraunces',Georgia,serif;">Portal Penghuni</h1>
        <p class="text-sm mt-1" style="color:#909A8F;">Perumahan — Sistem Manajemen Warga</p>
    </div>

    {{-- Card --}}
    <div class="w-full max-w-sm rounded-2xl p-7" style="background:#ffffff;border:1px solid #E0DFD4;box-shadow:0 1px 2px rgba(22,74,64,0.04),0 8px 20px -8px rgba(22,74,64,0.06);">

        @if ($step === 1)
            {{-- ══ STEP 1 — Verifikasi identitas ══ --}}
            <h2 class="text-base font-semibold mb-1" style="color:#17231E;">Lupa Password</h2>
            <p class="text-xs mb-6" style="color:#909A8F;">Buktikan identitas Anda untuk mengatur ulang password. Tidak diperlukan email verifikasi.</p>

            <form wire:submit="verify" class="space-y-4">

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Email Terdaftar</label>
                    <input wire:model="email" type="email" placeholder="email@anda.com" autocomplete="email"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.6rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('email')
                        <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilih metode verifikasi --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Verifikasi dengan</label>
                    <div class="flex gap-2">
                        <label class="flex-1 flex items-center gap-2 px-3 py-2 rounded-xl cursor-pointer select-none"
                            style="background:#ffffff;border:1px solid {{ $method === 'nik' ? '#164A40' : '#E0DFD4' }};">
                            <input wire:model.live="method" type="radio" value="nik" style="accent-color:#17231E;" class="w-4 h-4">
                            <span class="text-xs" style="color:#17231E;">NIK</span>
                        </label>
                        <label class="flex-1 flex items-center gap-2 px-3 py-2 rounded-xl cursor-pointer select-none"
                            style="background:#ffffff;border:1px solid {{ $method === 'phone' ? '#164A40' : '#E0DFD4' }};">
                            <input wire:model.live="method" type="radio" value="phone" style="accent-color:#17231E;" class="w-4 h-4">
                            <span class="text-xs" style="color:#17231E;">No. WhatsApp/HP</span>
                        </label>
                    </div>
                </div>

                {{-- Identifier --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">
                        {{ $method === 'nik' ? 'Nomor Induk Kependudukan (NIK)' : 'Nomor WhatsApp / HP' }}
                    </label>
                    <input wire:model="identifier" type="text" wire:key="identifier-{{ $method }}"
                        placeholder="{{ $method === 'nik' ? '16 digit NIK' : 'contoh: 0812xxxxxxx' }}"
                        inputmode="numeric"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.6rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('identifier')
                        <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                    wire:loading.attr="disabled" wire:target="verify">
                    <span wire:loading.remove wire:target="verify">Verifikasi Identitas</span>
                    <span wire:loading wire:target="verify" class="inline-flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Memproses...
                    </span>
                </button>

            </form>

        @else
            {{-- ══ STEP 2 — Password baru ══ --}}
            <h2 class="text-base font-semibold mb-1" style="color:#17231E;">Atur Password Baru</h2>
            <p class="text-xs mb-6" style="color:#909A8F;">Identitas terverifikasi. Silakan buat password baru untuk akun Anda.</p>

            <form wire:submit="resetPassword" class="space-y-4">

                {{-- Password baru --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Password Baru</label>
                    <input wire:model="password" type="password" placeholder="••••••••" autocomplete="new-password"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.6rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                    @error('password')
                        <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi password --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:#586359;">Ulangi Password Baru</label>
                    <input wire:model="password_confirmation" type="password" placeholder="••••••••" autocomplete="new-password"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.75rem;padding:0.6rem 0.875rem;width:100%;font-size:0.875rem;outline:none;"
                        onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-2.5 rounded-xl text-sm font-semibold transition-colors"
                    style="background:#164A40;color:#ffffff;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'"
                    wire:loading.attr="disabled" wire:target="resetPassword">
                    <span wire:loading.remove wire:target="resetPassword">Simpan Password Baru</span>
                    <span wire:loading wire:target="resetPassword" class="inline-flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Menyimpan...
                    </span>
                </button>

            </form>
        @endif

        {{-- Kembali ke login --}}
        <div class="mt-5 text-center">
            <a href="{{ route('penghuni.login') }}" wire:navigate class="text-xs hover:underline" style="color:#17231E;">
                &larr; Kembali ke halaman masuk
            </a>
        </div>

    </div>

</div>
