<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    /**
     * Enregistre l'abonnement push envoyé par le navigateur de l'admin
     * connecté (voir resources/js/pwa.js — window.OrSportPush.activer()).
     *
     * Nécessite le trait NotificationChannels\WebPush\HasPushSubscriptions
     * sur App\Models\User (ajouté une fois le package webpush installé —
     * voir les instructions fournies séparément).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
        ]);

        $request->user()->updatePushSubscription(
            $validated['endpoint'],
            $validated['keys']['p256dh'],
            $validated['keys']['auth'],
        );

        return response()->json(['status' => 'ok']);
    }
}
