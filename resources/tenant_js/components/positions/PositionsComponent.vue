<template>
    <span id="curriculum_component">
        <floating-add v-model="dialog" title="Nou càrrec">
            <position-add @close="dialog = false"></position-add>
        </floating-add>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <positions-list :positions="positions"></positions-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
import PositionAddComponent from './PositionAddComponent'
import PositionsListComponent from './PositionsListComponent'
import * as mutations from '../../store/mutation-types'

export default {
  name: 'Curriculum',
  components: {
    'position-add': PositionAddComponent,
    'positions-list': PositionsListComponent
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    positions: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    }
  },
  created () {
    this.$store.commit(mutations.SET_POSITIONS, this.positions)
    this.$store.commit(mutations.SET_USERS, this.users)
  }
}
</script>
