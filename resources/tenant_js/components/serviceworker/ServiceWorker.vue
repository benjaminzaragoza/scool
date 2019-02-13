<template>
    <span></span>
</template>

<style>

</style>

<script>
export default {
  name: 'ServiceWorker',
  data () {
    return {
      loading: false,
      isPushEnabled: false,
      pushButtonDisabled: true
    }
  },
  methods: {
    registerServiceWorker () {
      if (!('serviceWorker' in navigator)) {
        console.log('Service workers aren\'t supported in this browser.')
        return
      }

      navigator.serviceWorker.register('/sw.js')
        .then(() => this.initialiseServiceWorker())
    },
    initialiseServiceWorker () {
      if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
        console.log('Notifications aren\'t supported.')
        return
      }

      if (Notification.permission === 'denied') {
        console.log('The user has blocked notifications.')
        return
      }

      if (!('PushManager' in window)) {
        console.log('Push messaging isn\'t supported.')
        return
      }

      navigator.serviceWorker.ready.then(registration => {
        registration.pushManager.getSubscription()
          .then(subscription => {
            this.pushButtonDisabled = false

            if (!subscription) {
              return
            }

            this.updateSubscription(subscription)

            this.isPushEnabled = true
          })
          .catch(e => {
            console.log('Error during getSubscription()', e)
          })
      })
    }
  },
  mounted () {
    this.registerServiceWorker()
  }
}
</script>
