<template>
    <span id="moodle_component">
        <floating-add v-model="dialog" title="Nou usuari de Moodle">
            <moodle-users-add @created="add" @close="dialog = false" :local-users="localUsers"></moodle-users-add>
        </floating-add>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <moodle-users-list :users="dataUsers" :local-users="localUsers"></moodle-users-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
import MoodleUsersListComponent from './MoodleUsersListComponent'
import MoodleUsersAddComponent from './MoodleUsersAddComponent'
export default {
  name: 'MoodleUsers',
  components: {
    'moodle-users-list': MoodleUsersListComponent,
    'moodle-users-add': MoodleUsersAddComponent
  },
  data () {
    return {
      dialog: false,
      dataUsers: this.users
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    },
    localUsers: {
      type: Array,
      required: true
    },
    action: {
      type: String,
      default: null
    }
  },
  watch: {
    users (users) {
      this.dataUsers = users
    }
  },
  methods: {
    add (user) {
      this.dataUsers.push(user)
    }
  },
  created () {
    if (this.action && this.action === 'create') this.dialog = true
  }
}
</script>
