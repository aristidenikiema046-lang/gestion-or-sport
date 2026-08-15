<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
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
    <body class="font-sans antialiased bg-stade-950/5 min-h-screen py-6 sm:py-10 px-4 sm:px-6 print:px-0">
        <div class="no-print max-w-2xl mx-auto mb-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
            @if (Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('commandes.show', $commande) }}" class="text-sm font-medium text-stade-600 hover:text-stade-950 transition">
                    &larr; Retour à la commande
                </a>
            @else
                <span></span>
            @endif

            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <button type="button" id="copier-lien-btn" class="text-sm font-medium text-stade-600 hover:text-stade-950 transition underline decoration-dotted underline-offset-4">
                    Copier le lien
                </button>
                <button type="button" id="partager-btn" class="inline-flex items-center justify-center gap-2 rounded-lg bg-or-500 px-5 py-2.5 text-sm font-semibold text-stade-950 hover:bg-or-400 focus:outline-none focus:ring-2 focus:ring-or-500/50 focus:ring-offset-2 transition disabled:opacity-60">
                    <svg id="partager-icon" class="w-4 h-4 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="15" cy="4.5" r="2.25" />
                        <circle cx="5" cy="10" r="2.25" />
                        <circle cx="15" cy="15.5" r="2.25" />
                        <line x1="7" y1="8.8" x2="13" y2="5.7" />
                        <line x1="7" y1="11.2" x2="13" y2="14.3" />
                    </svg>
                    <span id="partager-label">Partager le PDF</span>
                </button>
                <button type="button" onclick="window.print()" class="inline-flex items-center justify-center gap-2 rounded-lg border border-stade-700/20 bg-white px-5 py-2.5 text-sm font-semibold text-stade-700 hover:bg-stade-950/5 focus:outline-none focus:ring-2 focus:ring-or-500/50 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.096 1.126.15A2.212 2.212 0 0118 8.653v4.097A2.25 2.25 0 0115.75 15h-.241l.111 1.06a1 1 0 01-.994 1.104H5.374a1 1 0 01-.994-1.104L4.491 15H4.25A2.25 2.25 0 012 12.75V8.653c0-1.082.775-2.034 1.874-2.201.374-.054.75-.104 1.126-.15V2.75zM6.5 6.11c1.64-.178 3.302-.27 4.983-.27a.75.75 0 000-1.5c-1.717 0-3.415.093-5.09.276V2.75a.25.25 0 01.25-.25h6.5a.25.25 0 01.25.25v3.086zM6.5 12a.75.75 0 01.75-.75h5.5a.75.75 0 010 1.5h-5.5a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                    </svg>
                    Imprimer / Enregistrer en PDF
                </button>
            </div>
        </div>

        <div class="bon max-w-2xl mx-auto bg-white rounded-2xl border border-stade-950/10 shadow-sm p-6 sm:p-10 print:p-10">
            <div class="flex flex-wrap items-start justify-between gap-4 pb-6 border-b-2 border-stade-950">
                <div class="flex items-center gap-2.5">
                    <x-brand-mark class="h-11 w-11 text-or-500 shrink-0" />
                    <x-application-logo class="text-2xl text-stade-950" />
                </div>
                <div class="text-right">
                    <p class="font-display text-lg tracking-tight text-stade-950">BON DE LIVRAISON</p>
                    <p class="text-sm text-stade-600">{{ $commande->reference }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 py-6 border-b border-stade-950/10">
                <div>
                    <p class="text-xs uppercase tracking-wide text-stade-600/70 font-semibold">Client</p>
                    <p class="mt-1.5 text-stade-950 font-medium">{{ $commande->client->nom_complet }}</p>
                    <p class="text-sm text-stade-600">{{ $commande->client->telephone }}</p>
                </div>
                <div class="sm:text-right">
                    <p class="text-xs uppercase tracking-wide text-stade-600/70 font-semibold">Date de livraison</p>
                    <p class="mt-1.5 text-stade-950 font-medium">
                        {{ $commande->date_livraison_effective?->format('d/m/Y à H:i') ?? 'Non renseignée' }}
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full mt-6 text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-stade-600/70 border-b border-stade-950/10">
                            <th class="py-2 font-semibold whitespace-nowrap">Type d'article</th>
                            <th class="py-2 font-semibold whitespace-nowrap">Qualité</th>
                            <th class="py-2 font-semibold whitespace-nowrap">Modèle</th>
                            <th class="py-2 font-semibold whitespace-nowrap">Nom de l'équipe</th>
                            <th class="py-2 font-semibold text-right whitespace-nowrap">Quantité</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-stade-950/10">
                            <td class="py-4 text-stade-950 font-medium">{{ $commande->type_article }}</td>
                            <td class="py-4 text-stade-950 font-medium">{{ $commande->qualite }}</td>
                            <td class="py-4 text-stade-700">{{ $commande->modele }}</td>
                            <td class="py-4 text-stade-700">{{ $commande->nom_equipe ?? '—' }}</td>
                            <td class="py-4 text-stade-950 font-semibold text-right">{{ $commande->quantite }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-10 pt-6 border-t border-stade-950/10 flex flex-wrap items-center justify-between gap-2 text-xs text-stade-600">
                <p>OR SPORT — Abidjan</p>
                <p>Document généré le {{ now()->format('d/m/Y à H:i') }}</p>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var pdfUrl = {!! \Illuminate\Support\Js::from(route('commandes.bon-livraison.pdf.public', $commande->partage_token)) !!};
                var lienUrl = {!! \Illuminate\Support\Js::from(route('commandes.bon-livraison.public', $commande->partage_token)) !!};
                var pdfFilename = {!! \Illuminate\Support\Js::from('bon-livraison-'.$commande->reference.'.pdf') !!};
                var shareTitle = {!! \Illuminate\Support\Js::from('Bon de livraison '.$commande->reference.' — OR SPORT') !!};
                var shareText = {!! \Illuminate\Support\Js::from('Bon de livraison '.$commande->reference.' pour '.$commande->client->nom_complet) !!};
                var checkIconPath = '<path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />';

                function etatSucces(btn, label, icon, defaultLabel, defaultIcon, texte) {
                    label.textContent = texte;
                    icon.setAttribute('fill', 'currentColor');
                    icon.setAttribute('stroke', 'none');
                    icon.innerHTML = checkIconPath;
                    btn.classList.remove('bg-or-500', 'hover:bg-or-400', 'text-stade-950');
                    btn.classList.add('bg-livree-500', 'text-white');

                    setTimeout(function () {
                        label.textContent = defaultLabel;
                        icon.setAttribute('fill', 'none');
                        icon.setAttribute('stroke', 'currentColor');
                        icon.innerHTML = defaultIcon;
                        btn.classList.remove('bg-livree-500', 'text-white');
                        btn.classList.add('bg-or-500', 'hover:bg-or-400', 'text-stade-950');
                    }, 2000);
                }

                function telechargerBlob(blob, filename) {
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                }

                // Bouton « Copier le lien » — option secondaire, indépendante
                // du partage de fichier ci-dessous.
                var copierBtn = document.getElementById('copier-lien-btn');
                if (copierBtn) {
                    copierBtn.addEventListener('click', async function () {
                        var texteOriginal = copierBtn.textContent;
                        try {
                            await navigator.clipboard.writeText(lienUrl);
                            copierBtn.textContent = 'Lien copié !';
                        } catch (erreur) {
                            window.prompt('Copiez ce lien :', lienUrl);
                        }
                        setTimeout(function () {
                            copierBtn.textContent = texteOriginal;
                        }, 2000);
                    });
                }

                // Bouton « Partager le PDF » — génère un vrai fichier PDF
                // côté serveur et le partage via l'API Web Share (fichier
                // joint, pas juste un lien) sur les navigateurs qui
                // supportent le partage de fichiers. Sinon, télécharge
                // directement le PDF (pas de boîte de dialogue d'impression).
                var partagerBtn = document.getElementById('partager-btn');
                var icon = document.getElementById('partager-icon');
                var label = document.getElementById('partager-label');
                if (!partagerBtn) return;

                var defaultLabel = label.textContent;
                var defaultIcon = icon.innerHTML;

                partagerBtn.addEventListener('click', async function () {
                    partagerBtn.disabled = true;
                    label.textContent = 'Préparation du PDF…';

                    try {
                        var reponse = await fetch(pdfUrl);
                        if (!reponse.ok) {
                            throw new Error('Échec de génération du PDF');
                        }
                        var blob = await reponse.blob();
                        var fichier = new File([blob], pdfFilename, { type: 'application/pdf' });

                        if (navigator.canShare && navigator.canShare({ files: [fichier] })) {
                            await navigator.share({ files: [fichier], title: shareTitle, text: shareText });
                            etatSucces(partagerBtn, label, icon, defaultLabel, defaultIcon, 'Partagé !');
                        } else {
                            telechargerBlob(blob, pdfFilename);
                            etatSucces(partagerBtn, label, icon, defaultLabel, defaultIcon, 'PDF téléchargé !');
                        }
                    } catch (erreur) {
                        if (erreur && erreur.name === 'AbortError') {
                            // Partage annulé par l'utilisateur : rien à faire.
                            label.textContent = defaultLabel;
                        } else {
                            // Échec réseau ou autre : repli sur un lien direct
                            // vers le PDF, que le navigateur téléchargera.
                            window.location.href = pdfUrl;
                            label.textContent = defaultLabel;
                        }
                    } finally {
                        partagerBtn.disabled = false;
                    }
                });
            });
        </script>
    </body>
</html>
