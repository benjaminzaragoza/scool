<template>
    <span>
        <v-toolbar color="primary" dense>
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                 <v-list>
                     <v-list-tile href="/users" target="_blank">
                        <v-list-tile-title>Usuaris locals</v-list-tile-title>
                    </v-list-tile>
                     <v-list-tile href="/users/permissions" target="_blank">
                        <v-list-tile-title>Gestionar Permisos</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/users/roles" target="_blank">
                        <v-list-tile-title>Gestionar Rols</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/google_users" target="_blank">
                        <v-list-tile-title>Usuaris de Google</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/ldap_users" target="_blank">
                        <v-list-tile-title>Usuaris Ldap</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Usuaris de moodle</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn id="moodle_users_help_button" icon class="white--text" href="http://docs.scool.cat/docs/moodle" target="_blank">
                <v-icon>help</v-icon>
            </v-btn>

            <fullscreen-dialog
                    v-role="'MoodleManager,UserManager'"
                    :flat="false"
                    class="white--text"
                    icon="settings"
                    v-model="settingsDialog"
                    color="primary"
                    title="Canviar la configuració moodle">
                        <moodle-settings module="moodle" @close="settingsDialog = false"></moodle-settings>
            </fullscreen-dialog>

            <v-btn id="moodle_users_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>

        <v-card>
            <v-card-title>
                <v-layout>
                  <v-flex xs9 style="align-self: flex-end;">
                      <v-layout>
                          <v-flex xs2 class="text-sm-left" style="align-self: center;">
                                <moodle-user-auth-types-select v-model="authType"></moodle-user-auth-types-select>
                          </v-flex>
                          <v-flex xs9>
                               <v-layout>
                                   <v-flex xs2>
                                        <moodle-user-language-select v-model="language"></moodle-user-language-select>
                                   </v-flex>
                                   <v-flex xs6>
                                        <moodle-user-filters-select v-model="selectedFilters" :filters="filterNames"></moodle-user-filters-select>
                                   </v-flex>
                               </v-layout>
                          </v-flex>
                      </v-layout>
                  </v-flex>
                  <v-flex xs3>
                      <v-text-field
                              append-icon="search"
                              label="Buscar"
                              single-line
                              hide-details
                              v-model="search"
                      ></v-text-field>
                  </v-flex>
                </v-layout>
            </v-card-title>
            <div id="massive_actions" v-if="selected.length > 0" style="text-align: left;">
                <moodle-users-delete-multiple :users="selected" :localUsers="localUsers" @deleted="selected=[];refresh(false)"></moodle-users-delete-multiple>
            </div>
            <v-data-table
                    class="px-0 mb-5 hidden-sm-and-down"
                    v-model="selected"
                    select-all
                    :headers="headers"
                    :items="filteredUsers"
                    :search="search"
                    item-key="id"
                    no-results-text="No s'ha trobat cap registre coincident"
                    no-data-text="No hi han dades disponibles"
                    rows-per-page-text="Usuaris per pàgina"
                    :rows-per-page-items="[5,10,25,50,100,200,500,1000,{'text':'Tots','value':-1}]"
                    :pagination.sync="pagination"
                    :loading="refreshing"
            >
                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                <template slot="items" slot-scope="props">
                    <tr :id="'user_row_' + props.item.id">
                        <td>
                            <v-checkbox
                                    v-model="props.selected"
                                    primary
                                    hide-details
                            ></v-checkbox>
                        </td>
                        <td class="text-xs-left cell" v-text="props.item.id"></td>
                        <td class="text-xs-left cell" v-text="props.item.idnumber"></td>
                        <td class="text-xs-left cell" style="max-width: 125px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <moodle-user-local-user :user="props.item" :local-users="localUsers"></moodle-user-local-user>
                        </td>
                        <td class="text-xs-left cell" style="max-width: 125px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <v-avatar color="primary" :title="props.item.fullname" size="32">
                              <img :src="props.item.profileimageurlsmall" alt="avatar">
                            </v-avatar>
                            <a
                                    :title="props.item.description" v-text="props.item.username" :href="'https://www.iesebre.com/moodle/user/profile.php?id=' + props.item.id"
                                    target="_blank"></a>
                        </td>
                        <td class="text-xs-left cell" style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                             <v-tooltip bottom>
                                <a slot="activator" target="_blank" :href="'https://mail.google.com/mail/?view=cm&fs=1&to=' + props.item.email">{{ props.item.email }}</a>
                                <span>{{ props.item.email }}</span>
                             </v-tooltip>
                        </td>
                        <td class="text-xs-left cell">
                            {{ props.item.firstname }}
                        </td>
                        <td class="text-xs-left cell">
                            {{ props.item.lastname }}
                        </td>
                        <td class="text-xs-left cell" v-text="props.item.auth"></td>
                        <td class="text-xs-left cell" v-text="props.item.lang"></td>
                        <td class="text-xs-left cell">{{ props.item.confirmed ? 'Sí' : 'No' }}</td>
                        <td class="text-xs-left cell">{{ props.item.suspended ? 'Sí' : 'No' }}</td>
                        <td class="text-xs-left cell">
                            <timeago v-if="props.item.lastaccess !== 0" :auto-update="60" :datetime="new Date(props.item.lastaccess*1000)"></timeago>
                            <span v-else>Mai</span>
                        </td>
                        <td>
                            <v-tooltip bottom>
                                <span slot="activator">{{ formatBoolean(props.item.inSync) }}</span>
                                <span v-if="props.item.inSync">Tot sembla correcte</span>
                                <span v-else>{{ formatMessages(props.item.errorMessages) }}</span>
                             </v-tooltip>
                        </td>
                        <td class="text-xs-left cell">
                            <json-dialog-component btn-class="ma-0" icon="visibility" name="Actual" title="Tota la informació de l'usuari" :json="props.item"></json-dialog-component>
                            <moodle-user-edit-link :user="props.item" class="ma-0"></moodle-user-edit-link>
                            <moodle-user-remove :user="props.item" class="ma-0"></moodle-user-remove>
                            <moodle-user-change-password :user="props.item"></moodle-user-change-password>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>
    </span>
