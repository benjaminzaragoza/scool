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

        1) Crear usuari (user_type teacher i rol teacher) -> Crear també usuari a Google I Ldap centre
        1.1 google Suite
        1.2 Ldap centre
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
            <v-btn icon dark @click.native="dialog = false">
                <v-icon>close</v-icon>
            </v-btn>
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
                              :users="users"
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
                              @assigned="teacherAssigned"
                      ></assign-teacher-info-to-user>
                  </v-card>
                </v-stepper-content>
                <v-stepper-step step="4">Dades personals</v-stepper-step>
                <v-stepper-content step="4">
                  <v-card>
                      <ul>
                          <li>Tinc nomes givenName, sn1 i sn2 i per tant ja tin un person id</li>
                          <li>Dades imprescindibles: email i tlf mòbil (SMS i gent sense email)</li>
                          <li>Dades secundaries: Adreça i DNI</li>
                          <li>Altres dades: Data naixament, lloc de naixement, sexe, altres emails i tlfs, notes, esta civil</li>
                      </ul>
                  </v-card>
                    <v-btn color="success" @click.native="">Guardar canvis</v-btn>
                  <v-btn color="primary" @click.native="step = 5">Continuar</v-btn>
                  <v-btn @click.native="step = 3" flat>Cancel</v-btn>
                </v-stepper-content>
                <v-stepper-step step="5">Fitxa del professor</v-stepper-step>
                <v-stepper-content step="5">
                  <v-card>
                      Fitxa de professor (TODO)
                  </v-card>
                  <v-btn color="success" @click.native="">Guardar canvis</v-btn>
                  <v-btn color="primary" @click.native="step = 6">Continuar</v-btn>
                  <v-btn @click.native="step = 4" flat>Cancel</v-btn>
                </v-stepper-content>
                <v-stepper-step step="6">Finalitzar</v-stepper-step>
                <v-stepper-content step="6">
                  <v-card>
                      Resum i altres TODO
                      <ul>
                          <li>Tasques extres que es volen realitzar (checkbox per marcar quiens accions realitzar i quiens no):</li>
                          <li>Enviar email de benvinguda (inclou explicació com entrar a la app i com establir paraula de pas)</li>
                          <li>Documents a annexar amb email benvinguda:</li>
                          <li>Docs de PGQ amb info sobre el centre i la seva organització</li>
                          <li>Poder escollir altres docs?</li>
                          <li>Afegir el professorat a la llista email: claustre (Google Apps)</li>
                          <li>Afegir el professorat a la llista email: tutors (inteligent? Si a qui substitueix és tutor afegir?)</li>
                          <li>Afegir el professor a la llista email: departament que li pertoca</li>
                          <li>De fet potser cal un apartat extra per assignar càrrecs?</li>
                          <li>Integració amb altres aplicacions:</li>
                          <li>Crear compte Gmail/GSuite for Education</li>
                          <li>Ebre-escool?</li>
                          <li>Crear usuari Ldap (Moodle) i Samba (cal crear usuari amb totes les dades SAmba, colocar-lo al lloc adequat: professors)</li>
                          <li>GLPI: no caldria si està inclòs aquí nova app incidències</li>
                          <li>Avisar encarregat/s ( via email) tema fotos (Laureà)</li>
                          <li>https://github.com/Institut-Ebre/Maninfo/blob/master/profes.md</li>
                          <li>Sobre Moodle: Info sobre com aconseguir accés als cursos: NuriaBordes o responsable</li>
                          <li>IsoTools: Persona de contacte per demanar alta usuari: pot estar email benvinguda</li>
                      </ul>
                  </v-card>
                  <v-btn color="primary" @click.native="finish">Finalitzar</v-btn>
                  <v-btn @click.native="step = 5" flat>Tornar endarrera</v-btn>
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
  import withSnackbar from '../mixins/withSnackbar'
  import * as actions from '../../store/action-types'

  export default {
    name: 'TeacherAddComponent',
    components: {
      'user-add-form': UserAddForm,
      'assign-job-to-user': AssignJobToUser,
      'assign-teacher-info-to-user': AssignTeacherInfoToUser
    },
    mixins: [withSnackbar],
    data () {
      return {
        dialog: false,
        step: 1,
        user: null,
        employee: null,
        teacher: null
      }
    },
    watch: {
      dialog (newDialog) {
        if (newDialog === false) {
          this.$store.dispatch(actions.GET_TEACHERS).catch(error => {
            this.showError(error)
          })
        }
      }
    },
    props: {
      users: {
        type: Array,
        required: true
      },
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
      finish () {
        this.showMessage('Professor creat correctament')
        setTimeout(() => { this.dialog = false }, 1000);
        this.clear()
        this.dialog = false
      },
      clear () {
        this.step= 1,
        this.user= null,
        this.employee= null,
        this.teacher= null
      },
      userCreated (user) {
        this.user = user
        this.step = 2
      },
      jobAssigned (employee) {
        this.employee = employee
        this.step = 3
      },
      teacherAssigned (teacher) {
        this.teacher = teacher
        this.step = 4
      }
    },
    created () {
      this.TEACHER_TYPE = 1
    }
  }
</script>
