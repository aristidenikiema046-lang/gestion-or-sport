<x-guest-layout>
    <div class="mb-5 text-sm text-ivoire-300/70">
        Indiquez votre adresse e-mail : nous vous envoyons un lien pour choisir un nouveau mot de passe.
    </div>

    <x-auth-session-status class="mb-4" variant="dark" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" variant="dark" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus variant="dark" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" variant="dark" />
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-sm text-ivoire-300/70 hover:text-or-400 transition">Retour à la connexion</a>
            <x-primary-button>
                Envoyer le lien
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
