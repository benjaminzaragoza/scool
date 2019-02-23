<template>
    <span></span>
</template>

<style>

</style>

<script>
export default {
  name: 'ServiceWorker',
  methods: {
    registerServiceWorker () {
      if (!('serviceWorker' in navigator)) {
        console.log('Service workers aren\'t supported in this browser.')
        return
      }
      if (document.readyState === 'complete') {
        window.addEventListener('load', () => {
          this.register()
        })
      } else {
        this.register()
      }
    },
    register () {
      navigator.serviceWorker.register('/sw.js')
        .then(function (registration) {
          console.log('Registration successful, scope is:', registration.scope)

          navigator.serviceWorker.ready
            .then(function (serviceWorkerRegistration) {
              return serviceWorkerRegistration.pushManager.subscribe({
                userVisibleOnly: true
              })
            })
            // .then(function (subscription) { console.log(subscription.endpoint) })
            .catch(function (error) {
              if (Notification.permission === 'denied') {
                console.log('Permission for Notifications was denied')
                // subscribeButton.disabled = true
              } else {
                console.log('TODO ################################ Unable to subscribe to push.', error)
                // subscribeButton.disabled = false
              }
            })

          // navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
          //   serviceWorkerRegistration.pushManager.subscribe()
          //     .then(function (subscription) {
          //       // The subscription was successful
          //       // subscribeButton.disabled = true
          //       // return sendSubscriptionToServer(subscription)
          //     })
          //     .catch(function (error) {
          //       if (Notification.permission === 'denied') {
          //         console.log('Permission for Notifications was denied')
          //         // subscribeButton.disabled = true
          //       } else {
          //         console.log('Unable to subscribe to push.', error)
          //         // subscribeButton.disabled = false
          //       }
          //     })
          // })
        })
        .catch(function (error) {
          console.log('Service worker registration failed, error:', error)
        })
    }
  },
  mounted () {
    this.registerServiceWorker()
  }
}
</script>