</template>

<script>

import MoodleSettings from './MoodleSettingsComponent'
import JsonDialogComponent from '../../ui/JsonDialogComponent'
import FullScreenDialog from '../../ui/FullScreenDialog'
import MoodleUserChangePassword from './MoodleUserChangePassword'
import MoodleUserEditLink from './MoodleUserEditLink'
import MoodleUserRemove from './MoodleUserRemove'
import MoodleUsersDeleteMultiple from './MoodleUsersDeleteMultiple'
import MoodleUserLocalUser from './MoodleUserLocalUser'
import MoodleUserAuthTypesSelect from './MoodleUserAuthTypesSelect'
import MoodleUserLanguageSelect from './MoodleUserLanguageSelect'
import MoodleUserFiltersSelect from './MoodleUserFiltersSelect'

var filterNames = [
  {
    id: 1,
    name: 'Sense usuari local',
    function: 'withoutLocalUser'
  },
  {
    id: 2,
    name: 'Amb usuari local',
    function: 'withLocalUser'
  },
  {
    id: 3,
    name: 'No sincronitzat',
    function: 'notSynced'
  },
  {
    id: 4,
    name: 'Sincronitzat',
    function: 'Synced'
  },
  {
    id: 5,
    name: 'Confirmat',
    function: 'confirmed'
  },
  {
    id: 6,
    name: 'No confirmat',
    function: 'notConfirmed'
  },
  {
    id: 7,
    name: 'Mai logat',
    function: 'neverLogged'
  },
  {
    id: 8,
    name: 'Com a mínim un login',
    function: 'loggedOnceAtLeast'
  }
]

