<template>
    <span>
        <v-toolbar color="blue darken-3">
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile href="/curriculum" target="_blank">
                        <v-list-tile-title>Estudis</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/curriculum/subjects" target="_blank">
                        <v-list-tile-title>Unitats Formàtives</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/public/curriculum" target="_blank">
                        <v-list-tile-title>Veure currículum</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="#" target="_blank">
                        <v-list-tile-title>TODO 0 Estadístiques</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Mòduls Professionals</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn id="subject_groups_help_button" icon class="white--text" href="http://docs.scool.cat/docs/curriculum" target="_blank">
                <v-icon>help</v-icon>
            </v-btn>
            <fullscreen-dialog
                    v-role="'CurriculumManager'"
                    :flat="false"
                    class="white--text"
                    icon="settings"
                    v-model="settingsDialog"
                    color="blue darken-3"
                    title="Canviar la configuració">
                <!--// TODO-->
                <!--<subject-groups-settings module="incidents" @close="settingsDialog = false" :incident-users="incidentUsers" :manager-users="managerUsers"></subject-groups-settings>-->
            </fullscreen-dialog>
            <v-btn id="subject_groups_refresh_button" icon class="white--text" @click="refresh(true)" :loading="refreshing" :disabled="refreshing">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>

        <v-card>
            <v-card-title>
                <v-layout>
                  <v-flex xs9 style="align-self: flex-end;">
                      <v-layout>
                          <v-flex xs12>
                               <v-layout>
                                   <v-flex xs8>
                                       <study-select v-model="selectedStudy"></study-select>
                                   </v-flex>
                                   <v-flex xs4>
                                       <v-autocomplete
                                               v-model="selectedTags"
                                               :items="dataTags"
                                               attach
                                               chips
                                               label="Etiquetes"
                                               multiple
                                               item-value="id"
                                               item-text="value"
                                       >
                                            <template slot="selection" slot-scope="data">
                                                <v-chip
                                                        small
                                                        label
                                                        @input="data.parent.selectItem(data.item)"
                                                        :selected="data.selected"
                                                        class="chip--select-multi"
                                                        :color="data.item.color"
                                                        text-color="white"
                                                        :key="JSON.stringify(data.item)"
                                                ><v-icon small left v-text="data.item.icon"></v-icon>{{ data.item.value }}</v-chip>
                                            </template>
                                            <template slot="item" slot-scope="data">
                                                <v-checkbox v-model="data.tile.props.value"></v-checkbox>
                                                <v-chip small label :title="data.item.description" :color="data.item.color" text-color="white">
                                                    <v-icon small left v-text="data.item.icon"></v-icon>{{ data.item.value }}
                                                </v-chip>
                                            </template>
                                       </v-autocomplete>
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
                    :items="filteredSubjectGroups"
                    :search="search"
                    item-key="id"
                    no-results-text="No s'ha trobat cap registre coincident"
                    no-data-text="No hi han dades disponibles"
                    rows-per-page-text="MPs per pàgina"
                    :rows-per-page-items="[5,10,25,50,100,200,{'text':'Tots','value':-1}]"
                    :pagination.sync="pagination"
                    :loading="refreshing"
            >
                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                <template slot="items" slot-scope="{item: subjectGroup}">
                    <tr :id="'subjectGroup_row_' + subjectGroup.id">
                        <td class="text-xs-left" v-text="subjectGroup.id"></td>
                        <td class="text-xs-left" v-text="subjectGroup.number"></td>
                        <td class="text-xs-left" v-text="subjectGroup.study_code" :title="subjectGroup.study_id + ' ' + subjectGroup.study_name"></td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="subjectGroup" field="code" label="Codi" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="subjectGroup" field="name" label="Nom" @save="refresh" class-name="limit150"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="subjectGroup" field="shortname" label="Nom curt" @save="refresh" class-name="limit100"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left" v-text="subjectGroup.hours"></td>
                        <td class="text-xs-left"> {{subjectGroup.start }} | {{ subjectGroup.end }}</td>
                        <td class="text-xs-left">
                            <subject-groups-tags @refresh="refresh(false)" :subjectGroup="subjectGroup" :tags="dataTags" ></subject-groups-tags>
                        </td>
                        <td class="text-xs-left" v-html="subjectGroup.formatted_created_at_diff" :title="subjectGroup.formatted_created_at"></td>
                        <td class="text-xs-left" :title="subjectGroup.formatted_updated_at">{{subjectGroup.formatted_updated_at_diff}}</td>
                        <td class="text-xs-left">
                            <changelog-loggable :loggable="subjectGroup"></changelog-loggable>
                            <fullscreen-dialog
                                    v-model="showDialog"
                                    title="Mostra el mòdul professional"
                                    :resource="subjectGroup"
                                    v-if="showDialog === false || showDialog === subjectGroup.id">
                                <subject-group-show :subject-group="subjectGroup" @close="showDialog = false"></subject-group-show>
                            </fullscreen-dialog>
                            <subject-group-delete :subject-group="subjectGroup" v-if="$hasRole('CurriculumManager')" @removed="updateStudies"></subject-group-delete>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>

    </span>
