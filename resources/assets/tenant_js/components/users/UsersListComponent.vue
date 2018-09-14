<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-toolbar color="blue darken-3">
                    <v-menu bottom>
                        <v-btn slot="activator" icon dark>
                            <v-icon>more_vert</v-icon>
                        </v-btn>

                        <v-list>
                            <v-list-tile>
                                <v-list-tile-title>TODO: llista d'usuaris</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-toolbar-title class="white--text title">Usuaris</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="settings">
                        <v-icon>settings</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text" @click="refresh">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>

                <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                <v-spacer></v-spacer>
                                <v-text-field
                                        append-icon="search"
                                        label="Buscar"
                                        single-line
                                        hide-details
                                        v-model="search"
                                ></v-text-field>
                            </v-card-title>
                            <v-data-table
                            class="px-0 mb-2 hidden-sm-and-down"
                            :headers="headers"
                            :items="internalUsers"
                            :search="search"
                            item-key="id"
                            expand
                            >
                            <template slot="items" slot-scope="{item: user}">
                                <tr @click="expand($event, props)">
                                    <td class="text-xs-left">
                                        {{ user.id }}
                                    </td>
                                    <td class="text-xs-left">
                                        {{ user.name }}
                                    </td>
                                    <td class="text-xs-left">{{ user.email }}</td>
                                    <td class="text-xs-left">{{ user.email_verified_at }}</td>
                                    <td class="text-xs-left">
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ user.last_login }}</span>
                                            <span>{{ user.last_login_ip }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ formatRoles(user) }}</span>
                                            <span>{{ formatRoles(user) }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-left">{{ user.formatted_created_at }}</td>
                                    <td class="text-xs-left">{{ user.formatted_updated_at }}</td>
                                    <td class="text-xs-left">
                                        <show-user-icon :user="user" :users="users"></show-user-icon>

                                        <confirm-icon icon="email"
                                                      :working="sendingWelcomeEmail"
                                                      @confirmed="sendWelcomeEmail(user)"
                                                      tooltip="(Re)Enviar email de benvinguda"
                                                      message="Esteu segurs que voleu enviar email de benvinguda a l'usuari?"
                                                      confirm="Enviar"
                                        ></confirm-icon>

                                        <confirm-icon icon="email"
                                                      :working="sendingResetPassword"
                                                      @confirmed="sendResetPasswordEmail(user)"
                                                      tooltip="Enviar email restauració paraula de pas"
                                                      message="Esteu segurs que voleu enviar email per canviar paraula de pas?"
                                                      confirm="Enviar"
                                        ></confirm-icon>



                                        <confirm-icon v-show="!user.email_verified_at" icon="email"
                                                      :working="sendingEmailConfirmation"
                                                      @confirmed="sendEmailConfirmation(user)"
                                                      tooltip="Enviar email confirmació email"
                                                      message="Esteu segurs que voleu enviar email per confirmar email de l'usuari?"
                                                      confirm="Enviar"
                                        ></confirm-icon>
                                        <v-btn icon class="mx-0" @click="editItem(user)">
                                            <v-icon color="teal">edit</v-icon>
                                        </v-btn>
                                        <v-btn icon class="mx-0" @click="showConfirmationDialog(user)">
                                            <v-icon color="pink">delete</v-icon>
                                            <v-dialog v-model="showDeleteUserDialog" max-width="500px">
                                                <v-card>
                                                    <v-card-text>
                                                        Esteu segurs que voleu eliminar aquest usuari?
                                                    </v-card-text>
                                                    <v-card-actions>
                                                        <v-btn flat @click.stop="showDeleteUserDialog=false">Cancel·lar</v-btn>
                                                        <v-btn color="primary" @click.stop="deleteUser" :loading="deleting">Esborrar</v-btn>
                                                    </v-card-actions>
                                                </v-card>
                                            </v-dialog>
                                        </v-btn>
                                    </td>
                                </tr>
                            </template>
                            </v-data-table>
                        </v-card>

                        <v-data-iterator
                                class="hidden-md-and-up"
                                content-tag="v-layout"
                                row
                                wrap
                                :items="internalUsers"
                        >
                            <v-flex
                                    slot="item"
                                    slot-scope="props"
                                    xs12
                                    sm6
                                    md4
                                    lg3
                            >
                                <v-card>
                                    <v-card-title><h4>{{ props.item.name }}</h4></v-card-title>
                                    <v-divider></v-divider>
                                    <v-list dense>
                                        <v-list-tile>
                                            <v-list-tile-content>Email:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.email }}</v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-content>Created at:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.created_at }}</v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-content>Updated at:</v-list-tile-content>
                                            <v-list-tile-content class="align-end">{{ props.item.updated_at }}</v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-card>
                            </v-flex>
                        </v-data-iterator>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  import { mapGetters } from 'vuex'
  import * as mutations from '../../store/mutation-types'
  import * as actions from '../../store/action-types'
  import withSnackbar from '../mixins/withSnackbar'
  import ConfirmIcon from '../ui/ConfirmIconComponent.vue'
  import ShowUserIcon from './ShowUserIconComponent.vue'

  import axios from 'axios'

  export default {
    mixins: [withSnackbar],
    components: {
      'confirm-icon': ConfirmIcon,
      'show-user-icon': ShowUserIcon
    },
    data () {
      return {
        showDeleteUserDialog: false,
        search: '',
        deleting: false,
        headers: [
          {text: 'Id', align: 'left', value: 'id'},
          {text: 'Name', value: 'name'},
          {text: 'Email', value: 'email'},
          {text: 'Verificació email', value: 'email_verified_at'},
          {text: 'Últim login', value: 'last_login'},
          {text: 'Rols', value: 'roles', sortable: false},
          {text: 'Data creació', value: 'formatted_created_at'},
          {text: 'Data actualització', value: 'formatted_updated_at'},
          {text: 'Accions', sortable: false}
        ],
        sendingWelcomeEmail: false,
        sendingResetPassword: false,
        sendingEmailConfirmation: false
      }
    },
    props: {
      users: {
        type: Array,
        required: false
      }
    },
    computed: {
      ...mapGetters({
        internalUsers: 'users'
      })
    },
    methods: {
      refresh () {
        this.$store.dispatch(actions.FETCH_USERS).catch(error => {
          this.showError(error)
        })
      },
      settings () {
        console.log('settings TODO') // TODO
      },
      sendWelcomeEmail (user) {
        this.sendingWelcomeEmail = true
        this.$store.dispatch(actions.WELCOME_EMAIL, user).then(response => {
          this.showMessage(`Correu electrònic enviat correctament`)
        }).catch(error => {
          console.dir(error)
          this.showError(error)
        }).then(() => {
          this.sendingWelcomeEmail = false
        })
      },
      sendResetPasswordEmail (user) {
        this.sendingResetPassword = true
        axios.post('password/email', {
          email: user.email
        }).then(response => {
          this.sendingResetPassword = false
          this.showMessage(`Correu electrònic enviat correctament`)
        }).catch(error => {
          console.log(error)
          this.showError(error)
        })
      },
      sendEmailConfirmation (user) {
        this.sendingEmailConfirmation = true
        this.$store.dispatch(actions.CONFIRM_USER_EMAIL, user).then(response => {
          this.showMessage(`Correu electrònic enviat per tal de confirmar el email`)
        }).catch(error => {
          console.dir(error)
          this.showError(error)
        }).then(() => {
          this.sendingEmailConfirmation = false
        })
      },
      formatRoles (user) {
        return Object.values(user.roles).join(', ')
      },
      showConfirmationDialog (user) {
        this.currentUser = user
        this.showDeleteUserDialog = true
      },
      deleteUser () {
        this.deleting = true
        this.$store.dispatch(actions.DELETE_USER, this.currentUser).then(response => {
          this.deleting = false
          this.showDeleteUserDialog = false
        }).catch(error => {
          console.log(error)
          this.showError(error)
          this.deleting = false
        }).then(() => {
          this.deleting = false
        })
      }
    },
    created () {
      this.$store.commit(mutations.SET_USERS, this.users)
    }
  }
</script>
