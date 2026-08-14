<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            // Jeton opaque utilisé pour le lien de partage public du bon de
            // livraison (WhatsApp, etc.) — jamais l'id auto-incrémenté, pour
            // qu'une commande ne puisse pas être devinée à partir d'une autre.
            $table->string('partage_token', 40)->nullable()->unique()->after('reference');
        });

        // Les commandes déjà en base doivent aussi pouvoir être partagées.
        DB::table('commandes')->whereNull('partage_token')->orderBy('id')->pluck('id')->each(
            fn (int $id) => DB::table('commandes')->where('id', $id)->update(['partage_token' => Str::random(40)])
        );
    }

    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn('partage_token');
        });
    }
};
