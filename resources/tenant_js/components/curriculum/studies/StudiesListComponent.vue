<template>
    <span>
        <v-toolbar dense color="blue darken-3">
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile href="/curriculum/subjects" target="_blank">
                        <v-list-tile-title>Unitats Formatives</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/curriculum/subjectGroups" target="_blank">
                        <v-list-tile-title>Mòduls Professionals</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/public/curriculum" target="_blank">
                        <v-list-tile-title>Veure currículum</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="#" target="_blank">
                        <v-list-tile-title>TODO 0 Estadístiques</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Estudis</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn id="studies_help_button" icon class="white--text" href="http://docs.scool.cat/docs/curriculum" target="_blank">
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
                        <!--<studies-settings module="incidents" @close="settingsDialog = false" :incident-users="incidentUsers" :manager-users="managerUsers"></studies-settings>-->
            </fullscreen-dialog>

            <v-btn id="studies_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
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
                                   <v-flex xs5>
                                       <department-select
                                               v-model="selectedDepartment"
                                       ></department-select>
                                   </v-flex>
                                   <v-flex xs4>
                                       <family-select
                                               v-model="selectedFamily"
                                       ></family-select>
                                   </v-flex>
                                   <v-flex xs3>
                                       <study-tags-select
                                                v-model="selectedTags">
                                       </study-tags-select>
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
                    class="hidden-sm-and-down"
                    :headers="headers"
                    :items="filteredStudies"
                    :search="search"
                    item-key="id"
                    no-results-text="No s'ha trobat cap registre coincident"
                    no-data-text="No hi han dades disponibles"
                    rows-per-page-text="Estudis per pàgina"
                    :rows-per-page-items="[5,10,25,50,100,200,{'text':'Tots','value':-1}]"
                    :pagination.sync="pagination"
                    :loading="refreshing"
            >
                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                <template slot="headerCell" slot-scope="props" style="padding: 5px">
                     {{ props.header.text }}
                </template>
                <template slot="items" slot-scope="{item: study}">
                    <tr :id="'study_row_' + study.id">
                        <td class="text-xs-left cell" v-text="study.id"></td>
                        <td class="text-xs-left cell">
                            <inline-text-field-edit-dialog v-model="study" field="code" label="Codi" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left cell">
                            <inline-text-field-edit-dialog v-model="study" field="name" label="Nom" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left cell">
                            <inline-text-field-edit-dialog v-model="study" field="shortname" label="Nom curt" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left cell">
                            <study-department :study="study" @assigned="refresh"></study-department>
                        </td>
                        <td class="text-xs-left cell">
                            <study-family :study="study" @assigned="refresh"></study-family>
                        </td>
                        <td class="text-xs-left cell">
                            <study-tags @refresh="refresh(false)" :study="study"></study-tags>
                        </td>
                        <td class="text-xs-left cell">
                            <study-completed :study="study"></study-completed>
                        </td>
                        <td class="text-xs-left cell" v-html="study.formatted_created_at_diff" :title="study.formatted_created_at"></td>
                        <td class="text-xs-left cell" :title="study.formatted_updated_at">{{study.formatted_updated_at_diff}}</td>
                        <td class="text-xs-left cell">
                            <changelog-loggable :loggable="study"></changelog-loggable>
                            <study-subject-group-add :study="study"></study-subject-group-add>
                            <study-subject-add :study="study"></study-subject-add>
                            <study-public-curriculum-show :study="study"></study-public-curriculum-show>
                            <fullscreen-dialog
                                    v-model="showDialog"
                                    title="Mostra l'estudi"
                                    :resource="study"
                                    v-if="showDialog === false || showDialog === study.id">
                                <study-show :study="study" @close="showDialog = false"></study-show>
                            </fullscreen-dialog>
                            <study-delete :study="study" v-if="$hasRole('CurriculumManager')"></study-delete>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>

    </span>
</template>

