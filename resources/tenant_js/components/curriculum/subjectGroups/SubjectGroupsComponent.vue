<template>
    <span id="curriculum_component">
        <floating-add v-model="dialog" title="Nou Mòdul Professional">
            <subject-group-add-form @close="dialog = false"></subject-group-add-form>
        </floating-add>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <subject-groups-list></subject-groups-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
import SubjectGroupsListComponent from './SubjectGroupsListComponent'
import SubjectGroupAddForm from './SubjectGroupAddForm'
import * as mutations from '../../../store/mutation-types'

export default {
  name: 'SubjectGroups',
  components: {
    'subject-groups-list': SubjectGroupsListComponent,
    'subject-group-add-form': SubjectGroupAddForm
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    subjectGroups: {
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
    }
  },
  created () {
    this.$store.commit(mutations.SET_SUBJECT_GROUPS, this.subjectGroups)
    this.$store.commit(mutations.SET_STUDIES, this.studies)
    this.$store.commit(mutations.SET_DEPARTMENTS, this.departments)
    this.$store.commit(mutations.SET_FAMILIES, this.families)
  }
}
</script>
