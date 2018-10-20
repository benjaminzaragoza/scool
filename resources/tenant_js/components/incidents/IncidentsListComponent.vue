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
            >
                <template slot="items" slot-scope="{item: incident}">
                    <tr :id="'incident_row_' + incident.id">
                        <td class="text-xs-left" v-html="incident.id"></td>
                        <td class="text-xs-left" :title="incident.user_email" v-html="incident.username"></td>
                        <td class="text-xs-left" v-html="incident.subject"></td>
                        <td class="text-xs-left" v-html="incident.description"></td>
                        <td class="text-xs-left" v-html="incident.formatted_closed_at"></td>
                        <td class="text-xs-left">
                            <incident-close :incident="incident"></incident-close>
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

export default {
  name: 'IncidentsList',
  components: {
    'incident-close': IncidentCloseComponent
  },
  data () {
    return {
      search: '',
      refreshing: false
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
      headers.push({ text: 'Id', align: 'left', value: 'id' })
      // if (this.showJobTypeHeader) {
      //   headers.push({text: 'Tipus', value: 'type'})
      // }
      headers.push({ text: 'Usuari', value: 'user_id' })
      headers.push({ text: 'Títol', value: 'subject' })
      headers.push({ text: 'Description', value: 'description' })
      headers.push({ text: 'Tancada', value: 'closed_at_timestamp' })
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
