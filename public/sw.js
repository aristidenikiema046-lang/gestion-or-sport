// Service worker OR SPORT — installation PWA, cache basique des assets
// statiques, et notifications push (voir section « Notifications » plus bas).

const CACHE_NAME = 'or-sport-static-v1';
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

// Cache-first, uniquement pour les fichiers statiques (css/js/images/fonts).
// Les pages HTML et les routes dynamiques (commandes, dashboard...) ne sont
// jamais interceptées : elles contiennent des données propres à chaque
// utilisateur connecté et ne doivent pas être mises en cache par le SW.
self.addEventListener('fetch', (event) => {
    const { request } = event;
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    if (url.origin !== self.location.origin) return;
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
