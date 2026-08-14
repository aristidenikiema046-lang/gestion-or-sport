<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CommandeAlerte extends Notification
{
    use Queueable;

    public function __construct(
        public Commande $commande,
        public string $type // 'retard', 'echeance', 'approche' ou 'rappel_semaine'
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        // Le canal n'est ajouté que si le package webpush est installé, pour
        // que cette commande reste utilisable avant sa mise en place.
        if (class_exists(WebPushChannel::class)) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'commande_id' => $this->commande->id,
            'reference' => $this->commande->reference,
            'type' => $this->type,
            'message' => $this->message(),
        ];
    }

    public function toWebPush(object $notifiable, self $notification): WebPushMessage
    {
        return (new WebPushMessage())
            ->title($this->titre())
            ->icon('/icons/icon-512x512.png')
            ->body($this->message())
            ->data(['url' => '/dashboard'])
            ->options(['TTL' => 3600]);
    }

    private function titre(): string
    {
        return match ($this->type) {
            'retard' => 'Livraison en retard',
            'echeance' => "Livraison prévue aujourd'hui",
            'rappel_semaine' => 'Livraison dans une semaine',
            default => 'Livraison à venir',
        };
    }

    private function message(): string
    {
        return match ($this->type) {
            'retard' => "La commande {$this->commande->reference} ({$this->commande->client->nom_complet}) est en retard de livraison.",
            'echeance' => "La livraison de {$this->commande->reference} est prévue aujourd'hui.",
            'rappel_semaine' => "La livraison de {$this->commande->reference} est prévue dans une semaine.",
            default => "La commande {$this->commande->reference} ({$this->commande->client->nom_complet}) doit être livrée bientôt.",
        };
    }
}