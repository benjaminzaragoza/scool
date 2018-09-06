<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-toolbar color="blue darken-3">
                    <v-menu bottom>
                        <v-btn slot="activator" icon dark>
                            <v-icon>more_vert</v-icon>
                        </v-btn>

                        <v-list>
                            <v-list-tile>
                                <v-list-tile-title>Llençol de professors</v-list-tile-title>
                            </v-list-tile>
                        </v-list>
                    </v-menu>
                    <v-toolbar-title class="white--text title">Professors</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="settings">
                        <v-icon>settings</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text" @click="refresh">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
                                <administrative-status-select
                                        :administrative-statuses="administrativeStatuses"
                                        v-model="administrativeStatus"
                                ></administrative-status-select>
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
                                    :items="filteredTeachers"
                                    :search="search"
                                    item-key="id"
                                    disable-initial-sort
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi han dades disponibles"
                                    rows-per-page-text="Professors per pàgina"
                            >
                                <template slot="items" slot-scope="{ item: teacher }">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ teacher.id }}
                                        </td>
                                        <td class="text-xs-left">{{ teacher.code }}</td>
                                        <td class="text-xs">
                                            <user-avatar :hash-id="teacher.hashid"
                                                         :alt="teacher.fullname"
                                            ></user-avatar>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="teacher.email" v-html="teacher.fullname "></span>
                                        </td>
                                        <td class="text-xs-left">
                                            <a :href="'https://admin.google.com/u/3/ac/users/' + teacher.gsuite_id" target="_blank"> TODO {{ teacher.email }}</a>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="teacher.department" v-html="teacher.department_code"></span>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="teacher.specialty" v-html="teacher.specialty_code"></span>
                                        </td>
                                        <td class="text-xs-left">
                                            <span :title="teacher.family" v-html="teacher.family_code"></span>
                                        </td>
                                        <td class="text-xs-left" v-if="showStatusHeader">
                                            <span :title="teacher.administrative_status" v-html="teacher.administrative_status_code"></span>
                                        </td>

                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <span :title="teacher.job_description" v-html="teacher.job"></span>
                                        </td>

                                        <td class="text-xs-left" v-if="showSubstituteHeaders" v-html="teacher.job_start_at"></td>
                                        <td class="text-xs-left" v-if="showSubstituteHeaders" v-html="teacher.job_end_at"></td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ teacher.formatted_created_at_diff }}</span>
                                                <span>{{ teacher.formatted_created_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <v-tooltip bottom>
                                                <span slot="activator">{{ teacher.formatted_updated_at_diff }}</span>
                                                <span>{{ teacher.formatted_updated_at }}</span>
                                            </v-tooltip>
                                        </td>
                                        <td class="text-xs-left">
                                            <show-teacher-icon :teacher="teacher" :teachers="teachers"></show-teacher-icon>

                                            <v-btn icon class="mx-0" @click="editItem(teacher)">
                                                <v-icon color="teal">edit</v-icon>
                                            </v-btn>
                                            <confirm-icon
                                                    :id="'teacher_remove_' + teacher.code"
                                                    icon="delete"
                                                    color="pink"
                                                    :working="removing"
                                                    @confirmed="remove(teacher)"
                                                    tooltip="Eliminar"
                                                    message="Esteu segurs que voleu eliminar totes les dades associades al professor?"
                                            ></confirm-icon>
                                            <model-docs :media="teacher.media"></model-docs>
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
  import * as mutations from '../../store/mutation-types'
  import * as actions from '../../store/action-types'
  import ShowTeacherIcon from './ShowTeacherIconComponent.vue'
  import AdministrativeStatusSelect from './AdministrativeStatusSelectComponent.vue'
  import ConfirmIcon from '../ui/ConfirmIconComponent.vue'
  import axios from 'axios'
  import withSnackbar from '../mixins/withSnackbar'
  import UserAvatar from '../ui/UserAvatarComponent'

  export default {
    mixins: [withSnackbar],
    components: {
      'show-teacher-icon': ShowTeacherIcon,
      'administrative-status-select': AdministrativeStatusSelect,
      'confirm-icon': ConfirmIcon,
      'user-avatar': UserAvatar
    },
    data () {
      return {
        administrativeStatus: {},
        showDeletePendingTeacherDialog: false,
        search: '',
        deleting: false,
        removing: false
      }
    },
    computed: {
      ...mapGetters({
        internalTeachers: 'teachers'
      }),
      filteredTeachers: function () {
        if (this.showStatusHeader) return this.internalTeachers
        return this.internalTeachers.filter(teacher => teacher.administrative_status_id === this.administrativeStatus.id)
      },
      headers () {
        let headers = []
        headers.push({text: 'Id', align: 'left', value: 'id'})
        headers.push({text: 'Codi', value: 'code'})
        headers.push({text: 'Foto', value: 'full_search', sortable: false})
        headers.push({text: 'Nom', value: 'fullname'})
        headers.push({text: 'GSuite', value: 'fullname'})
        headers.push({text: 'Departament', value: 'department_code'})
        headers.push({text: 'Especialitat', value: 'specialty_code'})
        headers.push({text: 'Familia', value: 'family_code'})
        if (this.showStatusHeader) headers.push({text: 'Estatus', value: 'administrative_status_code'})
        headers.push({text: 'Plaça', value: 'job'})
        if (this.showSubstituteHeaders) {
          headers.push({text: 'Data inici', value: 'job_start_at'})
          headers.push({text: 'Data fí', value: 'job_end_at'})
        }
        headers.push({text: 'Data creació', value: 'formatted_created_at'})
        headers.push({text: 'Data actualització', value: 'formatted_updated_at'})
        headers.push({text: 'Accions', sortable: false})
        return headers
      },
      showStatusHeader () {
        if (this.administrativeStatus != null && Object.keys(this.administrativeStatus).length !== 0) return false
        return true
      },
      showSubstituteHeaders () {
        if (this.administrativeStatus != null && Object.keys(this.administrativeStatus).length !== 0 && this.administrativeStatus.code === 'SUBSTITUT') return true
        return false
      }
    },
    props: {
      teachers: {
        type: Array,
        required: true
      },
      administrativeStatuses: {
        type: Array,
        required: true
      }
    },
    methods: {
      refresh () {
        this.$store.dispatch(actions.GET_TEACHERS).catch(error => {
          this.showError(error)
        })
      },
      settings () {
        console.log('settings TODO') //TODO
      },
      remove (teacher) {
        this.removing = true
        axios.delete('/api/v1/approved_teacher/' + teacher.user_id).then(response => {
          this.removing = false
          this.$store.commit(mutations.DELETE_TEACHER, teacher)
        }).catch(error => {
          this.removing = false
          console.log(error)
        })
      },
      teacherDescription (teacherCode) {
        let teacher = this.teachers.find(teacher => { return teacher.code === teacherCode })
        if (teacher) return teacher.user.name + ' (' + teacher.code + ')'
        return ''
      }
    },
    created () {
      this.$store.commit(mutations.SET_TEACHERS, this.teachers)
    }
  }
</script>
