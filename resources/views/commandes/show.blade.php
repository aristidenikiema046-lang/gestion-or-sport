<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-2">
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
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Qualité</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->qualite }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Modèle</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->modele }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Nom de l'équipe</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->nom_equipe ?? '—' }}</dd>
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
                            <dd class="mt-1 text-stade-950">{{ $commande->date_livraison_effective->format('d/m/Y à H:i') }}</dd>
                        </div>
                    @endif
                </dl>

                @if($commande->statut === 'livree')
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-2 rounded-xl bg-livree-500/10 ring-1 ring-inset ring-livree-500/25 px-4 py-3">
                        <svg class="w-5 h-5 shrink-0 text-livree-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium text-livree-600">
                            Livrée le {{ $commande->date_livraison_effective->format('d/m/Y à H:i') }}
                        </p>
                        <a href="{{ route('commandes.bon-livraison', $commande) }}" target="_blank" class="sm:ml-auto text-sm font-semibold text-or-600 hover:text-or-700 whitespace-nowrap">
                            Bon de livraison
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('commandes.edit', $commande) }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg border border-stade-700/20 bg-white px-6 py-3 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                            Modifier la commande
                        </a>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row gap-3">
                        <form method="POST" action="{{ route('commandes.livrer', $commande) }}"
                            onsubmit="return confirm('Marquer la commande {{ $commande->reference }} comme livrée ?');" class="sm:flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-livree-500 px-6 py-3 text-sm font-semibold text-white hover:bg-livree-600 focus:outline-none focus:ring-2 focus:ring-livree-500/50 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                Marquer comme livré
                            </button>
                        </form>
                        <a href="{{ route('commandes.edit', $commande) }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-stade-700/20 bg-white px-6 py-3 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                            Modifier
                        </a>
                    </div>
                @endif

                <div class="pt-4 border-t border-stade-950/5">
                    <a href="{{ route('commandes.index') }}" class="text-sm font-semibold text-or-600 hover:text-or-700">
                        &larr; Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
