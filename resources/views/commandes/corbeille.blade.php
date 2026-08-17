<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-display text-2xl text-stade-950 tracking-tight">
                    Corbeille
                </h2>
                <p class="mt-1 text-sm text-stade-600">
                    Commandes supprimées, restaurables à tout moment.
                </p>
            </div>
            <a href="{{ route('commandes.index') }}" class="text-sm font-semibold text-or-600 hover:text-or-700">
                &larr; Retour aux commandes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm overflow-hidden">
                @if($commandes->isEmpty())
                    <div class="px-6 py-14 text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-stade-950/5 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-stade-600/60" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482 41.03 41.03 0 00-2.365-.298V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="font-medium text-stade-950">La corbeille est vide</p>
                        <p class="text-sm text-stade-600 mt-1">Les commandes supprimées apparaîtront ici.</p>
                    </div>
                @else
                    {{-- Cartes empilées : lisibles sur mobile, sans scroll horizontal --}}
                    <ul class="divide-y divide-stade-950/5 sm:hidden">
                        @foreach($commandes as $commande)
                            <li class="px-4 py-4" x-data="{ confirmationSuppressionDefinitive: false }">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-medium text-stade-950 truncate">{{ $commande->client->nom_complet }}</p>
                                        <p class="text-xs text-stade-600/60 mt-0.5">{{ $commande->reference }}</p>
                                    </div>
                                    <span class="shrink-0 text-xs text-stade-600/70">
                                        Suppr. le {{ $commande->deleted_at->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="mt-3 flex items-center gap-2">
                                    <form method="POST" action="{{ route('commandes.restaurer', $commande) }}" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-stade-700/20 bg-white px-3 py-2 text-xs font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                                            <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9.53 2.47a.75.75 0 010 1.06L4.81 8.25H15a6.75 6.75 0 010 13.5h-3a.75.75 0 010-1.5h3a5.25 5.25 0 100-10.5H4.81l4.72 4.72a.75.75 0 11-1.06 1.06l-6-6a.75.75 0 010-1.06l6-6a.75.75 0 011.06 0z" clip-rule="evenodd" />
                                            </svg>
                                            Restaurer
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('commandes.force-delete', $commande) }}" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" x-on:click="confirmationSuppressionDefinitive = true" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg px-3 py-2 text-xs font-semibold text-retard-600 hover:bg-retard-500/10 transition">
                                            Supprimer définitivement
                                        </button>

                                        <x-modale-suppression-definitive :commande="$commande" prefix="mobile" />
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Tableau classique : à partir de la tablette --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-stade-600/70 uppercase tracking-wide">
                                    <th class="px-6 py-3">Référence</th>
                                    <th class="px-6 py-3">Client</th>
                                    <th class="px-6 py-3">Articles</th>
                                    <th class="px-6 py-3">Supprimée le</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stade-950/5">
                                @foreach($commandes as $commande)
                                    <tr class="hover:bg-stade-950/[0.02] transition" x-data="{ confirmationSuppressionDefinitive: false }">
                                        <td class="px-6 py-4 font-medium text-stade-950 whitespace-nowrap">{{ $commande->reference }}</td>
                                        <td class="px-6 py-4 text-stade-700 whitespace-nowrap">{{ $commande->client->nom_complet }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->resume_articles }}</td>
                                        <td class="px-6 py-4 text-stade-700 whitespace-nowrap">{{ $commande->deleted_at->format('d/m/Y à H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <form method="POST" action="{{ route('commandes.restaurer', $commande) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-stade-700/20 bg-white px-3 py-1.5 text-xs font-semibold text-stade-700 hover:bg-stade-950/5 transition whitespace-nowrap">
                                                        <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9.53 2.47a.75.75 0 010 1.06L4.81 8.25H15a6.75 6.75 0 010 13.5h-3a.75.75 0 010-1.5h3a5.25 5.25 0 100-10.5H4.81l4.72 4.72a.75.75 0 11-1.06 1.06l-6-6a.75.75 0 010-1.06l6-6a.75.75 0 011.06 0z" clip-rule="evenodd" />
                                                        </svg>
                                                        Restaurer
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('commandes.force-delete', $commande) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" x-on:click="confirmationSuppressionDefinitive = true" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-semibold text-retard-600 hover:bg-retard-500/10 transition whitespace-nowrap">
                                                        Supprimer définitivement
                                                    </button>

                                                    <x-modale-suppression-definitive :commande="$commande" prefix="desktop" />
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 sm:px-6 py-4 border-t border-stade-950/5">
                        {{ $commandes->links() }}
                    </div>
                @endif
            </section>

        </div>
    </div>
</x-app-layout>
