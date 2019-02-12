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
                    <user-photo-avatar
                            v-if="this.step === 2"
                            :user="this.user"
                            @close="close"
                            @back="step=1"
                            @forward="step=3"
                            :google="true"
                            :moodle="true"
                            :ldap="true"
                            :google-user="googleUser"
                            :moodle-user="moodleUser"
                            ></user-photo-avatar>
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
import UserRolesManage from './roles/UserRolesManage'
import UserAddPersonForm from '../people/UserAddPersonForm'
import UserPhotoAvatar from './UserPhotoAvatar'
export default {
  name: 'UserAddComponent',
  components: {
    'user-add-form': UserAddForm,
    'user-photo-avatar': UserPhotoAvatar,
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
    }
  }
}
</script>
