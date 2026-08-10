@props(['muted' => false])

<span {{ $attributes->merge(['class' => 'inline-flex items-baseline gap-[0.15em] font-display leading-none tracking-tight whitespace-nowrap select-none']) }}>
    <span class="text-or-500">OR</span><span class="{{ $muted ? 'text-stade-600' : 'text-current' }}">SPORT</span>
</span>
