<template>
    <span>
        <v-toolbar color="blue darken-3">
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile href="/changelog/module/moodle" target="_blank">
                        <v-list-tile-title>Mostrar historial incidències (registre de canvis)</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Usuaris de moodle</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn id="incidents_help_button" icon class="white--text" href="http://docs.scool.cat/docs/moodle" target="_blank">
                <v-icon>help</v-icon>
            </v-btn>

            <fullscreen-dialog
                    v-role="'MoodleManager,UserManager'"
                    :flat="false"
                    class="white--text"
                    icon="settings"
                    v-model="settingsDialog"
                    color="blue darken-3"
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
                      TODO FILTERS
                      <!--<v-layout>-->
                          <!--<v-flex xs3 class="text-sm-left" style="align-self: center;">-->
                                <!--<span @click="showOpenIncidents" :class="{ bolder: filter === 'open', 'no-wrap': true, 'pointer': true }">-->
                                    <!--<v-icon color="error" title="Obertes">lock_open</v-icon> Obertes: {{openIncidents ? openIncidents.length : 0}}-->
                                <!--</span>-->
                                <!--<span @click="showClosedIncidents" :class="{ bolder: filter === 'closed', 'no-wrap': true, 'pointer': true  }">-->
                                  <!--<v-icon color="success" title="Tancades">lock</v-icon> Tancades: {{closedIncidents ? closedIncidents.length : 0}}-->
                                <!--</span>-->
                                <!--<span @click="showAll" :class="{ bolder: filter === 'all', 'no-wrap': true, 'pointer': true  }">-->
                                  <!--<v-icon color="primary" title="Total">info</v-icon> Total: {{dataIncidents ? dataIncidents.length : 0}}-->
                                <!--</span>-->
                          <!--</v-flex>-->
                          <!--<v-flex xs9>-->
                               <!--<v-layout>-->
                                   <!--<v-flex xs4>-->
                                       <!--<user-select-->
                                               <!--label="Creada per:"-->
                                               <!--:users="creators"-->
                                               <!--v-model="creator"-->
                                       <!--&gt;</user-select>-->
                                   <!--</v-flex>-->
                                   <!--<v-flex xs4>-->
                                       <!--<user-select-->
                                               <!--label="Assignada a:"-->
                                               <!--:users="filteredAssignees"-->
                                               <!--v-model="assignee"-->
                                       <!--&gt;</user-select>-->
                                   <!--</v-flex>-->
                                   <!--<v-flex xs4>-->
                                       <!--<v-autocomplete-->
                                               <!--v-model="selectedTags"-->
                                               <!--:items="dataTags"-->
                                               <!--attach-->
                                               <!--chips-->
                                               <!--label="Etiquetes"-->
                                               <!--multiple-->
                                               <!--item-value="id"-->
                                               <!--item-text="value"-->
                                       <!--&gt;-->
                                            <!--<template slot="selection" slot-scope="data">-->
                                                <!--<v-chip-->
                                                        <!--small-->
                                                        <!--label-->
                                                        <!--@input="data.parent.selectItem(data.item)"-->
                                                        <!--:selected="data.selected"-->
                                                        <!--class="chip&#45;&#45;select-multi"-->
                                                        <!--:color="data.item.color"-->
                                                        <!--text-color="white"-->
                                                        <!--:key="JSON.stringify(data.item)"-->
                                                <!--&gt;<v-icon small left v-text="data.item.icon"></v-icon>{{ data.item.value }}</v-chip>-->
                                            <!--</template>-->
                                            <!--<template slot="item" slot-scope="data">-->
                                                <!--<v-checkbox v-model="data.tile.props.value"></v-checkbox>-->
                                                <!--<v-chip small label :title="data.item.description" :color="data.item.color" text-color="white">-->
                                                    <!--<v-icon small left v-text="data.item.icon"></v-icon>{{ data.item.value }}-->
                                                <!--</v-chip>-->
                                            <!--</template>-->
                                       <!--</v-autocomplete>-->
                                   <!--</v-flex>-->
                               <!--</v-layout>-->
                          <!--</v-flex>-->
                      <!--</v-layout>-->
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
            <v-data-table
                    class="px-0 mb-2 hidden-sm-and-down"
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
                <template slot="items" slot-scope="{item: user}">
                    <tr :id="'user_row_' + user.id">
                        <td class="text-xs-left" v-text="user.id"></td>
                        <td class="text-xs-left">
                            <template v-if="user.idnumber">
                                <template v-if="localUsers[user.idnumber]">
                                    <user-avatar
                                            class="mr-2" :hash-id="localUsers[user.idnumber].hash_id"
                                            :alt="localUsers[user.idnumber].name"
                                    ></user-avatar>
                                    <span :title="localUsers[user.idnumber].name +  ' - ' + user.idnumber">{{ localUsers[user.idnumber].email }}</span>

                                    <template v-if="findLocalUserByEmail(user.email)">
                                        <template v-if="localUsers[user.idnumber].email !== user.email">
                                            <v-btn @click="changeLocalUser()"
                                                   icon color="error" title="No coincideixen el usuari local i l'usuari de Moodle. Feu clic per arreglar-ho">
                                                <v-icon>error</v-icon>
                                            </v-btn>
                                        </template>
                                    </template>
                                    <template v-else>
                                        <v-btn @click="changeLocalUser()"
                                           icon color="success" title="No hi ha cap usuari local amb aquestes dades. Feu clic si el voleu importar de Moodle">
                                            <v-icon>add</v-icon>
                                        </v-btn>
                                    </template>
                                </template>
                                <template v-else>
                                    <v-btn @click="changeLocalUser()"
                                       icon color="success" title="No hi ha cap usuari local amb aquestes dades. Feu clic si el voleu importar de Moodle">
                                        <v-icon>add</v-icon>
                                    </v-btn>
                                    {{ user.idnumber }}
                                </template>
                            </template>
                            <template v-else>
                                    Cap
                                    <v-btn @click="changeLocalUser()"
                                        icon color="success" title="No hi ha cap usuari local amb aquestes dades. Feu clic si el voleu importar de Moodle">
                                        <v-icon>add</v-icon>
                                    </v-btn>
                            </template>

                        <td class="text-xs-left">
                            <v-avatar color="primary" :title="user.fullname">
                              <img :src="user.profileimageurlsmall" alt="avatar">
                            </v-avatar>
                            <a :title="user.description" v-text="user.username" :href="'https://www.iesebre.com/moodle/user/profile.php?id=' + user.id" target="_blank"></a>
                        </td>
                        <td class="text-xs-left">
                            <a target="_blank" :href="'https://mail.google.com/mail/?view=cm&fs=1&to=' + user.email">{{ user.email }}</a>
                        </td>
                        <td class="text-xs-left">
                            <template v-if="localUsers[user.idnumber]">
                                {{ user.firstname }}
                                <template v-if="user.firstname !== localUsers[user.idnumber].givenName">
                                    <v-btn icon color="success" title="El nom de l'usuari no concorda. Feu clic per canviar-lo">
                                        <v-icon>sync</v-icon>
                                    </v-btn>
                                </template>
                            </template>
                            <template v-else>
                                {{ user.firstname }}
                            </template>
                        </td>
                        <td class="text-xs-left">
                            <template v-if="localUsers[user.idnumber]">
                                {{ user.lastname }}
                                <template v-if="user.lastname !== localUsers[user.idnumber].lastname">
                                    <v-btn icon color="success" title="Els cognoms de l'usuari no concorden. Feu clic per canviar-los">
                                        <v-icon>sync</v-icon>
                                    </v-btn>
                                </template>
                            </template>
                            <template v-else>
                                {{ user.lastname }}
                            </template>
                        </td>
                        <td class="text-xs-left" v-text="user.auth"></td>
                        <td class="text-xs-left" v-text="user.lang"></td>
                        <td class="text-xs-left">{{ user.confirmed ? 'Sí' : 'No' }}</td>
                        <td class="text-xs-left">{{ user.suspended ? 'Sí' : 'No' }}</td>
                        <td class="text-xs-left">
                            <timeago v-if="user.lastaccess !== 0" :auto-update="60" :datetime="new Date(user.lastaccess*1000)"></timeago>
                            <span v-else>Mai</span>
                        </td>
                        <td v-text="isUserInSync(user)"></td>
                        <td class="text-xs-left">
                            <json-dialog-component icon="visibility" name="Actual" title="Tota la informació de l'usuari" :json="user"></json-dialog-component>
                            <v-btn icon title="Edita l'usuari a Moodle" flat color="success" class="ma-0" :href="'https://www.iesebre.com/moodle/user/editadvanced.php?id=' + user.id + '&course=1&returnto=profile'" target="_blank">
                                <v-icon>edit</v-icon>
                            </v-btn>
                            <v-btn icon title="Eliminar l'usuari" flat color="error" class="ma-0" @click="remove(user)" :disabled="removing === user.id" :loading="removing  === user.id">
                                <v-icon>remove</v-icon>
                            </v-btn>
                            <moodle-user-change-password></moodle-user-change-password>
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
import UserAvatar from '../../ui/UserAvatarComponent'
import MoodleUserChangePassword from './MoodleUserChangePassword'

