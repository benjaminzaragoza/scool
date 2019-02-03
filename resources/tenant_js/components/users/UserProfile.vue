<template>
    <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            @keydown.esc.stop.prevent="dialog=false">
        <show-user :user="user" @close="dialog=false"></show-user>
    </v-dialog>
</template>

<script>
import ShowUser from './ShowUserComponent'
export default {
  name: 'UserProfile',
  components: {
    'show-user': ShowUser
  },
  data () {
    return {
      dialog: true
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  }
}
</script>
