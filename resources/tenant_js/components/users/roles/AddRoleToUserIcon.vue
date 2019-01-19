<template>
    <span>
        <v-btn icon small color="success" flat class="ma-0" @click="dialog=true">
            <v-icon small>add</v-icon>
        </v-btn>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Afegir rol</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">Sortir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                         <v-card class="elevation-3">
                            <v-card-text>
                                 Escolliu un rol/s per afegir a l'usuari:
                                 <roles-select :exclude="user.roles"></roles-select>
                                 Rols actuals de l'usuari:
                                 <user-roles-list :user="user"></user-roles-list>
                            </v-card-text>
                             <v-card-actions>
                                 <v-btn color="primary" @click="add" :disabled="loading" :loading="loading">Afegir</v-btn>
                                 <v-btn flat @click="dialog = false">Sortir</v-btn>
                             </v-card-actions>
                         </v-card>
                    </v-flex>
                </v-layout>
                </v-container>
            </v-card>
        </v-dialog>

    </span>
</template>

<script>
import UserRolesList from './UserRolesList'
import RolesSelect from './RolesSelect'
export default {
  name: 'RemoveRoleToUserIcon',
  components: {
    'roles-select': RolesSelect,
    'user-roles-list': UserRolesList
  },
  data () {
    return {
      dialog: false,
      loading: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    add () {
      console.log('TODO ADD')
    }
  }
}
</script>
