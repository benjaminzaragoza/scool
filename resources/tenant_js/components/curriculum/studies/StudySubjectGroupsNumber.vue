<template>
    <span>
        Número MPs: {{ numberOfSubjectGroups || '-' }}
        <v-icon v-if="alert" color="error"
                title="Atenció. El nombre màxim de MPs de l'estudi que heu indicat és inferior al número de MPs de l'estudi. Si us plau modifiqueu el valor">
            notification_important
        </v-icon>
        Màxim MPs:
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
            <span v-else @dblclick="startEdit">{{ subjectGroupsNumber || '-' }}</span>
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
      subjectGroupsNumber: null,
      oldSubjectGroupsNumber: null
    }
  },
  methods: {
    startEdit () {
      this.editing = true
      this.oldSubjectGroupsNumber = this.subjectGroupsNumber
    },
    cancelEdit() {
      this.editing = false
      this.subjectGroupsNumber = this.oldSubjectGroupsNumber
    },
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
    },
    alert (alert) {
      if (alert) this.$snackbar.showError("Atenció. El nombre màxim de MPs de l'estudi que heu indicat és inferior al número de MPs de l'estudi. Si us plau modifiqueu el valor")
    }
  },
  computed: {
    numberOfSubjectGroups () {
      if (this.dataStudy && this.dataStudy.subjectGroups) return this.dataStudy.subjectGroups.length || 0
    },
    alert () {
      if (this.subjectGroupsNumber && this.dataStudy && this.dataStudy.subjectGroups) {
        if (this.dataStudy.subjectGroups.length > this.subjectGroupsNumber) return true
      }
      return false
    },
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
