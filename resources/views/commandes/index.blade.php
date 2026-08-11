<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Commandes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Recherche et filtres --}}
            <form method="GET" action="{{ route('commandes.index') }}" class="bg-white rounded-2xl border border-stade-950/5 shadow-sm p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row gap-3">
                    <label for="q" class="sr-only">Rechercher une commande</label>
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-stade-600/50" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                        <x-text-input
                            id="q"
                            name="q"
                            type="text"
                            value="{{ $recherche }}"
                            placeholder="Rechercher un client ou une référence (ex. Kouassi, CMD-0012)"
                            class="w-full pl-10 py-2.5"
                        />
                    </div>

                    <label for="statut" class="sr-only">Filtrer par statut</label>
                    <select
                        id="statut"
                        name="statut"
                        class="border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 sm:w-56 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30"
                    >
                        <option value="">Tous les statuts</option>
                        @foreach (\App\Models\Commande::STATUTS as $valeur => $label)
                            <option value="{{ $valeur }}" @selected($statutActif === $valeur)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-stade-950 px-5 py-2.5 text-sm font-semibold text-ivoire-100 hover:bg-stade-800 focus:outline-none focus:ring-2 focus:ring-or-500/50 focus:ring-offset-2 transition">
                        Rechercher
                    </button>
                </div>

                @if($recherche || $statutActif)
                    <div class="mt-3 flex items-center gap-2 text-sm">
                        <span class="text-stade-600">Filtres actifs :</span>
                        @if($recherche)
                            <span class="inline-flex items-center rounded-full bg-stade-950/5 px-2.5 py-1 text-xs font-medium text-stade-700">
                                &laquo;&nbsp;{{ $recherche }}&nbsp;&raquo;
                            </span>
                        @endif
                        @if($statutActif)
                            <x-statut-badge :statut="$statutActif" />
                        @endif
                        <a href="{{ route('commandes.index') }}" class="text-or-600 hover:text-or-700 font-semibold">
                            Réinitialiser
                        </a>
                    </div>
                @endif
            </form>

            <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm overflow-hidden">
                @if($commandes->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-stade-950/5 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-stade-600/60" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @if($recherche || $statutActif)
                            <p class="font-medium text-stade-950">Aucune commande ne correspond à votre recherche</p>
                            <p class="text-sm text-stade-600 mt-1">Essayez un autre nom, une autre référence, ou réinitialisez les filtres.</p>
                            <a href="{{ route('commandes.index') }}" class="inline-block mt-4 text-sm font-semibold text-or-600 hover:text-or-700">
                                Réinitialiser les filtres
                            </a>
                        @else
                            <p class="font-medium text-stade-950">Aucune commande enregistrée pour le moment</p>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-stade-600/70 uppercase tracking-wide">
                                    <th class="px-6 py-3">Référence</th>
                                    <th class="px-6 py-3">Client</th>
                                    <th class="px-6 py-3">Modèle</th>
                                    <th class="px-6 py-3">Taille</th>
                                    <th class="px-6 py-3">Qté</th>
                                    <th class="px-6 py-3">Statut</th>
                                    <th class="px-6 py-3">Livraison prévue</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stade-950/5">
                                @foreach($commandes as $commande)
                                    <tr class="hover:bg-stade-950/[0.02] transition">
                                        <td class="px-6 py-4 font-medium text-stade-950 whitespace-nowrap">{{ $commande->reference }}</td>
                                        <td class="px-6 py-4 text-stade-700 whitespace-nowrap">{{ $commande->client->nom_complet }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->modele_maillot }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->taille }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->quantite }}</td>
                                        <td class="px-6 py-4"><x-statut-badge :statut="$commande->statut" /></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5">
                                                @if($commande->en_retard)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-retard-500 shrink-0"></span>
                                                @elseif($commande->approche)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-approche-500 shrink-0"></span>
                                                @endif
                                                <span class="{{ $commande->en_retard ? 'text-retard-600 font-medium' : ($commande->approche ? 'text-approche-600 font-medium' : 'text-stade-700') }}">
                                                    {{ $commande->date_livraison_prevue->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('commandes.show', $commande) }}" class="text-or-600 hover:text-or-700 font-semibold text-xs whitespace-nowrap">
                                                Détails
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-stade-950/5">
                        {{ $commandes->links() }}
                    </div>
                @endif
            </section>

        </div>
    </div>
</x-app-layout>
