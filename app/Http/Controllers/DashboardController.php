<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $alertes = Commande::whereNotIn('statut', ['livree', 'annulee'])
            ->where('date_livraison_prevue', '<=', now()->addDays(2)->endOfDay())
            ->with('client')
            ->orderBy('date_livraison_prevue')
            ->get()
            ->filter(fn (Commande $commande) => $commande->en_retard || $commande->echeance_aujourdhui || $commande->approche)
            ->values();

        $repartition = Commande::selectRaw('statut, count(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        $parStatut = collect(Commande::STATUTS)->mapWithKeys(
            fn (string $label, string $statut) => [$statut => $repartition->get($statut, 0)]
        );

        $recentes = Commande::with('client')->latest()->take(8)->get();

        return view('dashboard', [
            'alertes' => $alertes,
            'parStatut' => $parStatut,
            'recentes' => $recentes,
            'vapidPublicKey' => config('webpush.vapid.public_key'),
        ]);
    }
}
