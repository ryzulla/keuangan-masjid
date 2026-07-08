@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors']) }}
    style="background:#ffffff;border:1px solid #e4e7ec;color:#1d2939;"
    onfocus="this.style.borderColor='#111827'" onblur="this.style.borderColor='#e4e7ec'">
