@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm']) }} style="color:#12805c;">
        {{ $status }}
    </div>
@endif
