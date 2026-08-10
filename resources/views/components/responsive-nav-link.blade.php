@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-or-500 text-start text-base font-medium text-ivoire-100 bg-white/5 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-ivoire-300/70 hover:text-ivoire-100 hover:bg-white/5 hover:border-stade-700 focus:outline-none focus:text-ivoire-100 focus:bg-white/5 focus:border-stade-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