<script>
import FullScreenDialog from '../../ui/FullScreenDialog'
import StudyDelete from './StudyDeleteComponent'
import StudyShowComponent from './StudyShowComponent'
import StudyDepartment from './StudyDepartment'
import StudyFamily from './StudyFamily'
import StudyPublicCurriculumShow from './StudyPublicCurriculumShow'
import StudyTags from './StudyTagsComponent'
import StudyTagsSelect from './StudyTagsSelect'
import StudySubjectGroupAdd from './StudySubjectGroupAdd'
import StudySubjectAdd from './StudySubjectAdd'
import StudyCompleted from './StudyCompleted'
import InlineTextFieldEditDialog from '../../ui/InlineTextFieldEditDialog'
import ChangelogLoggable from '../../changelog/ChangelogLoggable'
import DepartmentSelectComponent from '../departments/DepartmentsSelectComponent'
import FamilySelectComponent from '../families/FamilySelectComponent'
import * as actions from '../../../store/action-types'
import * as mutations from '../../../store/mutation-types'

var filters = {
  all: function (studies) {
    return studies
  }
}

export default {
  name: 'StudiesListComponent',
  components: {
    'fullscreen-dialog': FullScreenDialog,
    'study-delete': StudyDelete,
    'study-show': StudyShowComponent,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog,
    'study-department': StudyDepartment,
    'study-family': StudyFamily,
    'study-tags': StudyTags,
    'changelog-loggable': ChangelogLoggable,
    'family-select': FamilySelectComponent,
    'department-select': DepartmentSelectComponent,
    'study-public-curriculum-show': StudyPublicCurriculumShow,
    'study-tags-select': StudyTagsSelect,
    'study-subject-group-add': StudySubjectGroupAdd,
    'study-subject-add': StudySubjectAdd,
    'study-completed': StudyCompleted
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
      selectedTags: [],
      selectedDepartment: null,
      selectedFamily: null,
      dataTags: this.tags,
      showDialog: false
    }
  },
  computed: {
    dataStudies () {
      return this.$store.getters.studies
    },
    filteredStudies () {
      let filteredByState = filters[this.filter](this.dataStudies)
      if (this.selectedDepartment) filteredByState = filteredByState.filter(study => { return study.department_id === this.selectedDepartment })
      if (this.selectedFamily) filteredByState = filteredByState.filter(study => { return study.family_id === this.selectedFamily })
      if (this.selectedTags.length > 0) {
        filteredByState = filteredByState.filter(study => {
          return study.tags.some(tag => this.selectedTags.includes(tag.id))
        })
      }
      return filteredByState
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id', width: '1%' })
      headers.push({ text: 'Codi', value: 'code' })
      headers.push({ text: 'Nom', value: 'name' })
      headers.push({ text: 'Nom curt', value: 'shortname' })
      headers.push({ text: 'Departament', value: 'department_code' })
      headers.push({ text: 'Família', value: 'family_code' })
      headers.push({ text: 'Etiquetes', value: 'tags' })
      headers.push({ text: 'Completat', value: 'completed' })
      headers.push({ text: 'Creada', value: 'created_at_timestamp' })
      headers.push({ text: 'Última modificació', value: 'updated_at_timestamp' })
      headers.push({ text: 'Accions', value: 'user_email', sortable: false })
      return headers
    }
  },
  props: {
    studies: {
      type: Array,
      default: function () {
        return undefined
      }
    },
    study: {
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
      this.$store.dispatch(actions.SET_STUDIES).then(response => {
        if (message) this.$snackbar.showMessage('Estudis actualitzats correctament')
        this.refreshing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.refreshing = false
      })
    }
  },
  created () {
    if (this.studies === undefined) this.fetch()
    else this.$store.commit(mutations.SET_STUDIES, this.studies)
    this.filters = Object.keys(filters)
    if (this.study) {
      this.showDialog = this.study.id
      this.filter = 'all'
    }
  }
}
</script>

<style>
    .column {
        padding: 0 8px !important;
    }
    .cell {
        padding: 0 8px !important;
    }
</style>
