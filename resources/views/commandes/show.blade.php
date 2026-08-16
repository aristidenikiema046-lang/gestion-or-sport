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
                        <dt class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Type d'article</dt>
                        <dd class="mt-1 text-stade-950">{{ $commande->type_article }}</dd>
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

                <div class="rounded-xl border border-stade-950/5 bg-stade-950/[0.02] px-5 py-4">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h3 class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Paiement</h3>
                        <x-statut-paiement-badge :statut="$commande->statut_paiement" />
                    </div>
                    <dl class="grid grid-cols-3 gap-4">
                        <div>
                            <dt class="text-xs text-stade-600/70">Montant total</dt>
                            <dd class="mt-0.5 font-medium text-stade-950">{{ number_format((float) $commande->montant_total, 0, ',', ' ') }} FCFA</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stade-600/70">Avance versée</dt>
                            <dd class="mt-0.5 font-medium text-stade-950">{{ number_format((float) $commande->avance_versee, 0, ',', ' ') }} FCFA</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-stade-600/70">Reste à payer</dt>
                            <dd class="mt-0.5 font-medium {{ $commande->reste_a_payer > 0 ? 'text-retard-600' : 'text-livree-600' }}">{{ number_format($commande->reste_a_payer, 0, ',', ' ') }} FCFA</dd>
                        </div>
                    </dl>
                </div>

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
                    <div class="flex flex-wrap items-center gap-2" x-data="{ confirmationSuppression: false }">
                        <a href="{{ route('commandes.edit', $commande) }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg border border-stade-700/20 bg-white px-6 py-3 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                            Modifier la commande
                        </a>

                        <form method="POST" action="{{ route('commandes.destroy', $commande) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" x-on:click="confirmationSuppression = true" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2.5 text-sm font-medium text-retard-600/70 hover:text-retard-600 hover:bg-retard-500/5 transition">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482 41.03 41.03 0 00-2.365-.298V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                </svg>
                                Supprimer
                            </button>

                            {{-- Modale de confirmation (remplace window.confirm()) --}}
                            <div
                                x-show="confirmationSuppression"
                                x-on:keydown.escape.window="confirmationSuppression = false"
                                class="fixed inset-0 z-50 flex items-center justify-center px-4"
                                style="display: none;"
                                role="dialog"
                                aria-modal="true"
                                aria-labelledby="confirmation-suppression-titre"
                            >
                                <div
                                    x-show="confirmationSuppression"
                                    x-on:click="confirmationSuppression = false"
                                    x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-150"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute inset-0 bg-stade-950/60 backdrop-blur-sm"
                                ></div>

                                <div
                                    x-show="confirmationSuppression"
                                    x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave="ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                    class="relative w-full sm:max-w-sm bg-white rounded-2xl shadow-2xl p-6"
                                >
                                    <h3 id="confirmation-suppression-titre" class="font-display text-lg tracking-tight text-stade-950">
                                        Supprimer la commande
                                    </h3>
                                    <p class="mt-2 text-sm text-stade-600">
                                        Supprimer la commande {{ $commande->reference }} ? Elle pourra être restaurée depuis la corbeille.
                                    </p>
                                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                        <button type="button" x-on:click="confirmationSuppression = false" class="inline-flex items-center justify-center rounded-lg border border-stade-700/20 bg-white px-5 py-2.5 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                                            Annuler
                                        </button>
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-retard-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-retard-600 focus:outline-none focus:ring-2 focus:ring-retard-500/50 focus:ring-offset-2 transition">
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-3" x-data="{ confirmationLivraison: false, confirmationSuppression: false }">
                        <form method="POST" action="{{ route('commandes.livrer', $commande) }}" class="sm:flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="button" x-on:click="confirmationLivraison = true" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-livree-500 px-6 py-3 text-sm font-semibold text-white hover:bg-livree-600 focus:outline-none focus:ring-2 focus:ring-livree-500/50 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                                Marquer comme livré
                            </button>

                            {{-- Modale de confirmation (remplace window.confirm(), qui affiche le nom de domaine technique) --}}
                            <div
                                x-show="confirmationLivraison"
                                x-on:keydown.escape.window="confirmationLivraison = false"
                                class="fixed inset-0 z-50 flex items-center justify-center px-4"
                                style="display: none;"
                                role="dialog"
                                aria-modal="true"
                                aria-labelledby="confirmation-livraison-titre"
                            >
                                <div
                                    x-show="confirmationLivraison"
                                    x-on:click="confirmationLivraison = false"
                                    x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-150"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute inset-0 bg-stade-950/60 backdrop-blur-sm"
                                ></div>

                                <div
                                    x-show="confirmationLivraison"
                                    x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave="ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                    class="relative w-full sm:max-w-sm bg-white rounded-2xl shadow-2xl p-6"
                                >
                                    <h3 id="confirmation-livraison-titre" class="font-display text-lg tracking-tight text-stade-950">
                                        Confirmer la livraison
                                    </h3>
                                    <p class="mt-2 text-sm text-stade-600">
                                        Marquer la commande {{ $commande->reference }} comme livrée ?
                                    </p>
                                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                        <button type="button" x-on:click="confirmationLivraison = false" class="inline-flex items-center justify-center rounded-lg border border-stade-700/20 bg-white px-5 py-2.5 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                                            Annuler
                                        </button>
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-livree-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-livree-600 focus:outline-none focus:ring-2 focus:ring-livree-500/50 focus:ring-offset-2 transition">
                                            Confirmer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <a href="{{ route('commandes.edit', $commande) }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-stade-700/20 bg-white px-6 py-3 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                            Modifier
                        </a>

                        <form method="POST" action="{{ route('commandes.destroy', $commande) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" x-on:click="confirmationSuppression = true" class="inline-flex w-full sm:w-auto items-center justify-center gap-1.5 rounded-lg px-4 py-3 text-sm font-medium text-retard-600/70 hover:text-retard-600 hover:bg-retard-500/5 transition">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482 41.03 41.03 0 00-2.365-.298V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                </svg>
                                Supprimer
                            </button>

                            {{-- Modale de confirmation (remplace window.confirm()) --}}
                            <div
                                x-show="confirmationSuppression"
                                x-on:keydown.escape.window="confirmationSuppression = false"
                                class="fixed inset-0 z-50 flex items-center justify-center px-4"
                                style="display: none;"
                                role="dialog"
                                aria-modal="true"
                                aria-labelledby="confirmation-suppression-titre"
                            >
                                <div
                                    x-show="confirmationSuppression"
                                    x-on:click="confirmationSuppression = false"
                                    x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-150"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute inset-0 bg-stade-950/60 backdrop-blur-sm"
                                ></div>

                                <div
                                    x-show="confirmationSuppression"
                                    x-transition:enter="ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave="ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                    class="relative w-full sm:max-w-sm bg-white rounded-2xl shadow-2xl p-6"
                                >
                                    <h3 id="confirmation-suppression-titre" class="font-display text-lg tracking-tight text-stade-950">
                                        Supprimer la commande
                                    </h3>
                                    <p class="mt-2 text-sm text-stade-600">
                                        Supprimer la commande {{ $commande->reference }} ? Elle pourra être restaurée depuis la corbeille.
                                    </p>
                                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                        <button type="button" x-on:click="confirmationSuppression = false" class="inline-flex items-center justify-center rounded-lg border border-stade-700/20 bg-white px-5 py-2.5 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                                            Annuler
                                        </button>
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-retard-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-retard-600 focus:outline-none focus:ring-2 focus:ring-retard-500/50 focus:ring-offset-2 transition">
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
