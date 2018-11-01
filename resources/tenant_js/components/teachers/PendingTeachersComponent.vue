<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-toolbar color="blue darken-3">
                    <v-toolbar-title class="white--text title">Professors pendents</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-btn icon class="white--text" @click="settings">
                        <v-icon>settings</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text" @click="refresh">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                    <v-btn icon class="white--text" @click="minimized = false" v-if="minimized">
                        <v-icon>add</v-icon>
                    </v-btn>
                    <v-btn v-else icon class="white--text" @click="minimized = true">
                        <v-icon>minimize</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-card v-if="!minimized">
                    <v-card-text class="px-0 mb-2">
                        <v-card>
                            <v-card-title>
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
                                    :items="internalPendingTeachers"
                                    :search="search"
                                    item-key="id"
                                    no-results-text="No s'ha trobat cap registre coincident"
                                    no-data-text="No hi ha cap professor pendent de confirmar"
                                    rows-per-page-text="Professors per pàgina"
                            >
                                <template slot="items" slot-scope="{ item: teacher }">
                                    <tr>
                                        <td class="text-xs-left">
                                            {{ teacher.id }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ teacher.specialty && teacher.specialty.code }}
                                        </td>
                                        <td class="text-xs-left">
                                            {{ teacher.sn1 }} {{ teacher.sn2 }}, {{ teacher.name }}
                                        </td>
                                        <td class="text-xs-left">{{ teacher.email }}</td>
                                        <td class="text-xs-left">{{ teacher.mobile }}</td>
                                        <td class="text-xs-left" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <user-avatar :hash-id="teacher.teacher_hashid"
                                                         :alt="teacher.sn1 + ' ' + teacher.sn2 + ', ' + teacher.name"
                                            ></user-avatar>
                                        </td>
                                        <td class="text-xs-left">{{ teacher.start_date }}</td>
                                        <td class="text-xs-left">{{ teacher.created_at }}</td>
                                        <td class="text-xs-left">{{ teacher.updated_at }}</td>
                                        <td class="text-xs-left">
                                            <show-pending-teacher-icon
                                                    :pending-teacher="teacher"
                                                    :jobs="jobs"
                                                    :teachers="teachers"
                                                    :specialties="specialties"
                                                    :forces="forces"
                                                    :administrative-statuses="administrativeStatuses">
                                            ></show-pending-teacher-icon>

                                            <confirm-icon
                                                  :id="'pending_teacher_remove_' + teacher.email.replace('@', '_').replace('.', '_')"
                                                  icon="delete"
                                                  color="pink"
                                                  :working="removing"
                                                  @confirmed="remove(teacher)"
                                                  tooltip="Eliminar"
                                                  message="Esteu segurs que voleu eliminar aquesta proposta de nou professor?"
                                            ></confirm-icon>
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

<style>

</style>

<script>
import { mapGetters } from 'vuex'
import * as mutations from '../../store/mutation-types'
import * as actions from '../../store/action-types'
import ShowPendingTeacherIcon from './ShowPendingTeacherIconComponent.vue'
import withSnackbar from '../mixins/withSnackbar'
import ConfirmIcon from '../ui/ConfirmIconComponent.vue'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  components: {
    'show-pending-teacher-icon': ShowPendingTeacherIcon,
    'confirm-icon': ConfirmIcon,
    'user-avatar': UserAvatar
  },
  mixins: [withSnackbar],
  data () {
    return {
      minimized: true,
      removing: false,
      search: '',
      deleting: false,
      headers: [
        { text: 'Id', align: 'left', value: 'id' },
        { text: 'Especialitat', value: 'specialty.code' },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Mòbil', value: 'mobile' },
        { text: 'Substitueix', value: 'teacher_id' },
        { text: 'Data incorporació', value: 'start_date' },
        { text: 'Data creació', value: 'formatted_created_at' },
        { text: 'Data actualització', value: 'formatted_updated_at' },
        { text: 'Accions', sortable: false }
      ]
    }
  },
  computed: {
    ...mapGetters({
      internalPendingTeachers: 'pendingTeachers'
    })
  },
  props: {
    teachers: {
      type: Array,
      required: true
    },
    jobs: {
      type: Array,
      required: true
    },
    pendingTeachers: {
      type: Array,
      required: true
    },
    specialties: {
      type: Array,
      required: true
    },
    forces: {
      type: Array,
      required: true
    },
    administrativeStatuses: {
      type: Array,
      required: true
    }
  },
  methods: {
    settings () {
      // TODO: Settings like configure people will recieve notifications about pending teachers
      // Links to add to welcome emails, etc.
      console.log('TODO SETTINGS')
    },
    refresh () {
      this.$store.dispatch(actions.GET_PENDING_TEACHERS).catch(error => {
        this.showError(error)
      })
    },
    remove (teacher) {
      this.removing = true
      this.$store.dispatch(actions.REMOVE_PENDING_TEACHER, teacher).then(response => {
        this.removing = false
        this.showMessage('El professor pendent ha estat esborrat correctament')
      }).catch(error => {
        console.log(error)
        this.removing = false
        this.showError(error)
      })
    }
  },
  created () {
    this.$store.commit(mutations.SET_PENDING_TEACHERS, this.pendingTeachers)
    if (this.pendingTeachers.length > 0) {
      this.minimized = false
    }
  }
}
</script>
