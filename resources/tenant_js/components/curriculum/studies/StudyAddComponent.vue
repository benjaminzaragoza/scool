<template>
    <form>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <v-text-field
                            ref="name_field"
                            v-focus
                            v-model="name"
                            name="name"
                            label="Nom"
                            :error-messages="nameErrors"
                            @input="$v.name.$touch()"
                            @blur="$v.name.$touch()"
                            hint="Nom complet de l'estudi tal i com apareix a la documentació oficial"
                            autofocus
                    ></v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-text-field
                            v-model="shortname"
                            name="name"
                            label="Nom curt"
                            :error-messages="shortnameErrors"
                            @input="$v.shortname.$touch()"
                            @blur="$v.shortname.$touch()"
                            hint="Nom curt"
                            autofocus
                    ></v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-text-field
                            v-model="code"
                            name="code"
                            label="Codi"
                            :error-messages="codeErrors"
                            @input="$v.code.$touch()"
                            @blur="$v.code.$touch()"
                            hint="Codi"
                            autofocus
                    ></v-text-field>
                </v-flex>
                <v-flex xs12>
                    <department-select
                            v-model="department"
                            :departments="departments"
                            :error-messages="departmentErrors"
                            @input="$v.department.$touch()"
                            @blur="$v.department.$touch()"
                    ></department-select>
                </v-flex>
                <v-flex xs12>
                    <family-select
                            v-model="family"
                            :families="families"
                            :error-messages="familyErrors"
                            @input="$v.family.$touch()"
                            @blur="$v.family.$touch()"
                    ></family-select>
                </v-flex>
                <v-flex xs12>
                    <v-text-field
                            v-model="subjectGroupsNumber"
                            name="subjectGroupsNumber"
                            label="Número de MPs"
                            :error-messages="subjectGroupsNumberErrors"
                            @input="$v.subjectGroupsNumber.$touch()"
                            @blur="$v.subjectGroupsNumber.$touch()"
                            hint="Total de Mòduls Professionals de l'estudi"
                            autofocus
                    ></v-text-field>
                </v-flex>
                <v-flex xs12>
                    <v-text-field
                            v-model="subjectsNumber"
                            name="subjectsNumber"
                            label="Número de UFS"
                            :error-messages="subjectsNumberErrors"
                            @input="$v.subjectsNumber.$touch()"
                            @blur="$v.subjectsNumber.$touch()"
                            hint="Total d'Unitats Formatives de l'estudi"
                            autofocus
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-container>
        <v-btn @click="add(true)"
               color="primary"
               class="white--text"
               :loading="adding"
               :disabled="adding || this.$v.$invalid"
        >Afegir</v-btn>
        <v-btn @click="close()"
               id="close_button"
               color="error"
               class="white--text"
        >Tancar</v-btn>
    </form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, numeric } from 'vuelidate/lib/validators'
import * as actions from '../../../store/action-types'
import DepartmentSelectComponent from '../departments/DepartmentsSelectComponent'
import FamilySelectComponent from '../families/FamilySelectComponent'

export default {
  name: 'StudyAdd',
  components: {
    'family-select': FamilySelectComponent,
    'department-select': DepartmentSelectComponent
  },
  mixins: [validationMixin],
  validations: {
    name: { required },
    shortname: { required },
    code: { required },
    department: { required },
    family: { required },
    subjectGroupsNumber: { required, numeric },
    subjectsNumber: { required, numeric }
  },
  data () {
    return {
      name: '',
      shortname: '',
      code: '',
      family: null,
      department: null,
      subjectGroupsNumber: null,
      subjectsNumber: null,
      adding: false
    }
  },
  computed: {
    departments () {
      return this.$store.getters.departments
    },
    families () {
      return this.$store.getters.families
    },
    nameErrors () {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      !this.$v.name.required && errors.push('És obligatori indicar un nom.')
      return errors
    },
    shortnameErrors () {
      const errors = []
      if (!this.$v.shortname.$dirty) return errors
      !this.$v.shortname.required && errors.push('És obligatori indicar un nom curt.')
      return errors
    },
    codeErrors () {
      const errors = []
      if (!this.$v.code.$dirty) return errors
      !this.$v.code.required && errors.push('És obligatori indicar un codi')
      return errors
    },
    departmentErrors () {
      const errors = []
      if (!this.$v.department.$dirty) return errors
      !this.$v.department.required && errors.push('És obligatori indicar un departament')
      return errors
    },
    familyErrors () {
      const errors = []
      if (!this.$v.family.$dirty) return errors
      !this.$v.family.required && errors.push('És obligatori indicar una família')
      return errors
    },
    subjectGroupsNumberErrors () {
      const errors = []
      if (!this.$v.subjectGroupsNumber.$dirty) return errors
      !this.$v.subjectGroupsNumber.required && errors.push('És obligatori indicar el nombre total de Mòduls Professionals')
      !this.$v.subjectGroupsNumber.numeric && errors.push('Cal indicar un nombre enter positiu')
      return errors
    },
    subjectsNumberErrors () {
      const errors = []
      if (!this.$v.subjectsNumber.$dirty) return errors
      !this.$v.subjectsNumber.required && errors.push("És obligatori indicar el nombre total d'unitats formatives")
      !this.$v.subjectsNumber.numeric && errors.push('Cal indicar un nombre enter positiu')
      return errors
    }
  },
  methods: {
    close () {
      this.$emit('close')
    },
    add (close = false) {
      if (!this.$v.$invalid) {
        this.adding = true
        this.$store.dispatch(actions.ADD_STUDY, {
          name: this.name,
          shortname: this.shortname,
          code: this.code,
          department: this.department,
          family: this.family
        }).then(response => {
          this.$snackbar.showMessage('Estudi creat correctament')
          this.adding = false
          this.$emit('added', response.data)
          if (close) this.close()
        }).catch(error => {
          this.$snackbar.showError(error)
          this.adding = false
        })
      }
    }
  },
  mounted () {
    this.$nextTick(this.$refs.name_field.focus)
  }
}
</script>
