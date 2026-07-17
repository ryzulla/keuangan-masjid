@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors']) }}
    style="background:#ffffff;border:1px solid #e4e4e4;color:#161e2d;"
    onfocus="this.style.borderColor='#1563df'" onblur="this.style.borderColor='#e4e4e4'">
