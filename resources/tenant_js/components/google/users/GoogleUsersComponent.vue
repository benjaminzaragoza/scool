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
                                <v-flex xs9 style="align-self: flex-end;">
                                    <v-layout>
                                        <v-flex xs12 class="text-sm-left" style="align-self: center;">
                                            <google-user-filters-select v-model="selectedFilters" :filters="filterNames"></google-user-filters-select>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                <v-spacer></v-spacer>
                                <v-text-field
                                        append-icon="search"
                                        label="Buscar"
                                        single-line
                                        hide-details
                                        v-model="search"
                                ></v-text-field>
                            </v-card-title>
                            <div id="massive_actions" v-if="selected.length > 0" style="text-align: left;">
                                <google-users-delete-multiple :users="selected" @deleted="selected=[];refresh(false)"></google-users-delete-multiple>
                            </div>
                            <v-data-table
                                    class="px-0 mb-2 hidden-sm-and-down"
                                    v-model="selected"
                                    select-all
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
                                <template slot="items" slot-scope="props">
                                    <tr>
                                        <td>
                                            <v-checkbox
                                                    v-model="props.selected"
                                                    primary
                                                    hide-details
                                            ></v-checkbox>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <v-avatar size="32" slot="activator">
                                                    <img v-if="props.item.thumbnailPhotoUrl" :src="props.item.thumbnailPhotoUrl">
                                                    <img v-else src="/img/default.png" alt="photo per defecte">
                                                </v-avatar>
                                                <span>{{ props.item.id }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell" style="max-width: 125px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <google-user-local-user :user="props.item" :local-users="localUsers"></google-user-local-user>
                                        </td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ props.item.fullName }}</span>
                                                <span>{{ props.item.fullName }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <a slot="activator" target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + props.item.id">{{ props.item.primaryEmail }}</a>
                                                <span>{{ props.item.primaryEmail }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <user-show-link v-if="props.item.employeeId" :id="props.item.employeeId" :text="props.item.employeeId"></user-show-link>
                                        </td>
                                        <td class="text-xs-left cell" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <a slot="activator" target="_blank" :href="'https://mail.google.com/mail/?view=cm&fs=1&to=' + props.item.personalEmail">{{ props.item.personalEmail }}</a>
                                                <span>{{ props.item.personalEmail }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell" v-html="props.item.mobile"></td>
                                        <td class="text-xs-left cell" >
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ formatOrgUnitPath(props.item.orgUnitPath) }}</span>
                                                <span>{{ props.item.orgUnitPath }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell" v-html="formatBoolean(props.item.isAdmin)"></td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip v-if="props.item.suspensionReason" bottom>
                                                <span slot="activator">{{ formatBoolean(props.item.suspended) }}</span>
                                                <span>Raó de la suspensió: {{ props.item.suspensionReason }}</span>
                                            </v-tooltip>
                                            <template v-else>
                                                {{ formatBoolean(props.item.suspended) }}
                                            </template>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator">
                                                    <timeago v-if="props.item.lastLoginTime !== '1970-01-01T00:00:00.000Z'" :auto-update="60" :datetime="new Date(props.item.lastLoginTime)"></timeago>
                                                    <span v-else>Mai</span>
                                                </span>
                                                <span>{{ formatDateTime(props.item.lastLoginTime) }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator">
                                                    <timeago v-if="props.item.creationTime !== '1970-01-01T00:00:00.000Z'" :auto-update="60" :datetime="new Date(props.item.creationTime)"></timeago>
                                                    <span v-else>Mai</span>
                                                </span>
                                                <span>{{ formatDateTime(props.item.creationTime) }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ formatBoolean(props.item.inSync) }}</span>
                                                <span v-if="props.item.inSync">Tot sembla correcte</span>
                                                <span v-else v-html="formatMessages(props.item.errorMessages)"></span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <show-google-user-icon :user="props.item"></show-google-user-icon>
                                            <google-user-delete-icon :user="props.item"></google-user-delete-icon>
                                            <google-user-active-icon v-if="props.item.suspended" :user="props.item"></google-user-active-icon>
                                            <google-user-suspend-icon v-else :user="props.item"></google-user-suspend-icon>
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

import showGoogleUserIcon from './ShowGoogleUserIconComponent'
import moment from 'moment'
import UserShowLink from '../../users/UserShowLink'
import GoogleUserLocalUser from './GoogleUserLocalUser'
import GoogleUserFiltersSelect from './GoogleUserFiltersSelect'
import GoogleUsersDeleteMultiple from './GoogleUsersDeleteMultiple'
import GoogleUserDeleteIcon from './GoogleUserDeleteIcon'
import GoogleUserSuspendIcon from './GoogleUserSuspendIcon'
import GoogleUserActiveIcon from './GoogleUserActiveIcon'

var filterNames = [
  {
    id: 1,
    name: 'Sense Avatar',
    function: 'withoutAvatar'
  },
  {
    id: 2,
    name: 'Amb Avatar',
    function: 'withAvatar'
  },
  {
    id: 3,
    name: 'Amb usuari local',
    function: 'withLocalUser'
  },
  {
    id: 4,
    name: 'Sense usuari local',
    function: 'withoutLocalUser'
  },
  {
    id: 5,
    name: 'Amb email personal',
    function: 'withPersonalEmail'
  },
  {
    id: 6,
    name: 'Sense email personal',
    function: 'withoutPersonalEmail'
  },
  {
    id: 7,
    name: 'Amb employeeId',
    function: 'withEmployeeId'
  },
  {
    id: 8,
    name: 'Sense employeeId',
    function: 'withoutEmployeeId'
  },
  {
    id: 9,
    name: 'Amb mòbil',
    function: 'withMobile'
  },
  {
    id: 10,
    name: 'Sense mòbil',
    function: 'withoutMobile'
  },
  {
    id: 11,
    name: 'Suspesos',
    function: 'suspended'
  },
  {
    id: 12,
    name: 'Actius',
    function: 'active'
  },
  {
    id: 12,
    name: 'Logats almenys algun cop',
    function: 'loggedAtLeastOnce'
  },
  {
    id: 13,
    name: 'Mai logats',
    function: 'neverLogged'
  },
  {
    id: 13,
    name: 'Sincronitzats',
    function: 'sinchronized'
  },
  {
    id: 14,
    name: 'No Sincronitzats',
    function: 'unsinchronized'
  }
]

var filters = {
  all: function (users) {
    return users
  },
  withoutAvatar: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('localUser')
    }) : []
  },
  withAvatar: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('localUser')
    }) : []
  },
  withLocalUser: function (users) {
    return users ? users.filter(function (user) {
      return user.hasOwnProperty('localUser')
    }) : []
  },
  withoutLocalUser: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('localUser')
    }) : []
  },
  withPersonalEmail: function (users) {
    return users ? users.filter(function (user) {
      return user.hasOwnProperty('personalEmail') && user.personalEmail !== null
    }) : []
  },
  withoutPersonalEmail: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('personalEmail') || user.personalEmail === null
    }) : []
  },
  withEmployeeId: function (users) {
    return users ? users.filter(function (user) {
      return user.hasOwnProperty('employeeId') && user.employeeId !== null && user.employeeId !== ''
    }) : []
  },
  withoutEmployeeId: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('employeeId') || user.employeeId === null || user.employeeId === ''
    }) : []
  },
  withMobile: function (users) {
    return users ? users.filter(function (user) {
      return user.hasOwnProperty('mobile') && user.mobile !== null && user.mobile !== ''
    }) : []
  },
  withoutMobile: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('mobile') || user.mobile === null || user.mobile === ''
    }) : []
  },
  suspended: function (users) {
    return users ? users.filter(function (user) {
      return user.hasOwnProperty('suspended') && user.suspended === true
    }) : []
  },
  active: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('suspended') || user.suspended === false
    }) : []
  },
  loggedAtLeastOnce: function (users) {
    return users ? users.filter(function (user) {
      return user.hasOwnProperty('lastLoginTime') && user.lastLoginTime !== '1970-01-01T00:00:00.000Z'
    }) : []
  },
  neverLogged: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('lastLoginTime') || user.lastLoginTime === '1970-01-01T00:00:00.000Z'
    }) : []
  },
  sinchronized: function (users) {
    return users ? users.filter(function (user) {
      return user.hasOwnProperty('inSync') && user.inSync === true
    }) : []
  },
  unsinchronized: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('inSync') || user.inSync === false
    }) : []
  }
}

