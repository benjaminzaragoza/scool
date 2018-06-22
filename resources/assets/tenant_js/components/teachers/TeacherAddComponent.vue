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

        1) Crear usuari
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
                      <user-add-form @created="step = 2"></user-add-form>
                  </v-card>
                </v-stepper-content>
                <v-stepper-step :complete="step > 2" step="2">Configure analytics for this app</v-stepper-step>
                <v-stepper-content step="2">
                  <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                  <v-btn color="primary" @click.native="step = 3">Continuar</v-btn>
                  <v-btn flat @click.native="step = 1">Endarrera</v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="step > 3" step="3">Select an ad format and name ad unit</v-stepper-step>
                <v-stepper-content step="3">
                  <v-card color="grey lighten-1" class="mb-5" height="200px"></v-card>
                  <v-btn color="primary" @click.native="step = 4">Continuar</v-btn>
                  <v-btn flat>Cancel</v-btn>
                </v-stepper-content>
                <v-stepper-step step="4">View setup instructions</v-stepper-step>
                <v-stepper-content step="4">
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

  export default {
    name: 'TeacherAddComponent',
    components: {
      'user-add-form': UserAddForm
    },
    data () {
      return {
        dialog: false,
        step: 1
      }
    }
  }
</script>
