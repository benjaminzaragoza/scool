<template>
    <span>
    <v-btn
            fab
            bottom
            right
            color="accent"
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
            <v-toolbar-title class="white--text title">Afegir usuari</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon class="white--text">
                <v-icon>exit</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card>
            <v-stepper v-model="step" vertical>
                <v-stepper-step :complete="step > 1" step="1">
                  Dades de l'usuari
                  <small>Nom, Cognom, correu electrònic...</small>
                </v-stepper-step>
                <v-stepper-content step="1">
                  <v-card class="mb-5">
                      <user-add-form @created="userCreated" @googleUsercreated="googleUserCreated" :users="users"></user-add-form>
                  </v-card>
                </v-stepper-content>
                <v-stepper-step :complete="step > 2" step="2">Avatar</v-stepper-step>
                <v-stepper-content step="2">
                    <template>
                        <v-card>
                            <v-container grid-list-lg fluid>
                                <v-layout row wrap>
                                     <v-flex xs2>
                                         <template v-if="user">
                                              <user-avatar :hash-id="user.hashid"
                                                           :alt="user.name"
                                                           :user="user"
                                                           :editable="true"
                                                           :removable="true"
                                                           size="64"
                                              ></user-avatar>
                                             <span class="ml-2">Avatar (feu clic per canviar-lo)</span>
                                             <v-divider class="mt-3 mb-3"></v-divider>
                                             <v-list>
                                                <v-subheader>Usuari</v-subheader>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title>
                                                            {{ user.name }}
                                                        </v-list-tile-title>
                                                        <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                                <v-list-tile>
                                                    <v-list-tile-content>
                                                        <v-list-tile-title>
                                                            {{ user.email }}
                                                        </v-list-tile-title>
                                                        <v-list-tile-sub-title>Email</v-list-tile-sub-title>
                                                    </v-list-tile-content>
                                                </v-list-tile>
                                            </v-list>

                                         </template>
                                        <template v-else>
                                            <v-progress-circular indeterminate color="primary"></v-progress-circular> Carregant...
                                        </template>
                                     </v-flex>
                                     <v-flex xs8>
                                        <v-list two-line>
                                            <v-subheader>Comptes externes</v-subheader>
                                            <v-list-tile>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>
                                                        <a v-if="googleUser" target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + googleUser.id"> {{ googleUser.primaryEmail }}</a>
                                                        <template v-else>
                                                            <v-progress-circular indeterminate color="primary"
                                                            ></v-progress-circular>
                                                            Esperant les dades de l'usuari de Google
                                                        </template>
                                                    </v-list-tile-title>
                                                    <v-list-tile-sub-title>Email corporatiu (Google)</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            <v-list-tile>
                                                <v-list-tile-content>
                                                    <v-list-tile-title>
                                                        cn=Bla bla bla,dc=iesebre,dc=com()TODO
                                                    </v-list-tile-title>
                                                    <v-list-tile-sub-title>Ldap cn(TODO)</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                            <v-list-tile>
                                                <v-list-tile-content>
                                                    <v-list-tile-title v-if="user">
                                                        <a target="_blank" :href="'https://www.iesebre.com/moodle/user/profile.php?id='"> {{ user.name }}</a>
                                                    </v-list-tile-title>
                                                    <template v-else>
                                                            <v-progress-circular indeterminate color="primary"></v-progress-circular> Carregant...
                                                        </template>
                                                    <v-list-tile-sub-title>Usuari de moodle</v-list-tile-sub-title>
                                                </v-list-tile-content>
                                            </v-list-tile>
                                    </v-list>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                         </v-card>
                    </template>
                    <v-btn @click="close">Tancar</v-btn>
                    <v-btn color="error" @click="step = 1">Endarrera</v-btn>
                    <v-btn color="error" @click="step = 3">Següent</v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="step > 3" step="3">Dades Personals</v-stepper-step>
                <v-stepper-content step="3">
                    TODO Dades personals

                    <v-btn @click="close">Tancar</v-btn>
                    <v-btn color="error" @click="step = 2">Endarrera</v-btn>
                    <v-btn color="error" @click="step = 4">Següent</v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="step > 4" step="4">Assignar rol</v-stepper-step>

                <v-stepper-content step="4">
                    TODO assignar rol
                    <v-btn @click="close">Tancar</v-btn>
                    <v-btn color="error" @click="step = 2">Endarrera</v-btn>
                    <v-btn color="error" @click="step = 4">Següent</v-btn>
                </v-stepper-content>

            </v-stepper>
        </v-card>
    </v-dialog>
    </span>
</template>

<script>
import UserAddForm from './UserAddFormComponent'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'UserAddComponent',
  components: {
    'user-add-form': UserAddForm,
    'user-avatar': UserAvatar
  },
  data () {
    return {
      dialog: false,
      step: 1,
      user: null,
      googleUser: null
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    }
  },
  methods: {
    close () {
      this.step = 1
      this.dialog = false
      this.user = null
      this.googleUser = null
    },
    userCreated (user) {
      this.user = user
      this.step = 2
    },
    googleUserCreated (user) {
      this.googleUser = user
    }
  }
}
</script>
