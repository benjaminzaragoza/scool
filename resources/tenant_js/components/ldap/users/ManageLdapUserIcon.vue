<template>
    <span class="ma-0 pa-0">
        <template v-if="user.ldapDn">
            <div class="mt-0 mb-0 pa-0" style="width: fit-content;">
                <v-btn icon class="ma-0 pa-0" title="Editar correu electrònic corporatiu" @click.native.stop="openDialog">
                    <v-icon small color="teal">edit</v-icon>
                </v-btn>
                <v-btn icon class="ma-0 pa-0" title="Dessasignar email corporatiu" @click.native.stop="unassignLdapUser"
                       :loading="unassociating" :disabled="unassociating">
                    <v-icon small color="red">remove</v-icon>
                </v-btn>
                <v-btn icon class="ma-0 pa-0" title="Sincronitzar" @click.native.stop="sync"
                       :loading="syncing" :disabled="syncing">
                    <v-icon small color="teal">sync</v-icon>
                </v-btn>
            </div>
        </template>
        <v-tooltip bottom v-else>
            <v-btn slot="activator" small icon class="mx-0 pa-0" @click.native.stop="dialog=true">
                <v-icon small color="primary">add</v-icon>
            </v-btn>
            <span>Afegiu usuari de Ldap</span>
        </v-tooltip>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Compte de Ldap</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">
                            <v-icon class="mr-2">exit_to_app</v-icon> Sortir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                         <v-card class="elevation-3">
                             <v-toolbar class="headline font-weight-regular primary white--text" dense>
                                <v-toolbar-title class="font-weight-regular white--text">Associar compte de Ldap</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-btn icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                                        <v-icon>refresh</v-icon>
                                    </v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <v-card-text>
                                <v-subheader class="pa-0">Escolliu un compte de Ldap que vulgueu associar a l'usuari {{ user.name }} ({{ user.email }})</v-subheader>
                                <ldap-users-select :user="user" v-if="dialog" ref="select" @selected="select" @unselected="unselect"></ldap-users-select>
                                <p class="mt-3">
                                    <v-icon>add</v-icon><a :href="'https://iesebre.scool.test/ldap/users?action=create&user=' + user.id" target="_blank">Crear un nou compte de Ldap</a>.
                                        Un cop creat el nou compte, torneu a aquesta pàgina i actualitzeu prement el boto:
                                    <v-btn icon @click="refresh" :loading="refreshing" :disabled="refreshing">
                                        <v-icon>refresh</v-icon>
                                    </v-btn>
                                </p>
                            </v-card-text>
                            <v-card-actions>
                                <v-btn flat @click="dialog = false">
                                    <v-icon class="mr-2">exit_to_app</v-icon> Sortir
                                </v-btn>
                                <v-btn color="primary" @click="associate" :disabled="associating || !selectedLdapuser" :loading="associating">Associar</v-btn>
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
import LdapUsersSelectComponent from './LdapUsersSelectComponent'
export default {
  name: 'ManageLdapUserIcon',
  components: {
    'ldap-users-select': LdapUsersSelectComponent
  },
  data () {
    return {
      dialog: false,
      associating: false,
      refreshing: false,
      selectedLdapuser: null,
      unassociating: false,
      syncing: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    sync () {
      this.syncing = true
      window.axios.put('/api/v1/user/' + this.user.id + '/ldap').then(response => {
        this.$snackbar.showMessage('Usuari Ldap sincronitzat correctament')
        this.syncing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.syncing = false
      })
    },
    select (ldapUser) {
      this.selectedLdapuser = ldapUser
    },
    unselect () {
      this.selectedLdapuser = null
    },
    refresh () {
      this.$refs.select.refresh()
    },
    openDialog () {
      this.dialog = true
    },
    unassignLdapUser () {
      this.unassociating = true
      window.axios.delete('/api/v1/user/' + this.user.id + '/ldap').then(response => {
        this.$snackbar.showMessage('Usuari Ldap desassociat correctament')
        this.$emit('unassociated')
        this.unassociating = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.unassociating = false
      })
    },
    associate () {
      this.associating = true
      window.axios.post('/api/v1/user/' + this.user.id + '/ldap', {
        dn: this.selectedLdapuser.dn,
        uid: this.selectedLdapuser.uid
      }).then(response => {
        this.$snackbar.showMessage('Usuari Ldap associat correctament')
        this.$emit('associated', response.data)
        this.dialog = false
        this.associating = false
      }).catch(error => {
        if (error.status === 422) this.$snackbar.showError('Error de validació, possiblement el compte de Ldap que voleu assignar ja està assignat a un altre usuari.')
        else this.$snackbar.showError(error)
        this.associating = false
      })
    }
  }
}
</script>
