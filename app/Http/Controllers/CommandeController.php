<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommandeRequest;
use App\Http\Requests\UpdateCommandeRequest;
use App\Models\Client;
use App\Models\Commande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function index(Request $request): View
    {
        $recherche = $request->string('q')->trim()->value() ?: null;
        $statut = $request->string('statut')->value() ?: null;
        $typeArticle = $request->string('type_article')->value() ?: null;

        $commandes = Commande::query()
            ->with('client')
            ->when($recherche, function ($query) use ($recherche) {
                $query->where(function ($sub) use ($recherche) {
                    $sub->where('reference', 'like', "%{$recherche}%")
                        ->orWhereHas('client', function ($client) use ($recherche) {
                            $client->where('nom', 'like', "%{$recherche}%")
                                ->orWhere('prenom', 'like', "%{$recherche}%")
                                ->orWhereRaw("CONCAT(prenom, ' ', nom) LIKE ?", ["%{$recherche}%"])
                                ->orWhereRaw("CONCAT(nom, ' ', prenom) LIKE ?", ["%{$recherche}%"]);
                        });
                });
            })
            ->when($statut, fn ($query) => $query->where('statut', $statut))
            ->when($typeArticle, fn ($query) => $query->where('type_article', $typeArticle))
            ->orderByRaw("(statut NOT IN ('livree', 'annulee') AND date_livraison_prevue <= CURDATE()) DESC")
            ->orderBy('date_livraison_prevue')
            ->paginate(18)
            ->withQueryString();

        return view('commandes.index', [
            'commandes' => $commandes,
            'recherche' => $recherche,
            'statutActif' => $statut,
            'typeArticleActif' => $typeArticle,
        ]);
    }

    public function show(Commande $commande): View
    {
        $commande->load('client');

        return view('commandes.show', [
            'commande' => $commande,
        ]);
    }

    public function create(): View
    {
        return view('commandes.create', [
            'clients' => Client::orderBy('nom')->orderBy('prenom')->get(),
            'referenceProchaine' => Commande::prochaineReference(),
        ]);
    }

    public function store(StoreCommandeRequest $request): RedirectResponse
    {
        $donnees = $request->validated();

        $commande = DB::transaction(function () use ($donnees, $request) {
            if ($donnees['client_mode'] === 'nouveau') {
                $client = Client::create([
                    'nom' => $donnees['client_nom'],
                    'prenom' => $donnees['client_prenom'],
                    'telephone' => $donnees['client_telephone'],
                ]);
            } else {
                $client = Client::findOrFail($donnees['client_id']);
            }

            return Commande::create([
                'reference' => Commande::prochaineReference(),
                'client_id' => $client->id,
                'type_article' => $donnees['type_article'],
                'qualite' => $donnees['qualite'],
                'modele' => $donnees['modele'],
                'nom_equipe' => $donnees['nom_equipe'] ?? null,
                'badge' => $request->boolean('badge'),
                'quantite' => $donnees['quantite'],
                'statut' => $donnees['statut'],
                'montant_total' => $donnees['montant_total'],
                'avance_versee' => $donnees['avance_versee'] ?? 0,
                'date_commande' => $donnees['date_commande'],
                'date_livraison_prevue' => $donnees['date_livraison_prevue'],
            ]);
        });

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', "Commande {$commande->reference} créée avec succès.");
    }

    public function edit(Commande $commande): View
    {
        $commande->load('client');

        return view('commandes.edit', [
            'commande' => $commande,
            'clients' => Client::orderBy('nom')->orderBy('prenom')->get(),
        ]);
    }

    public function update(UpdateCommandeRequest $request, Commande $commande): RedirectResponse
    {
        $donnees = $request->validated();

        DB::transaction(function () use ($donnees, $request, $commande) {
            if ($donnees['client_mode'] === 'nouveau') {
                $client = Client::create([
                    'nom' => $donnees['client_nom'],
                    'prenom' => $donnees['client_prenom'],
                    'telephone' => $donnees['client_telephone'],
                ]);
            } else {
                $client = Client::findOrFail($donnees['client_id']);
            }

            $dateLivraisonEffective = $commande->date_livraison_effective;
            if ($donnees['statut'] === 'livree') {
                $dateLivraisonEffective ??= now();
            } else {
                $dateLivraisonEffective = null;
            }

            $commande->update([
                'client_id' => $client->id,
                'type_article' => $donnees['type_article'],
                'qualite' => $donnees['qualite'],
                'modele' => $donnees['modele'],
                'nom_equipe' => $donnees['nom_equipe'] ?? null,
                'badge' => $request->boolean('badge'),
                'quantite' => $donnees['quantite'],
                'statut' => $donnees['statut'],
                'montant_total' => $donnees['montant_total'],
                'avance_versee' => $donnees['avance_versee'] ?? 0,
                'date_commande' => $donnees['date_commande'],
                'date_livraison_prevue' => $donnees['date_livraison_prevue'],
                'date_livraison_effective' => $dateLivraisonEffective,
            ]);
        });

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', "Commande {$commande->reference} mise à jour avec succès.");
    }

    public function livrees(Request $request): View
    {
        $client = $request->string('client')->trim()->value() ?: null;
        $qualite = $request->string('qualite')->value() ?: null;
        $typeArticle = $request->string('type_article')->value() ?: null;
        $dateDebut = $request->string('date_debut')->value() ?: null;
        $dateFin = $request->string('date_fin')->value() ?: null;

        $commandes = Commande::query()
            ->with('client')
            ->where('statut', 'livree')
            ->when($client, function ($query) use ($client) {
                $query->whereHas('client', function ($sub) use ($client) {
                    $sub->where('nom', 'like', "%{$client}%")
                        ->orWhere('prenom', 'like', "%{$client}%")
                        ->orWhereRaw("CONCAT(prenom, ' ', nom) LIKE ?", ["%{$client}%"])
                        ->orWhereRaw("CONCAT(nom, ' ', prenom) LIKE ?", ["%{$client}%"]);
                });
            })
            ->when($qualite, fn ($query) => $query->where('qualite', $qualite))
            ->when($typeArticle, fn ($query) => $query->where('type_article', $typeArticle))
            ->when($dateDebut, fn ($query) => $query->whereDate('date_livraison_effective', '>=', $dateDebut))
            ->when($dateFin, fn ($query) => $query->whereDate('date_livraison_effective', '<=', $dateFin))
            ->orderByDesc('date_livraison_effective')
            ->paginate(20)
            ->withQueryString();

        return view('commandes.livrees', [
            'commandes' => $commandes,
            'client' => $client,
            'qualite' => $qualite,
            'typeArticle' => $typeArticle,
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ]);
    }

    public function marquerLivree(Commande $commande): RedirectResponse
    {
        if ($commande->statut !== 'livree') {
            $commande->update([
                'statut' => 'livree',
                'date_livraison_effective' => now(),
            ]);
        }

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', "Commande {$commande->reference} marquée comme livrée.");
    }

    public function bonLivraison(Commande $commande): View
    {
        $commande->load('client');

        return view('commandes.bon-livraison', [
            'commande' => $commande,
        ]);
    }
}
