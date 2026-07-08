<nav class="-mx-3 flex flex-1 justify-end items-center gap-1">
    @auth
        <a href="{{ url('/dashboard') }}"
            class="rounded-xl px-4 py-2 text-sm font-medium transition-colors"
            style="color:#111827;border:1px solid rgba(16,24,40,0.2);background:rgba(16,24,40,0.05);"
            onmouseover="this.style.background='rgba(16,24,40,0.12)'" onmouseout="this.style.background='rgba(16,24,40,0.05)'">
            Dashboard
        </a>
    @else
        <a href="{{ route('penghuni.login') }}"
            class="rounded-xl px-4 py-2 text-sm font-medium transition-colors"
            style="color:#667085;"
            onmouseover="this.style.color='#111827'" onmouseout="this.style.color='#667085'">
            Masuk
        </a>
        @if(Route::has('register'))
            <a href="{{ route('register') }}"
                class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors"
                style="background:#111827;color:#ffffff;"
                onmouseover="this.style.background='#1f2a37'" onmouseout="this.style.background='#1f2a37'">
                Daftar
            </a>
        @endif
    @endauth
</nav>
