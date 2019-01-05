<template>
    <span>
        <v-toolbar dense color="blue darken-3">
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile href="#" target="_blank">
                        <v-list-tile-title>TODO 0 Estadístiques</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Càrrecs</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn id="positions_help_button" icon class="white--text" href="http://docs.scool.cat/docs/positions" target="_blank">
                <v-icon>help</v-icon>
            </v-btn>

            <fullscreen-dialog
                    v-role="'PositionsManager'"
                    :flat="false"
                    class="white--text"
                    icon="settings"
                    v-model="settingsDialog"
                    color="blue darken-3"
                    title="Canviar la configuració">
                        <!--<positions-settings module="incidents" @close="settingsDialog = false" :incident-users="incidentUsers" :manager-users="managerUsers"></positions-settings>-->
            </fullscreen-dialog>

            <v-btn id="positions_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>

        <v-card>
            <v-card-title>
                <v-layout>
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
                    class="hidden-sm-and-down"
                    :headers="headers"
                    :items="filteredPositions"
                    :search="search"
                    item-key="id"
                    no-results-text="No s'ha trobat cap registre coincident"
                    no-data-text="No hi han dades disponibles"
                    rows-per-page-text="Càrrecs per pàgina"
                    :rows-per-page-items="[5,10,25,50,100,200,{'text':'Tots','value':-1}]"
                    :pagination.sync="pagination"
                    :loading="refreshing"
            >
                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                <template slot="headerCell" slot-scope="props" style="padding: 5px">
                     {{ props.header.text }}
                </template>
                <template slot="items" slot-scope="{item: position}">
                    <tr :id="'position_row_' + position.id">
                        <td class="text-xs-left cell" v-text="position.id"></td>
                        <td class="text-xs-left cell">
                            <inline-text-field-edit-dialog v-model="position" field="code" label="Codi" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left cell">
                            <inline-text-field-edit-dialog v-model="position" field="name" label="Nom" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left cell">
                            <inline-text-field-edit-dialog v-model="position" field="shortname" label="Nom curt" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left cell">
                            <position-users :position="position" @assigned="refresh" @unassigned="refresh"></position-users>
                        </td>
                        <td class="text-xs-left cell">
                            <position-resource @refresh="refresh(false)" :position="position"></position-resource>
                        </td>
                        <td class="text-xs-left cell" v-html="position.formatted_created_at_diff" :title="position.formatted_created_at"></td>
                        <td class="text-xs-left cell" :title="position.formatted_updated_at">{{position.formatted_updated_at_diff}}</td>
                        <td class="text-xs-left cell">
                            <changelog-loggable :loggable="position"></changelog-loggable>
                            <position-delete :position="position" v-if="$hasRole('PositionsManager')"></position-delete>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>

    </span>
</template>

<script>
import FullScreenDialog from '../ui/FullScreenDialog'
import InlineTextFieldEditDialog from '../ui/InlineTextFieldEditDialog'
import PositionDelete from './PositionDelete'
import PositionUsers from './PositionUsers'
import PositionResource from './PositionResource'
import ChangelogLoggable from '../changelog/ChangelogLoggable'
import * as actions from '../../store/action-types'
import * as mutations from '../../store/mutation-types'

var filters = {
  all: function (positions) {
    return positions
  }
}

export default {
  name: 'PositionsListComponent',
  components: {
    'fullscreen-dialog': FullScreenDialog,
    'position-delete': PositionDelete,
    'position-users': PositionUsers,
    'position-resource': PositionResource,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog,
    'changelog-loggable': ChangelogLoggable
  },
  data () {
    return {
      refreshing: false,
      settingsDialog: false,
      search: '',
      pagination: {
        rowsPerPage: 25
      },
      filter: 'all',
      // selectedFamily: null,
      showDialog: false
    }
  },
  computed: {
    dataPositions () {
      return this.$store.getters.positions
    },
    filteredPositions () {
      let filteredByState = filters[this.filter](this.dataPositions)
      // if (this.selectedFamily) filteredByState = filteredByState.filter(position => { return position.family_id === this.selectedFamily })
      return filteredByState
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id', width: '1%' })
      headers.push({ text: 'Codi', value: 'code' })
      headers.push({ text: 'Nom', value: 'name' })
      headers.push({ text: 'Nom curt', value: 'shortname' })
      headers.push({ text: 'Usuari/Usuaris', value: 'users' })
      headers.push({ text: 'A càrrec de', value: 'resource' })
      headers.push({ text: 'Creada', value: 'created_at_timestamp' })
      headers.push({ text: 'Última modificació', value: 'updated_at_timestamp' })
      headers.push({ text: 'Accions', value: 'user_email', sortable: false })
      return headers
    }
  },
  props: {
    positions: {
      type: Array,
      default: function () {
        return undefined
      }
    },
    position: {
      type: Object,
      default: function () {
        return undefined
      }
    }
  },
  methods: {
    refresh (message = true) {
      this.fetch(message)
    },
    fetch (message = true) {
      this.refreshing = true
      this.$store.dispatch(actions.SET_POSITIONS).then(response => {
        if (message) this.$snackbar.showMessage('Càrrecs actualitzats correctament')
        this.refreshing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.refreshing = false
      })
    }
  },
  created () {
    if (this.positions === undefined) this.fetch()
    else this.$store.commit(mutations.SET_POSITIONS, this.positions)
    this.filters = Object.keys(filters)
    if (this.position) {
      this.showDialog = this.position.id
      this.filter = 'all'
    }
  }
}
</script>
