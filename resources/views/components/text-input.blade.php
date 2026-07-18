@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3 py-2 text-sm rounded-xl outline-none transition-colors']) }}
    style="background:#ffffff;border:1px solid #E0DFD4;color:#17231E;"
    onfocus="this.style.borderColor='#164A40'" onblur="this.style.borderColor='#E0DFD4'">
