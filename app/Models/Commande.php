<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\UniqueConstraintViolationException;
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

    public function articles(): HasMany
    {
        return $this->hasMany(CommandeArticle::class);
    }

    // Prochaine référence disponible, au format CMD-0001. withTrashed() est
    // impératif ici : une commande dans la corbeille garde sa référence en
    // base (le soft delete ne fait que renseigner deleted_at), donc l'ignorer
    // fait recalculer un numéro déjà pris dès qu'elle est la plus récente.
    public static function prochaineReference(): string
    {
        $dernier = static::withTrashed()
            ->where('reference', 'like', 'CMD-%')
            ->get(['reference'])
            ->map(fn (self $commande) => (int) substr($commande->reference, 4))
            ->max() ?? 0;

        return 'CMD-'.str_pad((string) ($dernier + 1), 4, '0', STR_PAD_LEFT);
    }

    // Filet de sécurité en plus du calcul ci-dessus (qui devrait déjà
    // suffire) : si une collision de référence survient malgré tout
    // (ex. deux créations concurrentes au même instant), réessaie avec une
    // référence fraîchement recalculée plutôt que de planter la création.
    public static function creerAvecReferenceUnique(array $attributs, int $tentativesMax = 5): self
    {
        for ($tentative = 1; $tentative <= $tentativesMax; $tentative++) {
            try {
                return static::create(array_merge($attributs, [
                    'reference' => static::prochaineReference(),
                ]));
            } catch (UniqueConstraintViolationException $e) {
                if ($tentative === $tentativesMax) {
                    throw $e;
                }
            }
        }
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

    // Résumé lisible des articles, pour les listes (index, historique,
    // dashboard) qui n'ont plus la place d'afficher chaque ligne en détail
    // depuis qu'une commande peut en contenir plusieurs. À charger via
    // with('articles') dans la requête appelante pour éviter le N+1.
    public function getResumeArticlesAttribute(): string
    {
        $articles = $this->articles;

        if ($articles->isEmpty()) {
            return '—';
        }

        if ($articles->count() === 1) {
            return "{$articles->first()->type_article} {$articles->first()->qualite}";
        }

        $types = $articles->pluck('type_article')->unique();

        $typesResume = $types->count() <= 2
            ? $types->implode(', ')
            : $types->take(2)->implode(', ').', …';

        return $articles->count().' articles ('.$typesResume.')';
    }

    // Somme des quantités de toutes les lignes — remplace l'ancienne
    // quantité unique de la commande.
    public function getQuantiteTotaleAttribute(): int
    {
        return (int) $this->articles->sum('quantite');
    }
}