<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Ajouter un compte
        </h2>
        <p class="mt-1 text-sm text-stade-600">Créez un accès administrateur pour un membre de l'équipe.</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-stade-950/5">
                <form method="POST" action="{{ route('register') }}" class="p-8 space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Nom complet" />
                        <x-text-input id="name" class="block mt-1.5 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ex. Konan Yao" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Adresse e-mail" />
                        <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="prenom.nom@orsportswear.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Mot de passe" />
                        <x-text-input id="password" class="block mt-1.5 w-full" type="password" name="password" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
                        <x-text-input id="password_confirmation" class="block mt-1.5 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route('dashboard') }}" class="text-sm text-stade-600 hover:text-stade-950 transition">Annuler</a>
                        <x-primary-button>Créer le compte</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
