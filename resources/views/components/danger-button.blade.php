<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed']) }}
    style="background:rgba(176,64,44,0.1);color:#B0402C;border:1px solid rgba(176,64,44,0.3);"
    onmouseover="this.style.background='rgba(176,64,44,0.2)'" onmouseout="this.style.background='rgba(176,64,44,0.1)'">
    {{ $slot }}
</button>
