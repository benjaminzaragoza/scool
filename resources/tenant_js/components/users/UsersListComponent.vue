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
                            <v-list-tile>
                                <v-list-tile-title>TODO: llista d'usuaris AAAAAAAA</v-list-tile-title>
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
                                v-model="selected"
                                select-all
                                class="px-0 mb-2 hidden-sm-and-down"
                                :headers="headers"
                                :items="internalUsers"
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
                                    <td class="text-xs-left cell" style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ props.item.name }}</span>
                                            <span>{{ props.item.name }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-left cell" style="max-width: 100px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <v-tooltip bottom>
                                            <span slot="activator">{{ props.item.email }}</span>
                                            <span>{{ props.item.email }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-center cell">{{ formatBoolean(props.item.email_verified_at) }}</td>
                                    <td class="text-xs-left cell" style="max-width: 125px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span v-if="props.item.corporativeEmail">
                                            <v-tooltip bottom>
                                                <span slot="activator"><a target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + props.item.googleId">{{ props.item.corporativeEmail }}</a></span>
                                                <span>{{ props.item.corporativeEmail }}</span>
                                            </v-tooltip>
                                        </span>
                                        <manage-corporative-email-icon :user="props.item" @unassociated="refresh" @associated="refresh" @added="refresh"></manage-corporative-email-icon>
                                    </td>
                                    <td class="text-xs-left cell">{{ props.item.mobile }}</td>
                                    <td class="text-xs-left cell" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
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

                                        <v-btn icon class="mx-0" @click="editItem(props.item)">
                                            <v-icon color="teal">edit</v-icon>
                                        </v-btn>
                                        <v-btn icon class="mx-0" @click="showConfirmationDialog(props.item)">
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
import UserAvatar from '../ui/UserAvatarComponent'
import UserSendWelcomeEmail from './UserSendWelcomeEmail'
import UserSendResetPasswordEmail from './UserSendResetPasswordEmail'
import UserSendConfirmationEmail from './UserSendConfirmationEmail'
import UserEmails from './UserEmailsComponent'
import ManageCorporativeEmailIcon from '../google/users/ManageCorporativeEmailIcon'

export default {
  name: 'UsersList',
  mixins: [withSnackbar],
  components: {
    'user-emails': UserEmails,
    'user-send-welcome-email': UserSendWelcomeEmail,
    'user-send-reset-password-email': UserSendResetPasswordEmail,
    'user-send-confirmation-email': UserSendConfirmationEmail,
    'confirm-icon': ConfirmIcon,
    'show-user-icon': ShowUserIcon,
    'manage-corporative-email-icon': ManageCorporativeEmailIcon,
    'user-avatar': UserAvatar
  },
  data () {
    return {
      selected: [],
      showDeleteUserDialog: false,
      search: '',
      deleting: false,
      refreshing: false,
      headers: [
        { text: 'Id', align: 'left', value: 'id' },
        { text: 'Avatar', value: 'photo', sortable: false },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Verificat', value: 'email_verified_at' },
        { text: 'Email corporatiu', value: 'corporativeEmail' },
        { text: 'Mòbil', value: 'mobile' },
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
    }
  },
  computed: {
    ...mapGetters({
      internalUsers: 'users'
    })
  },
  methods: {
    formatBoolean (boolean) {
      return boolean ? 'Sí' : 'No'
    },
    refresh () {
      this.refreshing = true
      this.$store.dispatch(actions.FETCH_USERS).then(response => {
        this.refreshing = false
        this.showMessage('Usuaris actualizats correctament')
      }).catch(error => {
        this.refreshing = false
        this.showError(error)
      })
    },
    settings () {
      console.log('settings TODO') // TODO
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
