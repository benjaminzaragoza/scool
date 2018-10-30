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
            <v-btn icon class="white--text" @click="settings">
                <v-icon>settings</v-icon>
            </v-btn>
            <v-btn id="incidents_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>

        <v-card>
            <v-card-title>
                TODO Filters
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
                    :items="filteredIncidents"
                    :search="search"
                    item-key="id"
                    no-results-text="No s'ha trobat cap registre coincident"
                    no-data-text="No hi han dades disponibles"
                    rows-per-page-text="Incidències per pàgina"
                    :rows-per-page-items="[5,10,25,50,100,200,{'text':'Tots','value':-1}]"
                    :pagination.sync="pagination"
            >
                <template slot="items" slot-scope="{item: incident}">
                    <tr :id="'incident_row_' + incident.id">
                        <td class="text-xs-left" v-html="incident.id"></td>
                        <td class="text-xs-left" :title="incident.user_email" v-html="incident.user_name"></td>
                        <td>
                            <inline-text-field-edit-dialog v-model="incident" field="subject" label="Títol" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left" :title="incident.description">
                            <inline-text-area-edit-dialog v-model="incident" field="description" label="Descripció" @save="refresh"></inline-text-area-edit-dialog>
                        </td>
                        <td class="text-xs-left" v-html="incident.formatted_closed_at_diff" :title="incident.formatted_closed_at"></td>
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
                            <incident-close :incident="incident" v-can:close="incident"></incident-close>
                            <incident-delete :incident="incident" v-role="'IncidentsManager'"></incident-delete>
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
import InlineTextFieldEditDialog from '../ui/InlineTextFieldEditDialog'
import InlineTextAreaEditDialog from '../ui/InlineTextAreaEditDialog'
import FullScreenDialog from '../ui/FullScreenDialog'
export default {
  name: 'IncidentsList',
  components: {
    'fullscreen-dialog': FullScreenDialog,
    'incident-show': IncidentShowComponent,
    'incident-close': IncidentCloseComponent,
    'incident-delete': IncidentDeleteComponent,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog,
    'inline-text-area-edit-dialog': InlineTextAreaEditDialog
  },
  data () {
    return {
      search: '',
      refreshing: false,
      showDialog: false,
      addCommentDialog: false,
      pagination: {
        rowsPerPage: 25
      }
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
  watch: {
    incidents (newIncidents) {
      this.$store.commit(mutations.SET_INCIDENTS, newIncidents)
    }
  },
  computed: {
    filteredIncidents: function () {
      return this.$store.getters.incidents
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id', width: '1%' })
      // if (this.showJobTypeHeader) {
      //   headers.push({text: 'Tipus', value: 'type'})
      // }
      headers.push({ text: 'Usuari', value: 'user_id' })
      headers.push({ text: 'Títol', value: 'subject' })
      headers.push({ text: 'Description', value: 'description' })
      headers.push({ text: 'Tancada', value: 'closed_at_timestamp' })
      headers.push({ text: 'Creada', value: 'created_at_timestamp' })
      headers.push({ text: 'Última modificació', value: 'updated_at_timestamp' })
      headers.push({ text: 'Accions', sortable: false })
      return headers
    }
  },
  methods: {
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
  }
}
</script>
