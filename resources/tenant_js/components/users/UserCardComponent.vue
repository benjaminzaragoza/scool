<template>
  <div>
    <v-card color="grey lighten-5" hover raised class="mb-1">
      <v-layout>
        <v-flex xs1 class="align-self-center ma-2">
          <user-avatar :hash-id="user.hashid"
                       :alt="user.name"
          ></user-avatar>
        </v-flex>
        <v-flex xs10>
          <v-card-title primary-title class="grey--text">
            <div class="font-weight-black">{{ user.name }}</div>
            <div>{{ user.email }}</div>
          </v-card-title>
        </v-flex>
      </v-layout>
      <v-btn
              title="Afegir usuari"
              small
              dark
              fab
              absolute
              bottom
              right
              color="red lighten-3"
      >
        <v-icon>remove</v-icon>
      </v-btn>
    </v-card>
  </div>
</template>
<script>
import UserAvatar from '../ui/UserAvatarComponent'
export default {
  components: {
    'user-avatar': UserAvatar
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>
  .align-self-center {
    align-self: center;
  }
</style>
