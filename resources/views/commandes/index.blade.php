<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Commandes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if($statutActif)
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-stade-600">Filtré par statut :</span>
                    <x-statut-badge :statut="$statutActif" />
                    <a href="{{ route('commandes.index') }}" class="text-or-600 hover:text-or-700 font-semibold">
                        Réinitialiser
                    </a>
                </div>
            @endif

            <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm overflow-hidden">
                @if($commandes->isEmpty())
                    <div class="px-6 py-10 text-center text-sm text-stade-600">
                        Aucune commande {{ $statutActif ? 'pour ce statut' : 'enregistrée' }} pour le moment.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-stade-600/70 uppercase tracking-wide">
                                    <th class="px-6 py-3">Référence</th>
                                    <th class="px-6 py-3">Client</th>
                                    <th class="px-6 py-3">Modèle</th>
                                    <th class="px-6 py-3">Statut</th>
                                    <th class="px-6 py-3">Livraison prévue</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stade-950/5">
                                @foreach($commandes as $commande)
                                    <tr class="hover:bg-stade-950/[0.02] transition">
                                        <td class="px-6 py-4 font-medium text-stade-950">{{ $commande->reference }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->client->nom_complet }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->modele_maillot }}</td>
                                        <td class="px-6 py-4"><x-statut-badge :statut="$commande->statut" /></td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->date_livraison_prevue->format('d/m/Y') }}</td>
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
