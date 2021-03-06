(() => {
  'use strict'

  // DIVERSOS PROBLEMES -> Mantenir aquesta llista no és senzill. Mnatenir les versions també té el seu que
    // SOLUCIó: WORKBOX QUE s'encarregui de tota la feina
  const CACHE = 'scool-cache-' + Math.floor(Date.now() / 1000)
  const ITEMS_TO_CACHE = [
    '/',
    '/app.js',
    '/manifest.json',
    '/fonts/vendor/typeface-roboto/files/roboto-latin-400.woff2?5d4aeb4e5f5ef754e307d7ffaef688bd'
  ]

  const Offline = {
    init () {
      self.addEventListener('install', function (event) {
        event.waitUntil(
          caches.open(CACHE).then(function (cache) {
            return cache.addAll(ITEMS_TO_CACHE)
          })
        )
      })
    }
  }

  const AddToHomeScreen = {
    init () {
      self.addEventListener('fetch', function (event) {
        // console.log('ENTORN:')
        // console.log(process.env.NODE_ENV)
        // console.log('PROVA')
        // console.log('WORKER: fetch event in progress.')
        // PLEASE DO NOT REMOVE THIS FETCH HABDLER BECAUSE IS NEEDED FOR ADD TO HOME SCREEN
        // TODO -> OFFLINE

        // console.log('Request -->', event.request.url)
        if (event.request.method !== 'GET') return

        // To tell browser to evaluate the result of event
        // event.respondWith(
        //   caches.match(event.request) //To match current request with cached request it
        //     .then(function(response) {
        //       //If response found return it, else fetch again.
        //       return response || fetch(event.request);
        //     })
        //     .catch(function(error) {
        //       console.error("Error: ", error);
        //     })
      })
    }
  }

  const WebPush = {
    init () {
      self.addEventListener('push', this.notificationPush.bind(this))
      self.addEventListener('notificationclick', this.notificationClick.bind(this))
      self.addEventListener('notificationclose', this.notificationClose.bind(this))
    },

    /**
     * Handle notification push event.
     *
     * https://developer.mozilla.org/en-US/docs/Web/Events/push
     *
     * @param {NotificationEvent} event
     */
    notificationPush (event) {
      console.log(event)
      if (!(self.Notification && self.Notification.permission === 'granted')) {
        return
      }

      // https://developer.mozilla.org/en-US/docs/Web/API/PushMessageData
      if (event.data) {
        event.waitUntil(
          this.sendNotification(event.data.json())
        )
      }
    },

    /**
     * Handle notification click event.
     *
     * https://developer.mozilla.org/en-US/docs/Web/Events/notificationclick
     *
     * @param {NotificationEvent} event
     */
    notificationClick (event) {
      // console.log(event.notification)

      if (event.action === 'some_action') {
        // Do something...
        // self.clients.openWindow(event.action)
      } else {
        self.clients.openWindow('/')
      }
    },

    /**
     * Handle notification close event (Chrome 50+, Firefox 55+).
     *
     * https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerGlobalScope/onnotificationclose
     *
     * @param {NotificationEvent} event
     */
    notificationClose (event) {
      self.registration.pushManager.getSubscription().then(subscription => {
        if (subscription) {
          this.dismissNotification(event, subscription)
        }
      })
    },

    /**
     * Send notification to the user.
     *
     * https://developer.mozilla.org/en-US/docs/Web/API/ServiceWorkerRegistration/showNotification
     *
     * @param {PushMessageData|Object} data
     */
    sendNotification (data) {
      return self.registration.showNotification(data.title, data)
    },

    /**
     * Send request to server to dismiss a notification.
     *
     * @param  {NotificationEvent} event
     * @param  {String} subscription.endpoint
     * @return {Response}
     */
    dismissNotification ({ notification }, { endpoint }) {
      if (!notification.data || !notification.data.id) {
        return
      }

      const data = new FormData()
      data.append('endpoint', endpoint)

      // Send a request to the server to mark the notification as read.
      fetch(`/notifications/${notification.data.id}/dismiss`, {
        method: 'POST',
        body: data
      })
    }
  }

  // WebPush.init()
  AddToHomeScreen.init()
})()
