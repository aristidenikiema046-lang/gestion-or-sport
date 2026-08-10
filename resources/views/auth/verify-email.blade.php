<x-guest-layout>
    <div class="mb-5 text-sm text-ivoire-300/70">
        Merci de vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer. Vous ne l'avez pas reçu ? Nous pouvons vous en renvoyer un.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-or-400">
            Un nouveau lien de vérification a été envoyé à l'adresse e-mail fournie.
        </div>
    @endif

    <div class="flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Renvoyer l'e-mail de vérification
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-ivoire-300/70 hover:text-or-400 transition">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>
