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
      navigator.serviceWorker.register('/sw.js')
    }
  },
  mounted () {
    this.registerServiceWorker()
  }
}
</script>
