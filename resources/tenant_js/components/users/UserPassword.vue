<template>
    <span>
        <v-tooltip bottom>
            <v-btn slot="activator" flat icon color="secondary" @click="dialog=true" class="ma-0">
                <v-icon>vpn_key</v-icon>
            </v-btn>
            <span>Canviar la paraula de pas</span>
        </v-tooltip>
        <user-password-dialog v-if="dialog" :user="user" v-model="dialog"></user-password-dialog>
    </span>
</template>

<script>
import UserPasswordDialog from './UserPasswordDialog'
export default {
  name: 'UserPassword',
  components: {
    'user-password-dialog': UserPasswordDialog
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      dialog: false
    }
  }
}
</script>
