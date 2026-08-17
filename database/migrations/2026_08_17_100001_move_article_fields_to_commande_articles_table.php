<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Une commande = un seul article jusqu'ici, donc une seule ligne
        // par commande à recréer. Requête SQL brute (pas Eloquent) pour
        // que withTrashed n'ait pas d'importance : elle porte sur la table
        // telle quelle, donc les commandes déjà dans la corbeille sont
        // couvertes aussi — aucune commande ne doit perdre sa ligne d'article.
        DB::statement(<<<'SQL'
            INSERT INTO commande_articles (commande_id, type_article, qualite, modele, nom_equipe, quantite, created_at, updated_at)
            SELECT id, type_article, qualite, modele, nom_equipe, quantite, created_at, updated_at
            FROM commandes
        SQL);

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['type_article', 'qualite', 'modele', 'nom_equipe', 'badge', 'quantite']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * Best effort : si une commande a reçu plusieurs articles après le up(),
     * seul le premier (le plus ancien) est restauré sur la commande — la
     * notion même de "l'article de la commande" n'existe plus au singulier
     * une fois plusieurs lignes possibles.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('type_article')->nullable()->after('client_id');
            $table->string('qualite')->nullable()->after('type_article');
            $table->string('modele')->nullable()->after('qualite');
            $table->string('nom_equipe')->nullable()->after('modele');
            $table->boolean('badge')->default(false)->after('nom_equipe');
            $table->unsignedInteger('quantite')->default(1)->after('badge');
        });

        DB::statement(<<<'SQL'
            UPDATE commandes c
            JOIN (
                SELECT ca1.commande_id, ca1.type_article, ca1.qualite, ca1.modele, ca1.nom_equipe, ca1.quantite
                FROM commande_articles ca1
                WHERE ca1.id = (
                    SELECT MIN(ca2.id) FROM commande_articles ca2 WHERE ca2.commande_id = ca1.commande_id
                )
            ) premier_article ON premier_article.commande_id = c.id
            SET c.type_article = premier_article.type_article,
                c.qualite = premier_article.qualite,
                c.modele = premier_article.modele,
                c.nom_equipe = premier_article.nom_equipe,
                c.quantite = premier_article.quantite
        SQL);
    }
};
