<?php

namespace App\Http\Requests;

use App\Models\Commande;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommandeArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'type_article' => ['required', Rule::in(array_keys(Commande::TYPES_ARTICLE))],
            'qualite' => ['required', Rule::in(array_keys(Commande::QUALITES))],
            'modele' => ['required', Rule::in(array_keys(Commande::MODELES))],
            'nom_equipe' => ['nullable', 'string', 'max:255'],
            'quantite' => ['required', 'integer', 'min:1'],
        ];
    }

    public function attributes(): array
    {
        return [
            'type_article' => "type d'article",
            'qualite' => 'qualité',
            'modele' => 'modèle',
            'nom_equipe' => "nom de l'équipe",
            'quantite' => 'quantité',
        ];
    }
}
