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
            // Défaut 0 pour ne pas casser les lignes déjà en base (le champ
            // devient obligatoire au niveau de la validation applicative pour
            // toute création/édition à partir de maintenant, voir
            // StoreCommandeRequest).
            $table->decimal('montant_total', 10, 2)->default(0)->after('quantite');
            $table->decimal('avance_versee', 10, 2)->default(0)->after('montant_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['montant_total', 'avance_versee']);
        });
    }
};
