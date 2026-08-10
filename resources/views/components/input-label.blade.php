@props(['value', 'variant' => 'light'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm ' . ($variant === 'dark' ? 'text-ivoire-300' : 'text-stade-700')]) }}>
    {{ $value ?? $slot }}
</label>
