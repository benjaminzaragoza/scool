<template>
    <span>
        <v-toolbar color="blue darken-3">
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile href="/jobs/sheet_holders" target="_blank">
                        <v-list-tile-title>Llençol de places amb titulars</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/jobs/sheet_active_users" target="_blank">
                        <v-list-tile-title>TODO 1</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/jobs/sheet_substitutes" target="_blank">
                        <v-list-tile-title>TODO 2</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Incidències</v-toolbar-title>
            <v-spacer></v-spacer>

            <fullscreen-dialog
                    :flat="false"
                    class="white--text"
                    icon="settings"
                    v-model="settingsDialog"
                    color="blue darken-3"
                    title="Canviar la configuració de les incidències">
                        <settings module="incidents" title="Configuració del mòdul d'incidències" @close="settingsDialog = false"></settings>
            </fullscreen-dialog>

            <v-btn id="incidents_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>

        <v-card>
            <v-card-title>
                <v-layout>
                  <v-flex xs9 style="align-self: flex-end;">
                      <v-layout>
                          <v-flex xs3 class="text-sm-left" style="align-self: center;">
                                <span @click="showOpenIncidents" :class="{ bolder: filter === 'open', 'no-wrap': true }">
                                    <v-icon color="error" title="Obertes">lock_open</v-icon> Obertes: {{openIncidents ? openIncidents.length : 0}}
                                </span>
                                <span @click="showClosedIncidents" :class="{ bolder: filter === 'closed', 'no-wrap': true }">
                                  <v-icon color="success" title="Tancades">lock</v-icon> Tancades: {{closedIncidents ? closedIncidents.length : 0}}
                                </span>
                                <span @click="showAll" :class="{ bolder: filter === 'all', 'no-wrap': true }">
                                  <v-icon color="primary" title="Total">info</v-icon> Total: {{dataIncidents ? dataIncidents.length : 0}}
                                </span>
                          </v-flex>
                          <v-flex xs9>
                               <v-layout>
                                   <v-flex xs4>
                                       <user-select
                                               label="Creada per:"
                                               :users="creators"
                                               v-model="creator"
                                       ></user-select>
                                   </v-flex>
                                   <v-flex xs4>
                                       <user-select
                                               label="Assignada a:"
                                               :users="assignees"
                                               v-model="assignee"
                                       ></user-select>
                                   </v-flex>
                                   <v-flex xs4>
                                       <v-select
                                               v-model="selectedTags"
                                               :items="tags"
                                               attach
                                               chips
                                               label="Etiquetes"
                                               multiple
                                       ></v-select>
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
            <v-data-table
                    class="px-0 mb-2 hidden-sm-and-down"
                    :headers="headers"
                    :items="filteredIncidents"
                    :search="search"
                    item-key="id"
                    no-results-text="No s'ha trobat cap registre coincident"
                    no-data-text="No hi han dades disponibles"
                    rows-per-page-text="Incidències per pàgina"
                    :rows-per-page-items="[5,10,25,50,100,200,{'text':'Tots','value':-1}]"
                    :pagination.sync="pagination"
                    :loading="refreshing"
            >
                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                <template slot="items" slot-scope="{item: incident}">
                    <tr :id="'incident_row_' + incident.id">
                        <td class="text-xs-left" v-text="incident.id"></td>
                        <td class="text-xs-left" :title="incident.user_email">
                            <user-avatar class="mr-2" :hash-id="incident.user.hashid"
                                         :alt="incident.user.name"
                                         v-if="incident.user.hashid"
                            ></user-avatar>
                            {{incident.user_name}}
                        </td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="incident" field="subject" label="Títol" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left">
                            <inline-text-area-edit-dialog v-model="incident" :marked="false" field="description" label="Descripció" @save="refresh"></inline-text-area-edit-dialog>
                        </td>
                        <td v-if="filter!=='open'" v-html="incident.formatted_closed_at_diff" class="text-xs-left" :title="incident.formatted_closed_at"></td>
                        <td class="text-xs-left" v-html="incident.formatted_created_at_diff" :title="incident.formatted_created_at"></td>
                        <td class="text-xs-left" :title="incident.formatted_updated_at">{{incident.formatted_updated_at_diff}}</td>
                        <td class="text-xs-left">
                            <fullscreen-dialog
                                    :badge="incident.comments && incident.comments.length"
                                    badge-color="teal"
                                    icon="chat_bubble_outline"
                                    color="teal"
                                    v-model="addCommentDialog"
                                    title="Afegir un comentari"
                                    :resource="incident"
                                    v-if="addCommentDialog === false || addCommentDialog === incident.id">
                                <incident-show :show-data="false" :incident="incident" v-role="'Incidents'" @close="addCommentDialog = false"></incident-show>
                            </fullscreen-dialog>
                            <fullscreen-dialog
                                    v-model="showDialog"
                                    title="Mostra la incidència"
                                    :resource="incident"
                                    v-if="showDialog === false || showDialog === incident.id">
                                <incident-show :incident="incident" v-role="'Incidents'" @close="showDialog = false"></incident-show>
                            </fullscreen-dialog>
                            <incident-close v-model="incident" v-if="$can('close',incident)" @toggle="refresh"></incident-close>
                            <incident-delete :incident="incident" v-if="$hasRole('IncidentsManager')"></incident-delete>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>
    </span>
