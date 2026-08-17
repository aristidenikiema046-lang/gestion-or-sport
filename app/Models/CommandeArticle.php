<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandeArticle extends Model
{
    protected $fillable = [
        'commande_id',
        'type_article',
        'qualite',
        'modele',
        'nom_equipe',
        'quantite',
    ];

    protected $casts = [
        'quantite' => 'integer',
    ];

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }
}
