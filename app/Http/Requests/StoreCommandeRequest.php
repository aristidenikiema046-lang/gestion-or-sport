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
            'client_adresse' => ['required_if:client_mode,nouveau', 'nullable', 'string', 'max:255'],

            'modele_maillot' => ['required', 'string', 'max:255'],
            'taille' => ['required', Rule::in(['S', 'M', 'L', 'XL', 'XXL'])],
            'personnalisation_nom' => ['nullable', 'string', 'max:255'],
            'personnalisation_numero' => ['nullable', 'string', 'max:10'],
            'badge' => ['nullable', 'boolean'],
            'quantite' => ['required', 'integer', 'min:1'],
            'statut' => ['required', Rule::in(array_keys(Commande::STATUTS))],
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
        ];
    }

    public function attributes(): array
    {
        return [
            'client_id' => 'client',
            'client_nom' => 'nom du client',
            'client_prenom' => 'prénom du client',
            'client_telephone' => 'téléphone du client',
            'client_adresse' => 'adresse du client',
            'modele_maillot' => 'modèle du maillot',
            'taille' => 'taille',
            'quantite' => 'quantité',
            'statut' => 'statut',
            'date_commande' => 'date de commande',
            'date_livraison_prevue' => 'date de livraison prévue',
        ];
    }
}
