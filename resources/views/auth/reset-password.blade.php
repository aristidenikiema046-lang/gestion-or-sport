<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Adresse e-mail" variant="dark" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" variant="dark" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" variant="dark" />
        </div>

        <div>
            <x-input-label for="password" value="Nouveau mot de passe" variant="dark" />
            <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autocomplete="new-password" variant="dark" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" variant="dark" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" variant="dark" />
            <x-text-input id="password_confirmation" class="block mt-1.5 w-full" type="password" name="password_confirmation" required autocomplete="new-password" variant="dark" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" variant="dark" />
        </div>

        <x-primary-button class="w-full py-3 text-sm">
            Réinitialiser le mot de passe
        </x-primary-button>
    </form>
</x-guest-layout>
