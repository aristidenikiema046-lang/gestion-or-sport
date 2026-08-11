<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
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
}
