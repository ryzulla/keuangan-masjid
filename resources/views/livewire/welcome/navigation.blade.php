<nav class="-mx-3 flex flex-1 justify-end items-center gap-1">
    @auth
        <a href="{{ url('/dashboard') }}"
            class="rounded-xl px-4 py-2 text-sm font-medium transition-colors"
            style="color:#161e2d;border:1px solid rgba(21,99,223,0.2);background:rgba(21,99,223,0.05);"
            onmouseover="this.style.background='rgba(21,99,223,0.12)'" onmouseout="this.style.background='rgba(21,99,223,0.05)'">
            Dashboard
        </a>
    @else
        <a href="{{ route('penghuni.login') }}"
            class="rounded-xl px-4 py-2 text-sm font-medium transition-colors"
            style="color:#5c6368;"
            onmouseover="this.style.color='#1563df'" onmouseout="this.style.color='#5c6368'">
            Masuk
        </a>
        @if(Route::has('register'))
            <a href="{{ route('register') }}"
                class="rounded-xl px-4 py-2 text-sm font-semibold transition-colors"
                style="background:#1563df;color:#ffffff;"
                onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
                Daftar
            </a>
        @endif
    @endauth
</nav>
