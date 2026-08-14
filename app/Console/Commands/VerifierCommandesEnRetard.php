<?php

namespace App\Console\Commands;

use App\Models\Commande;
use App\Models\User;
use App\Notifications\CommandeAlerte;
use Illuminate\Console\Command;

class VerifierCommandesEnRetard extends Command
{
    protected $signature = 'commandes:verifier-alertes';
    protected $description = 'Vérifie les commandes en retard ou proches de la date de livraison et notifie les admins';

    public function handle(): void
    {
        $admins = User::where('role', 'admin')->get();

        $commandes = Commande::whereNotIn('statut', ['livree', 'annulee'])->get();

        foreach ($commandes as $commande) {
            $type = match (true) {
                $commande->en_retard => 'retard',
                $commande->echeance_aujourdhui => 'echeance',
                $commande->approche => 'approche',
                default => null,
            };

            if ($type === null) {
                continue;
            }

            foreach ($admins as $admin) {
                $admin->notify(new CommandeAlerte($commande, $type));
            }
        }

        $this->info("Vérification terminée : {$commandes->count()} commandes analysées.");
    }
}