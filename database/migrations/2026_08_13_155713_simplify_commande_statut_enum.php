<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Un ENUM MySQL rejette toute valeur absente de sa définition : on
        // élargit d'abord la colonne pour accepter à la fois les anciens et
        // le nouveau statut, le temps de basculer les données existantes,
        // avant de resserrer l'ENUM à sa liste finale.
        DB::statement("ALTER TABLE commandes MODIFY statut ENUM('en_attente', 'en_preparation', 'prete', 'en_confection', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente'");

        // "Prête" n'a pas d'équivalent direct dans le nouveau workflow :
        // c'est encore du suivi interne, pas une livraison, donc on la
        // rapproche d'"en_confection" plutôt que de "livree".
        DB::table('commandes')->where('statut', 'en_preparation')->update(['statut' => 'en_confection']);
        DB::table('commandes')->where('statut', 'prete')->update(['statut' => 'en_confection']);

        DB::statement("ALTER TABLE commandes MODIFY statut ENUM('en_attente', 'en_confection', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente'");
    }

    public function down(): void
    {
        // Même principe dans l'autre sens : élargir avant de convertir les
        // données, puis resserrer sur l'ancienne liste.
        DB::statement("ALTER TABLE commandes MODIFY statut ENUM('en_attente', 'en_preparation', 'prete', 'en_confection', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente'");

        DB::table('commandes')->where('statut', 'en_confection')->update(['statut' => 'en_preparation']);

        DB::statement("ALTER TABLE commandes MODIFY statut ENUM('en_attente', 'en_preparation', 'prete', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente'");
    }
};
