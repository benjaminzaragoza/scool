<template>
    <span>
    <v-btn
            fab
            bottom
            right
            color="pink"
            dark
            fixed
            @click.stop="dialog = !dialog"
    >
        <v-icon>add</v-icon>
    </v-btn>

        <!--
        TODO ESBORRAR

        1) Crear usuari (user_type teacher i rol teacher)
        2) Nom (people). Assignar usuari a les dades personals
          - Fullname (sn1, sn2, GivenName) es pot proposar a partir del nom complet/nom usuari
        3) Assignar Rol Teacher
        4) Assignar un Job a l'usuari (omplir taula employee: user_id<->job). Els jobs ja s'han donat d'alta a Jobs
        5) Assignar codi de professor (calcula la màquina) i departament
        6) Resta dades personals -> Link a la perfil dades personals (opcional)
        7) Resta dades professor -> Link a Fitxa de professor (opcional)
        -->
    <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog = false">
        <v-toolbar color="blue darken-3">
            <v-toolbar-title class="white--text title">Afegir professor</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon class="white--text">
                <v-icon>settings</v-icon>
            </v-btn>
            <v-btn icon class="white--text">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card>
            <v-stepper v-model="step" vertical>
                <v-stepper-step :complete="step > 1" step="1">
                  Usuari
                  <small>Creació usuari (nom, email, paraula de pas)</small>
                </v-stepper-step>
                <v-stepper-content step="1">
                  <v-card class="mb-5">
                      <user-add-form
                              @created="userCreated"
                              :user-type="TEACHER_TYPE"
                              role="Teacher">
                      </user-add-form>
                  </v-card>
                </v-stepper-content>
                <v-stepper-step :complete="step > 2" step="2">Assignar lloc de treball (plaça)</v-stepper-step>
                <v-stepper-content step="2">
                    <assign-job-to-user
                            :jobs="jobs"
                            :user="user && user.id"
                            @assigned="jobAssigned"
                            @back="step = 1"></assign-job-to-user>
                </v-stepper-content>
                <v-stepper-step :complete="step > 3" step="3">Dades professor</v-stepper-step>
                <v-stepper-content step="3">
                  <v-card class="mb-5">
                      <assign-teacher-info-to-user
                              :user="user && user.id"
                              @back="step = 2"
                              :administrative-statuses="administrativeStatuses"
                              :specialties="specialties"
                              :departments="departments"
                              :job="employee && employee.job_id"
                              :jobs="jobs"
                      ></assign-teacher-info-to-user>
                  </v-card>
                </v-stepper-content>
                <v-stepper-step step="4">Dades personals</v-stepper-step>
                <v-stepper-content step="4">
                  <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                  <v-btn color="primary" @click.native="step = 1">Continuar</v-btn>
                  <v-btn @click.native="step = 4" flat>Cancel</v-btn>
                </v-stepper-content>
                <v-stepper-step step="5">Fitxa del professor</v-stepper-step>
                <v-stepper-content step="5">
                  <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                  <v-btn color="primary" @click.native="step = 1">Continuar</v-btn>
                  <v-btn flat>Cancel</v-btn>
                </v-stepper-content>
              </v-stepper>
        </v-card>
    </v-dialog>
    </span>
</template>

<script>
  import UserAddForm from '../users/UserAddFormComponent'
  import AssignJobToUser from '../jobs/AssignJobToUserComponent'
  import AssignTeacherInfoToUser from '../teachers/AssignTeacherInfoToUserComponent'

  export default {
    name: 'TeacherAddComponent',
    components: {
      'user-add-form': UserAddForm,
      'assign-job-to-user': AssignJobToUser,
      'assign-teacher-info-to-user': AssignTeacherInfoToUser
    },
    data () {
      return {
        dialog: false,
        step: 1,
        user: null,
        employee: null
      }
    },
    props: {
      jobs: {
        type: Array,
        required: true
      },
      specialties: {
        type: Array,
        required: true
      },
      administrativeStatuses: {
        type: Array,
        required: true
      },
      departments: {
        type: Array,
        required: true
      }
    },
    methods: {
      userCreated (user) {
        this.user = user
        this.step = 2
      },
      jobAssigned (employee) {
        this.employee = employee
        this.step = 3
      }
    },
    created () {
      this.TEACHER_TYPE = 1
    }
  }
</script>
