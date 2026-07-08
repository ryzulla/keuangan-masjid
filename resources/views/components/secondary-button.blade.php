<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium rounded-xl transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed']) }}
    style="background:#f5f6f8;color:#344054;border:1px solid #d0d5dd;"
    onmouseover="this.style.background='#e4e7ec'" onmouseout="this.style.background='#f5f6f8'">
    {{ $slot }}
</button>
