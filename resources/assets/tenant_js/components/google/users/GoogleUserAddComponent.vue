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
    <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="close">
        <v-toolbar color="blue darken-3">
            <v-btn icon dark @click.native="close">
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title class="white--text title">Afegir usuari de Google</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon class="white--text">
                <v-icon>exit</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card>
            <v-stepper v-model="step" vertical>
                <v-stepper-step :complete="step > 1" step="1">
                  Dades de l'usuari
                  <small>Nom, Cognom, correu electrònic, descripció</small>
                </v-stepper-step>
                <v-stepper-content step="1">
                  <v-card class="mb-5">
                      <google-user-add-form @created="userCreated"></google-user-add-form>
                  </v-card>
                </v-stepper-content>
                <v-stepper-step :complete="step > 2" step="2">Dades de l'usuari</v-stepper-step>
                <v-stepper-content step="2">
                    <template v-if="user">
                    Google Suite User link: <a target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + user.id"> {{ user.primaryEmail }}</a>
                    </template>
                    <v-btn @click="close">Tancar</v-btn>
                    <v-btn color="error" @click="step = 1">Endarrera</v-btn>
                </v-stepper-content>
            </v-stepper>

        </v-card>
    </v-dialog>
    </span>
</template>

<script>
  import GoogleUserAddForm from './GoogleUserAddFormComponent'
  export default {
    name: 'GoogleUserAddComponent',
    components: {
      'google-user-add-form': GoogleUserAddForm
    },
    data () {
      return {
        dialog: false,
        step: 1,
        user: null
      }
    },
    methods: {
      close () {
        this.step = 1
        this.dialog = false
      },
      userCreated (user) {
        this.user = user
        this.step = 2
      }
    }
  }
</script>
