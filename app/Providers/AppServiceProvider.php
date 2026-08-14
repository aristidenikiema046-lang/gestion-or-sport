<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Minishlink\WebPush\WebPush;
use NotificationChannels\WebPush\WebPushChannel;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->bindWebPushLogger();
    }

    /**
     * minishlink/web-push falls back to trigger_error(E_USER_NOTICE) when
     * neither bcmath nor gmp is loaded (e.g. minimal Railway PHP image).
     * Laravel's error handler turns that into an uncaught ErrorException,
     * which crashes commandes:verifier-alertes. Passing a PSR-3 logger
     * makes it log through Laravel instead — the vendor's own documented
     * escape hatch, so this crash risk holds even on hosts without bcmath.
     */
    private function bindWebPushLogger(): void
    {
        $this->app->when(WebPushChannel::class)
            ->needs(WebPush::class)
            ->give(function () {
                $webpush = config('webpush');
                $auth = [];

                if (! empty($webpush['vapid']['public_key']) && ! empty($webpush['vapid']['private_key'])) {
                    $auth['VAPID'] = [
                        'publicKey' => $webpush['vapid']['public_key'],
                        'privateKey' => $webpush['vapid']['private_key'],
                        'subject' => $webpush['vapid']['subject'] ?: url('/'),
                    ];
                }

                return (new WebPush(
                    $auth, [], 30, $webpush['client_options'] ?? [], Log::getFacadeRoot()
                ))
                    ->setReuseVAPIDHeaders(true)
                    ->setAutomaticPadding($webpush['automatic_padding'] ?? true);
            });
    }
}