<?php

namespace App\Http\Requests;

use App\Models\Commande;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'client_mode' => ['required', Rule::in(['existant', 'nouveau'])],
            'client_id' => ['required_if:client_mode,existant', 'nullable', 'exists:clients,id'],
            'client_nom' => ['required_if:client_mode,nouveau', 'nullable', 'string', 'max:255'],
            'client_prenom' => ['required_if:client_mode,nouveau', 'nullable', 'string', 'max:255'],
            'client_telephone' => ['required_if:client_mode,nouveau', 'nullable', 'string', 'max:50'],

            'type_article' => ['required', Rule::in(array_keys(Commande::TYPES_ARTICLE))],
            'qualite' => ['required', Rule::in(array_keys(Commande::QUALITES))],
            'modele' => ['required', Rule::in(array_keys(Commande::MODELES))],
            'nom_equipe' => ['nullable', 'string', 'max:255'],
            'badge' => ['nullable', 'boolean'],
            'quantite' => ['required', 'integer', 'min:1'],
            'statut' => ['required', Rule::in(array_keys(Commande::STATUTS))],
            'montant_total' => ['required', 'numeric', 'min:0'],
            'avance_versee' => ['nullable', 'numeric', 'min:0', 'lte:montant_total'],
            'date_commande' => ['required', 'date'],
            'date_livraison_prevue' => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required_if' => 'Sélectionnez un client existant.',
            'client_id.exists' => "Le client sélectionné n'existe pas.",
            'date_livraison_prevue.after_or_equal' => 'La date de livraison ne peut pas être dans le passé.',
            'avance_versee.lte' => "L'avance versée ne peut pas dépasser le montant total.",
        ];
    }

    public function attributes(): array
    {
        return [
            'client_id' => 'client',
            'client_nom' => 'nom du client',
            'client_prenom' => 'prénom du client',
            'client_telephone' => 'téléphone du client',
            'type_article' => "type d'article",
            'qualite' => 'qualité',
            'modele' => 'modèle',
            'nom_equipe' => "nom de l'équipe",
            'quantite' => 'quantité',
            'statut' => 'statut',
            'montant_total' => 'montant total',
            'avance_versee' => 'avance versée',
            'date_commande' => 'date de commande',
            'date_livraison_prevue' => 'date de livraison prévue',
        ];
    }
}
