<nav class="-mx-3 flex flex-1 justify-end items-center gap-1">
    @auth
        <a href="{{ url('/dashboard') }}"
            class="rounded-xl px-4 py-2 text-sm font-medium transition-colors"
            style="color:#17231E;border:1px solid rgba(22,74,64,0.2);background:rgba(22,74,64,0.05);"
            onmouseover="this.style.background='rgba(22,74,64,0.12)'" onmouseout="this.style.background='rgba(22,74,64,0.05)'">
            Dashboard
        </a>
    @else
        <a href="{{ route('penghuni.login') }}"
            class="rounded-xl px-4 py-2 text-sm font-medium transition-colors"
            style="color:#586359;"
            onmouseover="this.style.color='#164A40'" onmouseout="this.style.color='#586359'">
            Masuk
        </a>
        @if(Route::has('register'))
            <a href="{{ route('register') }}"
                class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors"
                style="background:#164A40;color:#ffffff;"
                onmouseover="this.style.background='#0F3A32'" onmouseout="this.style.background='#0F3A32'">
                Daftar
            </a>
        @endif
    @endauth
</nav>
