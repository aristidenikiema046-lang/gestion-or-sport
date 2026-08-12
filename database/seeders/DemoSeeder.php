<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Commande;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Un admin de test
        $admin = User::firstOrCreate(
            ['email' => 'admin@orsportswear.com'],
            [
                'name' => 'Gérant OR SPORT',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Un client de test
        $client = Client::firstOrCreate([
            'nom' => 'Kouassi',
            'prenom' => 'Jean',
            'telephone' => '+225 0700000000',
            'adresse' => 'Marcory Anoumabo, Abidjan',
        ]);

        // Une commande EN RETARD (date passée, pas livrée)
        Commande::create([
            'reference' => 'CMD-0001',
            'client_id' => $client->id,
            'modele_maillot' => 'Maillot VIP F1',
            'taille' => 'M',
            'personnalisation_nom' => 'KOUASSI',
            'personnalisation_numero' => '10',
            'badge' => true,
            'quantite' => 2,
            'statut' => 'en_preparation',
            'date_commande' => now()->subDays(10),
            'date_livraison_prevue' => now()->subDays(2), // en retard
        ]);

        // Une commande QUI APPROCHE (dans 1 jour)
        Commande::create([
            'reference' => 'CMD-0002',
            'client_id' => $client->id,
            'modele_maillot' => 'Survêtement',
            'taille' => 'L',
            'quantite' => 1,
            'statut' => 'prete',
            'date_commande' => now()->subDays(3),
            'date_livraison_prevue' => now()->addDay(), // approche
        ]);

        // Une commande LIVRÉE (pas d'alerte attendue)
        Commande::create([
            'reference' => 'CMD-0003',
            'client_id' => $client->id,
            'modele_maillot' => 'Sublimation',
            'taille' => 'S',
            'quantite' => 3,
            'statut' => 'livree',
            'date_commande' => now()->subDays(15),
            'date_livraison_prevue' => now()->subDays(10),
            'date_livraison_effective' => now()->subDays(9),
        ]);
    }
}