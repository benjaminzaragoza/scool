<template>
    <span>
        <v-toolbar color="blue darken-3">
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
                          <v-flex xs9>
                               <v-layout>
                                   <v-flex xs4>
                                       <department-select
                                               v-model="selectedDepartment"
                                               :departments="departments"
                                       ></department-select>
                                   </v-flex>
                                   <v-flex xs4>
                                       <family-select
                                               v-model="selectedFamily"
                                               :families="families"
                                       ></family-select>
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
                <template slot="items" slot-scope="{item: study}">
                    <tr :id="'study_row_' + study.id">
                        <td class="text-xs-left" v-text="study.id"></td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="study" field="code" label="Codi" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="study" field="name" label="Nom" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="study" field="shortname" label="Nom curt" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left">
                            <study-department :study="study" :departments="departments" @assigned="refresh"></study-department>
                        </td>
                        <td class="text-xs-left">
                            <study-family :study="study" :families="families" @assigned="refresh"></study-family>
                        </td>
                        <td class="text-xs-left">
                            <study-tags @refresh="refresh(false)" :study="study" :tags="dataTags" ></study-tags>
                        </td>
                        <td class="text-xs-left" v-html="study.formatted_created_at_diff" :title="study.formatted_created_at"></td>
                        <td class="text-xs-left" :title="study.formatted_updated_at">{{study.formatted_updated_at_diff}}</td>
                        <td class="text-xs-left">
                            <changelog-loggable :loggable="study"></changelog-loggable>
                            <fullscreen-dialog
                                    v-model="showDialog"
                                    title="Mostra l'estudi"
                                    :resource="study"
                                    v-if="showDialog === false || showDialog === study.id">
                                <study-show :study="study" @close="showDialog = false" :tags="dataTags" :families="families" :departments="departments"></study-show>
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
import StudyTags from './StudyTagsComponent'
import StudyFamily from './StudyFamily'
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
  // open: function (incidents) {
  //   return incidents ? incidents.filter(function (incident) {
  //     return incident.closed_at === null
  //   }) : []
  // },
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
    'department-select': DepartmentSelectComponent
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
    departments: {
      type: Array,
      required: true
    },
    families: {
      type: Array,
      required: true
    },
    study: {
      type: Object,
      default: function () {
        return undefined
      }
    },
    tags: {
      type: Array,
      required: true
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
