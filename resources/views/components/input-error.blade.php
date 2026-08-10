@props(['messages', 'variant' => 'light'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm space-y-1 ' . ($variant === 'dark' ? 'text-retard-500' : 'text-retard-600')]) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
