<x-app-layout>
    <x-slot name="header">
        <h2 class="font-display text-2xl text-stade-950 tracking-tight">
            Tableau de bord
        </h2>
    </x-slot>

    @php
        $cardStyles = [
            'en_attente' => ['ring' => 'ring-stade-950/10', 'accent' => 'bg-stade-600', 'text' => 'text-stade-950'],
            'en_confection' => ['ring' => 'ring-or-500/25', 'accent' => 'bg-or-500', 'text' => 'text-or-700'],
            'livree' => ['ring' => 'ring-livree-500/25', 'accent' => 'bg-livree-500', 'text' => 'text-livree-600'],
            'annulee' => ['ring' => 'ring-stade-950/10', 'accent' => 'bg-stade-600/40', 'text' => 'text-stade-600'],
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Notifications push --}}
            @if($vapidPublicKey)
                <section
                    x-data="{
                        etat: 'verification', // verification, non-supporte, inactif, actif, refuse, erreur
                        vapidPublicKey: @js($vapidPublicKey),
                        async init() {
                            if (!window.OrSportPush || !window.OrSportPush.supporte()) {
                                this.etat = 'non-supporte';
                                return;
                            }
                            if (Notification.permission === 'denied') {
                                this.etat = 'refuse';
                                return;
                            }
                            this.etat = (await window.OrSportPush.estAbonne()) ? 'actif' : 'inactif';
                        },
                        async activer() {
                            this.etat = 'verification';
                            try {
                                await window.OrSportPush.activer(this.vapidPublicKey);
                                this.etat = 'actif';
                            } catch (erreur) {
                                this.etat = erreur.message === 'denied' ? 'refuse' : 'erreur';
                            }
                        },
                    }"
                    class="bg-white rounded-2xl border border-stade-950/5 shadow-sm px-6 py-5 flex items-center justify-between gap-4 flex-wrap"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-or-500/10 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-or-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM8.5 16a1.5 1.5 0 003 0h-3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-stade-950">Notifications push</p>
                            <p class="text-sm text-stade-600" x-show="etat === 'verification'">Vérification en cours&hellip;</p>
                            <p class="text-sm text-stade-600" x-show="etat === 'inactif'">Reçois une alerte sur ton téléphone dès qu'une livraison approche ou arrive à échéance.</p>
                            <p class="text-sm text-livree-600 font-medium" x-show="etat === 'actif'">Activées sur cet appareil.</p>
                            <p class="text-sm text-retard-600" x-show="etat === 'refuse'">Notifications bloquées par le navigateur. Autorise-les dans les réglages du site pour les activer.</p>
                            <p class="text-sm text-retard-600" x-show="etat === 'erreur'">Une erreur est survenue, réessaie.</p>
                            <p class="text-sm text-stade-600" x-show="etat === 'non-supporte'">Non prises en charge par ce navigateur.</p>
                        </div>
                    </div>
                    <button
                        type="button"
                        x-show="etat === 'inactif' || etat === 'erreur'"
                        x-on:click="activer"
                        class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-stade-950 text-white text-sm font-semibold px-4 py-2.5 hover:bg-stade-900 transition"
                    >
                        Activer les notifications
                    </button>
                </section>
            @endif

            {{-- Alertes --}}
            <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stade-950/5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full {{ $alertes->isEmpty() ? 'bg-livree-500/10' : 'bg-retard-500/10' }} flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 {{ $alertes->isEmpty() ? 'text-livree-600' : 'text-retard-500' }}" viewBox="0 0 20 20" fill="currentColor">
                                @if($alertes->isEmpty())
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                @else
                                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
                                @endif
                            </svg>
                        </div>
                        <h3 class="font-display text-lg tracking-tight text-stade-950">Alertes livraison</h3>
                    </div>
                    @if($alertes->isNotEmpty())
                        <span class="shrink-0 text-xs font-semibold text-stade-600 bg-stade-950/5 rounded-full px-2.5 py-1">
                            {{ $alertes->count() }} à surveiller
                        </span>
                    @endif
                </div>

                @if($alertes->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <div class="mx-auto w-12 h-12 rounded-full bg-livree-500/10 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-livree-600" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="font-medium text-stade-950">Tout est à jour</p>
                        <p class="text-sm text-stade-600 mt-1">Aucune commande en retard ni de livraison imminente.</p>
                    </div>
                @else
                    <ul class="divide-y divide-stade-950/5">
                        @foreach($alertes as $commande)
                            @php
                                $enRetard = $commande->en_retard;
                                $echeanceAujourdhui = $commande->echeance_aujourdhui;
                                $delta = (int) now()->startOfDay()->diffInDays($commande->date_livraison_prevue, false);
                                $texte = match(true) {
                                    $enRetard => 'En retard de '.abs($delta).' jour'.(abs($delta) > 1 ? 's' : ''),
                                    $echeanceAujourdhui => "Livraison prévue aujourd'hui",
                                    $delta === 1 => 'Livraison prévue demain',
                                    default => 'Dans '.$delta.' jours',
                                };
                                $couleurPastille = $enRetard ? 'bg-retard-500' : 'bg-approche-500';
                                $couleurTexte = $enRetard ? 'text-retard-600' : 'text-approche-600';
                            @endphp
                            <li>
                                <a href="{{ route('commandes.show', $commande) }}" class="flex items-center gap-3 sm:gap-4 px-4 sm:px-6 py-4 hover:bg-stade-950/[0.02] transition">
                                    <span class="w-1.5 self-stretch rounded-full {{ $couleurPastille }}"></span>
                                    <div class="min-w-0 flex-1 flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-4">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-baseline gap-2">
                                                <p class="font-medium text-stade-950 truncate">{{ $commande->client->nom_complet }}</p>
                                                <span class="text-xs text-stade-600/60 shrink-0">{{ $commande->reference }}</span>
                                            </div>
                                            <p class="text-sm text-stade-600 truncate">Qualité {{ $commande->qualite }} &middot; {{ $commande->modele }}</p>
                                        </div>
                                        <div class="flex items-center gap-2 shrink-0">
                                            <x-statut-badge :statut="$commande->statut" class="shrink-0" />
                                            <span class="text-xs font-semibold whitespace-nowrap {{ $couleurTexte }}">
                                                {{ $texte }}
                                            </span>
                                        </div>
                                    </div>
                                    <svg class="w-4 h-4 text-stade-600/40 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            {{-- Résumé par statut --}}
            <section>
                <h3 class="font-display text-lg tracking-tight text-stade-950 mb-4">Commandes par statut</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($parStatut as $statut => $total)
                        @php $style = $cardStyles[$statut]; @endphp
                        <a href="{{ route('commandes.index', ['statut' => $statut]) }}"
                           class="group relative bg-white rounded-2xl border border-stade-950/5 shadow-sm ring-1 ring-inset {{ $style['ring'] }} p-5 hover:shadow-md hover:-translate-y-0.5 transition overflow-hidden">
                            <span class="absolute top-0 inset-x-0 h-1 {{ $style['accent'] }}"></span>
                            <p class="text-3xl font-display tracking-tight {{ $style['text'] }}">{{ $total }}</p>
                            <p class="mt-1 text-sm font-medium text-stade-600 group-hover:text-stade-950 transition">
                                {{ \App\Models\Commande::STATUTS[$statut] }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </section>

            {{-- Commandes récentes --}}
            <section class="bg-white rounded-2xl border border-stade-950/5 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-stade-950/5 flex items-center justify-between">
                    <h3 class="font-display text-lg tracking-tight text-stade-950">Commandes récentes</h3>
                    <a href="{{ route('commandes.index') }}" class="text-sm font-semibold text-or-600 hover:text-or-700 transition">
                        Voir toutes &rarr;
                    </a>
                </div>

                @if($recentes->isEmpty())
                    <div class="px-6 py-10 text-center text-sm text-stade-600">
                        Aucune commande enregistrée pour le moment.
                    </div>
                @else
                    {{-- Cartes empilées : lisibles sur mobile, sans scroll horizontal --}}
                    <ul class="divide-y divide-stade-950/5 sm:hidden">
                        @foreach($recentes as $commande)
                            <li>
                                <a href="{{ route('commandes.show', $commande) }}" class="block px-4 py-4 active:bg-stade-950/[0.03] transition">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="font-medium text-stade-950 truncate">{{ $commande->client->nom_complet }}</p>
                                            <p class="text-xs text-stade-600/60 mt-0.5">{{ $commande->reference }}</p>
                                        </div>
                                        <x-statut-badge :statut="$commande->statut" class="shrink-0" />
                                    </div>
                                    <div class="mt-2.5 flex items-center justify-between gap-3">
                                        <p class="text-sm text-stade-700 truncate">Qualité {{ $commande->qualite }} &middot; {{ $commande->modele }}</p>
                                        <span class="shrink-0 text-xs text-stade-600">{{ $commande->date_livraison_prevue->format('d/m/Y') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Tableau classique : à partir de la tablette --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs font-semibold text-stade-600/70 uppercase tracking-wide">
                                    <th class="px-6 py-3">Client</th>
                                    <th class="px-6 py-3">Qualité</th>
                                    <th class="px-6 py-3">Modèle</th>
                                    <th class="px-6 py-3">Statut</th>
                                    <th class="px-6 py-3">Livraison prévue</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stade-950/5">
                                @foreach($recentes as $commande)
                                    <tr class="hover:bg-stade-950/[0.02] transition">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-stade-950">{{ $commande->client->nom_complet }}</div>
                                            <div class="text-xs text-stade-600/60">{{ $commande->reference }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->qualite }}</td>
                                        <td class="px-6 py-4 text-stade-700">{{ $commande->modele }}</td>
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
                @endif
            </section>

        </div>
    </div>
</x-app-layout>
