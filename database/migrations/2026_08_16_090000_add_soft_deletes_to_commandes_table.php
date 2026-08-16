<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Suppression douce : une commande supprimée passe dans la
            // corbeille (deleted_at renseigné) au lieu d'être perdue
            // immédiatement, pour permettre de corriger une erreur de
            // saisie (mauvais client, doublon) sans risque.
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
