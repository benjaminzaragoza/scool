<template>
    <form>
        <study-select
                v-model="dataStudy"
                :studies="studies"
                :departments="departments"
                :families="families"
                :error-messages="dataStudyErrors"
                @input="$v.dataStudy.$touch()"
                @blur="$v.dataStudy.$touch()"
        ></study-select>

        <!--
        Per cada estudi tenim la info : numero de MPS i número total de UFS
        També hem de tenir una llista dels MPS -> $study->subjectGroups
        -->
        <subject-group-number
                v-model="number"
                @input="$v.number.$touch()"
                @blur="$v.number.$touch()"
                :error-messages="numberErrors"
                :study="dataStudy"
        ></subject-group-number>

        <subject-group-code
                v-model="code"
                @input="$v.code.$touch()"
                @blur="$v.code.$touch()"
                :study="dataStudy"
                :number="number"
                :error-messages="codeErrors"
        ></subject-group-code>

        <v-text-field
                ref="name"
                v-model="name"
                name="name"
                label="Nom"
                :error-messages="nameErrors"
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
                hint="Nom del MP"
        ></v-text-field>

        <v-text-field
                v-model="shortname"
                name="shortname"
                label="Nom curt"
                :error-messages="shortnameErrors"
                @input="$v.shortname.$touch()"
                @blur="$v.shortname.$touch()"
                hint="Nom curt del MP"
        ></v-text-field>

        <v-text-field
                v-model="hours"
                name="hours"
                label="Hores"
                :error-messages="hoursErrors"
                @input="$v.hours.$touch()"
                @blur="$v.hours.$touch()"
                hint="Número d'hores totals del MP"
        ></v-text-field>

        <v-text-field
                v-model="freeHours"
                name="freeHours"
                label="Hores lliure disposició"
                hint="Número d'hores de lliure disposició del MP"
        ></v-text-field>

        <v-text-field
                v-model="weekHours"
                name="weekHours"
                label="Hores setmanals"
                hint="Número d'hores setmanals del MP"
        ></v-text-field>

        <subject-group-types
                v-model="type"
                :error-messages="typeErrors"
                @input="$v.type.$touch()"
                @blur="$v.type.$touch()"
        ></subject-group-types>

        <v-menu
                :close-on-content-click="false"
                v-model="startMenu"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
        >
            <v-text-field
                    slot="activator"
                    v-model="start"
                    label="Data d'inici"
                    prepend-icon="event"
                    readonly
            ></v-text-field>
            <v-date-picker
                    v-model="start"
                    @input="startMenu = false"
                    :allowed-dates="allowedDates"
                    locale="ca-es"
                    :first-day-of-week="1"
            ></v-date-picker>
        </v-menu>

        <v-menu
                :close-on-content-click="false"
                v-model="endMenu"
                :nudge-right="40"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
        >
            <v-text-field
                    slot="activator"
                    v-model="end"
                    label="Data fí"
                    prepend-icon="event"
                    readonly
            ></v-text-field>
            <v-date-picker
                    v-model="end"
                    @input="endMenu = false"
                    :allowed-dates="allowedDates"
                    locale="ca-es"
                    :first-day-of-week="1"
            ></v-date-picker>
        </v-menu>

        <v-btn @click="add(true)"
               color="success"
               :disabled="adding || $v.$invalid"
               :loading="adding">Afegir MP</v-btn>

        <v-btn @click="add(false)"
               color="primary"
               :disabled="adding || $v.$invalid"
               :loading="adding">Afegir MP i continuar</v-btn>
    </form>
</template>

<script>

import StudySelect from '../studies/StudySelect'
import SubjectGroupCode from './SubjectGroupCode'
import SubjectGroupNumber from './SubjectGroupNumber'
import SubjectGroupTypes from './SubjectGroupTypes'
import { validationMixin } from 'vuelidate'
import { required, numeric } from 'vuelidate/lib/validators'
import * as actions from '../../../store/action-types'

