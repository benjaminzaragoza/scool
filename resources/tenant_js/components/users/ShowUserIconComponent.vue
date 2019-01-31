<template>
    <span style="display:inline-block">
        <v-tooltip bottom>
            <v-btn slot="activator" v-if="icon" icon class="mx-0" title="" @click.native.stop="prepareDialog">
                <v-icon color="primary">visibility</v-icon>
            </v-btn>
            <span>Vegeu la fitxa de l'usuari</span>
        </v-tooltip>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <show-user :user="user" :users="users" @close="dialog=false"></show-user>
        </v-dialog>
    </span>
</template>

<style>

</style>

<script>
import ShowUserComponent from './ShowUserComponent'

export default {
  name: 'ShowUserIcon',
  components: {
    'show-user': ShowUserComponent
  },
  data () {
    return {
      dialog: false,
      internalUser: this.user
    }
  },
  props: {
    user: {
      type: Object,
      default: () => { return {} }
    },
    users: {
      type: Array,
      default: () => []
    },
    icon: {
      type: Boolean,
      default: true
    }
  },
  watch: {
    user: function (newUser) {
      this.internalUser = this.user
    }
  },
  methods: {
    prepareDialog () {
      if (!_.isEmpty(this.internalUser) && this.users.length > 0) {
        this.dialog = true
        return
      }
      window.axios.get('/api/v1/user').then(response => {
        this.internalUser = response.data
        this.dialog = true
      }).catch(error => {
        console.log(error)
      })
    }
  }
}
</script>
