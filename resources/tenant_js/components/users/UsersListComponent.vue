<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-toolbar color="primary" dense>
                    <v-menu bottom>
                        <v-btn slot="activator" icon dark>
                            <v-icon>more_vert</v-icon>
                        </v-btn>

                        <v-list>
                            <v-list-tile href="/users/permissions" target="_blank">
                                <v-list-tile-title>Gestionar Permisos</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/users/roles" target="_blank">
                                <v-list-tile-title>Gestionar Rols</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/google_users" target="_blank">
                                <v-list-tile-title>Usuaris de Google</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/moodle/users" target="_blank">
                                <v-list-tile-title>Usuaris Moodle</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile href="/ldap_users" target="_blank">
                                <v-list-tile-title>Usuaris Ldap</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-toolbar-title class="white--text title">Usuaris</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="settings">
                        <v-icon>settings</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text" @click="refresh" :disabled="refreshing" :loading="refreshing">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>

                <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                <v-layout>
                                    <v-flex xs9 style="align-self: flex-end;">
                                        <v-layout>
                                            <v-flex xs2 class="text-sm-left" style="align-self: center;">
                                                <user-types-select
                                                        :user-types="userTypes"
                                                        v-model="userType"
                                                ></user-types-select>
                                            </v-flex>
                                            <v-flex xs10>
                                                <v-layout>
                                                    <v-flex xs4>
                                                        <roles-select v-model="selectedRoles"></roles-select>
                                                    </v-flex>
                                                    <v-flex xs8>
                                                        <user-filters-select v-model="selectedFilters" :filters="filterNames"></user-filters-select>
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
                                <users-delete-multiple :users="selected" @deleted="selected=[];refresh(false)"></users-delete-multiple>
                            </div>
                            <v-data-table
                                v-model="selected"
                                select-all
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
                                        {{ props.item.id }}
                                    </td>
                                    <td class="text-xs-left cell">
                                        <user-avatar :hash-id="props.item.hashid"
                                                     :alt="props.item.name"
                                                     :user="props.item"
                                                     :editable="true"
                                                     :removable="true"
                                        ></user-avatar>
                                    </td>
                                    <td class="text-xs-left cell" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <user-edit-name :user="props.item" @saved="refresh(false)"></user-edit-name>
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ props.item.name }}</span>
                                            <span>{{ props.item.name }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-left cell" style="max-width: 175px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <user-edit-email :user="props.item" @saved="refresh(false)"></user-edit-email>
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ props.item.email }}</span>
                                            <span>{{ props.item.email }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-center cell">
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ formatBoolean(props.item.email_verified_at) }}</span>
                                            <span v-if="props.item.email_verified_at">{{ props.item.email_verified_at }}</span>
                                            <span v-else>{{ formatBoolean(props.item.email_verified_at) }}</span>
                                        </v-tooltip>
                                    <td class="text-xs-left cell" style="max-width: 125px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span v-if="props.item.corporativeEmail">
                                            <v-tooltip bottom>
                                                <span slot="activator"><a target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + props.item.googleId">{{ props.item.corporativeEmail }}</a></span>
                                                <span>{{ props.item.corporativeEmail }}</span>
                                            </v-tooltip>
                                        </span>
                                        <manage-corporative-email-icon :user="props.item" @unassociated="refresh" @associated="refresh" @added="refresh"></manage-corporative-email-icon>
                                    </td>
                                    <td class="text-xs-left cell">
                                        <inline-text-field-edit-dialog mask="###-###-###" placeholder="666777895" hint="9 números seguits sense codi de país" v-model="props.item" field="mobile" label="Mòbil" @save="refresh"></inline-text-field-edit-dialog>
                                    </td>
                                    <td class="text-xs-center cell">
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ formatBoolean(props.item.mobile_verified_at) }}</span>
                                            <span v-if="props.item.mobile_verified_at">{{ props.item.mobile_verified_at }}</span>
                                            <span v-else>{{ formatBoolean(props.item.mobile_verified_at) }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-left cell">
                                        <manage-moodle-user-icon :user="props.item" @unassociated="refresh" @associated="refresh" @added="refresh"></manage-moodle-user-icon>
                                    </td>
                                    <td class="text-xs-left cell">{{ formatUserType(props.item.user_type_id) }}</td>
                                    <td class="text-xs-left cell">{{ formatBoolean(props.item.admin) }}</td>
                                    <td class="text-xs-left cell" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <user-roles-manage-button :user="props.item" @added="refresh(false)" @removed="refresh(false)"></user-roles-manage-button>
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ formatRoles(props.item) }}</span>
                                            <span>{{ formatRoles(props.item) }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-left cell">
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ props.item.last_login_diff }}</span>
                                            <span>{{ props.item.last_login_ip }} | {{ props.item.last_login_formatted }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-left cell" v-html="props.item.formatted_created_at_diff" :title="props.item.formatted_created_at"></td>
                                    <td class="text-xs-left cell" :title="props.item.formatted_updated_at">{{props.item.formatted_updated_at_diff}}</td>
                                    <td class="text-xs-left cell">
                                        <show-user-icon :user="props.item" :users="users"></show-user-icon>
                                        <user-emails :user="props.item"></user-emails>
                                        <!--<user-send-welcome-email :user="props.item"></user-send-welcome-email>-->
                                        <!--<user-send-reset-password-email :user="props.item"></user-send-reset-password-email>-->
                                        <!--<user-send-confirmation-email :user="props.item"></user-send-confirmation-email>-->
                                        <user-delete :user="props.item"></user-delete>
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
import ShowUserIcon from './ShowUserIconComponent.vue'
import UserAvatar from '../ui/UserAvatarComponent'
import UserSendWelcomeEmail from './UserSendWelcomeEmail'
import UserSendResetPasswordEmail from './UserSendResetPasswordEmail'
import UserSendConfirmationEmail from './UserSendConfirmationEmail'
import UsersDeleteMultiple from './UsersDeleteMultiple'
import UserEmails from './UserEmailsComponent'
import UserDelete from './UserDeleteComponent'
import ManageCorporativeEmailIcon from '../google/users/ManageCorporativeEmailIcon'
import ManageMoodleUserIcon from '../moodle/users/ManageMoodleUserIcon'
import UserTypesSelect from './UserTypesSelect'
import RolesSelect from './roles/RolesSelect'
import UserFiltersSelect from './UserFiltersSelect'
import UserRolesManageButton from './roles/UserRolesManageButton'
import UserEditName from './UserEditName'
import UserEditEmail from './UserEditEmail'
import InlineTextFieldEditDialog from '../ui/InlineTextFieldEditDialog'

var filterNames = [
  {
    id: 1,
    name: 'Email confirmat',
    function: 'confirmedEmail'
  },
  {
    id: 2,
    name: 'Email no confirmat',
    function: 'unconfirmedEmail'
  },
  {
    id: 3,
    name: 'Mòbil confirmat',
    function: 'confirmedMobile'
  },
  {
    id: 4,
    name: 'Mòbil no confirmat',
    function: 'unconfirmedMobile'
  },
  {
    id: 5,
    name: 'Amb email corporatiu',
    function: 'withCorporativeEmail'
  },
  {
    id: 6,
    name: 'Sense email corporatiu',
    function: 'withoutCorporativeEmail'
  },
  {
    id: 7,
    name: 'Admins',
    function: 'admins'
  },
  {
    id: 8,
    name: 'Amb Avatar',
    function: 'withAvatar'
  },
  {
    id: 9,
    name: 'Sense Avatar',
    function: 'withoutAvatar'
  },
  {
    id: 10,
    name: 'No han entrat mai',
    function: 'neverLogged'
  }
]

var filters = {
  all: function (users) {
    return users
  },
  byUserType: function (users, userType) {
    return users ? users.filter(function (user) {
      return user.user_type_id === userType
    }) : []
  },
  byRoles: function (users, roles) {
    return users ? users.filter(function (user) {
      return user.roles.some(role => roles.includes(role.id))
    }) : []
  },
  confirmedEmail: function (users) {
    return users ? users.filter(function (user) {
      return user.email_verified_at !== null
    }) : []
  },
  unconfirmedEmail: function (users) {
    return users ? users.filter(function (user) {
      return user.email_verified_at === null
    }) : []
  },
  confirmedMobile: function (users) {
    return users ? users.filter(function (user) {
      return user.mobile_verified_at !== null
    }) : []
  },
  unconfirmedMobile: function (users) {
    return users ? users.filter(function (user) {
      return user.mobile_verified_at === null
    }) : []
  },
  withCorporativeEmail: function (users) {
    return users ? users.filter(function (user) {
      return user.corporativeEmail !== null
    }) : []
  },
  withoutCorporativeEmail: function (users) {
    return users ? users.filter(function (user) {
      return user.corporativeEmail === null
    }) : []
  },
  admins: function (users) {
    return users ? users.filter(function (user) {
      return user.isSuperAdmin
    }) : []
  },
  withAvatar: function (users) {
    return users ? users.filter(function (user) {
      return user.isSuperAdmin
    }) : []
  },
  withoutAvatar: function (users) {
    return users ? users.filter(function (user) {
      return user.isSuperAdmin
    }) : []
  },
  neverLogged: function (users) {
    return users ? users.filter(function (user) {
      return user.last_login === null
    }) : []
  }
}

export default {
  name: 'UsersList',
  components: {
    'user-emails': UserEmails,
    'user-delete': UserDelete,
    'user-send-welcome-email': UserSendWelcomeEmail,
    'user-send-reset-password-email': UserSendResetPasswordEmail,
    'user-send-confirmation-email': UserSendConfirmationEmail,
    'show-user-icon': ShowUserIcon,
    'manage-corporative-email-icon': ManageCorporativeEmailIcon,
    'manage-moodle-user-icon': ManageMoodleUserIcon,
    'user-avatar': UserAvatar,
    'users-delete-multiple': UsersDeleteMultiple,
    'user-types-select': UserTypesSelect,
    'roles-select': RolesSelect,
    'user-filters-select': UserFiltersSelect,
    'user-roles-manage-button': UserRolesManageButton,
    'user-edit-name': UserEditName,
    'user-edit-email': UserEditEmail,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog
  },
  data () {
    return {
      selected: [],
      search: '',
      refreshing: false,
      userType: null,
      selectedRoles: [],
      selectedFilters: [],
      headers: [
        { text: 'Id', align: 'left', value: 'id' },
        { text: 'Avatar', value: 'photo', sortable: false },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Verificat', value: 'email_verified_at' },
        { text: 'Email corporatiu', value: 'corporativeEmail' },
        { text: 'Mòbil', value: 'mobile' },
        { text: 'Verificat', value: 'mobile_verified_at' },
        { text: 'Moodle', value: 'moodle' },
        { text: 'Tipus', value: 'user_type_id' },
        { text: 'Admin', value: 'admin' },
        { text: 'Rols', value: 'roles', sortable: false },
        { text: 'Últim login', value: 'last_login' },
        { text: 'Data creació', value: 'created_at_timestamp' },
        { text: 'Data actualització', value: 'updated_at_timestamp' },
        { text: 'Accions', sortable: false }
      ]
    }
  },
  props: {
    users: {
      type: Array,
      required: false
    },
    userTypes: {
      type: Array,
      required: false
    },
    roles: {
      type: Array,
      required: false
    }
  },
  computed: {
    ...mapGetters({
      internalUsers: 'users'
    }),
    filteredUsers: function () {
      let filteredUsers = this.internalUsers
      if (this.userType) filteredUsers = filters['byUserType'](this.internalUsers, this.userType)
      if (this.selectedRoles.length > 0) filteredUsers = filters['byRoles'](this.internalUsers, this.selectedRoles)
      if (this.selectedFilters.length > 0) {
        this.selectedFilters.forEach(filter => {
          console.log('filter:')
          console.log(filter)
          filteredUsers = filters[filter.function](this.internalUsers)
        })
      }
      return filteredUsers
    }
  },
  methods: {
    formatUserType (userType) {
      if (userType) return this.userTypesTranslation[userType]
    },
    formatBoolean (boolean) {
      return boolean ? 'Sí' : 'No'
    },
    refresh (message = true) {
      this.refreshing = true
      this.$store.dispatch(actions.FETCH_USERS).then(response => {
        this.refreshing = false
        if (message) this.$snackbar.showMessage('Usuaris actualizats correctament')
      }).catch(error => {
        this.refreshing = false
        this.$snackbar.showError(error)
      })
    },
    settings () {
      console.log('settings TODO') // TODO
    },
    formatRoles (user) {
      return user.roles.map(role => role.name).join(', ')
    }
  },
  created () {
    this.$store.commit(mutations.SET_USERS, this.users)
    this.$store.commit(mutations.SET_ROLES, this.roles)
    this.filterNames = filterNames
    this.userTypesTranslation = {
      1: 'Professor',
      2: 'Estudiant',
      3: 'Conserge',
      4: 'Administratiu',
      5: 'Familiar'
    }
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