export default {
  name: 'SubjectGroupAddForm',
  mixins: [validationMixin],
  validations: {
    dataStudy: { required },
    number: { required, numeric },
    code: { required },
    name: { required },
    shortname: { required },
    hours: { required, numeric },
    type: { required }
  },
  components: {
    'study-select': StudySelect,
    'subject-group-number': SubjectGroupNumber,
    'subject-group-code': SubjectGroupCode,
    'subject-group-types': SubjectGroupTypes
  },
  data () {
    return {
      dataStudy: this.study,
      number: null,
      hours: null,
      freeHours: 0,
      weekHours: null,
      name: '',
      shortname: '',
      code: '',
      type: 'Normal',
      start: null,
      startMenu: false,
      end: null,
      endMenu: false,
      adding: false
    }
  },
  props: {
    study: {},
    subjectGroup: {},
    subjectGroups () {
      return this.$store.getters.subjectGroups
    },
    families () {
      return this.$store.getters.families
    },
    studies () {
      return this.$store.getters.studies
    },
    departments () {
      return this.$store.getters.departments
    },
    courses () {
      return this.$store.getters.courses
    }
  },
  computed: {
    dataStudyErrors () {
      const errors = []
      if (!this.$v.dataStudy.$dirty) return errors
      !this.$v.dataStudy.required && errors.push('És obligatori indicar un estudi.')
      return errors
    },
    numberErrors () {
      const errors = []
      if (!this.$v.number.$dirty) return errors
      !this.$v.number.required && errors.push('És obligatori indicar el número de MP')
      !this.$v.number.numeric && errors.push('El número de MP ha de ser un enter positiu')
      return errors
    },
    hoursErrors () {
      const errors = []
      if (!this.$v.hours.$dirty) return errors
      !this.$v.hours.required && errors.push('És obligatori indicar el número de hores del MP')
      !this.$v.hours.numeric && errors.push("El número d'hores del MP ha de ser un enter positiu")
      return errors
    },
    nameErrors () {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      !this.$v.name.required && errors.push('És obligatori indicar el nom del MP')
      return errors
    },
    shortnameErrors () {
      const errors = []
      if (!this.$v.shortname.$dirty) return errors
      !this.$v.shortname.required && errors.push('És obligatori indicar un nom curt pel MP')
      return errors
    },
    codeErrors () {
      const errors = []
      if (!this.$v.code.$dirty) return errors
      !this.$v.code.required && errors.push('És obligatori indicar un codi pel MP')
      return errors
    },
    typeErrors () {
      const errors = []
      if (!this.$v.type.$dirty) return errors
      !this.$v.type.required && errors.push('És obligatori indicar el tipus de MP')
      return errors
    }
  },
  watch: {
    study (study) {
      this.dataStudy = study
    }
  },
  methods: {
    partialReset () {
      this.number = parseInt(this.number) + 1
      this.name = ''
      this.shortname = ''
      this.hours = null
      this.start = null
      this.end = null
      console.log('hrefs:')
      console.log(this.$refs)
      console.log('this.$refs.name:')
      console.log(this.$refs.name)
      this.$nextTick(this.$refs.name.focus)
    },
    allowedDates: val => ![0, 6].includes(new Date(val).getDay()),
    add (close = false) {
      if (!this.$v.$invalid) {
        this.adding = true
        this.$store.dispatch(actions.ADD_SUBJECT_GROUP, {
          name: this.name,
          shortname: this.shortname,
          code: this.code,
          number: this.number,
          study_id: this.dataCourse.id,
          course_id: this.dataCourse.id,
          hours: this.hours,
          start: this.start,
          end: this.end
        }).then(response => {
          this.$snackbar.showMessage('Mòdul Professional creat correctament')
          this.adding = false
          this.$emit('added', response.data)
          if (close) {
            this.$emit('close')
          } else {
            this.partialReset()
          }
        }).catch(error => {
          console.log('error:')
          console.log(error)
          this.$snackbar.showError(error)
          this.adding = false
        })
      }
    }
  }
}
</script>
