@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium mb-1.5']) }} style="color:#586359;">
    {{ $value ?? $slot }}
</label>
