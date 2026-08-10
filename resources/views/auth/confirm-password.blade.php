<x-guest-layout>
    <div class="mb-5 text-sm text-ivoire-300/70">
        Zone sécurisée : merci de confirmer votre mot de passe avant de continuer.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Mot de passe" variant="dark" />
            <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autocomplete="current-password" variant="dark" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" variant="dark" />
        </div>

        <x-primary-button class="w-full py-3 text-sm">
            Confirmer
        </x-primary-button>
    </form>
</x-guest-layout>
