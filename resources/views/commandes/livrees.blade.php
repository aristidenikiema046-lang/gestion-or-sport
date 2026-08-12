<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Historique des livraisons
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Filtres --}}
            <form method="GET" action="{{ route('commandes.livrees') }}" class="bg-white rounded-2xl border border-stade-950/5 shadow-sm p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div class="lg:col-span-2">
                        <label for="client" class="sr-only">Client</label>
                        <x-text-input id="client" name="client" type="text" value="{{ $client }}"
                            placeholder="Nom du client" class="w-full" />
                    </div>
                    <div>
                        <label for="modele" class="sr-only">Modèle</label>
                        <x-text-input id="modele" name="modele" type="text" value="{{ $modele }}"
                            placeholder="Modèle de maillot" class="w-full" />
                    </div>
                    <div>
                        <label for="date_debut" class="sr-only">Livré depuis le</label>
                        <x-text-input id="date_debut" name="date_debut" type="date" value="{{ $dateDebut }}" class="w-full" />
                    </div>
                    <div>
                        <label for="date_fin" class="sr-only">Livré jusqu'au</label>
                        <x-text-input id="date_fin" name="date_fin" type="date" value="{{ $dateFin }}" class="w-full" />
                    </div>
                </div>

                <div class="mt-3 flex items-center justify-between gap-3">
                    <div>
                        @if($client || $modele || $dateDebut || $dateFin)
                            <a href="{{ route('commandes.livrees') }}" class="text-sm font-semibold text-or-600 hover:text-or-700">
                                Réinitialiser les filtres
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-stade-950 px-5 py-2.5 text-sm font-semibold text-ivoire-100 hover:bg-stade-800 focus:outline-none focus:ring-2 focus:ring-or-500/50 focus:ring-offset-2 transition">
                        Filtrer
                    </button>
                </div>
            </form>

            <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm overflow-hidden">
                @if($commandes->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-stade-950/5 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-stade-600/60" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @if($client || $modele || $dateDebut || $dateFin)
                            <p class="font-medium text-stade-950">Aucune livraison ne correspond à ces filtres</p>
                            <a href="{{ route('commandes.livrees') }}" class="inline-block mt-4 text-sm font-semibold text-or-600 hover:text-or-700">
                                Réinitialiser les filtres
                            </a>
                        @else
                            <p class="font-medium text-stade-950">Aucune commande livrée pour le moment</p>
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
                                    <th class="px-6 py-3">Qté</th>
                                    <th class="px-6 py-3">Livrée le</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stade-950/5">
                                @foreach($commandes as $commande)
                                    <tr class="hover:bg-stade-950/[0.02] transition">
                                        <td class="px-6 py-4 font-medium text-stade-950 whitespace-nowrap">{{ $commande->reference }}</td>
                                        <td class="px-6 py-4 text-stade-700 whitespace-nowrap">{{ $commande->client->nom_complet }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->modele_maillot }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->quantite }}</td>
                                        <td class="px-6 py-4 text-stade-700 whitespace-nowrap">
                                            {{ $commande->date_livraison_effective?->format('d/m/Y à H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <a href="{{ route('commandes.show', $commande) }}" class="text-stade-600 hover:text-stade-950 font-semibold text-xs mr-4">
                                                Détails
                                            </a>
                                            <a href="{{ route('commandes.bon-livraison', $commande) }}" target="_blank" class="text-or-600 hover:text-or-700 font-semibold text-xs whitespace-nowrap">
                                                Bon de livraison
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
