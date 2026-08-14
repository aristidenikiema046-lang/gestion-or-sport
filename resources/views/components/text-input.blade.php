@props(['disabled' => false, 'variant' => 'light'])

@php
$classes = $variant === 'dark'
    ? 'border bg-stade-800/70 border-white/10 text-ivoire-100 placeholder:text-stade-600 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/40 rounded-lg shadow-sm px-3.5 py-2.5'
    : 'border bg-white border-stade-700/15 text-stade-950 placeholder:text-stade-600/70 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 rounded-lg shadow-sm px-3.5 py-2.5';
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
