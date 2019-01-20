<template>
    <span>
        <v-tooltip bottom>
          <v-btn slot="activator" icon small color="success" flat class="ma-0" @click="dialog=true">
            <v-icon small>edit</v-icon>
          </v-btn>
          <span>Modificar el email</span>
        </v-tooltip>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Email de l'usuari</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false"><v-icon small>close</v-icon> Sortir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                        <user-mail-manage :user="user" @saved="saved" @close="dialog=false"></user-mail-manage>
                    </v-flex>
                </v-layout>
                </v-container>
            </v-card>

        </v-dialog>
    </span>
</template>

<script>
import UserMailManage from './UserMailManage'
export default {
  name: 'UserEditMail',
  components: {
    'user-mail-manage': UserMailManage
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    saved (payload) {
      this.$emit('saved', payload)
    }
  }
}
</script>