var filters = {
  all: function (incidents) {
    return incidents
  }
}

export default {
  name: 'MoodleUsersList',
  components: {
    'moodle-settings': MoodleSettings,
    'json-dialog-component': JsonDialogComponent,
    'fullscreen-dialog': FullScreenDialog,
    'user-avatar': UserAvatar,
    'moodle-user-change-password': MoodleUserChangePassword
  },
  data () {
    return {
      search: '',
      refreshing: false,
      removing: false,
      filter: 'all',
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
      type: Object,
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
      return filters[this.filter](this.dataUsers)
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id', width: '1%' })
      headers.push({ text: 'Usuari local', align: 'left', value: 'idnumber', width: '1%' })
      headers.push({ text: 'Usuari', align: 'left', value: 'username' })
      headers.push({ text: 'Correu electrònic', align: 'left', value: 'email' })
      headers.push({ text: 'Nom', align: 'left', value: 'firstname' })
      headers.push({ text: 'Cognoms', align: 'left', value: 'lastname' })
      headers.push({ text: 'Auth', align: 'left', value: 'auth' })
      headers.push({ text: 'Idioma', align: 'left', value: 'lang' })
      headers.push({ text: 'Confirmat', align: 'left', value: 'confirmed' })
      headers.push({ text: 'Suspès', align: 'left', value: 'suspended' })
      headers.push({ text: 'Últim accés', align: 'left', value: 'lastaccess' })
      headers.push({ text: 'Sincronitzat', align: 'left', sortable: false })
      headers.push({ text: 'Accions', value: 'user_email', sortable: false })
      return headers
    }
  },
  methods: {
    isUserInSync (user) {
      const localUser = this.localUsers[user.idnumber]
      if (localUser && localUser.email === user.email && localUser.lastname === user.lastname && localUser.givenName === user.firstname) return 'Sí'
      return 'No'
    },
    findLocalUserByEmail (email) {
      return Object.values(this.localUsers).find((user) => {
        return user.email === email
      })
    },
    changeLocalUser () {
      console.log('TODO change local user')
    },
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
    async remove (user) {
      let res = await this.$confirm('Els usuaris esborrar no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removing = user.id
        window.axios.delete('/api/v1/moodle/users/' + user.id).then(() => {
          this.dataUsers.splice(this.dataUsers.indexOf(user), 1)
          this.$snackbar.showMessage('Usuari esborrat correctament')
          this.removing = null
        }).catch(error => {
          this.$snackbar.showError(error)
          this.removing = null
        })
      }
    }
  }
}
</script>
