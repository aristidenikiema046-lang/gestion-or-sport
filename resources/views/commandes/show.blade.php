<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-display text-2xl text-stade-950 tracking-tight">
                Commande {{ $commande->reference }}
            </h2>
            <x-statut-badge :statut="$commande->statut" class="text-sm" />
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-stade-950/5 shadow-sm p-8 space-y-6">

                @if($commande->en_retard)
                    <div class="rounded-xl bg-retard-500/10 ring-1 ring-inset ring-retard-500/25 px-4 py-3 text-sm font-medium text-retard-600">
                        Cette commande est en retard de livraison.
                    </div>
                @elseif($commande->approche)
                    <div class="rounded-xl bg-approche-500/10 ring-1 ring-inset ring-approche-500/25 px-4 py-3 text-sm font-medium text-approche-600">
                        La livraison de cette commande approche.
                    </div>
                @endif

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Client</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->client->nom_complet }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Téléphone</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->client->telephone }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Modèle</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->modele_maillot }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Taille</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->taille }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Personnalisation</dt>
                        <dd class="mt-1 text-stade-950">
                            @if($commande->personnalisation_nom || $commande->personnalisation_numero)
                                {{ $commande->personnalisation_nom }} {{ $commande->personnalisation_numero }}
                            @else
                                &mdash;
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Quantité</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->quantite }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Date de commande</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->date_commande->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Livraison prévue</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->date_livraison_prevue->format('d/m/Y') }}</dd>
                    </div>
                    @if($commande->date_livraison_effective)
                        <div>
                            <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Livrée le</dt>
                            <dd class="mt-1 text-stade-950">{{ $commande->date_livraison_effective->format('d/m/Y') }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="pt-4 border-t border-stade-950/5">
                    <a href="{{ route('commandes.index') }}" class="text-sm font-semibold text-or-600 hover:text-or-700">
                        &larr; Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
