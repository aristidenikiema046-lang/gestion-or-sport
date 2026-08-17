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

                {{-- Articles --}}
                <div
                    class="rounded-xl border border-stade-950/5 bg-stade-950/[0.02] px-5 py-4"
                    x-data="{
                        ajoutOuvert: @js(old('_form') === 'ajout_article'),
                        articleEnEdition: @js(old('_form') === 'edition_article' ? [
                            'id' => old('article_id'),
                            'type_article' => old('type_article'),
                            'qualite' => old('qualite'),
                            'modele' => old('modele'),
                            'nom_equipe' => old('nom_equipe'),
                            'quantite' => old('quantite'),
                        ] : null),
                        articleASupprimer: null,
                        ouvrirEdition(article) { this.articleEnEdition = { ...article }; },
                        fermerEdition() { this.articleEnEdition = null; },
                        ouvrirSuppression(article) { this.articleASupprimer = article; },
                        fermerSuppression() { this.articleASupprimer = null; }
                    }"
                >
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h3 class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide">Articles</h3>
                        @if(!in_array($commande->statut, ['livree', 'annulee'], true))
                            <button type="button" x-on:click="ajoutOuvert = true" class="inline-flex items-center gap-1 text-xs font-semibold text-or-600 hover:text-or-700">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                Ajouter un article
                            </button>
                        @endif
                    </div>

                    <div class="overflow-x-auto -mx-5">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-stade-600/70 uppercase tracking-wide border-b border-stade-950/5">
                                    <th class="px-5 py-2">Type</th>
                                    <th class="px-5 py-2">Qualité</th>
                                    <th class="px-5 py-2">Modèle</th>
                                    <th class="px-5 py-2">Équipe</th>
                                    <th class="px-5 py-2 text-right">Qté</th>
                                    <th class="px-5 py-2"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stade-950/5">
                                @foreach($commande->articles as $article)
                                    <tr>
                                        <td class="px-5 py-3 text-stade-950 font-medium whitespace-nowrap">{{ $article->type_article }}</td>
                                        <td class="px-5 py-3 text-stade-700 whitespace-nowrap">{{ $article->qualite }}</td>
                                        <td class="px-5 py-3 text-stade-700 whitespace-nowrap">{{ $article->modele }}</td>
                                        <td class="px-5 py-3 text-stade-700">{{ $article->nom_equipe ?? '—' }}</td>
                                        <td class="px-5 py-3 text-stade-950 font-semibold text-right">{{ $article->quantite }}</td>
                                        <td class="px-5 py-3 text-right whitespace-nowrap">
                                            <button
                                                type="button"
                                                x-on:click="ouvrirEdition(@js($article->only(['id', 'type_article', 'qualite', 'modele', 'nom_equipe', 'quantite'])))"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-stade-600 hover:text-stade-950 hover:bg-stade-950/5 transition"
                                                aria-label="Modifier cet article"
                                            >
                                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                                </svg>
                                            </button>
                                            <button
                                                type="button"
                                                x-on:click="ouvrirSuppression(@js($article->only(['id', 'type_article', 'qualite'])))"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-retard-600/70 hover:text-retard-600 hover:bg-retard-500/5 transition"
                                                aria-label="Retirer cet article"
                                            >
                                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482 41.03 41.03 0 00-2.365-.298V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Modale "Ajouter un article" --}}
                    <div x-show="ajoutOuvert" x-on:keydown.escape.window="ajoutOuvert = false" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="ajout-article-titre">
                        <div x-show="ajoutOuvert" x-on:click="ajoutOuvert = false" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-stade-950/60 backdrop-blur-sm"></div>
                        <div x-show="ajoutOuvert" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2" class="relative w-full sm:max-w-md bg-white rounded-2xl shadow-2xl p-6">
                            <h3 id="ajout-article-titre" class="font-display text-lg tracking-tight text-stade-950 mb-4">Ajouter un article</h3>
                            <form method="POST" action="{{ route('commandes.articles.store', $commande) }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="_form" value="ajout_article">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label value="Type d'article" />
                                        <select name="type_article" class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('type_article') border-retard-500 @enderror">
                                            <option value="">Choisir…</option>
                                            @foreach(\App\Models\Commande::TYPES_ARTICLE as $valeur => $label)
                                                <option value="{{ $valeur }}" @selected(old('type_article') === $valeur)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('type_article')" class="mt-1" />
                                    </div>
                                    <div>
                                        <x-input-label value="Qualité" />
                                        <select name="qualite" class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('qualite') border-retard-500 @enderror">
                                            <option value="">Choisir…</option>
                                            @foreach(\App\Models\Commande::QUALITES as $valeur => $label)
                                                <option value="{{ $valeur }}" @selected(old('qualite') === $valeur)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('qualite')" class="mt-1" />
                                    </div>
                                    <div>
                                        <x-input-label value="Modèle" />
                                        <select name="modele" class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('modele') border-retard-500 @enderror">
                                            <option value="">Choisir…</option>
                                            @foreach(\App\Models\Commande::MODELES as $valeur => $label)
                                                <option value="{{ $valeur }}" @selected(old('modele') === $valeur)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('modele')" class="mt-1" />
                                    </div>
                                    <div>
                                        <x-input-label value="Quantité" />
                                        <x-text-input type="number" min="1" name="quantite" value="{{ old('quantite', 1) }}" class="mt-1 block w-full @error('quantite') border-retard-500 @enderror" />
                                        <x-input-error :messages="$errors->get('quantite')" class="mt-1" />
                                    </div>
                                </div>
                                <div>
                                    <x-input-label value="Nom de l'équipe (optionnel)" />
                                    <x-text-input type="text" name="nom_equipe" value="{{ old('nom_equipe') }}" class="mt-1 block w-full @error('nom_equipe') border-retard-500 @enderror" />
                                    <x-input-error :messages="$errors->get('nom_equipe')" class="mt-1" />
                                </div>
                                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
                                    <button type="button" x-on:click="ajoutOuvert = false" class="inline-flex items-center justify-center rounded-lg border border-stade-700/20 bg-white px-5 py-2.5 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                                        Annuler
                                    </button>
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-or-500 px-5 py-2.5 text-sm font-semibold text-stade-950 hover:bg-or-400 focus:outline-none focus:ring-2 focus:ring-or-500/50 focus:ring-offset-2 transition">
                                        Ajouter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modale "Modifier l'article" (une seule instance, réutilisée pour chaque ligne) --}}
                    <div x-show="articleEnEdition !== null" x-on:keydown.escape.window="fermerEdition()" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="edition-article-titre">
                        <div x-show="articleEnEdition !== null" x-on:click="fermerEdition()" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-stade-950/60 backdrop-blur-sm"></div>
                        <template x-if="articleEnEdition">
                            <div x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2" class="relative w-full sm:max-w-md bg-white rounded-2xl shadow-2xl p-6">
                                <h3 id="edition-article-titre" class="font-display text-lg tracking-tight text-stade-950 mb-4">Modifier l'article</h3>
                                <form method="POST" :action="`{{ url('/commandes/'.$commande->id.'/articles') }}/${articleEnEdition.id}`" class="space-y-4">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="_form" value="edition_article">
                                    <input type="hidden" name="article_id" :value="articleEnEdition.id">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <x-input-label value="Type d'article" />
                                            <select name="type_article" x-model="articleEnEdition.type_article" class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('type_article') border-retard-500 @enderror">
                                                @foreach(\App\Models\Commande::TYPES_ARTICLE as $valeur => $label)
                                                    <option value="{{ $valeur }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('type_article')" class="mt-1" />
                                        </div>
                                        <div>
                                            <x-input-label value="Qualité" />
                                            <select name="qualite" x-model="articleEnEdition.qualite" class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('qualite') border-retard-500 @enderror">
                                                @foreach(\App\Models\Commande::QUALITES as $valeur => $label)
                                                    <option value="{{ $valeur }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('qualite')" class="mt-1" />
                                        </div>
                                        <div>
                                            <x-input-label value="Modèle" />
                                            <select name="modele" x-model="articleEnEdition.modele" class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('modele') border-retard-500 @enderror">
                                                @foreach(\App\Models\Commande::MODELES as $valeur => $label)
                                                    <option value="{{ $valeur }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('modele')" class="mt-1" />
                                        </div>
                                        <div>
                                            <x-input-label value="Quantité" />
                                            <x-text-input type="number" min="1" name="quantite" x-model.number="articleEnEdition.quantite" class="mt-1 block w-full @error('quantite') border-retard-500 @enderror" />
                                            <x-input-error :messages="$errors->get('quantite')" class="mt-1" />
                                        </div>
                                    </div>
                                    <div>
                                        <x-input-label value="Nom de l'équipe (optionnel)" />
                                        <x-text-input type="text" name="nom_equipe" x-model="articleEnEdition.nom_equipe" class="mt-1 block w-full @error('nom_equipe') border-retard-500 @enderror" />
                                        <x-input-error :messages="$errors->get('nom_equipe')" class="mt-1" />
                                    </div>
                                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
                                        <button type="button" x-on:click="fermerEdition()" class="inline-flex items-center justify-center rounded-lg border border-stade-700/20 bg-white px-5 py-2.5 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                                            Annuler
                                        </button>
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-or-500 px-5 py-2.5 text-sm font-semibold text-stade-950 hover:bg-or-400 focus:outline-none focus:ring-2 focus:ring-or-500/50 focus:ring-offset-2 transition">
                                            Enregistrer
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </template>
                    </div>

                    {{-- Modale de confirmation "Retirer l'article" --}}
                    <div x-show="articleASupprimer !== null" x-on:keydown.escape.window="fermerSuppression()" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="suppression-article-titre">
                        <div x-show="articleASupprimer !== null" x-on:click="fermerSuppression()" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-stade-950/60 backdrop-blur-sm"></div>
                        <template x-if="articleASupprimer">
                            <div x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2" class="relative w-full sm:max-w-sm bg-white rounded-2xl shadow-2xl p-6">
                                <h3 id="suppression-article-titre" class="font-display text-lg tracking-tight text-stade-950">Retirer cet article</h3>
                                <p class="mt-2 text-sm text-stade-600">
                                    Retirer <span x-text="articleASupprimer.type_article"></span> (<span x-text="articleASupprimer.qualite"></span>) de cette commande ?
                                </p>
                                <form method="POST" :action="`{{ url('/commandes/'.$commande->id.'/articles') }}/${articleASupprimer.id}`" class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" x-on:click="fermerSuppression()" class="inline-flex items-center justify-center rounded-lg border border-stade-700/20 bg-white px-5 py-2.5 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 transition">
                                        Annuler
                                    </button>
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-retard-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-retard-600 focus:outline-none focus:ring-2 focus:ring-retard-500/50 focus:ring-offset-2 transition">
                                        Retirer
                                    </button>
                                </form>
                            </div>
                        </template>
                    </div>
                </div>

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