var filters = {
  all: function (users) {
    return users
  },
  byAuthType: function (users, authType) {
    return users ? users.filter(function (user) {
      return user.auth === authType
    }) : []
  },
  byLanguage: function (users, language) {
    return users ? users.filter(function (user) {
      return user.lang === language
    }) : []
  },
  withoutLocalUser: function (users) {
    return users ? users.filter(function (user) {
      return !user.hasOwnProperty('localUser')
    }) : []
  },
  withLocalUser: function (users) {
    return users ? users.filter(function (user) {
      return user.hasOwnProperty('localUser')
    }) : []
  },
  notSynced: function (users) {
    return users ? users.filter(function (user) {
      return !user.inSync
    }) : []
  },
  Synced: function (users) {
    return users ? users.filter(function (user) {
      return user.inSync
    }) : []
  },
  confirmed: function (users) {
    return users ? users.filter(function (user) {
      return user.confirmed
    }) : []
  },
  notConfirmed: function (users) {
    return users ? users.filter(function (user) {
      return !user.confirmed
    }) : []
  },
  neverLogged: function (users) {
    return users ? users.filter(function (user) {
      return user.lastaccess === 0
    }) : []
  },
  loggedOnceAtLeast: function (users) {
    return users ? users.filter(function (user) {
      return user.lastaccess !== 0
    }) : []
  }
}

export default {
  name: 'MoodleUsersList',
  components: {
    'moodle-settings': MoodleSettings,
    'json-dialog-component': JsonDialogComponent,
    'fullscreen-dialog': FullScreenDialog,
    'moodle-user-change-password': MoodleUserChangePassword,
    'moodle-user-edit-link': MoodleUserEditLink,
    'moodle-user-remove': MoodleUserRemove,
    'moodle-users-delete-multiple': MoodleUsersDeleteMultiple,
    'moodle-user-local-user': MoodleUserLocalUser,
    'moodle-user-auth-types-select': MoodleUserAuthTypesSelect,
    'moodle-user-language-select': MoodleUserLanguageSelect,
    'moodle-user-filters-select': MoodleUserFiltersSelect
  },
  data () {
    return {
      authType: null,
      language: null,
      selected: [],
      search: '',
      refreshing: false,
      filter: 'all',
      selectedFilters: [],
      dataUsers: this.users,
      settingsDialog: false,
      pagination: {
        rowsPerPage: 25
      }
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    },
    localUsers: {
      type: Array,
      required: true
    }
  },
  watch: {
    users (users) {
      this.dataUsers = users
    }
  },
  computed: {
    filteredUsers: function () {
      let filteredUsers = this.dataUsers
      if (this.authType) filteredUsers = filters['byAuthType'](this.dataUsers, this.authType)
      if (this.language) filteredUsers = filters['byLanguage'](this.dataUsers, this.language)
      if (this.selectedFilters.length > 0) {
        this.selectedFilters.forEach(filter => {
          filteredUsers = filters[filter.function](this.dataUsers)
        })
      }
      return filteredUsers
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id', width: '1%' })
      headers.push({ text: 'IdNum', align: 'left', value: 'idnumber', width: '1%' })
      headers.push({ text: 'Usuari local', align: 'left', value: 'idnumber', width: '1%' })
      headers.push({ text: 'Usuari Moodle', align: 'left', value: 'username' })
      headers.push({ text: 'Correu electrònic', align: 'left', value: 'email' })
      headers.push({ text: 'Nom', align: 'left', value: 'firstname' })
      headers.push({ text: 'Cognoms', align: 'left', value: 'lastname' })
      headers.push({ text: 'Auth', align: 'left', value: 'auth' })
      headers.push({ text: 'Idioma', align: 'left', value: 'lang' })
      headers.push({ text: 'Confirmat', align: 'left', value: 'confirmed' })
      headers.push({ text: 'Suspès', align: 'left', value: 'suspended' })
      headers.push({ text: 'Últim accés', align: 'left', value: 'lastaccess' })
      headers.push({ text: 'Sincronitzat', align: 'left', value: 'inSync' })
      headers.push({ text: 'Accions', value: 'user_email', sortable: false })
      return headers
    }
  },
  methods: {
    refresh () {
      this.refreshing = true
      window.axios.get('/api/v1/moodle/users').then(response => {
        this.dataUsers = response.data
        this.$snackbar.showMessage('Usuaris actualitzats correctament')
        this.refreshing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.refreshing = false
      })
    },
    formatBoolean (boolean) {
      return boolean ? 'Sí' : 'No'
    },
    formatMessages (messages) {
      return messages.join('<br/>')
    }
  },
  created () {
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
