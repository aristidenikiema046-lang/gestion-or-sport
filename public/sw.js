// Service worker OR SPORT — installation PWA, cache basique des assets
// statiques, et notifications push (voir section « Notifications » plus bas).

const CACHE_NAME = 'or-sport-static-v2';
const STATIC_EXTENSIONS = /\.(?:css|js|png|jpg|jpeg|svg|webp|woff2?|ttf)$/;
const PRECACHE_ASSETS = [
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_ASSETS))
    );
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => Promise.all(
            keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
        ))
    );
    self.clients.claim();
});

self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) return;

    // Requêtes de navigation (chargement de page, y compris le lancement de
    // l'app installée en PWA depuis l'écran d'accueil) : toujours réseau
    // d'abord, avec un respondWith() explicite. Sans ça, certains
    // navigateurs mobiles peuvent laisser la navigation initiale sans
    // réponse claire pendant le lancement en mode standalone, ce qui bloque
    // l'écran de démarrage indéfiniment. La promesse ici se résout ou
    // échoue toujours — jamais d'attente infinie — et le fetch() est
    // explicitement géré par un .catch() pour éviter toute Promise en
    // suspens si le réseau échoue au démarrage à froid.
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => caches.match(request))
        );
        return;
    }

    // Cache-first, uniquement pour les fichiers statiques (css/js/images/fonts).
    // Les routes dynamiques (commandes, dashboard...) ne sont jamais mises en
    // cache : elles contiennent des données propres à chaque utilisateur
    // connecté.
    if (!STATIC_EXTENSIONS.test(url.pathname)) return;

    event.respondWith(
        caches.match(request).then((cached) => {
            if (cached) return cached;

            return fetch(request)
                .then((response) => {
                    if (response.ok) {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
                    }
                    return response;
                })
                .catch(() => cached);
        })
    );
});

// Notifications push (VAPID / laravel-notification-channels/webpush).
self.addEventListener('push', (event) => {
    if (!event.data) return;

    let payload;
    try {
        payload = event.data.json();
    } catch (erreur) {
        payload = { title: 'OR SPORT', body: event.data.text() };
    }

    const url = payload.data?.url || payload.url || '/dashboard';

    event.waitUntil(
        self.registration.showNotification(payload.title || 'OR SPORT', {
            body: payload.body || payload.options?.body,
            icon: payload.icon || '/icons/icon-192x192.png',
            badge: '/icons/icon-192x192.png',
            data: { url },
        })
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    const url = event.notification.data?.url || '/dashboard';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            for (const client of clientList) {
                if (client.url.includes(url) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
