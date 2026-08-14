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
            // Défaut "Maillots" pour les lignes déjà en base : c'était le
            // seul type d'article confectionné jusqu'ici, donc un backfill
            // fiable plutôt qu'une valeur neutre à corriger manuellement.
            $table->string('type_article')->default('Maillots')->after('client_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('type_article');
        });
    }
};
