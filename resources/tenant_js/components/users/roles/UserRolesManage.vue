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
                         <v-card class="elevation-3">
                            <v-card-text>
                                 Escolliu un rol/s per afegir a l'usuari
                                <user-avatar :hash-id="user.hashid"
                                      :alt="user.name"
                                      :user="user"
                                ></user-avatar>
                                <span class="font-weight-medium">{{ user.name }}</span>:
                                 <roles-select :exclude="user.roles" v-model="selectedRoles"></roles-select>
                                 Rols actuals de l'usuari:
                                 <user-roles-list :user="user" :roles="user.roles"></user-roles-list>
                            </v-card-text>
                             <v-card-actions>
                                 <v-btn color="primary" @click="add" :disabled="loading" :loading="loading">
                                     <v-icon>add</v-icon> Afegir</v-btn>
                                 <v-btn flat @click="dialog = false">
                                     <v-icon small>close</v-icon> Sortir</v-btn>
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
import UserAvatar from '../../ui/UserAvatarComponent'

export default {
  name: 'UserRolesManage',
  components: {
    'roles-select': RolesSelect,
    'user-roles-list': UserRolesList,
    'user-avatar': UserAvatar
  },
  data () {
    return {
      dialog: false,
      loading: false,
      selectedRoles: []
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
      window.axios.post('/api/v1/user/' + this.user.id + '/role/multiple', {
        'roles': this.selectedRoles.map(role => role.id)
      }).then(() => {
        this.$snackbar.showMessage('Rol/s afegit/s correctament')
        this.$emit('added', this.selectedRoles)
        this.selectedRoles.forEach(role => {
          this.user.roles.push({
            'name': role.name,
            'guard_name': role.guard_name
          })
        })
        this.selectedRoles = []
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    }
  }
}
</script>
