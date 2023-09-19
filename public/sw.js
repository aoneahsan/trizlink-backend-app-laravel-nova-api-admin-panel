importScripts('https://www.gstatic.com/firebasejs/9.4.0/firebase-app-compat.js')
importScripts(
  'https://www.gstatic.com/firebasejs/9.4.0/firebase-messaging-compat.js'
)

const cacheName = 'my-app-cache'
const filesToCache = [
  '/',
  // '/css/app.css',
  // '/js/app.js',
  '/images/zaions-logo-192.png',
  '/images/zaions-logo-512.png'
]

// Initialize Firebase app
firebase.initializeApp({
  apiKey: '<your-api-key>',
  authDomain: '<your-auth-domain>',
  projectId: '<your-project-id>',
  storageBucket: '<your-storage-bucket>',
  messagingSenderId: '<your-messaging-sender-id>',
  appId: '<your-app-id>'
})

// Initialize Firebase messaging
const messaging = firebase.messaging()

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(cacheName).then(cache => {
      return cache.addAll(filesToCache)
    })
  )
})

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== cacheName) {
            return caches.delete(cache)
          }
        })
      )
    })
  )
})

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request)
    })
  )
})

// Update cache on new app version
self.addEventListener('message', event => {
  if (event.data.action === 'skipWaiting') {
    self.skipWaiting()
  }
})

// Handle incoming push notifications
messaging.onMessage(payload => {
  console.log('Received message:', payload)

  const notificationTitle = payload.notification.title
  const notificationOptions = {
    body: payload.notification.body,
    icon: payload.notification.icon
  }

  self.registration.showNotification(notificationTitle, notificationOptions)
})
