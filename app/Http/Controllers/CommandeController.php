<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function index(Request $request): View
    {
        $statut = $request->string('statut')->value() ?: null;

        $commandes = Commande::query()
            ->with('client')
            ->when($statut, fn ($query) => $query->where('statut', $statut))
            ->latest('date_commande')
            ->paginate(15)
            ->withQueryString();

        return view('commandes.index', [
            'commandes' => $commandes,
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
