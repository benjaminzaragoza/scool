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
            <v-toolbar-title class="white--text title">Unitats formatives</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn id="subjects_help_button" icon class="white--text" href="http://docs.scool.cat/docs/curriculum" target="_blank">
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
                        <!--<subjects-settings module="incidents" @close="settingsDialog = false" :incident-users="incidentUsers" :manager-users="managerUsers"></subjects-settings>-->
            </fullscreen-dialog>

            <v-btn id="subjects_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
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
                                   <v-flex xs7>
                                       <study-select v-model="selectedStudy"></study-select>
                                   </v-flex>
                                   <v-flex xs5>
                                       <subject-groups-select v-model="selectedSubjectGroup" :study="selectedStudy"></subject-groups-select>
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
                    :items="filteredSubjects"
                    :search="search"
                    item-key="id"
                    no-results-text="No s'ha trobat cap registre coincident"
                    no-data-text="No hi han dades disponibles"
                    rows-per-page-text="UFs per pàgina"
                    :rows-per-page-items="[5,10,25,50,100,200,{'text':'Tots','value':-1}]"
                    :pagination.sync="pagination"
                    :loading="refreshing"
            >
                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                <template slot="items" slot-scope="{item: subject}">
                    <tr :id="'subject_row_' + subject.id">
                        <td class="text-xs-left" v-text="subject.id"></td>
                        <td class="text-xs-left" v-text="subject.number"></td>
                        <td class="text-xs-left" v-text="subject.study_code" :title="subject.study_id + ' ' + subject.study_name"></td>
                        <td class="text-xs-left" v-text="subject.subject_group_code" :title="subject.subject_group_id + ' ' + subject.subject_group_name"></td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="subject" field="code" label="Codi" @save="refresh"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="subject" field="name" label="Nom" @save="refresh" class-name="limit150"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left">
                            <inline-text-field-edit-dialog v-model="subject" field="shortname" label="Nom curt" @save="refresh" class-name="limit100"></inline-text-field-edit-dialog>
                        </td>
                        <td class="text-xs-left" v-text="subject.hours"></td>
                        <td class="text-xs-left"> {{subject.start }} | {{ subject.end }}</td>
                        <td class="text-xs-left" v-html="subject.formatted_created_at_diff" :title="subject.formatted_created_at"></td>
                        <td class="text-xs-left" :title="subject.formatted_updated_at">{{subject.formatted_updated_at_diff}}</td>
                        <td class="text-xs-left">
                            <changelog-loggable :loggable="subject"></changelog-loggable>
                            <fullscreen-dialog
                                    v-model="showDialog"
                                    title="Mostra l'estudi"
                                    :resource="subject"
                                    v-if="showDialog === false || showDialog === subject.id">
                                <subject-show :subject="subject" @close="showDialog = false"></subject-show>
                            </fullscreen-dialog>
                            <subject-delete :subject="subject" v-if="$hasRole('CurriculumManager')"></subject-delete>
                        </td>
                    </tr>
                </template>
            </v-data-table>
        </v-card>

    </span>
</template>

<script>
import FullScreenDialog from '../../ui/FullScreenDialog'
import SubjectDelete from './SubjectDeleteComponent'
import SubjectShowComponent from './SubjectShowComponent'
import SubjectDepartment from './SubjectDepartment'
import SubjectFamily from './SubjectFamily'
import StudySelect from '../studies/StudySelect'
import SubjectGroupsSelect from '../subjectGroups/SubjectGroupsSelect'

import InlineTextFieldEditDialog from '../../ui/InlineTextFieldEditDialog'
import ChangelogLoggable from '../../changelog/ChangelogLoggable'
import * as actions from '../../../store/action-types'

var filters = {
  all: function (subjects) {
    return subjects
  }
}

export default {
  name: 'SubjectsListComponent',
  components: {
    'fullscreen-dialog': FullScreenDialog,
    'subject-delete': SubjectDelete,
    'subject-show': SubjectShowComponent,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog,
    'subject-department': SubjectDepartment,
    'subject-family': SubjectFamily,
    'changelog-loggable': ChangelogLoggable,
    'study-select': StudySelect,
    'subject-groups-select': SubjectGroupsSelect
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
      selectedSubjectGroup: null,
      showDialog: false
    }
  },
  computed: {
    dataSubjects () {
      return this.$store.getters.subjects
    },
    filteredSubjects () {
      let filteredByState = filters[this.filter](this.dataSubjects)
      if (this.selectedStudy) filteredByState = filteredByState.filter(subject => { return subject.study_id === this.selectedStudy.id })
      if (this.selectedSubjectGroup) filteredByState = filteredByState.filter(subject => { return subject.subject_group_id === this.selectedSubjectGroup.id })
      return filteredByState
    },
    headers () {
      let headers = []
      headers.push({ text: 'Id', align: 'left', value: 'id', width: '1%' })
      headers.push({ text: '#', value: 'number', width: '1%' })
      headers.push({ text: 'Estudi', align: 'study_code', value: 'study_code' })
      headers.push({ text: 'Mòdul', align: 'subject_code', value: 'subject_code' })
      headers.push({ text: 'Codi', value: 'code' })
      headers.push({ text: 'Nom', value: 'name' })
      headers.push({ text: 'Nom curt', value: 'shortname' })
      headers.push({ text: 'Hores', value: 'hours', width: '1%' })
      headers.push({ text: 'Dates', value: 'start' })
      headers.push({ text: 'Creada', value: 'created_at_timestamp' })
      headers.push({ text: 'Última modificació', value: 'updated_at_timestamp' })
      headers.push({ text: 'Accions', value: 'user_email', sortable: false })
      return headers
    }
  },
  props: {
    subject: {
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
      this.$store.dispatch(actions.SET_SUBJECTS).then(response => {
        if (message) this.$snackbar.showMessage('Unitats Formatives actualitzades correctament')
        this.refreshing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.refreshing = false
      })
    }
  },
  created () {
    this.filters = Object.keys(filters)
    if (this.subject) {
      this.showDialog = this.subject.id
      this.filter = 'all'
    }
  }
}
</script>
