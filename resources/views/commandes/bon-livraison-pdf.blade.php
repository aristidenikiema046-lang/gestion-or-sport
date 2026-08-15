<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Bon de livraison {{ $commande->reference }}</title>
    <style>
        @page {
            margin: 28px 34px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #0a0c0e;
            font-size: 12px;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #0a0c0e;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .header table {
            width: 100%;
        }

        .logo-cell img {
            width: 34px;
            height: 34px;
            vertical-align: middle;
        }

        .brand {
            font-size: 19px;
            font-weight: bold;
            vertical-align: middle;
            padding-left: 8px;
        }

        .brand .or {
            color: #c9a227;
        }

        .title-cell {
            text-align: right;
            vertical-align: middle;
        }

        .doc-title {
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.03em;
        }

        .doc-ref {
            font-size: 12px;
            color: #454c55;
        }

        .infos {
            width: 100%;
            border-bottom: 1px solid #d9d9d9;
            padding-bottom: 16px;
            margin-bottom: 18px;
        }

        .infos table {
            width: 100%;
        }

        .infos td {
            vertical-align: top;
        }

        .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #454c55;
            font-weight: bold;
        }

        .value {
            font-size: 12px;
            margin-top: 4px;
            font-weight: bold;
            color: #0a0c0e;
        }

        .value-sub {
            font-size: 11px;
            color: #454c55;
            margin-top: 2px;
        }

        table.articles {
            width: 100%;
            border-collapse: collapse;
        }

        table.articles th {
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #454c55;
            border-bottom: 1px solid #d9d9d9;
            padding: 0 0 6px 0;
        }

        table.articles td {
            padding: 12px 0;
            border-bottom: 1px solid #d9d9d9;
            font-size: 12px;
            color: #2a2f36;
        }

        table.articles td.principal {
            font-weight: bold;
            color: #0a0c0e;
        }

        table.articles td.qte {
            text-align: right;
            font-weight: bold;
            color: #0a0c0e;
        }

        .footer {
            margin-top: 36px;
            padding-top: 14px;
            border-top: 1px solid #d9d9d9;
            font-size: 9px;
            color: #454c55;
        }

        .footer table {
            width: 100%;
        }

        .footer .right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo-cell" style="width: 60%;">
                    <img src="{{ $logoBase64 }}" alt="">
                    <span class="brand"><span class="or">OR</span> SPORT</span>
                </td>
                <td class="title-cell" style="width: 40%;">
                    <div class="doc-title">BON DE LIVRAISON</div>
                    <div class="doc-ref">{{ $commande->reference }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="infos">
        <table>
            <tr>
                <td style="width: 50%;">
                    <div class="label">Client</div>
                    <div class="value">{{ $commande->client->nom_complet }}</div>
                    <div class="value-sub">{{ $commande->client->telephone }}</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="label">Date de livraison</div>
                    <div class="value">
                        {{ $commande->date_livraison_effective?->format('d/m/Y à H:i') ?? 'Non renseignée' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="articles">
        <thead>
            <tr>
                <th>Type d'article</th>
                <th>Qualité</th>
                <th>Modèle</th>
                <th>Nom de l'équipe</th>
                <th style="text-align: right;">Quantité</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="principal">{{ $commande->type_article }}</td>
                <td class="principal">{{ $commande->qualite }}</td>
                <td>{{ $commande->modele }}</td>
                <td>{{ $commande->nom_equipe ?? '—' }}</td>
                <td class="qte">{{ $commande->quantite }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td>OR SPORT — Abidjan</td>
                <td class="right">Document généré le {{ now()->format('d/m/Y à H:i') }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
