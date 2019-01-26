<template>
    <span>
        <v-tooltip bottom>
            <v-btn
                    slot="activator"
                    small
                    flat
                    @click="associateLocalUser()"
                    icon
                    class="ma-0"
                    color="success" >
                <v-icon small>edit</v-icon>
            </v-btn>
            <span>Associar nou o canviar usuari local</span>
        </v-tooltip>
        <template v-if="user.localUser">
            <user-avatar
                    class="mr-2" :hash-id="user.localUser.hash_id"
                    :alt="user.localUser.name"
            ></user-avatar><br/>
            <v-tooltip bottom>
                <span slot="activator">{{ user.localUser.email }}</span>
                <span>{{ user.localUser.id +  ' - ' + user.localUser.name }}</span>
            </v-tooltip>
        </template>
        <template v-else>
            <v-tooltip bottom >
                <v-btn
                       slot="activator"
                       small
                       flat
                       @click="importMoodleUserToLocalUser()"
                       icon
                       color="primary"
                       class="ma-0">
                    <v-icon small>add</v-icon>
                </v-btn>
                <span>No hi ha cap usuari local amb aquestes dades. Feu clic si el voleu importar de Moodle</span>
            </v-tooltip>
        </template>
    </span>
</template>

<script>
import UserAvatar from '../../ui/UserAvatarComponent'

export default {
  name: 'MoodleUserLocalUser',
  components: {
    'user-avatar': UserAvatar
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    localUsers: {
      type: Array,
      required: true
    }
  },
  methods: {
    associateLocalUser () {
      console.log('TODO associateLocalUser')
    },
    importMoodleUserToLocalUser () {
      console.log('TODO importMoodleUserToLocalUser')
    }
  }
}
</script>
