<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommandeRequest;
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
            ->orderByRaw("(statut NOT IN ('livree', 'annulee') AND date_livraison_prevue <= CURDATE()) DESC")
            ->orderBy('date_livraison_prevue')
            ->paginate(18)
            ->withQueryString();

        return view('commandes.index', [
            'commandes' => $commandes,
            'recherche' => $recherche,
            'statutActif' => $statut,
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
                    'adresse' => $donnees['client_adresse'],
                ]);
            } else {
                $client = Client::findOrFail($donnees['client_id']);
            }

            return Commande::create([
                'reference' => Commande::prochaineReference(),
                'client_id' => $client->id,
                'modele_maillot' => $donnees['modele_maillot'],
                'taille' => $donnees['taille'],
                'personnalisation_nom' => $donnees['personnalisation_nom'] ?? null,
                'personnalisation_numero' => $donnees['personnalisation_numero'] ?? null,
                'badge' => $request->boolean('badge'),
                'quantite' => $donnees['quantite'],
                'statut' => $donnees['statut'],
                'date_commande' => $donnees['date_commande'],
                'date_livraison_prevue' => $donnees['date_livraison_prevue'],
            ]);
        });

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', "Commande {$commande->reference} créée avec succès.");
    }
}