</template>

<script>
import * as actions from '../../store/action-types'
import * as mutations from '../../store/mutation-types'
import IncidentCloseComponent from './IncidentCloseComponent'
import IncidentShowComponent from './IncidentShowComponent'
import IncidentDeleteComponent from './IncidentDeleteComponent'
import Settings from '../ui/SettingsComponent'
import InlineTextFieldEditDialog from '../ui/InlineTextFieldEditDialog'
import InlineTextAreaEditDialog from '../ui/InlineTextAreaEditDialog'
import FullScreenDialog from '../ui/FullScreenDialog'
import UserSelect from '../users/UsersSelectComponent.vue'
import UserAvatar from '../ui/UserAvatarComponent'

var filters = {
  all: function (incidents) {
    return incidents
  },
  open: function (incidents) {
    return incidents ? incidents.filter(function (incident) {
      return incident.closed_at === null
    }) : []
  },
  closed: function (incidents) {
    return incidents ? incidents.filter(function (incident) {
      return incident.closed_at !== null
    }) : []
  }
}

export default {
  name: 'IncidentsList',
  components: {
    'fullscreen-dialog': FullScreenDialog,
    'incident-show': IncidentShowComponent,
    'incident-close': IncidentCloseComponent,
    'incident-delete': IncidentDeleteComponent,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog,
    'inline-text-area-edit-dialog': InlineTextAreaEditDialog,
    'user-select': UserSelect,
    'user-avatar': UserAvatar,
    'settings': Settings
  },
  data () {
    return {
      search: '',
      refreshing: false,
      showDialog: false,
      addCommentDialog: false,
      settingsDialog: false,
      pagination: {
        rowsPerPage: 25
      },
      filter: 'open',
      selectedTags: [],
      tags: ['Tag1', 'Tag2', 'Tag3'],
      creator: null,
      assignee: null,
      assignees: [{
        id: 1,
        hashid: 'MX',
        name: 'Sergi Tur',
        email: 'sergiturbadenas@gmail.com'
      },
      {
        id: 2,
        hashid: 'RX',
        name: 'Dolors Sanjuan',
        email: 'dolors@iesebre.com'
      },
      {
        id: 3,
        hashid: 'MX',
        name: 'Pepa Parda',
        email: 'pepaparda@gmail.com'
      }]
    }
  },
  props: {
    incidents: {
      type: Array,
      default: function () {
        return undefined
      }
    }
  },
  computed: {
    creators () {
      let creators = this.dataIncidents ? this.dataIncidents.map(incident => {
        return {
          id: incident.user_id,
          name: incident.user_name,
          email: incident.user_email,
          hashid: incident.user && incident.user.hashid
        }
      }) : []
      if (window.user && window.user.id) {
        return this.moveLoggedUserToFirstPosition(creators)
      }
      return creators
    },
    dataIncidents () {
      return this.$store.getters.incidents
    },
    openIncidents () {
      return this.dataIncidents && this.dataIncidents.filter(incident => incident.closed_at === null)
    },
    closedIncidents () {
      return this.dataIncidents && this.dataIncidents.filter(incident => incident.closed_at !== null)
    },
    filteredIncidents: function () {
      let filteredByState = filters[this.filter](this.dataIncidents)
      if (this.creator) return filteredByState.filter(incident => { return incident.user_id === this.creator })
      return filteredByState
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id', width: '1%' })
      // if (this.showJobTypeHeader) {
      //   headers.push({text: 'Tipus', value: 'type'})
      // }
      headers.push({ text: 'Usuari', value: 'user_name' })
      headers.push({ text: 'Títol', value: 'subject' })
      headers.push({ text: 'Description', value: 'description' })
      if (this.filter !== 'open') headers.push({ text: 'Tancada', value: 'closed_at_timestamp' })
      headers.push({ text: 'Creada', value: 'created_at_timestamp' })
      headers.push({ text: 'Última modificació', value: 'updated_at_timestamp' })
      // user_email is added as value to allow searching by email!
      headers.push({ text: 'Accions', value: 'user_email', sortable: false })
      return headers
    }
  },
  methods: {
    moveLoggedUserToFirstPosition (users) {
      let loggedUser = users.find(creator => {
        return creator.id === window.user.id
      })
      users.splice(users.indexOf(loggedUser), 1)
      users.unshift(loggedUser)
      return users
    },
    showClosedIncidents () {
      this.filter = 'closed'
    },
    showOpenIncidents () {
      this.filter = 'open'
    },
    showAll () {
      this.filter = 'all'
    },
    settings () {
      console.log('TODO settings')
    },
    refresh () {
      this.fetch()
    },
    fetch () {
      this.refreshing = true
      this.$store.dispatch(actions.SET_INCIDENTS).then(response => {
        this.$snackbar.showMessage('Incidències actualitzades correctament')
        this.refreshing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.refreshing = false
      })
    }
  },
  created () {
    if (this.incidents === undefined) this.fetch()
    else this.$store.commit(mutations.SET_INCIDENTS, this.incidents)
    this.filters = Object.keys(filters)
  }
}
</script>

<style scoped>
    .bolder {
        font-weight: bold;
    }
    .no-wrap {
        white-space: nowrap;
    }
</style>
