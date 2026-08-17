<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommandeArticleRequest;
use App\Http\Requests\UpdateCommandeArticleRequest;
use App\Models\Commande;
use App\Models\CommandeArticle;
use Illuminate\Http\RedirectResponse;

class CommandeArticleController extends Controller
{
    public function store(StoreCommandeArticleRequest $request, Commande $commande): RedirectResponse
    {
        if (in_array($commande->statut, ['livree', 'annulee'], true)) {
            return redirect()
                ->route('commandes.show', $commande)
                ->with('error', "Impossible d'ajouter un article : la commande est déjà livrée ou annulée.");
        }

        $commande->articles()->create($request->validated());

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', 'Article ajouté à la commande.');
    }

    public function update(UpdateCommandeArticleRequest $request, Commande $commande, CommandeArticle $article): RedirectResponse
    {
        // L'article est résolu par son seul id (route non imbriquée côté
        // Eloquent) : sans cette vérification, on pourrait modifier
        // l'article d'une autre commande en devinant simplement son id.
        abort_if($article->commande_id !== $commande->id, 404);

        $article->update($request->validated());

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', 'Article mis à jour.');
    }

    public function destroy(Commande $commande, CommandeArticle $article): RedirectResponse
    {
        abort_if($article->commande_id !== $commande->id, 404);

        if ($commande->articles()->count() <= 1) {
            return redirect()
                ->route('commandes.show', $commande)
                ->with('error', 'Impossible de retirer ce dernier article : une commande doit toujours contenir au moins un article.');
        }

        $article->delete();

        return redirect()
            ->route('commandes.show', $commande)
            ->with('success', 'Article retiré de la commande.');
    }
}
