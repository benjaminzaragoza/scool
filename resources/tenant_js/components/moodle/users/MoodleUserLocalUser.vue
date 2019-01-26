<template>
    <span>
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
            Cap
            <v-btn small @click="associateLocalUser()"
                   icon color="success" title="No hi ha cap usuari local amb aquestes dades. Feu clic si el voleu importar de Moodle">
                <v-icon small>add</v-icon>
            </v-btn>
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
      type: Object,
      required: true
    }
  },
  methods: {
    associateLocalUser () {
      console.log('TODO associateLocalUser')
    }
  }
}
</script>
