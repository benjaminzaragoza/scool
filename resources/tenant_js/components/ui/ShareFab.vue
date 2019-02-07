<template>
    <v-btn
            v-if="show()"
            v-model="fab"
            color="accent"
            dark
            fab
            fixed
            bottom
            right
            large
            @click="share"
    >
        <v-icon>share</v-icon>
    </v-btn>
</template>

<script>
export default {
  name: 'ShareFab',
  data () {
    return {
      fab: false
    }
  },
  methods: {
    show () {
      if (('share' in navigator)) return true
      return false
    },
    share () {
      if (!('share' in navigator)) {
        return
      }

      navigator.share({
        title: "L'app de l'Institut de l'Ebre",
        text: "L'app per a tota la comunitat educativa de l'Institut de l'Ebre",
        url: 'https://iesebre.scool.cat'
      })
        .then(() => console.log('Successful share'))
        .catch(error => console.log('Error sharing:', error))
    }
  }
}
</script>
