<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium rounded-xl transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed']) }}
    style="background:#F1F3EC;color:#17231E;border:1px solid #D8D6C9;"
    onmouseover="this.style.background='#E0DFD4'" onmouseout="this.style.background='#F1F3EC'">
    {{ $slot }}
</button>
