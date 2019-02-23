<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-dialog v-model="settingsDialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-btn icon dark @click.native="dialog = false">
                                <v-icon>close</v-icon>
                            </v-btn>
                            <v-toolbar-title>Configuració Usuaris Ldap</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-toolbar-items>
                                <v-btn dark flat @click.native="dialog = false">Guardar</v-btn>
                            </v-toolbar-items>
                        </v-toolbar>
                        <v-list three-line subheader>
                            <v-subheader>General</v-subheader>
                            <v-list-tile avatar>
                                <v-list-tile-action>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <!--// TODO-->
                                    <v-list-tile-title>TODO</v-list-tile-title>
                                    <v-list-tile-sub-title>TODO</v-list-tile-sub-title>
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
                            <v-list-tile href="/google_users" target="_blank">
                                <v-list-tile-title>Usuaris Google</v-list-tile-title>
                            </v-list-tile>
                            <v-list-tile>
                                <v-list-tile-title><a target="_blank" href="https://admin.google.com/u/3/ac/users">Panell administració Google</a></v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-toolbar-title class="white--text title">Ldap users</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="settingsDialog = true">
                        <v-icon>settings</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card>
                    <v-card-text class="px-0 mb-5">
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

                            <div id="massive_actions" v-if="selected.length > 0" style="text-align: left;">
                                <ldap-users-delete-multiple :users="selected" @deleted="selected=[];refresh(false)"></ldap-users-delete-multiple>
                            </div>

                            <v-data-table
                                    v-model="selected"
                                    select-all
                                    class="px-0 mb-5"
                                    :headers="headers"
                                    :items="filteredUsers"
                                    :search="search"
                                    item-key="id"
                                    disable-initial-sort
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi han dades disponibles"
                                    rows-per-page-text="Usuaris per pàgina"
                                    :rows-per-page-items="[5,10,25,50,100,200,500,1000,{'text':'Tots','value':-1}]"
                                    :pagination.sync="pagination"
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
                                        <td>
                                            <v-avatar v-if="props.item.jpegphoto"
                                                    size="45"
                                                    color="grey lighten-4"
                                            >
                                                <img  :src="props.item.jpegphoto" :alt="props.item.cn"/>
                                            </v-avatar>
                                        </td>
                                        <td class="text-xs-left cell" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <v-tooltip bottom>
                                                <span slot="activator" v-text="props.item.rdn"></span>
                                                <span v-text="props.item.dn"></span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator" v-text="props.item.cn"></span>
                                                <span>GivenName: {{props.item.givenname}} Sn1: {{props.item.sn1}} Sn2: {{props.item.sn2}} </span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell" v-html="props.item.uid"></td>
                                        <td class="text-xs-left cell" v-html="props.item.uidnumber"></td>
                                        <td class="text-xs-left cell" v-html="props.item.gidnumber"></td>
                                        <td class="text-xs-left cell" v-html="props.item.homedirectory"></td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator" v-text="props.item.sambarid"></span>
                                                <span v-text="props.item.sambasid"></span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom v-if="props.item.sambapwdlastset != 0">
                                                <span slot="activator" v-text="props.item.sambapwdlastset_human"></span>
                                                <span>{{ props.item.sambapwdlastset_formatted }} | {{ props.item.sambapwdlastset }}</span>
                                            </v-tooltip>
                                            <span v-else>Sense valor</span>
                                        </td>
                                        <td class="text-xs-left cell" v-html="props.item.passwordtype"></td>
                                        <td class="text-xs-left cell" v-html="props.item.highschooluserid"></td>
                                        <td class="text-xs-left cell" v-html="props.item.highschoolpersonalemail"></td>
                                        <td class="text-xs-left cell" v-html="props.item.email"></td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator" v-text="props.item.createHuman"></span>
                                                <span>{{ props.item.createFormatted }} | {{ props.item.createTimestamp }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator" v-text="props.item.creatorsNameRDN"></span>
                                                <span v-text="props.item.creatorsName"></span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator" v-text="props.item.modifiersNameRDN"></span>
                                                <span v-text="props.item.modifiersName"></span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <v-tooltip bottom>
                                                <span slot="activator" v-text="props.item.modifyHuman"></span>
                                                <span>{{ props.item.modifyFormatted }} | {{ props.item.modifyTimestamp }}</span>
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
                                            <show-ldap-user-icon :user="props.item" :users="users"></show-ldap-user-icon>
                                            <ldap-user-change-password :user="props.item"></ldap-user-change-password>
                                            <ldap-user-delete-icon :user="props.item"></ldap-user-delete-icon>
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
import ShowLdapUserIconComponent from './ShowLdapUserIconComponent'
import LdapUsersDeleteMultiple from './LdapUsersDeleteMultiple'
import LdapUsersDeleteIcon from './LdapUsersDeleteIcon'
import LdapUserChangePassword from './LdapUserChangePassword'

export default {
  name: 'LdapUsers',
  components: {
    'show-ldap-user-icon': ShowLdapUserIconComponent,
    'ldap-users-delete-multiple': LdapUsersDeleteMultiple,
    'ldap-user-delete-icon': LdapUsersDeleteIcon,
    'ldap-user-change-password': LdapUserChangePassword
  },
  data () {
    return {
      selected: [],
      search: '',
      removing: false,
      refreshing: false,
      settingsDialog: false,
      internalUsers: this.users,
      pagination: {
        rowsPerPage: 25
      }
    }
  },
  computed: {
    filteredUsers: function () {
      return this.internalUsers
    },
    headers () {
      let headers = []
      headers.push({ text: 'Foto', value: 'jpegphoto', sortable: false })
      headers.push({ text: 'DN', value: 'dn' })
      headers.push({ text: 'CN', value: 'cn' })
      headers.push({ text: 'uid', value: 'uid' })
      headers.push({ text: 'uidnumber', value: 'uidnumber' })
      headers.push({ text: 'gidnumber', value: 'gidnumber' })
      headers.push({ text: 'homedirectory', value: 'homedirectory' })
      headers.push({ text: 'sambasid', value: 'sambarid' })
      headers.push({ text: 'sambapwdlastset', value: 'sambapwdlastset' })
      headers.push({ text: 'passwordtype', value: 'passwordtype' })
      headers.push({ text: 'highschooluserid', value: 'highschooluserid' })
      headers.push({ text: 'highschoolpersonalemail', value: 'highschoolpersonalemail' })
      headers.push({ text: 'email', value: 'email' })
      headers.push({ text: 'Data creació', value: 'createtimestamp' })
      headers.push({ text: 'Usuari creació', value: 'creatorsName' })
      headers.push({ text: 'modifiersName', value: 'modifiersName' })
      headers.push({ text: 'modifyTimestamp', value: 'modifyTimestamp' })
      headers.push({ text: 'Sincronitzat', align: 'left', value: 'inSync' })
      headers.push({ text: 'Accions', sortable: false })
      return headers
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    }
  },
  methods: {
    refresh () {
      this.refreshing = true
      window.axios.get('/api/v1/ldap/users').then(response => {
        this.refreshing = false
        this.internalUsers = response.data
        this.$snackbar.showMessage('Usuaris actualitzats correctament')
      }).catch(error => {
        this.refreshing = false
        this.$snackbar.showError(error)
      })
    },
    formatBoolean (boolean) {
      return boolean ? 'Sí' : 'No'
    },
    formatMessages (messages) {
      if (messages) return messages.join('<br/>')
      return ''
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
