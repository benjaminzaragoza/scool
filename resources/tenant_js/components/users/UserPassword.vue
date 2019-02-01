<template>
    <span>
        <v-tooltip bottom>
            <v-btn slot="activator" flat icon color="secondary" @click="dialog=true">
                <v-icon>vpn_key</v-icon>
            </v-btn>
            <span>Canviar la paraula de pas</span>
        </v-tooltip>
        <v-dialog
                v-if="dialog"
                v-model="dialog"
                width="750"
                @keydown.esc="dialog=false"
        >
            <v-toolbar color="primary" dense>
                <v-toolbar-title class="white--text">Canviar paraula de pas de
                    <span>{{ user.name }}</span> </v-toolbar-title>
            </v-toolbar>
            <user-password-card-form :user="user" @close="dialog=false"></user-password-card-form>
        </v-dialog>
    </span>
</template>

<script>
import UserPasswordCardForm from './UserPasswordCardForm'
export default {
  name: 'UserPassword',
  components: {
    'user-password-card-form': UserPasswordCardForm
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
