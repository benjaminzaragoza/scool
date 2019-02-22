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
