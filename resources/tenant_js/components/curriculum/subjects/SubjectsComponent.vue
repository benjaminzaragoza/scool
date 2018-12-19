<template>
    <span id="curriculum_component">
        <floating-add v-model="dialog" title="Nou estudi">
            <subject-add></subject-add>
        </floating-add>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <subjects-list :subjects="subjects" :studies="studies" :subject-groups="subjectGroups"></subjects-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
import SubjectAddComponent from './SubjectAddComponent'
import SubjectListComponent from './SubjectListComponent'
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
    subjectGroups: {
      type: Array,
      required: true
    }
  }
}
</script>
