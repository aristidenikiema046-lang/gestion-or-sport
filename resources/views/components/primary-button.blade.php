<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-or-500 border border-transparent rounded-lg font-semibold text-xs text-stade-950 uppercase tracking-widest hover:bg-or-400 focus:bg-or-400 active:bg-or-600 focus:outline-none focus:ring-2 focus:ring-or-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
