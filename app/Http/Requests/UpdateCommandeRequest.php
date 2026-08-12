<?php

namespace App\Http\Requests;

// Mêmes règles que la création, sauf la date de livraison prévue : en
// édition elle peut rester dans le passé (ex. correction d'une commande
// déjà en retard sans changer sa date). Voir StoreCommandeRequest.
class UpdateCommandeRequest extends StoreCommandeRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'date_livraison_prevue' => ['required', 'date'],
        ];
    }
}
