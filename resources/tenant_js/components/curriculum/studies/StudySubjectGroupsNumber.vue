<template>
    <span>
        Número MPs de l'estudi:
        <template v-if="dataStudy">
            <span v-if="editing">
                <v-layout row wrap align-center justify-center fill-height>
                    <v-flex xs1>
                      <v-text-field
                              ref="subjectGroupsNumber"
                              v-model="subjectGroupsNumber"
                              name="subjectGroupsNumber"
                              label="Número de MPs"
                              :error-messages="subjectGroupsNumberErrors"
                              @input="$v.subjectGroupsNumber.$touch()"
                              @blur="$v.subjectGroupsNumber.$touch()"
                              loading="submitting"
                              @keyup.enter="edit"
                              autofocus
                      ></v-text-field>
                    </v-flex>
                </v-layout>
            </span>
            <span v-else @dblclick="editing = true">{{ subjectGroupsNumber }}</span>
            <span v-if="editing">
                <v-icon color="success" @click="edit">check</v-icon>
                <v-icon color="error" @click="editing = false">cancel</v-icon>
            </span>
            <v-icon v-else small color="success" @click="editing = true">edit</v-icon>
        </template>
    </span>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, numeric } from 'vuelidate/lib/validators'
import * as actions from '../../../store/action-types'
export default {
  name: 'StudySubjectGroupsNumber',
  mixins: [validationMixin],
  validations: {
    subjectGroupsNumber: { required, numeric }
  },
  data () {
    return {
      dataStudy: this.study,
      editing: false,
      submitting: false,
      subjectGroupsNumber: null
    }
  },
  methods: {
    edit () {
      if (!this.$v.$invalid && this.dataStudy) {
        this.submitting = true
        window.axios.put('/api/v1/studies/' + this.dataStudy.id + '/subject_groups_number', {
          subject_groups_number: this.subjectGroupsNumber
        }).then(() => {
          this.$snackbar.showMessage('Número de MPs de la UF modificat correctament')
          this.$emit('modified')
          this.submitting = false
          this.editing = false
        }).catch(error => {
          this.$snackbar.showError(error)
          this.submitting = false
        })
      }
    }
  },
  watch: {
    study (study) {
      this.dataStudy = study
      this.subjectGroupsNumber = this.dataStudy && (this.dataStudy.subject_groups_number || 0)
    }
  },
  computed: {
    subjectGroupsNumberErrors () {
      const errors = []
      if (!this.$v.subjectGroupsNumber.$dirty) return errors
      !this.$v.subjectGroupsNumber.required && errors.push('És obligatori indicar el nombre total de Mòduls Professionals')
      !this.$v.subjectGroupsNumber.numeric && errors.push('Cal indicar un nombre enter positiu')
      return errors
    }
  },
  props: {
    study: {}
  },
  created () {
    this.subjectGroupsNumber = this.dataStudy && (this.dataStudy.subject_groups_number || 0)
  }
}
</script>