</template>

<script>
import FullScreenDialog from '../../ui/FullScreenDialog'
import SubjectGroupDeleteComponent from './SubjectGroupDeleteComponent'
import SubjectGroupShowComponent from './SubjectGroupShowComponent'
import StudySelect from '../studies/StudySelect'

import InlineTextFieldEditDialog from '../../ui/InlineTextFieldEditDialog'
import ChangelogLoggable from '../../changelog/ChangelogLoggable'
import * as actions from '../../../store/action-types'
import SubjectGroupsTags from './SubjectGroupsTags'

var filters = {
  all: function (subjects) {
    return subjects
  }
}

export default {
  name: 'SubjectGroupsListComponent',
  components: {
    'fullscreen-dialog': FullScreenDialog,
    'subject-group-delete': SubjectGroupDeleteComponent,
    'subject-group-show': SubjectGroupShowComponent,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog,
    'changelog-loggable': ChangelogLoggable,
    'study-select': StudySelect,
    'subject-groups-tags': SubjectGroupsTags
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
      selectedStudy: null,
      showDialog: false,
      selectedTags: [],
    }
  },
  computed: {
    dataTags () {
      return this.$store.getters.subjectGroupTags
    },
    dataSubjectGroups () {
      return this.$store.getters.subjectGroups
    },
    filteredSubjectGroups () {
      let filteredByState = filters[this.filter](this.dataSubjectGroups)
      if (this.selectedStudy) filteredByState = filteredByState.filter(subjectGroup => { return subjectGroup.study_id === this.selectedStudy.id })
      if (this.selectedTags.length > 0) {
        filteredByState = filteredByState.filter(subjectGroup => {
          return subjectGroup.tags.some(tag => this.selectedTags.includes(tag.id))
        })
      }
      return filteredByState
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id', width: '1%' })
      headers.push({ text: '#', value: 'number', width: '1%' })
      headers.push({ text: 'Estudi', align: 'study_code', value: 'study_code' })
      headers.push({ text: 'Codi', value: 'code' })
      headers.push({ text: 'Nom', value: 'name' })
      headers.push({ text: 'Nom curt', value: 'shortname' })
      headers.push({ text: 'Hores', value: 'hours', width: '1%' })
      headers.push({ text: 'Dates', value: 'start' })
      headers.push({ text: 'Etiquetes', value: 'tags' })
      headers.push({ text: 'Creada', value: 'created_at_timestamp' })
      headers.push({ text: 'Última modificació', value: 'updated_at_timestamp' })
      headers.push({ text: 'Accions', value: 'user_email', sortable: false })
      return headers
    }
  },
  props: {
    subjectGroup: {
      type: Object,
      default: function () {
        return undefined
      }
    }
  },
  methods: {
    updateStudies () {
      this.$store.dispatch(actions.SET_STUDIES)
    },
    refresh (message = true) {
      this.fetch(message)
    },
    fetch (message = true) {
      this.refreshing = true
      this.$store.dispatch(actions.SET_SUBJECT_GROUPS).then(response => {
        if (message) this.$snackbar.showMessage('Mòduls professionals actualitzats correctament')
        this.refreshing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.refreshing = false
      })
    }
  },
  created () {
    this.filters = Object.keys(filters)
    if (this.subjectGroup) {
      this.showDialog = this.subjectGroup.id
      this.filter = 'all'
    }
  }
}
</script>
