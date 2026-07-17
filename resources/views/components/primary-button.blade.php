<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed']) }}
    style="background:#1563df;color:#ffffff;"
    onmouseover="this.style.background='#0e49a6'" onmouseout="this.style.background='#0e49a6'">
    {{ $slot }}
</button>
