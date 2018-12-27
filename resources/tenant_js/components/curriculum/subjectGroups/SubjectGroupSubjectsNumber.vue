<template>
    <span>
        Número UFS: {{ numberOfSubjects || '-' }}
        <v-icon v-if="alert" color="error"
                title="Atenció. El nombre màxim de UFs del Mòdul Professional que heu indicat és inferior al número de UFs del MP. Si us plau modifiqueu el valor">
            notification_important
        </v-icon>
        Màxim UFs:
        <template v-if="dataSubjectGroup">
            <span v-if="editing">
                <v-layout row wrap align-center justify-center fill-height>
                    <v-flex xs1>
                      <v-text-field
                              ref="subjectsNumber"
                              v-model="subjectsNumber"
                              name="subjectsNumber"
                              label="Número de UFs"
                              :error-messages="subjectsNumberErrors"
                              @input="$v.subjectsNumber.$touch()"
                              @blur="$v.subjectsNumber.$touch()"
                              loading="submitting"
                              @keyup.enter="edit"
                              autofocus
                      ></v-text-field>
                    </v-flex>
                </v-layout>
            </span>
            <span v-else @dblclick="startEdit">{{ subjectsNumber || '-' }}</span>
            <span v-if="editing">
                <v-icon color="success" @click="edit">check</v-icon>
                <v-icon color="error" @click="cancelEdit">cancel</v-icon>
            </span>
            <v-icon v-else small color="success" @click="startEdit">edit</v-icon>
        </template>
    </span>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, numeric } from 'vuelidate/lib/validators'
export default {
  name: 'SubjectGroupSubjectsNumber',
  mixins: [validationMixin],
  validations: {
    subjectsNumber: { required, numeric }
  },
  data () {
    return {
      dataSubjectGroup: this.subjectGroup,
      editing: false,
      submitting: false,
      subjectsNumber: null,
      oldSubjectsNumber: null
    }
  },
  props: {
    subjectGroup: {}
  },
  watch: {
    subjectGroup (subjectGroup) {
      this.dataSubjectGroup = subjectGroup
      this.subjectsNumber = this.dataSubjectGroup && (this.dataSubjectGroup.subjects_number || 0)
    },
    alert (alert) {
      if (alert) this.$snackbar.showError("Atenció. El nombre màxim de UFs del MP que heu indicat és inferior al número de UFs del MP. Si us plau modifiqueu el valor")
    }
  },
  computed: {
    numberOfSubjects () {
      if (this.dataSubjectGroup && this.dataSubjectGroup.subjectGroups) return this.dataSubjectGroup.subjectGroups.length || 0
    },
    alert () {
      if (this.subjectsNumber && this.dataSubjectGroup && this.dataSubjectGroup.subjectGroups) {
        if (this.dataSubjectGroup.subjectGroups.length > this.subjectsNumber) return true
      }
      return false
    },
    subjectsNumberErrors () {
      const errors = []
      if (!this.$v.subjectsNumber.$dirty) return errors
      !this.$v.subjectsNumber.required && errors.push('És obligatori indicar el nombre total de Unitats Formatives')
      !this.$v.subjectsNumber.numeric && errors.push('Cal indicar un nombre enter positiu')
      return errors
    }
  },
  methods: {
    startEdit () {
      this.editing = true
      this.oldSubjectsNumber = this.subjectsNumber
    },
    cancelEdit () {
      this.editing = false
      this.subjectsNumber = this.oldSubjectsNumber
    },
    edit () {
      if (!this.$v.$invalid && this.dataSubjectGroup) {
        this.submitting = true
        window.axios.put('/api/v1/subject_groups/' + this.dataSubjectGroup.id + '/subjects_number', {
          subjects_number: this.subjectsNumber
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
  created () {
    this.subjectsNumber = this.dataSubjectGroup && (this.dataSubjectGroup.subjects_number || 0)
  }
}
</script>
