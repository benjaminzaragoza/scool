<template>
    <span id="curriculum_component">
        <floating-add v-model="dialog" title="Nova Unitat Formativa">
            <subject-add @close="dialog=false"></subject-add>
        </floating-add>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <subjects-list></subjects-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
import SubjectAddComponent from './SubjectAddComponent'
import SubjectListComponent from './SubjectsListComponent'
import * as mutations from '../../../store/mutation-types'

export default {
  name: 'Subjects',
  components: {
    'subject-add': SubjectAddComponent,
    'subjects-list': SubjectListComponent
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    subjects: {
      type: Array,
      required: true
    },
    studies: {
      type: Array,
      required: true
    },
    departments: {
      type: Array,
      required: true
    },
    families: {
      type: Array,
      required: true
    },
    subjectGroups: {
      type: Array,
      required: true
    },
    courses: {
      type: Array,
      required: true
    }
  },
  created () {
    this.$store.commit(mutations.SET_DEPARTMENTS, this.departments)
    this.$store.commit(mutations.SET_FAMILIES, this.families)
    this.$store.commit(mutations.SET_COURSES, this.courses)
    this.$store.commit(mutations.SET_STUDIES, this.studies)
    this.$store.commit(mutations.SET_SUBJECTS, this.subjects)
    this.$store.commit(mutations.SET_SUBJECT_GROUPS, this.subjectGroups)
  }
}
</script>
