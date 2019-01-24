<template>
    <span class="ma-0 pa-0">
        <template v-if="user.corporativeEmail">
            <div class="mt-0 mb-0 pa-0" style="width: fit-content;">
                <v-btn icon class="ma-0 pa-0" title="Editar correu electrònic corporatiu" @click.native.stop="openDialog">
                    <v-icon small color="teal">edit</v-icon>
                </v-btn>
                <v-btn icon class="ma-0 pa-0" title="Dessasignar email corporatiu" @click.native.stop="unassignGoogleUser"
                    :loading="unassociating" :disabled="unassociating">
                    <v-icon small color="red">remove</v-icon>
                </v-btn>
                <v-btn icon class="ma-0 pa-0" title="Sincronitzar" @click.native.stop="sync"
                       :loading="syncing" :disabled="syncing">
                    <v-icon small color="teal">sync</v-icon>
                </v-btn>
            </div>
        </template>
        <v-btn small v-else icon class="mx-0 pa-0" title="Afegiu correu corporatiu" @click.native.stop="addGoogleUser"
            :loading="searching" :disabled="searching">
            <v-icon color="primary" small>add</v-icon>
        </v-btn>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition" @keydown.esc="dialog = false">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Editar correu corporatiu</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn dark flat @click.native="dialog = false">
                            <v-icon class="mr-2">exit_to_app</v-icon> Sortir
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                         <v-card class="elevation-3">
                             <v-toolbar class="headline font-weight-regular primary white--text" dense>
                                <v-toolbar-title class="font-weight-regular white--text">Associar compte de Google</v-toolbar-title>
                                <v-spacer></v-spacer>
                                <v-toolbar-items>
                                    <v-btn icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                                        <v-icon>refresh</v-icon>
                                    </v-btn>
                                </v-toolbar-items>
                            </v-toolbar>
                            <v-card-text>
                                <v-subheader class="pa-0">Escolliu un compte de Google que vulgueu associar a l'usuari {{ user.name }} ({{ user.email }})</v-subheader>
                                <google-users-select :user="user" v-if="dialog" ref="select" @selected="select"></google-users-select>
                                <p class="mt-3">
                                    <v-icon>add</v-icon><a href="https://iesebre.scool.test/google_users?action=create" target="_blank">Crear un nou compte de Google</a>.
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
                                <v-btn color="primary" @click="associate" :disabled="associating || !selectedGoogleuser" :loading="associating">Associar</v-btn>
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
import GoogleUsersSelectComponent from './GoogleUsersSelectComponent'

export default {
  name: 'ManageCorporativeEmailIcon',
  components: {
    'google-users-select': GoogleUsersSelectComponent
  },
  data () {
    return {
      searching: false,
      dialog: false,
      refreshing: false,
      associating: false,
      unassociating: false,
      syncing: false,
      selectedGoogleuser: null
    }
  },
  props: {
    user: {
      type: Object,
      default: () => { return {} }
    }
  },
  methods: {
    sync () {
      this.syncing = true
      window.axios.put('/api/v1/user/' + this.user.id + '/gsuite').then(response => {
        this.$snackbar.showMessage('Usuari Google sincronitzat correctament')
        this.syncing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.syncing = false
      })
    },
    unassignGoogleUser () {
      this.unassociating = true
      window.axios.delete('/api/v1/user/' + this.user.id + '/gsuite').then(response => {
        this.$snackbar.showMessage('Usuari Google desassociat correctament')
        this.$emit('unassociated')
        this.unassociating = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.unassociating = false
      })
    },
    addGoogleUser () {
      this.searching = true
      window.axios.post('/api/v1/gsuite/users/search', {
        employeeId: this.user.id,
        personalEmail: this.user.email,
        mobile: this.user.mobile
      }).then(response => {
        this.searching = false
        window.axios.post('/api/v1/user/' + this.user.id + '/gsuite', {
          google_id: response.data.id,
          google_email: response.data.primaryEmail
        }).then(response => {
          this.$snackbar.showMessage('Usuari Google assignat correctament')
          this.$emit('added', response.data)
        }).catch(error => {
          this.$snackbar.showError(error)
        })
      }).catch(error => {
        this.searching = false
        console.log(error)
        this.dialog = true
      })
    },
    select (googleUser) {
      this.selectedGoogleuser = googleUser
    },
    associate () {
      this.associating = true
      window.axios.post('/api/v1/user/' + this.user.id + '/gsuite', {
        google_id: this.selectedGoogleuser.id,
        google_email: this.selectedGoogleuser.primaryEmail
      }).then(response => {
        this.$snackbar.showMessage('Usuari Google associat correctament')
        this.$emit('associated', response.data)
        this.dialog = false
        this.associating = false
      }).catch(error => {
        if (error.status === 422) this.$snackbar.showError('Error de validació, possiblement el compte de Google que voleu assignar ja està assignat a un altre usuari.')
        else this.$snackbar.showError(error)
        this.associating = false
      })
    },
    openDialog () {
      this.dialog = true
    },
    refresh () {
      this.$refs.select.refresh()
    }
  }
}
</script>
