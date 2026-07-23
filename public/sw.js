// বইঘর Service Worker - PWA
const CACHE_NAME = 'boighor-v1.2';
const STATIC_ASSETS = [
    '/',
    '/css/app.css',
    '/js/app.js',
    'https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
    'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
];

// Install Event
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(STATIC_ASSETS).catch(() => {});
        }).then(() => self.skipWaiting())
    );
});

// Activate Event - cleanup old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

// Fetch Event - Network first, fallback to cache
self.addEventListener('fetch', event => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') return;

    // Skip admin routes
    if (event.request.url.includes('/admin/')) return;

    // Skip API routes
    if (event.request.url.includes('/books/') && event.request.url.includes('/download')) return;

    event.respondWith(
        fetch(event.request)
            .then(response => {
                if (response && response.status === 200) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(event.request, clone));
                }
                return response;
            })
            .catch(() => caches.match(event.request).then(cached => {
                if (cached) return cached;
                // Offline fallback for HTML pages
                if (event.request.headers.get('accept')?.includes('text/html')) {
                    return caches.match('/');
                }
            }))
    );
});

// Background Sync (for offline download queue)
self.addEventListener('sync', event => {
    if (event.tag === 'download-queue') {
        event.waitUntil(processDownloadQueue());
    }
});

async function processDownloadQueue() {
    // Process any queued downloads when connection is restored
    const cache = await caches.open('download-queue');
    const requests = await cache.keys();
    return Promise.all(requests.map(async req => {
        try {
            const response = await fetch(req);
            if (response.ok) await cache.delete(req);
        } catch { }
    }));
}

// Push Notifications
self.addEventListener('push', event => {
    const data = event.data?.json() || {};
    event.waitUntil(
        self.registration.showNotification(data.title || 'বইঘর', {
            body: data.body || 'নতুন বই যোগ হয়েছে!',
            icon: '/images/icon-192.png',
            badge: '/images/icon-72.png',
            tag: 'boighor-notification',
            data: { url: data.url || '/' },
            actions: [
                { action: 'open', title: 'দেখুন' },
                { action: 'close', title: 'বন্ধ করুন' }
            ]
        })
    );
});

self.addEventListener('notificationclick', event => {
    event.notification.close();
    if (event.action !== 'close') {
        event.waitUntil(clients.openWindow(event.notification.data?.url || '/'));
    }
});
