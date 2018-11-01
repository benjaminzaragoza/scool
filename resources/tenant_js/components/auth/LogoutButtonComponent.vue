<template>
    <v-btn :loading="logoutLoading" @click="logout" :flat="flat" :color="color">
        <v-icon right dark>exit_to_app</v-icon>
        Sortir
    </v-btn>
</template>

<style>

</style>

<script>
import * as actions from '../../store/action-types'

export default {
  data () {
    return {
      logoutLoading: false
    }
  },
  props: {
    color: {
      type: String,
      default: 'orange'
    },
    flat: {
      type: Boolean,
      default: true
    },
    redirect: {
      type: String,
      default: '/'
    }
  },
  methods: {
    logout () {
      this.logoutLoading = true
      this.$store.dispatch(actions.LOGOUT).then(response => {
        window.location = this.redirect
      }).catch(error => {
        console.log(error)
      }).then(() => {
        this.logoutLoading = false
      })
    }
  }
}
</script>
