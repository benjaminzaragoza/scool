<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <changelog-list :users="users" :logs="logs"></changelog-list>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import ChangelogListComponent from './ChangelogListComponent'
export default {
  name: 'ChangeLogComponent',
  components: {
    'changelog-list': ChangelogListComponent
  },
  props: {
    logs: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    }
  }
}
</script>
