<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-medium rounded-xl transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed']) }}
    style="background:#f7f7f7;color:#161e2d;border:1px solid #d9d9d9;"
    onmouseover="this.style.background='#e4e4e4'" onmouseout="this.style.background='#f7f7f7'">
    {{ $slot }}
</button>
