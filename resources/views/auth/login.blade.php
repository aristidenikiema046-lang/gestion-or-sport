<x-guest-layout>
    <x-auth-session-status class="mb-4" variant="dark" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" variant="dark" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')"
                required autofocus autocomplete="username" variant="dark" placeholder="vous@orsportswear.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" variant="dark" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" variant="dark" />
            <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password"
                required autocomplete="current-password" variant="dark" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" variant="dark" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-white/20 bg-stade-800 text-or-500 focus:ring-or-500/40" name="remember">
                <span class="ms-2 text-sm text-ivoire-300/70">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-ivoire-300/70 hover:text-or-400 transition" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <x-primary-button class="w-full py-3 text-sm">
            Se connecter
        </x-primary-button>
    </form>
</x-guest-layout>
