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
                                    <v-list-tile-title>
                                        <a v-if="this.googleUser" target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + this.googleUser.id"> {{ this.googleUser.primaryEmail }}</a>
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
                                    <v-list-tile-title v-if="this.moodleUser">
                                        <a target="_blank" :href="'https://www.iesebre.com/moodle/user/profile.php?id=' + this.moodleUser.id"> {{ this.moodleUser.id }}</a>
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
export default {
  name: 'UserPhotoAvatar',
  components: {
    'user-avatar': UserAvatar
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    googleUser: {
      type: Object
    },
    moodleUser: {
      type: Object
    }
  },
  methods: {
    avatarSaved (path) {
      this.$emit('avatarSaved', path)
    }
  }
}
</script>
