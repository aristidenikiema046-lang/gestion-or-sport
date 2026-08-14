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
        ]);

        // Une commande EN RETARD (date passée, pas livrée)
        Commande::create([
            'reference' => 'CMD-0001',
            'client_id' => $client->id,
            'qualite' => 'F1',
            'modele' => 'Couture',
            'nom_equipe' => 'KOUASSI',
            'badge' => true,
            'quantite' => 2,
            'statut' => 'en_confection',
            'date_commande' => now()->subDays(10),
            'date_livraison_prevue' => now()->subDays(2), // en retard
        ]);

        // Une commande QUI APPROCHE (dans 1 jour)
        Commande::create([
            'reference' => 'CMD-0002',
            'client_id' => $client->id,
            'qualite' => 'F2',
            'modele' => 'Sublimation',
            'quantite' => 1,
            'statut' => 'en_confection',
            'date_commande' => now()->subDays(3),
            'date_livraison_prevue' => now()->addDay(), // approche
        ]);

        // Une commande LIVRÉE (pas d'alerte attendue)
        Commande::create([
            'reference' => 'CMD-0003',
            'client_id' => $client->id,
            'qualite' => 'F1',
            'modele' => 'Sublimation',
            'quantite' => 3,
            'statut' => 'livree',
            'date_commande' => now()->subDays(15),
            'date_livraison_prevue' => now()->subDays(10),
            'date_livraison_effective' => now()->subDays(9),
        ]);
    }
}