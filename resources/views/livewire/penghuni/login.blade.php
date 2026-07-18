@php
    // Denah motif: 40 plots (8×5), a few occupied, one marked "Rumah Anda"
    $filled = [2,5,9,11,16,20,26,29,33,34,37];
    $you    = 19;
@endphp

<div class="min-h-screen grid lg:grid-cols-2" style="background:#ffffff;">

    {{-- ══ Left: site-plan panel (denah) ══ --}}
    <div class="relative hidden lg:flex flex-col justify-between p-12"
        style="background:#f9fafb;border-right:1px solid #E0DFD4;">

        {{-- brand --}}
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                style="background:#164A40;">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 11l9-7 9 7M5 9.5V20a1 1 0 001 1h12a1 1 0 001-1V9.5"/>
                </svg>
            </div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:1.2rem;line-height:1;letter-spacing:-.01em;color:#17231E;">
                Portal Penghuni
                <span style="display:block;font-family:'IBM Plex Mono',monospace;font-weight:500;font-size:.6rem;letter-spacing:.14em;text-transform:uppercase;color:#909A8F;margin-top:.35rem;">
                    Perumahan · Sistem Manajemen Warga
                </span>
            </div>
        </div>

        {{-- lead --}}
        <p style="max-width:32ch;font-size:1.05rem;line-height:1.55;color:#586359;">
            Satu portal untuk <b style="color:#17231E;font-weight:600;">IPL</b>, program warga, dan data keluarga —
            dialamatkan ke rumah Anda.
        </p>

        {{-- denah grid --}}
        <div>
            <div class="flex justify-between mb-2.5"
                style="font-family:'IBM Plex Mono',monospace;font-size:.63rem;letter-spacing:.1em;text-transform:uppercase;color:#909A8F;">
                <span>Denah Perumahan</span><span>Blok A – P</span>
            </div>
            <div class="grid gap-1.5" style="grid-template-columns:repeat(8,1fr);max-width:360px;" aria-hidden="true">
                @for ($i = 0; $i < 40; $i++)
                    @if ($i === $you)
                        <div style="aspect-ratio:1;border-radius:4px;background:#164A40;"></div>
                    @elseif (in_array($i, $filled))
                        <div style="aspect-ratio:1;border-radius:4px;border:1px solid #E0DFD4;background:#F1F3EC;"></div>
                    @else
                        <div style="aspect-ratio:1;border-radius:4px;border:1px solid #E0DFD4;"></div>
                    @endif
                @endfor
            </div>
            <div class="flex items-center gap-2 mt-3"
                style="font-family:'IBM Plex Mono',monospace;font-size:.66rem;letter-spacing:.04em;color:#586359;">
                <span style="width:9px;height:9px;border-radius:2px;background:#164A40;"></span>
                Rumah Anda · <b style="color:#17231E;font-weight:600;">Blok&nbsp;C-4</b>
            </div>
        </div>
    </div>

    {{-- ══ Right: sign-in form ══ --}}
    <div class="flex items-center justify-center px-6 py-10 sm:px-10" style="background:#ffffff;">
        <div class="w-full max-w-sm">

            {{-- mobile brand --}}
            <div class="lg:hidden flex flex-col items-center text-center mb-8">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background:#164A40;">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 11l9-7 9 7M5 9.5V20a1 1 0 001 1h12a1 1 0 001-1V9.5"/>
                    </svg>
                </div>
                <h1 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:1.4rem;color:#17231E;">Portal Penghuni</h1>
                <p class="text-sm mt-1.5" style="color:#909A8F;">Perumahan — Sistem Manajemen Warga</p>
            </div>

            <div class="mb-1.5" style="font-family:'IBM Plex Mono',monospace;font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:#909A8F;">
                Masuk ke akun
            </div>
            <h2 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:1.75rem;line-height:1.1;letter-spacing:-.01em;color:#17231E;">Selamat datang</h2>
            <p class="text-sm mt-2 mb-7" style="color:#586359;">Gunakan email yang didaftarkan oleh pengurus perumahan.</p>

            @if (session('status'))
                <div class="mb-5 rounded-xl px-4 py-3 text-xs"
                    style="background:rgba(18,128,92,0.08);border:1px solid rgba(18,128,92,0.35);color:#12805c;">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="login" class="space-y-4">

                {{-- Email --}}
                <div>
                    <label class="block mb-1.5" style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:#909A8F;">Email</label>
                    <input wire:model="email" type="email" placeholder="nama@email.com" autocomplete="email"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.6rem;padding:0.7rem 0.9rem;width:100%;font-size:0.9rem;outline:none;transition:border-color .15s,box-shadow .15s;"
                        onfocus="this.style.borderColor='#164A40';this.style.boxShadow='0 0 0 3px rgba(22,74,64,.10)'"
                        onblur="this.style.borderColor='#E0DFD4';this.style.boxShadow='none'">
                    @error('email')
                        <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label style="font-family:'IBM Plex Mono',monospace;font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:#909A8F;">Kata sandi</label>
                        <a href="{{ route('penghuni.password.request') }}" wire:navigate class="text-xs" style="color:#17231E;text-decoration:underline;text-decoration-color:transparent;transition:text-decoration-color .15s;"
                            onmouseover="this.style.textDecorationColor='#17231E'" onmouseout="this.style.textDecorationColor='transparent'">Lupa kata sandi?</a>
                    </div>
                    <input wire:model="password" type="password" placeholder="••••••••" autocomplete="current-password"
                        style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;border-radius:0.6rem;padding:0.7rem 0.9rem;width:100%;font-size:0.9rem;outline:none;transition:border-color .15s,box-shadow .15s;"
                        onfocus="this.style.borderColor='#164A40';this.style.boxShadow='0 0 0 3px rgba(22,74,64,.10)'"
                        onblur="this.style.borderColor='#E0DFD4';this.style.boxShadow='none'">
                    @error('password')
                        <p class="text-xs mt-1.5" style="color:#B0402C;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input wire:model="remember" type="checkbox" id="remember" style="accent-color:#17231E;" class="rounded w-4 h-4">
                    <label for="remember" class="text-xs cursor-pointer select-none" style="color:#586359;">Ingat saya di perangkat ini</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full transition-colors"
                    style="background:#164A40;color:#ffffff;border:none;border-radius:0.6rem;padding:0.85rem;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.55rem;"
                    onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#164A40'"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove class="inline-flex items-center gap-2">
                        Masuk
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M20 12H4"/></svg>
                    </span>
                    <span wire:loading class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Memproses…
                    </span>
                </button>
            </form>

            {{-- footer --}}
            <div class="mt-7 pt-5" style="border-top:1px solid #E0DFD4;">
                <p class="text-xs" style="color:#586359;">
                    Belum punya akses? Hubungi <b style="color:#17231E;font-weight:600;">pengurus RT / DKM</b> untuk didaftarkan.
                </p>
                <p class="text-xs mt-3" style="color:#909A8F;">
                    Login sebagai Admin / Pengurus?
                    <a href="{{ route('login') }}" wire:navigate style="color:#17231E;text-decoration:underline;">Klik di sini</a>
                </p>
            </div>

        </div>
    </div>
</div>
