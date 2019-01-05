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
import StudyAddComponent from './studies/StudyAddComponent'
import StudiesListComponent from './studies/StudiesListComponent'
import * as mutations from '../../store/mutation-types'

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
    },
    departments: {
      type: Array,
      required: true
    },
    families: {
      type: Array,
      required: true
    },
    tags: {
      type: Array,
      required: true
    },
    courses: {
      type: Array,
      required: true
    },
    subjectGroups: {
      type: Array,
      required: true
    },
    subjectGroupTags: {
      type: Array,
      required: true
    }
  },
  created () {
    this.$store.commit(mutations.SET_STUDIES, this.studies)
    this.$store.commit(mutations.SET_DEPARTMENTS, this.departments)
    this.$store.commit(mutations.SET_FAMILIES, this.families)
    this.$store.commit(mutations.SET_STUDIES_TAGS, this.tags)
    this.$store.commit(mutations.SET_COURSES, this.courses)
    this.$store.commit(mutations.SET_SUBJECT_GROUPS, this.subjectGroups)
    this.$store.commit(mutations.SET_SUBJECT_GROUP_TAGS, this.subjectGroupTags)
  }
}
</script>
