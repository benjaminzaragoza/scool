<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-dialog v-model="settingsDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-btn icon dark @click.native="settingsDialog = false">
                                <v-icon>close</v-icon>
                            </v-btn>
                            <v-toolbar-title>Configuració Usuaris Google</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-toolbar-items>
                                <v-btn dark flat @click.native="dialog = false">Guardar</v-btn>
                            </v-toolbar-items>
                        </v-toolbar>
                        <v-list three-line subheader>
                            <v-subheader>General</v-subheader>
                            <v-list-tile avatar>
                                <v-list-tile-action>
                                    <v-checkbox v-model="googleWatch"></v-checkbox>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <v-list-tile-title>Observar canvis d'usuaris Google (Watch)</v-list-tile-title>
                                    <v-list-tile-sub-title>Activar les notificacions push de Google per tal de registrar/observar els canvis d'usuaris.
                                        <a href="https://developers.google.com/admin-sdk/directory/v1/guides/push" target="_blank">Documentació</a></v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-card>
                </v-dialog>
                <v-toolbar color="primary" dense>
                    <v-menu bottom>
                        <v-btn slot="activator" icon dark>
                            <v-icon>more_vert</v-icon>
                        </v-btn>
                        <v-list>
                            <v-list-tile>
                                <v-list-tile-title><a target="_blank" href="https://admin.google.com/u/3/ac/users">Panell administració Google</a></v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/users" target="_blank">
                                <v-list-tile-title>Usuaris locals</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/users/permissions" target="_blank">
                                <v-list-tile-title>Gestionar Permisos</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/users/roles" target="_blank">
                                <v-list-tile-title>Gestionar Rols</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/moodle/users" target="_blank">
                                <v-list-tile-title>Usuaris Moodle</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/ldap_users" target="_blank">
                                <v-list-tile-title>Usuaris Ldap</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-toolbar-title class="white--text">Google users</v-toolbar-title>
                    <v-spacer></v-spacer>


                    <v-tooltip bottom>
                        <v-btn slot="activator" id="incidents_help_button" icon class="white--text" href="http://docs.scool.cat/docs/google/users" target="_blank">
                            <v-icon>help</v-icon>
                        </v-btn>
                        <span>Ajuda</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <v-btn slot="activator" icon class="white--text" @click="settingsDialog = true">
                            <v-icon>settings</v-icon>
                        </v-btn>
                        <span>Configuració</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <v-btn slot="activator" id="users_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                            <v-icon>refresh</v-icon>
                        </v-btn>
                        <span>Actualitzar</span>
                    </v-tooltip>

                </v-toolbar>
                <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                TODO Filter here?
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
                                    :items="filteredUsers"
                                    :search="search"
                                    item-key="id"
                                    disable-initial-sort
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi han dades disponibles"
                                    rows-per-page-text="Usuaris per pàgina"
                                    :rows-per-page-items="[5,10,25,50,100,200,500,1000,{'text':'Tots','value':-1}]"
                            >
                                <template slot="items" slot-scope="{ item: user }">
                                    <tr>
                                        <td class="text-xs-left">
                                            <v-avatar size="32">
                                                <img v-if="user.thumbnailPhotoUrl" :src="user.thumbnailPhotoUrl">
                                                <img v-else src="/img/default.png" alt="photo per defecte">
                                            </v-avatar>
                                        </td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ user.fullName }}</span>
                                                <span>{{ user.fullName }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <a slot="activator" target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + user.id">{{ user.primaryEmail }}</a>
                                                <span>{{ user.primaryEmail }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left" v-html="user.employeeId"></td>
                                        <td class="text-xs-left" v-html="user.personalEmail"></td>
                                        <td class="text-xs-left" v-html="user.mobile"></td>
                                        <td class="text-xs-left" >
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ formatOrgUnitPath(user.orgUnitPath) }}</span>
                                                <span>{{ user.orgUnitPath }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left" v-html="formatBoolean(user.isAdmin)"></td>
                                        <td class="text-xs-left">
                                            <v-tooltip v-if="user.suspensionReason" bottom>
                                                <span slot="activator">{{ formatBoolean(user.suspended) }}</span>
                                                <span>Raó de la suspensió: {{ user.suspensionReason }}</span>
                                            </v-tooltip>
                                            <template v-else>
                                                {{ formatBoolean(user.suspended) }}
                                            </template>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">
                                                    <timeago v-if="user.lastLoginTime !== '1970-01-01T00:00:00.000Z'" :auto-update="60" :datetime="new Date(user.lastLoginTime)"></timeago>
                                                    <span v-else>Mai</span>
                                                </span>
                                                <span>{{ formatDateTime(user.lastLoginTime) }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">
                                                    <timeago v-if="user.creationTime !== '1970-01-01T00:00:00.000Z'" :auto-update="60" :datetime="new Date(user.creationTime)"></timeago>
                                                    <span v-else>Mai</span>
                                                </span>
                                                <span>{{ formatDateTime(user.creationTime) }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <show-google-user-icon :user="user"></show-google-user-icon>
                                            <v-btn icon class="mx-0" @click="">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <confirm-icon
                                                    :id="'google_user_remove_' + user.primaryEmail.replace('@','_')"
                                                    icon="delete"
                                                    color="pink"
                                                    :working="removing"
                                                    @confirmed="remove(user)"
                                                    tooltip="Eliminar"
                                                    message="Esteu segurs que voleu eliminar el compte de Google?"
                                            ></confirm-icon>
                                            <confirm-icon v-if="!user.suspended"
                                                    :id="'google_user_suspend_' + user.primaryEmail.replace('@','_')"
                                                    icon="stop"
                                                    color="pink"
                                                    :working="suspending"
                                                    @confirmed="suspend(user)"
                                                    tooltip="Suspendre"
                                                    message="Esteu segurs que voleu suspendre l'usuari?"
                                            ></confirm-icon>
                                            <confirm-icon v-else
                                                          :id="'google_user_activate_' + user.primaryEmail.replace('@','_')"
                                                          icon="play_arrow"
                                                          color="primary"
                                                          :working="activating"
                                                          @confirmed="activate(user)"
                                                          tooltip="Activar"
                                                          message="Esteu segurs que voleu activar l'usuari?"
                                            ></confirm-icon>
                                        </td>
                                    </tr>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import { mapGetters } from 'vuex'
import * as mutations from '../../../store/mutation-types'

import axios from 'axios'
import ConfirmIcon from '../../ui/ConfirmIconComponent'
import showGoogleUserIcon from './ShowGoogleUserIconComponent'
import moment from 'moment'

export default {
  name: 'GoogleUsersComponent',
  components: {
    'confirm-icon': ConfirmIcon,
    'show-google-user-icon': showGoogleUserIcon
  },
  data () {
    return {
      search: '',
      removing: false,
      suspending: false,
      activating: false,
      refreshing: false,
      settingsDialog: false,
      googleWatch: false
    }
  },
  computed: {
    ...mapGetters({
      internalUsers: 'googleUsers'
    }),
    filteredUsers: function () {
      return this.internalUsers
    },
    headers () {
      let headers = []
      headers.push({ text: 'Avatar', value: 'thumbnailPhotoUrl', sortable: false })
      headers.push({ text: 'Nom', value: 'fullName' })
      headers.push({ text: 'Correu electrònic', value: 'primaryEmail' })
      headers.push({ text: 'User id', value: 'employeeId' })
      headers.push({ text: 'Email personal', value: 'personalEmail' })
      headers.push({ text: 'Mòbil', value: 'mobile' })
      headers.push({ text: 'Path', value: 'orgUnitPath' })
      headers.push({ text: 'Admin?', value: 'isAdmin' })
      headers.push({ text: 'Suspès?', value: 'suspended' })
      headers.push({ text: 'Últim login', value: 'lastLoginTime' })
      headers.push({ text: 'Data creació', value: 'creationTime' })
      headers.push({ text: 'Accions', sortable: false })
      return headers
    }
  },
  watch: {
    googleWatch (newValue) {
      console.log('googleWatch changed to : ' + newValue)
      if (newValue) {
        axios.get('/api/v1/gsuite/users/watch').then(response => {
          console.log(response)
        }).catch(error => {
          console.log(error)
        })
      }
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    }
  },
  methods: {
    formatDateTime (date) {
      return moment(new Date(date)).format('hh:mm a DD-MM-YYYY')
    },
    formatBoolean (boolean) {
      return boolean ? 'Sí' : 'No'
    },
    formatOrgUnitPath (orgUnitPath) {
      if (orgUnitPath.length > 17) {
        return orgUnitPath.substr(0, 17) + '...'
      } else return orgUnitPath
    },
    refresh () {
      this.refreshing = true
      axios.get('/api/v1/gsuite/users').then(response => {
        this.refreshing = false
        this.$store.commit(mutations.SET_GOOGLE_USERS, response.data)
      }).catch(error => {
        this.refreshing = false
        console.log(error)
      })
    },
    remove (user) {
      this.removing = true
      axios.delete('/api/v1/gsuite/users/' + user.id).then(response => {
        if (user.employeeId) {
          axios.delete('/api/v1/user/' + user.employeeId + '/gsuite').then(response => {
            this.$snackbar.showMessage('Usuari esborrat correctament')
            this.removing = false
            this.$store.commit(mutations.DELETE_GOOGLE_USER, user)
          }).catch(error => {
            console.log(error)
            this.$snackbar.showMessage('Usuari esborrat correctament de Google però amb error local')
            this.$snackbar.showError(error)
            this.removing = false
          })
        } else {
          this.$snackbar.showMessage('Usuari esborrat correctament')
          this.removing = false
          this.$store.commit(mutations.DELETE_GOOGLE_USER, user)
        }
        this.$snackbar.showMessage('Usuari esborrat correctament')
      }).catch(error => {
        this.removing = false
        this.$snackbar.showError(error)
      })
    },
    suspend (user) {
      this.suspending = true
      // TODO
      axios.delete('/api/v1/gsuite/active/users/' + user.id).then(response => {
        this.suspending = false
        this.$snackbar.showMessage('Usuari suspès correctament')
      }).catch(error => {
        this.suspending = false
        this.$snackbar.showError(error)
      })
    },
    activate (user) {
      this.activating = true
      // TODO
      axios.post('/api/v1/gsuite/active/users/' + user.id).then(response => {
        this.activating = false
        this.$snackbar.showMessage('Usuari activat correctament')
      }).catch(error => {
        this.activating = false
        this.$snackbar.showError(error)
      })
    }
  },
  created () {
    this.$store.commit(mutations.SET_GOOGLE_USERS, this.users)
  }
}
</script>
