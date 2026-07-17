@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium mb-1.5']) }} style="color:#5c6368;">
    {{ $value ?? $slot }}
</label>
