<template>
    <v-card class="elevation-3">
        <v-card-text>
            Escolliu un rol/s per afegir a l'usuari
            <user-avatar :hash-id="user.hashid"
                         :alt="user.name"
                         :user="user"
            ></user-avatar>
            <span class="font-weight-medium">{{ user.name }}</span>:
            <roles-select :exclude="user.roles" v-model="selectedRoles"></roles-select>
            Rols actuals de l'usuari:
            <user-roles-list :user="user" :roles="user.roles"></user-roles-list>
        </v-card-text>
        <v-card-actions v-if="dialog">
            <v-btn color="primary" @click="add" :disabled="loading || this.selectedRoles.length === 0" :loading="loading">
                <v-icon>add</v-icon> Afegir</v-btn>
            <v-btn flat @click="close">
                <v-icon small>close</v-icon> Sortir</v-btn>
        </v-card-actions>
        <v-card-actions v-else>
            <v-btn flat @click="close">
                <v-icon>exit_to_app</v-icon>
                Tancar
            </v-btn>
            <v-btn flat @click="$emit('back')">
                <v-icon class="mr-2">arrow_back</v-icon>
                Endarrera
            </v-btn>
            <v-btn flat @click="$emit('forward')" v-if="forwardButton">
                <v-icon class="mr-2">arrow_forward</v-icon>
                Següent
            </v-btn>
            <v-btn color="primary" @click="add" :disabled="loading || this.selectedRoles.length === 0" :loading="loading">
                <v-icon>add</v-icon> Assignar rol/s
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import UserRolesList from './UserRolesList'
import RolesSelect from './RolesSelect'
import UserAvatar from '../../ui/UserAvatarComponent'

export default {
  name: 'UserRolesManage',
  components: {
    'roles-select': RolesSelect,
    'user-roles-list': UserRolesList,
    'user-avatar': UserAvatar
  },
  data () {
    return {
      loading: false,
      selectedRoles: []
    }
  },
  props: {
    forwardButton: {
      type: Boolean,
      required: false
    },
    user: {
      type: Object,
      required: true
    },
    dialog: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    close () {
      this.$emit('close')
    },
    add () {
      this.loading = true
      window.axios.post('/api/v1/user/' + this.user.id + '/role/multiple', {
        'roles': this.selectedRoles.map(role => role.id)
      }).then(() => {
        this.$snackbar.showMessage('Rol/s afegit/s correctament')
        this.$emit('added', this.selectedRoles)
        this.selectedRoles.forEach(role => {
          this.user.roles.push({
            'name': role.name,
            'guard_name': role.guard_name
          })
        })
        this.selectedRoles = []
        this.loading = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    }
  }
}
</script>
