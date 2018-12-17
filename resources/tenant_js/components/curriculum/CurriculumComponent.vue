<template>
    <span id="curriculum_component">
        <floating-add v-model="dialog" title="Nou estudi">
            <study-add @close="dialog = false"></study-add>
        </floating-add>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <studies-list :studies="studies"></studies-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
import StudyAddComponent from './StudyAddComponent'
import StudiesListComponent from './StudiesListComponent'

export default {
  name: 'Curriculum',
  components: {
    'study-add': StudyAddComponent,
    'studies-list': StudiesListComponent
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    studies: {
      type: Array,
      default: function () {
        return undefined
      }
    }
  }
}
</script>
