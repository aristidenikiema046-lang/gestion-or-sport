@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-or-500 text-sm font-medium leading-5 text-ivoire-100 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-ivoire-300/60 hover:text-ivoire-100 hover:border-stade-700 focus:outline-none focus:text-ivoire-100 focus:border-stade-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
