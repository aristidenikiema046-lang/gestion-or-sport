<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('modele_maillot');
            $table->string('taille');
            $table->string('personnalisation_nom')->nullable();
            $table->string('personnalisation_numero')->nullable();
            $table->boolean('badge')->default(false);
            $table->unsignedInteger('quantite')->default(1);
            $table->enum('statut', ['en_attente', 'en_preparation', 'prete', 'livree', 'annulee'])->default('en_attente');
            $table->dateTime('date_commande');
            $table->date('date_livraison_prevue');
            $table->dateTime('date_livraison_effective')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};