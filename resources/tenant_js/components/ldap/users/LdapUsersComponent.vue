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
                                    <v-checkbox v-model="googleWatch"></v-checkbox>
                                </v-list-tile-action>
                                <v-list-tile-content>
                                    <!--// TODO-->
                                    <v-list-tile-title>TODO Observar canvis d'usuaris Ldap (Watch)</v-list-tile-title>
                                    <v-list-tile-sub-title>Activar les notificacions push de Ldap per tal de registrar/observar els canvis d'usuaris.
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
                                <v-list-tile-title><a target="_blank" href="https://admin.google.com/u/3/ac/users">Panell administració Ldap</a></v-list-tile-title>
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
                                    class="px-0 mb-5 hidden-sm-and-down"
                                    :headers="headers"
                                    :items="filteredUsers"
                                    :search="search"
                                    item-key="id"
                                    disable-initial-sort
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi han dades disponibles"
                                    rows-per-page-text="Grups per pàgina"
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
                                        <td class="text-xs-left cell" v-html="props.item.passwordtype"></td>
                                        <td class="text-xs-left cell" v-html="props.item.highschooluserid"></td>
                                        <td class="text-xs-left cell" v-html="props.item.highschoolpersonalemail"></td>
                                        <td class="text-xs-left cell" v-html="props.item.email">
                                            <!--// TODO-->
                                            <!--<a target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + props.item.id">{{ props.item.email }}</a>-->
                                        </td>
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
                                        <td>
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ formatBoolean(props.item.inSync) }}</span>
                                                <span v-if="props.item.inSync">Tot sembla correcte</span>
                                                <span v-else v-html="formatMessages(props.item.errorMessages)"></span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left cell">
                                            <show-ldap-user-icon :user="props.item" :users="users"></show-ldap-user-icon>
                                            <v-btn icon class="mx-0" @click="">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <confirm-icon
                                                    icon="delete"
                                                    color="accent"
                                                    :working="removing"
                                                    @confirmed="remove(props.item)"
                                                    tooltip="Eliminar"
                                                    message="Esteu segurs que voleu eliminar l'usuari?"
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

import withSnackbar from '../../mixins/withSnackbar'
import axios from 'axios'
import ConfirmIcon from '../../ui/ConfirmIconComponent'
import ShowLdapUserIconComponent from './ShowLdapUserIconComponent'
import LdapUsersDeleteMultiple from './LdapUsersDeleteMultiple'

export default {
  name: 'LdapUsersComponent',
  mixins: [withSnackbar],
  components: {
    'confirm-icon': ConfirmIcon,
    'show-ldap-user-icon': ShowLdapUserIconComponent,
    'ldap-users-delete-multiple': LdapUsersDeleteMultiple
  },
  data () {
    return {
      selected: [],
      search: '',
      removing: false,
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
      headers.push({ text: 'Foto', value: 'photo', sortable: false })
      headers.push({ text: 'DN', value: 'dn' })
      headers.push({ text: 'CN', value: 'cn' })
      headers.push({ text: 'uid', value: 'uid' })
      headers.push({ text: 'uidnumber', value: 'uidnumber' })
      headers.push({ text: 'gidnumber', value: 'gidnumber' })
      headers.push({ text: 'homedirectory', value: 'homedirectory' })
      headers.push({ text: 'sambasid', value: 'sambarid' })
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
        this.removing = false
        this.$store.commit(mutations.DELETE_GOOGLE_USER, user)
        this.showMessage('Usuari esborrat correctament')
      }).catch(error => {
        this.removing = false
        this.showError(error)
      })
    },
    formatBoolean (boolean) {
      return boolean ? 'Sí' : 'No'
    },
    formatMessages (messages) {
      if (messages) return messages.join('<br/>')
      return ''
    }
  },
  created () {
    this.$store.commit(mutations.SET_GOOGLE_USERS, this.users)
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
