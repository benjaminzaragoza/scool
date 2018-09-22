<template>
    <span style="display:inline-block">
        <template v-if="user.corporativeEmail">
            <v-btn icon class="mx-0" title="Editar correu electrònic corporatiu" @click.native.stop="openDialog">
                <v-icon small color="teal">edit</v-icon>
            </v-btn>
            <v-btn icon class="mx-0" title="Dessasignar email corporatiu" @click.native.stop="unassignGoogleUser">
                <v-icon small color="red">remove</v-icon>
            </v-btn>
        </template>
        <v-btn v-else icon class="mx-0" title="Afegiu correu corporatiu" @click.native.stop="addGoogleUser">
            <v-icon color="primary">add</v-icon>
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
                        <v-btn dark flat @click.native="dialog = false">Sortir</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container grid-list-lg fluid>
                <v-layout row wrap>
                     <v-flex xs12>
                         <v-card>
                             <v-toolbar class="headline font-weight-regular blue-grey white--text">
                                <v-toolbar-title class="headline font-weight-regular blue-grey white--text">Associar compte de Google</v-toolbar-title>
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
                            </v-card-text>
                            <v-card-actions>
                                <v-btn color="primary" @click="associate" :disabled="associating || !selectedGoogleuser" :loading="associating">Associar</v-btn>
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
  import withSnackbar from '../../mixins/withSnackbar'
  import GoogleUsersSelectComponent from './GoogleUsersSelectComponent'
  import axios from 'axios'

  export default {
    name: 'ManageCorporativeEmailIcon',
    mixins: [withSnackbar],
    components: {
      'google-users-select': GoogleUsersSelectComponent
    },
    data () {
      return {
        dialog: false,
        refreshing: false,
        associating: false,
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
      unassignGoogleUser () {
        console.log('HEY')
      },
      addGoogleUser () {
        axios.post('/api/v1/gsuite/users/search', {
          employeeId: this.user.id,
          personalEmail: this.user.email,
          mobile: this.user.mobile
        }).then(response => {
          axios.post('/api/v1/user/' + this.user.id + '/gsuite', {
            google_id: response.data.id,
            google_email: response.data.primaryEmail
          }).then(response => {
            this.showMessage('Usuari Google assignat correctament')
            this.$emit('added', response.data)
          }).catch(error => {
            console.log(error)
            this.showError(error)
          })
        }).catch(error => {
          console.log(error)
          this.dialog = true
        })
      },
      select (googleUser) {
        this.selectedGoogleuser = googleUser
      },
      associate () {
        this.associating = true
        axios.post('/api/v1/user/' + this.user.id + '/gsuite', {
          google_id: this.selectedGoogleuser.id,
          google_email: this.selectedGoogleuser.primaryEmail
        }).then(response => {
          this.showMessage('Usuari Google associat correctament')
          this.$emit('associated', response.data)
          this.dialog = false
          this.associating = false
        }).catch(error => {
          console.log('ERROR')
          console.log(error)
          console.log('STATUS')
          console.log(error.status)
          if (error.status === 422) this.showError('Error de validació, possiblement el compte de Google que voleu assignar ja està assignat a un altre usuari.')
          else this.showError(error)
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
