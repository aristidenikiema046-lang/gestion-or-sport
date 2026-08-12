<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bon de livraison {{ $commande->reference }} — {{ config('app.name', 'OR SPORT') }}</title>

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @media print {
                .no-print {
                    display: none !important;
                }
                body {
                    background: #fff !important;
                    padding: 0 !important;
                }
                .bon {
                    box-shadow: none !important;
                    border: none !important;
                    border-radius: 0 !important;
                }
            }
            @page {
                margin: 1.5cm;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-stade-950/5 min-h-screen py-10">
        <div class="no-print max-w-2xl mx-auto px-4 mb-6 flex items-center justify-between">
            <a href="{{ route('commandes.show', $commande) }}" class="text-sm font-medium text-stade-600 hover:text-stade-950 transition">
                &larr; Retour à la commande
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg bg-or-500 px-5 py-2.5 text-sm font-semibold text-stade-950 hover:bg-or-400 focus:outline-none focus:ring-2 focus:ring-or-500/50 focus:ring-offset-2 transition">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.096 1.126.15A2.212 2.212 0 0118 8.653v4.097A2.25 2.25 0 0115.75 15h-.241l.111 1.06a1 1 0 01-.994 1.104H5.374a1 1 0 01-.994-1.104L4.491 15H4.25A2.25 2.25 0 012 12.75V8.653c0-1.082.775-2.034 1.874-2.201.374-.054.75-.104 1.126-.15V2.75zM6.5 6.11c1.64-.178 3.302-.27 4.983-.27a.75.75 0 000-1.5c-1.717 0-3.415.093-5.09.276V2.75a.25.25 0 01.25-.25h6.5a.25.25 0 01.25.25v3.086zM6.5 12a.75.75 0 01.75-.75h5.5a.75.75 0 010 1.5h-5.5a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                </svg>
                Imprimer / Enregistrer en PDF
            </button>
        </div>

        <div class="bon max-w-2xl mx-auto bg-white rounded-2xl border border-stade-950/10 shadow-sm p-10">
            <div class="flex items-start justify-between pb-6 border-b-2 border-stade-950">
                <div class="flex items-center gap-2.5">
                    <x-brand-mark class="h-10 w-10 text-or-500" />
                    <x-application-logo class="text-2xl text-stade-950" />
                </div>
                <div class="text-right">
                    <p class="font-display text-lg tracking-tight text-stade-950">BON DE LIVRAISON</p>
                    <p class="text-sm text-stade-600">{{ $commande->reference }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 py-6 border-b border-stade-950/10">
                <div>
                    <p class="text-xs uppercase tracking-wide text-stade-600/70 font-semibold">Client</p>
                    <p class="mt-1.5 text-stade-950 font-medium">{{ $commande->client->nom_complet }}</p>
                    <p class="text-sm text-stade-600">{{ $commande->client->telephone }}</p>
                    @if($commande->client->adresse)
                        <p class="text-sm text-stade-600">{{ $commande->client->adresse }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs uppercase tracking-wide text-stade-600/70 font-semibold">Date de livraison</p>
                    <p class="mt-1.5 text-stade-950 font-medium">
                        {{ $commande->date_livraison_effective?->format('d/m/Y à H:i') ?? 'Non renseignée' }}
                    </p>
                </div>
            </div>

            <table class="w-full mt-6 text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wide text-stade-600/70 border-b border-stade-950/10">
                        <th class="py-2 font-semibold">Modèle</th>
                        <th class="py-2 font-semibold">Taille</th>
                        <th class="py-2 font-semibold">Personnalisation</th>
                        <th class="py-2 font-semibold text-right">Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-stade-950/10">
                        <td class="py-4 text-stade-950 font-medium">{{ $commande->modele_maillot }}</td>
                        <td class="py-4 text-stade-700">{{ $commande->taille }}</td>
                        <td class="py-4 text-stade-700">
                            @if($commande->personnalisation_nom || $commande->personnalisation_numero)
                                {{ $commande->personnalisation_nom }} {{ $commande->personnalisation_numero }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="py-4 text-stade-950 font-semibold text-right">{{ $commande->quantite }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-10 pt-6 border-t border-stade-950/10 flex items-center justify-between text-xs text-stade-600">
                <p>OR SPORT — Abidjan</p>
                <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
            </div>
        </div>
    </body>
</html>
