<template>
    <span>
        <v-tooltip bottom>
          <v-btn slot="activator" icon small color="success" flat class="ma-0" @click="dialog=true">
            <v-icon small>edit</v-icon>
          </v-btn>
          <span>Modificar els rols</span>
        </v-tooltip>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Gestionar rols</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false"><v-icon small>close</v-icon> Sortir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                        <user-roles-manage :user="user" @added="added" @removed="removed" @close="dialog=false"></user-roles-manage>
                    </v-flex>
                </v-layout>
                </v-container>
            </v-card>

        </v-dialog>
    </span>
</template>

<script>
import UserRolesManage from './UserRolesManage'

export default {
  name: 'UserRolesManageButton',
  components: {
    'user-roles-manage': UserRolesManage
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
    added () {
      this.$emit('added')
    },
    removed () {
      this.$emit('removed')
    }
  }
}
</script>
