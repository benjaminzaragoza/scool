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
        <v-toolbar color="primary">
            <v-btn icon dark @click.native="close">
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title class="white--text">Afegir usuari</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon class="white--text">
                <v-icon>exit</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card>
            <v-stepper v-model="step" vertical>
                <v-stepper-step :complete="step > 1" step="1">
                  Dades de l'usuari
                  <small>Nom, Cognoms, correu electrònic...</small>
                </v-stepper-step>
                <v-stepper-content step="1">
                  <v-card class="mb-5">
                      <user-add-form
                              @created="userCreated"
                              @googleUserCreated="googleUserCreated"
                              @moodleUserCreated="moodleUserCreated"
                              :users="users"
                              :userTypes="userTypes"></user-add-form>
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
                                                           @input="avatarSaved"
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
                                                    <v-list-tile-title v-if="moodleUser">
                                                        <a target="_blank" :href="'https://www.iesebre.com/moodle/user/profile.php?id=' + moodleUser.id"> {{ moodleUser.id }}</a>
                                                    </v-list-tile-title>
                                                    <template v-else>
                                                            <v-progress-circular indeterminate color="primary"></v-progress-circular>
                                                            Esperant les dades de l'usuari de Moodle
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
                    <v-btn flat @click="close">
                        <v-icon class="mr-2">exit_to_app</v-icon>Tancar
                    </v-btn>
                    <v-btn flat @click="step = 1">
                        <v-icon class="mr-2">arrow_back</v-icon>Endarrera
                    </v-btn>
                    <v-btn color="primary" @click="step = 3">
                        <v-icon class="mr-2">arrow_forward</v-icon>Següent
                    </v-btn>
                </v-stepper-content>
                <v-stepper-step :complete="step > 3" step="3">Dades Personals</v-stepper-step>
                <v-stepper-content step="3">
                    <user-add-person-form
                            :user="this.user"
                            @close="close"
                            @back="step=2"
                            @forward="step=4"></user-add-person-form>
                </v-stepper-content>
                <v-stepper-step :complete="step > 4" step="4">Assignar rol</v-stepper-step>

                <v-stepper-content step="4">
                    <user-roles-manage v-if="user" :user="user"
                                       @back="step=3"
                                       :step="4" @close="close" :dialog="false"></user-roles-manage>
                </v-stepper-content>

            </v-stepper>
        </v-card>
    </v-dialog>
    </span>
</template>

<script>
import UserAddForm from './UserAddFormComponent'
import UserAvatar from '../ui/UserAvatarComponent'
import UserRolesManage from './roles/UserRolesManage'
import UserAddPersonForm from '../people/UserAddPersonForm'
export default {
  name: 'UserAddComponent',
  components: {
    'user-add-form': UserAddForm,
    'user-avatar': UserAvatar,
    'user-roles-manage': UserRolesManage,
    'user-add-person-form': UserAddPersonForm
  },
  data () {
    return {
      dialog: false,
      step: 1,
      user: null,
      googleUser: null,
      moodleUser: null
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    },
    userTypes: {
      type: Array,
      required: true
    }
  },
  methods: {
    changeStep (step) {
      this.step = step
    },
    close () {
      this.step = 1
      this.dialog = false
      this.user = null
      this.googleUser = null
      this.moodleUser = null
    },
    userCreated (user) {
      this.user = user
      this.step = 2
      this.$emit('created', user)
    },
    googleUserCreated (user) {
      this.googleUser = user
      this.$emit('googleUsercreated', user)
    },
    moodleUserCreated (user) {
      this.moodleUser = user
      this.$emit('moodleUsercreated', user)
    },
    avatarSaved (path) {
      this.$emit('avatarSaved', path)
    }
  }
}
</script>
