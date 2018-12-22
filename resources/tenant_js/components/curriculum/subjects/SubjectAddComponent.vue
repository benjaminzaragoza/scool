<template>
    <v-card>
        <v-stepper v-model="step" vertical>
            <v-stepper-step :complete="step > 1" step="1">
                Estudi
                <small>Seleccioneu l'estudi al que pertany la Unitat Formativa. Feu clic a la icona + per afegir l'estudi si encara no existeix</small>
            </v-stepper-step>
            <v-stepper-content step="1">
                <study-select v-model="study"></study-select>
                <v-btn v-if="study" @click="step=2" color="primary">Continuar</v-btn>
            </v-stepper-content>
            <v-stepper-step :complete="step > 2" step="2">Mòdul Professional
                <small>Seleccioneu el Mòdul Professional al que pertany la Unitat Formativa. Feu clic a la icona + per afegir el mòdul si encara no existeix</small>
            </v-stepper-step>
            <v-stepper-content step="2">
                <subject-group-select v-model="subjectGroup" :study="study"></subject-group-select>
                <v-btn v-if="subjectGroup" @click="step=3"  color="primary">Continuar</v-btn>
                <v-btn @click="step=1">Tornar a escollir estudi</v-btn>
            </v-stepper-content>
            <v-stepper-step :complete="step > 3" step="3">Curs
                <small>Seleccioneu el curs al que pertany la Unitat Formativa. Feu clic a la icona + per afegir el curs si encara no existeix</small>
            </v-stepper-step>
            <v-stepper-content step="3">
                <courses-select v-model="course" :study="study"></courses-select>
                <v-btn v-if="course" @click="step=4"  color="primary">Continuar</v-btn>
                <v-btn @click="step=2">Tornar a escollir Mòdul</v-btn>
            </v-stepper-content>
            <v-stepper-step step="4">Unitat Formativa</v-stepper-step>
            <v-stepper-content step="4">
                <v-btn @click="step=3">Tornar a escollir Curs</v-btn>
                <subject-add-form v-if="step === 4" :study="study" :course="course" :subject-group="subjectGroup" @close="close"></subject-add-form>
            </v-stepper-content>
        </v-stepper>
    </v-card>

</template>

<script>
import StudySelect from '../studies/StudySelect'
import SubjectGroupSelect from '../subjectGroups/SubjectGroupsSelect'
import SubjectAddForm from './SubjectAddForm'
import CoursesSelect from '../courses/CoursesSelect'
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
  },
  methods: {
    close () {
      this.$emit('close')
    }
  }
}
</script>
