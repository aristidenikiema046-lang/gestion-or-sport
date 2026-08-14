<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Commande extends Model
{
    use HasFactory;

    public const STATUTS = [
        'en_attente' => 'En attente',
        'en_confection' => 'En confection',
        'livree' => 'Livrée',
        'annulee' => 'Annulée',
    ];

    public const QUALITES = [
        'F1' => 'F1',
        'F2' => 'F2',
    ];

    public const MODELES = [
        'Sublimation' => 'Sublimation',
        'Couture' => 'Couture',
    ];

    protected $fillable = [
        'reference',
        'client_id',
        'qualite',
        'modele',
        'nom_equipe',
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

    // Jeton de partage généré automatiquement — jamais assignable en masse,
    // pour qu'une requête forgée ne puisse pas imposer sa propre valeur.
    protected static function booted(): void
    {
        static::creating(function (Commande $commande) {
            $commande->partage_token ??= Str::random(40);
        });
    }

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

    // Est-ce que la commande est en retard (date de livraison prévue
    // strictement dépassée — n'inclut pas "aujourd'hui", voir
    // echeance_aujourdhui juste en dessous) ?
    public function getEnRetardAttribute(): bool
    {
        return $this->statut !== 'livree'
            && $this->statut !== 'annulee'
            && $this->date_livraison_prevue->lt(Carbon::today());
    }

    // Est-ce que la livraison est prévue aujourd'hui même ? Cas distinct du
    // retard (la date n'est pas encore dépassée) et de l'approche (ce n'est
    // pas "bientôt", c'est aujourd'hui).
    public function getEcheanceAujourdhuiAttribute(): bool
    {
        return $this->statut !== 'livree'
            && $this->statut !== 'annulee'
            && $this->date_livraison_prevue->isToday();
    }

    // Est-ce que la livraison approche (dans les 2 jours, hors aujourd'hui) ?
    public function getApprocheAttribute(): bool
    {
        return $this->statut !== 'livree'
            && $this->statut !== 'annulee'
            && $this->date_livraison_prevue->isFuture()
            && $this->date_livraison_prevue->diffInDays(Carbon::now(), absolute: true) <= 2;
    }
}