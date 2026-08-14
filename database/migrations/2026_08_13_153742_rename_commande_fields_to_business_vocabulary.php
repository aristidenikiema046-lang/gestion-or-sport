<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            // "Qualité" (F1 / F2) — anciennement un champ libre "modèle de maillot".
            $table->renameColumn('modele_maillot', 'qualite');
            // "Modèle" (Sublimation / Couture) — anciennement la taille du maillot.
            $table->renameColumn('taille', 'modele');
            // "Nom de l'équipe" — anciennement le nom à floquer.
            $table->renameColumn('personnalisation_nom', 'nom_equipe');
        });

        // Les anciennes valeurs libres ne correspondent pas forcément aux
        // nouvelles options fermées : on les ramène sur une valeur par
        // défaut plutôt que de laisser des commandes existantes dans un
        // état qui ne peut plus être sélectionné dans le formulaire.
        DB::table('commandes')->whereNotIn('qualite', ['F1', 'F2'])->update(['qualite' => 'F1']);
        DB::table('commandes')->whereNotIn('modele', ['Sublimation', 'Couture'])->update(['modele' => 'Sublimation']);

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('personnalisation_numero');
        });
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('personnalisation_numero')->nullable();
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->renameColumn('qualite', 'modele_maillot');
            $table->renameColumn('modele', 'taille');
            $table->renameColumn('nom_equipe', 'personnalisation_nom');
        });
    }
};
