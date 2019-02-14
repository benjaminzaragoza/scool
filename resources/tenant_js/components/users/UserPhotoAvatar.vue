<template>
    <span>
        <v-card>
            <v-container grid-list-lg fluid>
                <v-layout row wrap>
                    <v-flex xs2>
                        <template v-if="user">
                            <user-avatar :hash-id="this.user.hashid"
                                         :alt="this.user.name"
                                         :user="this.user"
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
                                            {{ this.user.name }}
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Nom</v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            {{ this.user.email }}
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
                                    <v-list-tile-title >
                                        <template v-if="dataGoogleUser">
                                            <a v-if="!is_empty(dataGoogleUser)" target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + dataGoogleUser.id"> {{ dataGoogleUser.primaryEmail }}</a>
                                            <template v-else>
                                                <v-progress-circular :size="15" indeterminate color="primary"></v-progress-circular>
                                                Esperant les dades de l'usuari de Google
                                            </template>
                                        </template>
                                        <template v-else>
                                            Sense usuari Google
                                        </template>
                                    </v-list-tile-title>
                                    <v-list-tile-sub-title>Usuari de Google</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <v-list-tile-title >
                                        <template v-if="dataLdapUser">
                                            <span v-if="!is_empty(dataLdapUser)">
                                                LDAP CN TODO
                                            </span>
                                            <template v-else>
                                                <v-progress-circular :size="15" indeterminate color="primary"></v-progress-circular>
                                                Esperant les dades de l'usuari Ldap
                                            </template>
                                        </template>
                                        <template v-else>
                                            Sense usuari Ldap
                                        </template>
                                    </v-list-tile-title>
                                    <v-list-tile-sub-title>Usuari de Ldap</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile>
                                <v-list-tile-content>
                                    <v-list-tile-title >
                                        <template v-if="dataMoodleUser">
                                            <a v-if="!is_empty(dataMoodleUser)" target="_blank" :href="'https://www.iesebre.com/moodle/user/profile.php?id=' + dataMoodleUser.id"> {{ dataMoodleUser.id }}</a>
                                            <template v-else>
                                                <v-progress-circular :size="15" indeterminate color="primary"></v-progress-circular>
                                                Esperant les dades de l'usuari de Moodle
                                            </template>
                                        </template>
                                        <template v-else>
                                            Sense usuari Moodle
                                        </template>
                                    </v-list-tile-title>
                                    <v-list-tile-sub-title>Usuari de Moodle</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-card>
        <v-btn flat @click="$emit('close')">
            <v-icon class="mr-2">exit_to_app</v-icon>Tancar
        </v-btn>
        <v-btn flat @click="$emit('back')">
            <v-icon class="mr-2">arrow_back</v-icon>Endarrera
        </v-btn>
        <v-btn color="primary" @click="$emit('forward')">
            <v-icon class="mr-2">arrow_forward</v-icon>Següent
        </v-btn>
    </span>
</template>

<script>
import UserAvatar from '../ui/UserAvatarComponent'
import helpers from '../../utils/helpers'
export default {
  name: 'UserPhotoAvatar',
  components: {
    'user-avatar': UserAvatar
  },
  data () {
    return {
      dataGoogleUser: this.googleUser,
      dataLdapUser: this.ldapUser,
      dataMoodleUser: this.moodleUser
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    googleUser: {},
    ldapUser: {},
    moodleUser: {}
  },
  watch: {
    googleUser (googleUser) {
      this.dataGoogleUser = googleUser
    },
    ldapUser (ldapUser) {
      this.dataLdapUser = ldapUser
    },
    moodleUser (moodleUser) {
      this.dataMoodleUser = moodleUser
    }
  },
  methods: {
    avatarSaved (path) {
      this.$emit('avatarSaved', path)
    },
    is_empty (object) {
      return helpers.is_empty(object)
    }
  }
}
</script>
