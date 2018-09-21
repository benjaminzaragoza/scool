<template>
    <span style="display:inline-block">
        <v-btn icon class="mx-0" title="Editar correu electrònic corporatiu" @click.native.stop="openDialog">
            <v-icon small color="teal">edit</v-icon>
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
                                <v-btn color="primary" @click="associate" :disabled="disabled" :loading="associating">Associar</v-btn>
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
    name: 'EditCorporativeEmailIcon',
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
    computed: {
      disabled () {
        if (this.associating || this.selectedGoogleuser != null) return true
      }
    },
    methods: {
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
          console.log(error)
          if (error.status === 422) this.showError("sdasdsda")
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
