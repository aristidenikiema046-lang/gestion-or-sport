@props(['status', 'variant' => 'light'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm ' . ($variant === 'dark' ? 'text-or-400' : 'text-emerald-600')]) }}>
        {{ $status }}
    </div>
@endif
