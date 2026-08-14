// Enregistrement du service worker (installation PWA + cache statique + push).
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch((erreur) => {
            console.error("Échec de l'enregistrement du service worker :", erreur);
        });
    });
}

// Conversion de la clé publique VAPID (base64 URL-safe) au format Uint8Array
// attendu par PushManager.subscribe().
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map((char) => char.charCodeAt(0)));
}

// API utilisée par le bouton "Activer les notifications" du dashboard.
window.OrSportPush = {
    supporte() {
        return 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
    },

    async estAbonne() {
        if (!this.supporte()) return false;
        const registration = await navigator.serviceWorker.ready;
        const subscription = await registration.pushManager.getSubscription();
        return subscription !== null;
    },

    async activer(vapidPublicKey) {
        if (!this.supporte()) {
            throw new Error('unsupported');
        }

        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            throw new Error('denied');
        }

        const registration = await navigator.serviceWorker.ready;
        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
            });
        }

        const reponse = await fetch('/notifications/push-subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                Accept: 'application/json',
            },
            body: JSON.stringify(subscription),
        });

        if (!reponse.ok) {
            throw new Error('server');
        }

        return subscription;
    },
};
