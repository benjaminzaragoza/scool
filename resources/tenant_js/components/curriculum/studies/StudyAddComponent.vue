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
                    <department-select v-model="department"></department-select>
                </v-flex>
                <v-flex xs12>
                    <family-select v-model="family"></family-select>
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
import { required } from 'vuelidate/lib/validators'
import * as actions from '../../../store/action-types'
import DepartmentSelectComponent from '../departments/DepartmentsSelectComponent'
import FamilySelectComponent from '../families/FamilySelectComponent'

export default {
  'name': 'StudyAdd',
  'components': {
    'family-select': FamilySelectComponent,
    'department-select': DepartmentSelectComponent
  },
  mixins: [validationMixin],
  validations: {
    name: { required },
    shortname: { required },
    code: { required },
    department: { required },
    family: { required }
  },
  data () {
    return {
      name: '',
      shortname: '',
      code: '',
      family: null,
      department: null,
      adding: false
    }
  },
  computed: {
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
    }
  },
  methods: {
    close () {
      this.$emit('close')
    },
    add (close = false) {
      if (!this.$v.$invalid) {
        this.adding = true
        this.$store.dispatch(actions.ADD_INCIDENT, {
          subject: this.subject,
          description: this.description
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
