<template>
    <span>
        <user-avatar class="mr-2" :hash-id="user.hashid"
                     :alt="user.name"
                     v-if="user.hashid"
                     v-for="user in dataExistingUsers"
                     :key="user.id"
                     @click="remove(user)"
        ></user-avatar>

        <v-progress-circular
                size="16"
                v-if="removing"
                indeterminate
                color="primary"
        ></v-progress-circular>
        <template v-if="role">
            <v-btn v-role="role" icon flat color="teal" class="text--white ma-0" @click="showAddDialog">
              <v-icon>add</v-icon>
            </v-btn>
        </template>

        <v-dialog v-model="userAddDialog" max-width="500px">
          <v-card>
            <v-card-text>

            <users-select
                    v-model="newUser"
                    :users="dataUsers"
            >
            </users-select>
          </v-card-text>
            <v-card-actions>
            <v-btn flat link @click="userAddDialog=false">Tancar</v-btn>
            <v-btn color="success" flat @click="add" :loading="adding" :disabled="adding || newUser === null">Assignar</v-btn>
          </v-card-actions>
          </v-card>
        </v-dialog>
    </span>
</template>

<script>
import UsersSelect from './UsersSelectComponent'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'UserAvatarList',
  components: {
    'users-select': UsersSelect,
    'user-avatar': UserAvatar
  },
  data () {
    return {
      newUser: null,
      userAddDialog: false,
      dataExistingUsers: this.existingUsers,
      dataUsers: []
    }
  },
  props: {
    adding: {
      type: Boolean
    },
    removing: {
      type: Boolean
    },
    users: {
      type: Array,
      required: false
    },
    existingUsers: {
      type: Array,
      default: () => {
        return []
      }
    },
    role: {
      type: String,
      default: null
    }
  },
  computed: {
    storeUsers () {
      return this.$store.getters.users
    }
  },
  watch: {
    users (users) {
      this.dataUsers = users
    },
    existingUsers (existingUsers) {
      this.dataExistingUsers = existingUsers
    },
    adding (adding) {
      if (!adding && this.userAddDialog) {
        this.userAddDialog = false
      }
    }
  },
  methods: {
    add () {
      this.$emit('added', this.newUser)
    },
    async remove (user) {
      let res = await this.$confirm('Segur que voleu treure aquest usuari del càrrec?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.$emit('deleted', user)
      }
    },
    showAddDialog () {
      this.userAddDialog = true
    }
  },
  created () {
    this.dataUsers = this.users ? this.users : this.storeUsers
  }
}
</script>
