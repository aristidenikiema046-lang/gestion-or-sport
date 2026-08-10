<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommandeAlerte extends Notification
{
    use Queueable;

    public function __construct(
        public Commande $commande,
        public string $type // 'retard' ou 'approche'
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $message = $this->type === 'retard'
            ? "La commande {$this->commande->reference} ({$this->commande->client->nom_complet}) est en retard de livraison."
            : "La commande {$this->commande->reference} ({$this->commande->client->nom_complet}) doit être livrée bientôt.";

        return [
            'commande_id' => $this->commande->id,
            'reference' => $this->commande->reference,
            'type' => $this->type,
            'message' => $message,
        ];
    }
}