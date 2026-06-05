const CACHE_NAME = "kopi-elang-static-v3";
const ASSETS_TO_CACHE = [
  "/manifest.json",
  "/icons/icon-192.png",
  "/icons/icon-512.png",
  "/icons/apple-touch-icon.png",
  "/icons/splash-logo.png"
];

// Install Event - Pre-cache minimal static assets for PWA installability
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(ASSETS_TO_CACHE);
    }).then(() => self.skipWaiting())
  );
});

// Activate Event - Clean up old caches
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            console.log("[Service Worker] Deleting old cache:", cache);
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event - Cache-First for local static assets, Network-Only for all other requests
self.addEventListener("fetch", (event) => {
  const url = new URL(event.request.url);

  // 1. DILARANG KERAS men-cache request non-GET (POST, PUT, PATCH, DELETE)
  if (event.request.method !== "GET") {
    return;
  }

  // 2. Hanya proses static asset lokal dari origin yang sama (Dilarang cache Google Fonts / asset remote)
  const isLocalOrigin = url.origin === self.location.origin;
  if (!isLocalOrigin) {
    return; // Jalankan network request langsung (Network-Only)
  }

  // 3. DILARANG KERAS men-cache request Livewire, API, AJAX, auth, admin, sales, laporan, atau navigasi HTML
  const isLivewire = url.pathname.includes("/livewire/") || event.request.headers.get("X-Livewire");
  const isApi = url.pathname.startsWith("/api/") || event.request.headers.get("Accept") === "application/json";
  const isAuth = url.pathname.startsWith("/login") || url.pathname.startsWith("/logout") || url.pathname.startsWith("/register");
  const isCriticalData = url.pathname.startsWith("/admin") || url.pathname.startsWith("/sales") || url.pathname.startsWith("/portal") || url.pathname.startsWith("/dashboard");
  
  // Deteksi request navigasi halaman HTML
  const isNavigation = event.request.mode === "navigate" || (event.request.headers.get("Accept") && event.request.headers.get("Accept").includes("text/html"));

  if (isLivewire || isApi || isAuth || isCriticalData || isNavigation) {
    return; // Jalankan network request langsung (Network-Only)
  }

  // 4. Cache-First HANYA untuk local static assets milik aplikasi yang aman
  const isStaticAsset = 
    url.pathname.startsWith("/build/") || // Vite assets
    url.pathname.startsWith("/icons/") || // PWA icons
    url.pathname === "/manifest.json" ||
    url.pathname === "/favicon.ico";

  if (isStaticAsset) {
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request).then((networkResponse) => {
          if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== "basic") {
            return networkResponse;
          }
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
          return networkResponse;
        }).catch(() => {
          return cachedResponse;
        });
      })
    );
  }
});
