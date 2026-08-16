<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Commande extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUTS = [
        'en_attente' => 'En attente',
        'en_confection' => 'En confection',
        'livree' => 'Livrée',
        'annulee' => 'Annulée',
    ];

    public const QUALITES = [
        'F1' => 'F1',
        'F2' => 'F2',
        'F3' => 'F3',
    ];

    public const MODELES = [
        'Sublimation' => 'Sublimation',
        'Couture' => 'Couture',
    ];

    public const TYPES_ARTICLE = [
        'Maillots' => 'Maillots',
        'Tenue de sortie' => 'Tenue de sortie',
        'Survêtement' => 'Survêtement',
        'Sacs' => 'Sacs',
        'Chaussettes' => 'Chaussettes',
        'Tee-shirts' => 'Tee-shirts',
    ];

    public const STATUTS_PAIEMENT = [
        'non_paye' => 'Non payé',
        'acompte_verse' => 'Acompte versé',
        'solde' => 'Soldé',
    ];

    protected $fillable = [
        'reference',
        'client_id',
        'type_article',
        'qualite',
        'modele',
        'nom_equipe',
        'badge',
        'quantite',
        'statut',
        'montant_total',
        'avance_versee',
        'date_commande',
        'date_livraison_prevue',
        'date_livraison_effective',
    ];

    protected $casts = [
        'date_commande' => 'datetime',
        'date_livraison_prevue' => 'date',
        'date_livraison_effective' => 'datetime',
        'badge' => 'boolean',
        'montant_total' => 'decimal:2',
        'avance_versee' => 'decimal:2',
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

    // Reste-t-il exactement 7 jours avant la livraison ? Comparaison
    // volontairement basée sur Carbon::today() (minuit) des deux côtés,
    // jamais Carbon::now() : le cron tourne à 08:00, donc comparer contre
    // now() introduit un décalage horaire qui rend diffInDays() fractionnaire
    // (ex. 6.67 au lieu de 7 pile), et l'échéance à J-7 ne serait alors
    // jamais détectée. diffInDays() renvoie un float même quand la valeur
    // est un jour entier ("double(7)", pas "int(7)") — d'où le cast (int)
    // avant le === pour éviter un faux négatif silencieux.
    public function getRappelUneSemaineAttribute(): bool
    {
        return $this->statut !== 'livree'
            && $this->statut !== 'annulee'
            && (int) Carbon::today()->diffInDays($this->date_livraison_prevue, absolute: false) === 7;
    }

    // Montant restant dû. Accesseur calculé, jamais stocké, pour ne jamais
    // pouvoir dériver vers une valeur incohérente avec montant_total/avance_versee.
    public function getResteAPayerAttribute(): float
    {
        return (float) $this->montant_total - (float) $this->avance_versee;
    }

    // Statut de paiement dérivé automatiquement des montants — jamais saisi
    // à la main, pour ne pas pouvoir se retrouver désynchronisé du calcul.
    public function getStatutPaiementAttribute(): string
    {
        return match (true) {
            (float) $this->avance_versee <= 0 => 'non_paye',
            (float) $this->avance_versee >= (float) $this->montant_total => 'solde',
            default => 'acompte_verse',
        };
    }
}