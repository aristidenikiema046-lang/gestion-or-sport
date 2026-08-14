{{--
    Partial partagé entre commandes.create et commandes.edit.
    Attend : $commande (null en création), $clients, $referenceValue,
    $referenceHelp, $submitLabel, $cancelUrl.
--}}
<div class="space-y-6">

    {{-- Client --}}
    <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm p-6 sm:p-8" x-data="{ mode: '{{ old('client_mode', 'existant') }}' }">
        <h3 class="font-display text-lg tracking-tight text-stade-950 mb-4">Client</h3>

        <div class="inline-flex rounded-lg border border-stade-700/15 p-1 bg-stade-950/[0.03]">
            <button type="button" @click="mode = 'existant'"
                :class="mode === 'existant' ? 'bg-white shadow-sm text-stade-950' : 'text-stade-600'"
                class="px-4 py-2 sm:py-1.5 rounded-md text-sm font-medium transition">
                Client existant
            </button>
            <button type="button" @click="mode = 'nouveau'"
                :class="mode === 'nouveau' ? 'bg-white shadow-sm text-stade-950' : 'text-stade-600'"
                class="px-4 py-2 sm:py-1.5 rounded-md text-sm font-medium transition">
                Nouveau client
            </button>
        </div>
        <input type="hidden" name="client_mode" :value="mode">
        <x-input-error :messages="$errors->get('client_mode')" class="mt-2" />

        <div x-show="mode === 'existant'" class="mt-5">
            <x-input-label for="client_id" value="Sélectionner un client" />
            <select id="client_id" name="client_id"
                class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('client_id') border-retard-500 @enderror">
                <option value="">Choisir…</option>
                @foreach($clients as $clientOption)
                    <option value="{{ $clientOption->id }}" @selected((string) old('client_id', $commande?->client_id) === (string) $clientOption->id)>
                        {{ $clientOption->nom_complet }} — {{ $clientOption->telephone }}
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            @if($clients->isEmpty())
                <p class="mt-2 text-sm text-stade-600">Aucun client enregistré pour l'instant — utilisez « Nouveau client ».</p>
            @endif
        </div>

        <div x-show="mode === 'nouveau'" x-cloak class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="client_prenom" value="Prénom" />
                <x-text-input id="client_prenom" name="client_prenom" type="text" value="{{ old('client_prenom') }}"
                    class="mt-1 block w-full @error('client_prenom') border-retard-500 @enderror" />
                <x-input-error :messages="$errors->get('client_prenom')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="client_nom" value="Nom" />
                <x-text-input id="client_nom" name="client_nom" type="text" value="{{ old('client_nom') }}"
                    class="mt-1 block w-full @error('client_nom') border-retard-500 @enderror" />
                <x-input-error :messages="$errors->get('client_nom')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="client_telephone" value="Téléphone" />
                <x-text-input id="client_telephone" name="client_telephone" type="text" value="{{ old('client_telephone') }}"
                    placeholder="+225 07 00 00 00 00"
                    class="mt-1 block w-full @error('client_telephone') border-retard-500 @enderror" />
                <x-input-error :messages="$errors->get('client_telephone')" class="mt-1" />
            </div>
        </div>
    </section>

    {{-- Article --}}
    <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm p-6 sm:p-8">
        <h3 class="font-display text-lg tracking-tight text-stade-950 mb-4">Détails de l'article</h3>

        <div class="space-y-4">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <x-input-label for="type_article" value="Type d'article" />
                    <select id="type_article" name="type_article"
                        class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('type_article') border-retard-500 @enderror">
                        <option value="">Choisir…</option>
                        @foreach(\App\Models\Commande::TYPES_ARTICLE as $valeur => $label)
                            <option value="{{ $valeur }}" @selected(old('type_article', $commande?->type_article) === $valeur)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('type_article')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="qualite" value="Qualité" />
                    <select id="qualite" name="qualite"
                        class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('qualite') border-retard-500 @enderror">
                        <option value="">Choisir…</option>
                        @foreach(\App\Models\Commande::QUALITES as $valeur => $label)
                            <option value="{{ $valeur }}" @selected(old('qualite', $commande?->qualite) === $valeur)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('qualite')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="modele" value="Modèle" />
                    <select id="modele" name="modele"
                        class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('modele') border-retard-500 @enderror">
                        <option value="">Choisir…</option>
                        @foreach(\App\Models\Commande::MODELES as $valeur => $label)
                            <option value="{{ $valeur }}" @selected(old('modele', $commande?->modele) === $valeur)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('modele')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="quantite" value="Quantité" />
                    <x-text-input id="quantite" name="quantite" type="number" min="1" value="{{ old('quantite', $commande?->quantite ?? 1) }}"
                        class="mt-1 block w-full @error('quantite') border-retard-500 @enderror" />
                    <x-input-error :messages="$errors->get('quantite')" class="mt-1" />
                </div>
            </div>

            <div class="flex items-center">
                <label class="inline-flex items-center gap-2 py-1 cursor-pointer select-none">
                    <input type="hidden" name="badge" value="0">
                    <input type="checkbox" name="badge" value="1" @checked(old('badge', $commande?->badge))
                        class="w-5 h-5 rounded border-stade-700/30 text-or-500 focus:ring-2 focus:ring-or-500/40" />
                    <span class="text-sm text-stade-700">Badge officiel</span>
                </label>
            </div>

            <div>
                <x-input-label for="nom_equipe" value="Nom de l'équipe (optionnel)" />
                <x-text-input id="nom_equipe" name="nom_equipe" type="text" value="{{ old('nom_equipe', $commande?->nom_equipe) }}"
                    class="mt-1 block w-full @error('nom_equipe') border-retard-500 @enderror" />
                <x-input-error :messages="$errors->get('nom_equipe')" class="mt-1" />
            </div>
        </div>
    </section>

    {{-- Paiement --}}
    <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm p-6 sm:p-8"
        x-data="{
            montantTotal: {{ (float) old('montant_total', $commande?->montant_total ?? 0) }},
            avanceVersee: {{ (float) old('avance_versee', $commande?->avance_versee ?? 0) }},
            get resteAPayer() { return Math.max(0, this.montantTotal - this.avanceVersee); }
        }">
        <h3 class="font-display text-lg tracking-tight text-stade-950 mb-4">Paiement</h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <x-input-label for="montant_total" value="Montant total (FCFA)" />
                <x-text-input id="montant_total" name="montant_total" type="number" min="0" step="1"
                    x-model.number="montantTotal"
                    value="{{ old('montant_total', $commande?->montant_total) }}"
                    class="mt-1 block w-full @error('montant_total') border-retard-500 @enderror" />
                <x-input-error :messages="$errors->get('montant_total')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="avance_versee" value="Avance versée (FCFA)" />
                <x-text-input id="avance_versee" name="avance_versee" type="number" min="0" step="1"
                    x-model.number="avanceVersee"
                    value="{{ old('avance_versee', $commande?->avance_versee ?? 0) }}"
                    class="mt-1 block w-full @error('avance_versee') border-retard-500 @enderror" />
                <x-input-error :messages="$errors->get('avance_versee')" class="mt-1" />
            </div>
            <div>
                <x-input-label value="Reste à payer" />
                <div class="mt-1 flex items-center h-[46px] px-3.5 rounded-lg border border-stade-700/15 bg-stade-950/[0.03] text-stade-950 font-semibold"
                    x-text="resteAPayer.toLocaleString('fr-FR') + ' FCFA'"></div>
            </div>
        </div>
    </section>

    {{-- Suivi & livraison --}}
    <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm p-6 sm:p-8">
        <h3 class="font-display text-lg tracking-tight text-stade-950 mb-4">Suivi &amp; livraison</h3>

        <div class="space-y-4">
            <div>
                <x-input-label value="Référence" />
                <input type="text" disabled value="{{ $referenceValue }}"
                    class="mt-1 block w-full border bg-stade-950/[0.03] border-stade-700/15 text-stade-600 rounded-lg shadow-sm py-2.5" />
                <p class="mt-1 text-xs text-stade-600/70">{{ $referenceHelp }}</p>
            </div>

            <div>
                <x-input-label for="statut" value="Statut" />
                <select id="statut" name="statut"
                    class="mt-1 block w-full border bg-white border-stade-700/15 text-stade-950 rounded-lg shadow-sm py-2.5 pl-3 pr-9 focus:outline-none focus:border-or-500 focus:ring-2 focus:ring-or-500/30 @error('statut') border-retard-500 @enderror">
                    @foreach(\App\Models\Commande::STATUTS as $valeur => $label)
                        <option value="{{ $valeur }}" @selected(old('statut', $commande?->statut ?? 'en_attente') === $valeur)>{{ $label }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('statut')" class="mt-1" />
                @if($commande)
                    <p class="mt-1 text-xs text-stade-600/70">
                        Passer le statut à « Livrée » renseigne automatiquement la date de livraison effective si elle est vide.
                        La retirer de « Livrée » l'efface.
                    </p>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="date_commande" value="Date de commande" />
                    <x-text-input id="date_commande" name="date_commande" type="date"
                        value="{{ old('date_commande', $commande?->date_commande?->toDateString() ?? now()->toDateString()) }}"
                        class="mt-1 block w-full @error('date_commande') border-retard-500 @enderror" />
                    <x-input-error :messages="$errors->get('date_commande')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="date_livraison_prevue" value="Date de livraison prévue" />
                    <x-text-input id="date_livraison_prevue" name="date_livraison_prevue" type="date"
                        min="{{ $commande ? '' : now()->toDateString() }}"
                        value="{{ old('date_livraison_prevue', $commande?->date_livraison_prevue?->toDateString()) }}"
                        class="mt-1 block w-full @error('date_livraison_prevue') border-retard-500 @enderror" />
                    <x-input-error :messages="$errors->get('date_livraison_prevue')" class="mt-1" />
                    @if($commande)
                        <p class="mt-1 text-xs text-stade-600/70">Peut rester dans le passé si la commande est déjà en retard.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3 sm:gap-4">
        <a href="{{ $cancelUrl }}" class="text-center sm:text-left text-sm font-semibold text-stade-600 hover:text-stade-950 transition">
            Annuler
        </a>
        <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center rounded-lg bg-or-500 px-6 py-3 text-sm font-semibold text-stade-950 hover:bg-or-400 focus:outline-none focus:ring-2 focus:ring-or-500/50 focus:ring-offset-2 transition">
            {{ $submitLabel }}
        </button>
    </div>
</div>