export default {
  name: 'GoogleUsersComponent',
  components: {
    'show-google-user-icon': showGoogleUserIcon,
    'google-user-delete-icon': GoogleUserDeleteIcon,
    'google-user-suspend-icon': GoogleUserSuspendIcon,
    'google-user-active-icon': GoogleUserActiveIcon,
    'user-show-link': UserShowLink,
    'google-user-local-user': GoogleUserLocalUser,
    'google-user-filters-select': GoogleUserFiltersSelect,
    'google-users-delete-multiple': GoogleUsersDeleteMultiple
  },
  data () {
    return {
      selected: [],
      search: '',
      refreshing: false,
      settingsDialog: false,
      googleWatch: false,
      selectedFilters: []
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    },
    localUsers: {
      required: true
    }
  },
  computed: {
    ...mapGetters({
      internalUsers: 'googleUsers'
    }),
    filteredUsers: function () {
      let filteredUsers = this.internalUsers
      // if (this.authType) filteredUsers = filters['byAuthType'](this.internalUsers, this.authType)
      if (this.selectedFilters.length > 0) {
        this.selectedFilters.forEach(filter => {
          filteredUsers = filters[filter.function](this.internalUsers)
        })
      }
      return filteredUsers
    },
    headers () {
      let headers = []
      headers.push({ text: 'Avatar', value: 'thumbnailPhotoUrl', sortable: false })
      headers.push({ text: 'Usuari local', align: 'left', value: 'localUser' })
      headers.push({ text: 'Nom', value: 'fullName' })
      headers.push({ text: 'Correu electrònic', value: 'primaryEmail' })
      headers.push({ text: 'employeeId', value: 'employeeId' })
      headers.push({ text: 'Email personal', value: 'personalEmail' })
      headers.push({ text: 'Mòbil', value: 'mobile' })
      headers.push({ text: 'Path', value: 'orgUnitPath' })
      headers.push({ text: 'Admin?', value: 'isAdmin' })
      headers.push({ text: 'Suspès?', value: 'suspended' })
      headers.push({ text: 'Últim login', value: 'lastLoginTime' })
      headers.push({ text: 'Data creació', value: 'creationTime' })
      headers.push({ text: 'Sincronitzat', align: 'left', value: 'inSync' })
      headers.push({ text: 'Accions', sortable: false })
      return headers
    }
  },
  watch: {
    googleWatch (newValue) {
      console.log('googleWatch changed to : ' + newValue)
      if (newValue) {
        window.axios.get('/api/v1/gsuite/users/watch').then(response => {
          console.log(response)
        }).catch(error => {
          console.log(error)
        })
      }
    }
  },
  methods: {
    formatDateTime (date) {
      return moment(new Date(date)).format('hh:mm a DD-MM-YYYY')
    },
    formatBoolean (boolean) {
      return boolean ? 'Sí' : 'No'
    },
    formatMessages (messages) {
      if (messages) return messages.join('<br/>')
      return ''
    },
    formatOrgUnitPath (orgUnitPath) {
      if (orgUnitPath.length > 17) {
        return orgUnitPath.substr(0, 17) + '...'
      } else return orgUnitPath
    },
    refresh () {
      this.refreshing = true
      window.axios.get('/api/v1/gsuite/users').then(response => {
        this.refreshing = false
        this.$store.commit(mutations.SET_GOOGLE_USERS, response.data)
        this.$snackbar.showMessage('Usuaris actualitzats correctament')
      }).catch(error => {
        this.refreshing = false
        this.$snackbar.showError(error)
        console.log(error)
      })
    }
  },
  created () {
    this.$store.commit(mutations.SET_GOOGLE_USERS, this.users)
    this.filterNames = filterNames
  }
}
</script>

<style>
    .column {
        padding: 3px 3px !important;
    }
    .cell {
        padding: 3px 3px !important;
    }
    table.v-table tbody td:first-child, table.v-table tbody td:not(:first-child), table.v-table tbody th:first-child, table.v-table tbody th:not(:first-child), table.v-table thead td:first-child, table.v-table thead td:not(:first-child), table.v-table thead th:first-child, table.v-table thead th:not(:first-child) {
        padding: 0 5px !important;
    }
</style>
