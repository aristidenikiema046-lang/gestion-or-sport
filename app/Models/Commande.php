<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Commande extends Model
{
    use HasFactory;

    public const STATUTS = [
        'en_attente' => 'En attente',
        'en_preparation' => 'En préparation',
        'prete' => 'Prête',
        'livree' => 'Livrée',
        'annulee' => 'Annulée',
    ];

    protected $fillable = [
        'reference',
        'client_id',
        'modele_maillot',
        'taille',
        'personnalisation_nom',
        'personnalisation_numero',
        'badge',
        'quantite',
        'statut',
        'date_commande',
        'date_livraison_prevue',
        'date_livraison_effective',
    ];

    protected $casts = [
        'date_commande' => 'datetime',
        'date_livraison_prevue' => 'date',
        'date_livraison_effective' => 'datetime',
        'badge' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Prochaine référence disponible, au format CMD-0001.
    public static function prochaineReference(): string
    {
        $dernier = static::query()
            ->where('reference', 'like', 'CMD-%')
            ->get(['reference'])
            ->map(fn (self $commande) => (int) substr($commande->reference, 4))
            ->max() ?? 0;

        return 'CMD-'.str_pad((string) ($dernier + 1), 4, '0', STR_PAD_LEFT);
    }

    // Est-ce que la commande est en retard ?
    public function getEnRetardAttribute(): bool
    {
        return $this->statut !== 'livree'
            && $this->statut !== 'annulee'
            && $this->date_livraison_prevue->isPast();
    }

    // Est-ce que la livraison approche (dans les 2 jours) ?
    public function getApprocheAttribute(): bool
    {
        return $this->statut !== 'livree'
            && $this->statut !== 'annulee'
            && $this->date_livraison_prevue->isFuture()
            && $this->date_livraison_prevue->diffInDays(Carbon::now(), absolute: true) <= 2;
    }
}