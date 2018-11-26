<template>
    <div>
        <v-text-field
                append-icon="search"
                label="Buscar"
                single-line
                hide-details
                v-model="search"
                class="mb-3"
        ></v-text-field>
        <v-data-iterator
                :items="dataRoleUsers"
                :rows-per-page-items="rowsPerPageItems"
                :pagination.sync="pagination"
                content-tag="v-layout"
                row
                wrap
                :search="search"
        >
      <v-flex
              slot="item"
              slot-scope="{ item:user }"
              xs12
              sm6
              md4
              lg2
      >
        <user-card :user="user">
            <template slot="actions" slot-scope="{ user }"
            >
                <v-btn
                        @click="remove(user)"
                        title="Eliminar usuari"
                        small
                        dark
                        fab
                        absolute
                        bottom
                        right
                        color="red lighten-1"
                        :loading="removing"
                        :disabled="removing"
                >
                    <v-icon>remove</v-icon>
                </v-btn>
            </template>
        </user-card>
      </v-flex>
    </v-data-iterator>
        <v-btn  @click="showAddDialog"
                title="Afegir usuari"
                absolute
                dark
                fab
                down
                left
                color="pink"
        >
            <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="addDialog" persistent max-width="600px">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-btn icon dark @click.native="addDialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Indiqueu l'usuari a afegir</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <user-select
                            label="Usuari a afegir"
                            :users="dataUsers"
                            v-model="newUser"
                            :item-value="null"
                    ></user-select>
                    <v-btn flat @click.native="addDialog = false">
                        <v-icon >close</v-icon> Cancel·lar
                    </v-btn>
                    <v-btn @click.native="add" :loading="adding" :disabled="adding" color="primary">
                        <v-icon>add</v-icon> Afegir
                    </v-btn>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import UserCard from '../users/UserCardComponent'
import UserSelect from '../users/UsersSelectComponent'

export default {
  name: 'RoleUsers',
  components: {
    'user-card': UserCard,
    'user-select': UserSelect
  },
  data () {
    return {
      rowsPerPageItems: [12, 24, 36, 48, 60, 72, 84, 96, { 'text': 'Tots', 'value': -1 }],
      pagination: {
        rowsPerPage: 12
      },
      dataUsers: [],
      search: '',
      removing: false,
      adding: false,
      dataRoleUsers: [],
      addDialog: false,
      newUser: null
    }
  },
  props: {
    role: {
      type: String,
      required: true
    },
    roleUsers: {
      type: Array,
      default: null
    },
    users: {
      type: Array,
      default: null
    }
  },
  methods: {
    showAddDialog () {
      this.addDialog = true
    },
    add () {
      this.adding = true
      window.axios.post('/api/v1/user/' + this.newUser.id + '/role/' + this.role, {}).then(() => {
        this.adding = false
        this.dataRoleUsers.push(this.newUser)
        this.$snackbar.showMessage("S'ha afegit correctament el rol a l'usuari")
        this.addDialog = false
        this.newUser = null
      }).catch(error => {
        this.adding = false
        this.$snackbar.showError(error)
      })
    },
    remove (user) {
      this.removing = true
      window.axios.delete('/api/v1/user/' + user.id + '/role/' + this.role).then(() => {
        this.removing = false
        this.dataRoleUsers.splice(this.dataRoleUsers.indexOf(user), 1)
        this.$snackbar.showMessage("S'ha tret correctament el rol a l'usuari")
      }).catch(error => {
        this.removing = false
        this.$snackbar.showError(error)
      })
    },
    fetchUsers () {
      window.axios.get('/api/v1/users').then(response => {
        this.dataUsers = response.data
        this.$emit('loaded')
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    fetchRoleUsers () {
      window.axios.get('/api/v1/role/' + this.role + '/users').then(response => {
        this.dataRoleUsers = response.data
        this.$emit('loaded')
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    }
  },
  created () {
    if (!this.roleUsers) this.fetchRoleUsers()
    else {
      this.dataRoleUsers = this.roleUsers
      this.$emit('loaded')
    }
    if (!this.users) this.fetchUsers()
    else this.dataUsers = this.users
  }
}
</script>
