<template>
    <span id="moodle_component">
        <floating-add v-model="dialog" title="Nova incidència">
            <moodle-users-add @close="dialog = false"></moodle-users-add>
        </floating-add>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <moodle-users-list :users="users"></moodle-users-list>
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
      dialog: false
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    }
  }
}
</script>
