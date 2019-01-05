<template>
    <span>
        <v-chip small label :color="user.color" text-color="white" v-for="(user,key) in positionUsers" :key="user.id" close v-model="close[key]">
            <v-icon left v-text="user.icon"></v-icon>{{ user.value }}
        </v-chip>
        <v-progress-circular
                size="16"
                v-if="removing"
                indeterminate
                color="primary"
        ></v-progress-circular>
        <v-btn v-role="'PositionsManager'" icon flat color="teal" class="text--white ma-0" @click="showAddDialog">
          <v-icon>add</v-icon>
        </v-btn>
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
            <v-btn color="success" flat @click="add()" :loading="adding" :disabled="adding || newUser === null">Assignar</v-btn>
          </v-card-actions>
          </v-card>
        </v-dialog>
    </span>
</template>

<script>
import UsersSelect from '../users/UsersSelect'

export default {
  name: 'UserAvatarList',
  components: {
    'users-select': UsersSelect
  },
  data () {
    return {
      removing: false,
      newUser: null,
      adding: false,
      userAddDialog: false,
      userRemoveDialog: false,
      close: [],
      positionUsers: [],
      dataUsers: []
    }
  },
  props: {
    users: {
      type: Array,
      required: false
    },
    position: {
      type: Object,
      required: true
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
    position: {
      handler: function (newPosition) {
        this.sync(newPosition.users)
      },
      deep: true
    },
    close (close) {
      let userObjToRemove
      if (this.positionUsers) {
        userObjToRemove = this.positionUsers[close.indexOf(false)]
      }
      if (userObjToRemove && this.$hasRole('PositionsManager')) {
        this.remove(userObjToRemove)
      }
    }
  },
  methods: {
    add () {
      this.adding = true
      window.axios.post('/api/v1/positions/' + this.position.id + '/users/' + this.newUser.id, {}).then(() => {
        this.$emit('refresh')
        this.adding = false
        this.userAddDialog = false
        this.newUser = null
      }).catch(error => {
        this.$snackbar.showError(error)
        this.adding = false
      })
    },
    remove (user) {
      this.removing = true
      const url = '/api/v1/positions/' + this.position.id + '/users/' + user.id
      window.axios.delete(url).then(() => {
        this.$emit('refresh')
        this.removing = false
        this.userRemoveDialog = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.removing = false
      })
    },
    showAddDialog () {
      this.userAddDialog = true
    },
    showRemoveDialog () {
      this.userRemoveDialog = true
    }
  },
  created () {
    this.dataUsers = this.users ? this.users : this.storeUsers
    this.positionUsers = this.position.users
  }
}
</script>
