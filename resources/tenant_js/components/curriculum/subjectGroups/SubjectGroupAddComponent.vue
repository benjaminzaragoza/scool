<template>
    <v-card>
        <v-stepper v-model="step" vertical>
            <v-stepper-step :complete="step > 1" step="1">
                Estudi
                <small>Seleccioneu l'estudi al que pertany el Mòdul Professional. Feu clic a la icona + per afegir l'estudi si encara no existeix</small>
            </v-stepper-step>
            <v-stepper-content step="1">
                <study-select v-model="study"></study-select>
                <v-btn v-if="study" @click="step=2" color="primary">Continuar</v-btn>
            </v-stepper-content>
            <v-stepper-step step="2">Mòdul Professional</v-stepper-step>
            <v-stepper-content step="2">
                <v-btn @click="step=1">Tornar a escollir Estudi</v-btn>
                <subject-group-add-form v-if="step === 2" :study="study" @close="close"></subject-group-add-form>
            </v-stepper-content>
        </v-stepper>
    </v-card>
</template>

<script>
import StudySelect from '../studies/StudySelect'
import SubjectGroupAddForm from './SubjectGroupAddForm'
export default {
  name: 'SubjectGroupAdd',
  data () {
    return {
      step: 1,
      study: null
    }
  },
  components: {
    'study-select': StudySelect,
    'subject-group-add-form': SubjectGroupAddForm
  },
  watch: {
    study (newStudy) {
      if (newStudy) this.step = 2
    }
  },
  methods: {
    close () {
      this.$emit('close')
    }
  }
}
</script>
