<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0a0c0e">

        <title>{{ config('app.name', 'OR SPORT') }} — Espace de gestion</title>

        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icons/favicon-16x16.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">

        @fonts

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="relative min-h-screen overflow-hidden bg-stade-950 flex flex-col items-center justify-center px-6 py-12">
            {{-- Ambient stadium gradient --}}
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_18%_15%,var(--color-stade-800)_0%,var(--color-stade-950)_55%)]"></div>

            {{-- Diagonal sublimation sash, top-right --}}
            <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rotate-45 bg-gradient-to-b from-or-500/15 via-or-500/0 to-transparent"></div>

            {{-- Signature mark, watermark behind the card --}}
            <x-brand-mark class="pointer-events-none absolute h-[34rem] w-[34rem] -right-40 top-1/2 -translate-y-1/2 text-or-500/[0.07] -rotate-6 hidden lg:block" />

            <div class="relative w-full sm:max-w-md">
                <div class="mb-8 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center">
                        <img src="{{ asset('images/logo-full-800.png') }}" alt="OR SPORT" class="h-28 w-auto sm:h-32" />
                    </a>
                    <p class="mt-2 text-xs uppercase tracking-[0.2em] text-or-500/80">Espace de gestion</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-stade-900/80 px-8 py-9 shadow-2xl backdrop-blur-sm">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center text-xs text-ivoire-300/40">
                    Commandes &amp; livraisons — OR SPORT, Abidjan
                </p>
            </div>
        </div>
    </body>
</html>
