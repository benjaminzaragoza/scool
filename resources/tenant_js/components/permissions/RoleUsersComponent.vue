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
        <user-card :user="user"></user-card>
      </v-flex>
    </v-data-iterator>
        <v-btn
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
      rowsPerPageItems: [4, 8, 12],
      pagination: {
        rowsPerPage: 8
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
    fetchUsers () {
      this.users = [
        {
          name: 'Sergi Tur Badenas',
          email: 'sergiturbadenas@gmail.com',
          hashid: 'Og1'
        },
        {
          name: 'Dolors Sanjuan blablan',
          email: 'dolors@gmail.com',
          hashid: 'RX'
        },
        {
          name: 'Dolors Sanjuan blablan',
          email: 'dolors@gmail.com',
          hashid: 'RX'
        },
        {
          name: 'Dolors Sanjuan blablan',
          email: 'dolors@gmail.com',
          hashid: 'RX'
        },
        {
          name: 'Dolors Sanjuan blablan',
          email: 'dolors@gmail.com',
          hashid: 'RX'
        },
        {
          name: 'Dolors Sanjuan blablan',
          email: 'dolors@gmail.com',
          hashid: 'RX'
        },
        {
          name: 'Dolors Sanjuan blablan',
          email: 'dolors@gmail.com',
          hashid: 'RX'
        },
        {
          name: 'Dolors Sanjuan blablan',
          email: 'dolors@gmail.com',
          hashid: 'RX'
        },
        {
          name: 'Dolors Sanjuan blablan',
          email: 'dolors@gmail.com',
          hashid: 'RX'
        }
      ]
    }
  },
  created () {
    this.fetchUsers()
  }
}
</script>
