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
        <subject-group-select
                v-model="dataSubjectGroup"
                :subject-groups="subjectGroups"
                :error-messages="dataSubjectGroupErrors"
                @input="$v.dataSubjectGroup.$touch()"
                @blur="$v.dataSubjectGroup.$touch()"
        ></subject-group-select>
        <courses-select
                v-model="dataCourse"
                :courses="courses"
                :error-messages="dataCourseErrors"
                @input="$v.dataCourse.$touch()"
                @blur="$v.dataCourse.$touch()"
        ></courses-select>

        <subject-number
                v-model="number"
                @input="$v.number.$touch()"
                @blur="$v.number.$touch()"
                :error-messages="numberErrors"
                :study="dataStudy"
                :subject-group="dataSubjectGroup"
        ></subject-number>

        <subject-code
                v-model="code"
                @input="$v.code.$touch()"
                @blur="$v.code.$touch()"
                :course="dataCourse"
                :subject-group="dataSubjectGroup"
                :error-messages="codeErrors"
        ></subject-code>

        <v-text-field
                v-model="name"
                name="name"
                label="Nom"
                :error-messages="nameErrors"
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
                hint="Nom de la Unitat Formativa"
                autofocus
        ></v-text-field>

        <v-text-field
                v-model="shortname"
                name="shortname"
                label="Nom curt"
                :error-messages="shortnameErrors"
                @input="$v.shortname.$touch()"
                @blur="$v.shortname.$touch()"
                hint="Nom curt de la UF"
                autofocus
        ></v-text-field>

        <!--TODO-->
        <!--'type_id' => 1,-->

        <v-text-field
                v-model="hours"
                name="hours"
                label="Hores"
                :error-messages="hoursErrors"
                @input="$v.hours.$touch()"
                @blur="$v.hours.$touch()"
                hint="Número d'hores totals de la UF"
                autofocus
        ></v-text-field>

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
    </form>
</template>

<script>
import StudySelect from '../studies/StudySelect'
import SubjectGroupSelect from './SubjectGroupsSelect'
import CoursesSelect from '../courses/CoursesSelect'
import SubjectCode from './SubjectCode'
import SubjectNumber from './SubjectNumber'
import { validationMixin } from 'vuelidate'
import { required, numeric } from 'vuelidate/lib/validators'
export default {
  name: 'SubjectAddForm',
  mixins: [validationMixin],
  validations: {
    dataStudy: { required },
    dataSubjectGroup: { required },
    dataCourse: { required },
    hours: { required },
    name: { required },
    shortname: { required },
    code: { required },
    number: { required, numeric }
  },
  components: {
    'study-select': StudySelect,
    'subject-group-select': SubjectGroupSelect,
    'courses-select': CoursesSelect,
    'subject-number': SubjectNumber,
    'subject-code': SubjectCode
  },
  data () {
    return {
      dataStudy: this.study,
      dataSubjectGroup: this.subjectGroup,
      dataCourse: this.course,
      number: null,
      hours: null,
      name: '',
      shortname: '',
      code: '',
      start: null,
      startMenu: false,
      end: null,
      endMenu: false
    }
  },
  props: {
    study: {},
    subjectGroup: {},
    course: {},
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
    dataSubjectGroupErrors () {
      const errors = []
      if (!this.$v.dataSubjectGroup.$dirty) return errors
      !this.$v.dataSubjectGroup.required && errors.push('És obligatori indicar un mòdul professional.')
      return errors
    },
    dataCourseErrors () {
      const errors = []
      if (!this.$v.dataCourse.$dirty) return errors
      !this.$v.dataCourse.required && errors.push('És obligatori indicar un curs')
      return errors
    },
    numberErrors () {
      const errors = []
      if (!this.$v.number.$dirty) return errors
      !this.$v.number.required && errors.push('És obligatori indicar el número de UF')
      !this.$v.number.numeric && errors.push('El número de UF ha de ser un enter positiu')
      return errors
    },
    hoursErrors () {
      const errors = []
      if (!this.$v.hours.$dirty) return errors
      !this.$v.hours.required && errors.push('És obligatori indicar el número de hores de la UF')
      !this.$v.number.numeric && errors.push("El número d'hores de la UF ha de ser un enter positiu")
      return errors
    },
    nameErrors () {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      !this.$v.name.required && errors.push('És obligatori indicar el nom de la UF')
      return errors
    },
    shortnameErrors () {
      const errors = []
      if (!this.$v.shortname.$dirty) return errors
      !this.$v.shortname.required && errors.push('És obligatori indicar un nom curt per la UF')
      return errors
    },
    codeErrors () {
      const errors = []
      if (!this.$v.code.$dirty) return errors
      !this.$v.code.required && errors.push('És obligatori indicar un codi per a la UF')
      return errors
    }
  },
  watch: {
    study (study) {
      this.dataStudy = study
    },
    course (course) {
      this.dataCourse = course
    },
    subjectGroup (subjectGroup) {
      this.dataSubjectGroup = subjectGroup
    }
  },
  methods: {
    allowedDates: val => ![0, 6].includes(new Date(val).getDay())
  }
}
</script>
