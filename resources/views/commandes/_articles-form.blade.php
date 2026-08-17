{{--
    Bloc "Articles" pour commandes.create — liste dynamique de lignes gérée
    en Alpine (ajout/retrait côté client), toujours revalidée côté serveur
    par StoreCommandeRequest quel que soit ce que le JS a laissé passer.
    Inclus uniquement quand $showArticles est vrai (voir _form.blade.php).
--}}
@php
    // Aplati les erreurs "articles.0.qualite" en map JS-friendly, pour que
    // Alpine puisse afficher l'erreur au bon endroit après un aller-retour
    // serveur, même si le nombre de lignes affichées a changé entre-temps.
    $erreursArticles = collect($errors->keys())
        ->filter(fn ($cle) => str_starts_with($cle, 'articles.'))
        ->mapWithKeys(fn ($cle) => [$cle => $errors->first($cle)]);
@endphp
<section
    class="bg-white rounded-2xl border border-stade-950/5 shadow-sm p-6 sm:p-8"
    x-data="{
        lignes: @js(old('articles') ?: [['type_article' => '', 'qualite' => '', 'modele' => '', 'nom_equipe' => '', 'quantite' => 1]]),
        erreurs: @js($erreursArticles),
        ajouterLigne() {
            this.lignes.push({ type_article: '', qualite: '', modele: '', nom_equipe: '', quantite: 1 });
        },
        retirerLigne(index) {
            if (this.lignes.length > 1) {
                this.lignes.splice(index, 1);
            }
        },
        erreur(index, champ) {
            return this.erreurs['articles.' + index + '.' + champ] ?? null;
        }
    }"
>
    <h3 class="font-display text-lg tracking-tight text-stade-950 mb-4">Articles</h3>

    <template x-for="(ligne, index) in lignes" :key="index">
        <div class="rounded-xl border border-stade-950/10 p-4 sm:p-5 mb-4 last:mb-0">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold text-stade-600/70 uppercase tracking-wide" x-text="'Article ' + (index + 1)"></span>
                <button
                    type="button"
                    x-show="lignes.length > 1"
                    x-on:click="retirerLigne(index)"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-retard-600/70 hover:text-retard-600 transition"
                >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482 41.03 41.03 0 00-2.365-.298V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" />
                    </svg>
                    Retirer
                </button>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <x-input-label value="Type d'article" />
                    <select
                        :name="`articles[${index}][type_article]`"
                        x-model="ligne.type_article"
                        :class="erreur(index, 'type_article') ? 'border-retard-500' : 'border-stade-700/15'"
                        class="mt-1 block w-full border bg-white text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30"
                    >
                        <option value="">Choisir…</option>
                        @foreach(\App\Models\Commande::TYPES_ARTICLE as $valeur => $label)
                            <option value="{{ $valeur }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-retard-600" x-show="erreur(index, 'type_article')" x-text="erreur(index, 'type_article')"></p>
                </div>
                <div>
                    <x-input-label value="Qualité" />
                    <select
                        :name="`articles[${index}][qualite]`"
                        x-model="ligne.qualite"
                        :class="erreur(index, 'qualite') ? 'border-retard-500' : 'border-stade-700/15'"
                        class="mt-1 block w-full border bg-white text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30"
                    >
                        <option value="">Choisir…</option>
                        @foreach(\App\Models\Commande::QUALITES as $valeur => $label)
                            <option value="{{ $valeur }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-retard-600" x-show="erreur(index, 'qualite')" x-text="erreur(index, 'qualite')"></p>
                </div>
                <div>
                    <x-input-label value="Modèle" />
                    <select
                        :name="`articles[${index}][modele]`"
                        x-model="ligne.modele"
                        :class="erreur(index, 'modele') ? 'border-retard-500' : 'border-stade-700/15'"
                        class="mt-1 block w-full border bg-white text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30"
                    >
                        <option value="">Choisir…</option>
                        @foreach(\App\Models\Commande::MODELES as $valeur => $label)
                            <option value="{{ $valeur }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-retard-600" x-show="erreur(index, 'modele')" x-text="erreur(index, 'modele')"></p>
                </div>
                <div>
                    <x-input-label value="Quantité" />
                    <x-text-input
                        type="number" min="1"
                        x-bind:name="`articles[${index}][quantite]`"
                        x-model.number="ligne.quantite"
                        class="mt-1 block w-full"
                    />
                    <p class="mt-1 text-sm text-retard-600" x-show="erreur(index, 'quantite')" x-text="erreur(index, 'quantite')"></p>
                </div>
            </div>

            <div class="mt-4">
                <x-input-label value="Nom de l'équipe (optionnel)" />
                <x-text-input
                    type="text"
                    x-bind:name="`articles[${index}][nom_equipe]`"
                    x-model="ligne.nom_equipe"
                    class="mt-1 block w-full"
                />
                <p class="mt-1 text-sm text-retard-600" x-show="erreur(index, 'nom_equipe')" x-text="erreur(index, 'nom_equipe')"></p>
            </div>
        </div>
    </template>

    <x-input-error :messages="$errors->get('articles')" class="mb-3" />

    <button
        type="button"
        x-on:click="ajouterLigne()"
        class="inline-flex items-center gap-1.5 rounded-lg border border-dashed border-stade-700/25 px-4 py-2.5 text-sm font-semibold text-stade-700 hover:border-or-500 hover:text-or-600 hover:bg-or-500/5 transition"
    >
        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
        </svg>
        Ajouter un article
    </button>
</section>
