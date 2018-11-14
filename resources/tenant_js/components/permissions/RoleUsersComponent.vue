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
                :items="users"
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
            <template slot="actions">
                <v-btn
                        @click="remove"
                        title="Eliminar usuari"
                        small
                        dark
                        fab
                        absolute
                        bottom
                        right
                        color="red lighten-1"
                >
                    <v-icon>remove</v-icon>
                </v-btn>
            </template>
        </user-card>
      </v-flex>
    </v-data-iterator>
        <v-btn  @click="add"
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
    </div>
</template>

<script>
import UserCard from '../users/UserCardComponent'

export default {
  name: 'RoleUsers',
  components: {
    'user-card': UserCard
  },
  data () {
    return {
      rowsPerPageItems: [12, 24, 36, 48, 60, 72, 84, 96, { 'text': 'Tots', 'value': -1 }],
      pagination: {
        rowsPerPage: 12
      },
      users: [],
      search: ''
    }
  },
  props: {
    role: {
      type: String,
      required: true
    }
  },
  methods: {
    add () {
      console.log('ADD TODO')
    },
    remove () {
      console.log('REMOVE TODO')
    },
    fetchUsers () {
      window.axios.get('/api/v1/users').then(response => {
        this.users = response.data
        this.$emit('loaded')
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    }
  },
  created () {
    this.fetchUsers()
  }
}
</script>
