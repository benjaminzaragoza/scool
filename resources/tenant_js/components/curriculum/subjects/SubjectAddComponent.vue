<template>
    <v-card>
        <v-stepper v-model="step" vertical>
            <v-stepper-step :complete="step > 1" step="1">
                Estudi
                <small>Seleccioneu l'estudi al que pertany la Unitat Formativa</small>
            </v-stepper-step>
            <v-stepper-content step="1">
                <study-select v-model="study" :studies="studies"></study-select>
            </v-stepper-content>
            <v-stepper-step :complete="step > 2" step="2">Mòdul Professional</v-stepper-step>
            <v-stepper-content step="2">
                <subject-group-select v-model="subjectGroup" :subject-groups="subjectGroups"></subject-group-select>
                <v-btn @click="step=1">Tornar a escollir estudi</v-btn>
            </v-stepper-content>
            <v-stepper-step :complete="step > 3" step="3">Curs</v-stepper-step>
            <v-stepper-content step="3">
                <courses-select v-model="course" :courses="courses"></courses-select>
                <v-btn @click="step=2">Tornar a escollir Mòdul</v-btn>
            </v-stepper-content>
            <v-stepper-step step="4">Unitat Formativa</v-stepper-step>
            <v-stepper-content step="4">
                <subject-add-form :studies="studies" :subject-groups="subjectGroups" :courses="courses"></subject-add-form>
            </v-stepper-content>
        </v-stepper>
    </v-card>

</template>

<script>
import StudySelect from '../studies/StudySelect'
import SubjectGroupSelect from './SubjectGroupsSelect'
import SubjectAddForm from './SubjectAddForm'
import CoursesSelect from '../courses/SubjectGroupsSelect'
export default {
  name: 'SubjectAdd',
  data () {
    return {
      step: 1,
      study: null,
      subjectGroup: null,
      course: null
    }
  },
  components: {
    'study-select': StudySelect,
    'subject-group-select': SubjectGroupSelect,
    'courses-select': CoursesSelect,
    'subject-add-form': SubjectAddForm
  },
  props: {
    studies: {
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
  watch: {
    study (newStudy) {
      if (newStudy) this.step = 2
    },
    subjectGroup (newSubjectGroup) {
      if (newSubjectGroup) this.step = 3
    },
    course (newCourse) {
      if (newCourse) this.step = 4
    }
  }
}
</script>
