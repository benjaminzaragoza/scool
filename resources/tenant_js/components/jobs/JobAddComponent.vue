<template>
    <span>
        <v-btn
                fab
                bottom
                right
                color="pink"
                dark
                fixed
                @click.stop="dialog = !dialog"
        >
            <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog = false">
            <v-toolbar color="blue darken-3">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title">Afegir plaça</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <form>
                                        <v-container fluid grid-list-md text-xs-center>
                                            <v-layout row wrap>
                                                <v-flex md2>
                                                    <v-text-field
                                                            v-model="code"
                                                            name="code"
                                                            label="Codi"
                                                            :counter="4"
                                                            :error-messages="codeErrors"
                                                            @input="$v.code.$touch()"
                                                            @blur="$v.code.$touch()"
                                                            autofocus
                                                    ></v-text-field>
                                                </v-flex>
                                                <v-flex md2>
                                                    <job-type-select
                                                            :job-types="jobTypes"
                                                            v-model="jobType"
                                                            label="Tipus de plaça"
                                                            :error-messages="jobTypeErrors"
                                                            @input="$v.jobType.$touch()"
                                                            @blur="$v.jobType.$touch()"
                                                    ></job-type-select>
                                                </v-flex>
                                                <v-flex md5>
                                                    <specialty-select
                                                            v-if="isTeacher"
                                                            :specialties="specialties"
                                                            name="specialty"
                                                            label="Especialitat"
                                                            :error-messages="specialtyErrors"
                                                            @input="$v.specialty.$touch()"
                                                            @blur="$v.specialty.$touch()"
                                                            v-model="specialty"
                                                            :required="false"
                                                    ></specialty-select>
                                                </v-flex>
                                                <v-flex md3>
                                                    <family-select
                                                            v-if="isTeacher"
                                                            :families="families"
                                                            name="family"
                                                            label="Família"
                                                            v-model="family"
                                                            :required="false"
                                                    ></family-select>
                                                </v-flex>
                                                <v-flex md5>
                                                    <user-select
                                                            name="holder"
                                                            label="Escolliu un titular"
                                                            :users="internalUsers"
                                                            v-model="holder"
                                                    ></user-select>
                                                </v-flex>
                                                <v-flex md1>
                                                    <v-text-field
                                                            v-model="order"
                                                            name="order"
                                                            label="Ordre"
                                                            :error-messages="orderErrors"
                                                            @input="$v.order.$touch()"
                                                            @blur="$v.order.$touch()"
                                                    ></v-text-field>
                                                </v-flex>
                                                <v-flex md6>
                                                    <v-text-field
                                                            v-model="notes"
                                                            name="notes"
                                                            label="Observacions"
                                                    ></v-text-field>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                        <v-btn flat color="red" @click="clear">Netejar</v-btn>
                                        <v-btn @click="add(false)"
                                               color="teal"
                                               class="white--text"
                                               :loading="adding"
                                               :disabled="adding"
                                        >Afegir</v-btn>
                                        <v-btn @click="add(true)"
                                               color="primary"
                                               class="white--text"
                                               :loading="adding"
                                               :disabled="adding"
                                        >Afegir i tancar</v-btn>
                                    </form>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
    </span>

</template>

<script>
import { validationMixin } from 'vuelidate'
import withSnackbar from '../mixins/withSnackbar'
import { required, maxLength, requiredIf, numeric } from 'vuelidate/lib/validators'
import * as actions from '../../store/action-types'
import JobTypeSelect from './JobTypeSelectComponent.vue'
import SpecialtySelect from '../specialties/SpecialtySelectComponent'
import FamilySelect from '../families/FamilySelectComponent'
import UserSelect from '../users/UsersSelectComponent.vue'
import axios from 'axios'

export default {
  mixins: [validationMixin, withSnackbar],
  components: {
    'job-type-select': JobTypeSelect,
    'specialty-select': SpecialtySelect,
    'family-select': FamilySelect,
    'user-select': UserSelect
  },
  validations: {
    code: { required, maxLength: maxLength(4) },
    jobType: { required },
    specialty: { requiredIf: requiredIf((component) => {
      return component.jobType === component.teacherId
    }) },
    order: { required, numeric }
  },
  data () {
    return {
      dialog: false,
      error: false,
      errors: [],
      adding: false,
      jobType: null,
      specialty: null,
      code: this.proposedCode,
      family: null,
      holder: null,
      notes: '',
      order: 1,
      internalUsers: this.users
    }
  },
  props: {
    proposedCode: {
      type: String,
      required: false
    },
    teacherType: {
      type: String,
      required: true
    },
    jobTypes: {
      type: Array,
      required: true
    },
    specialties: {
      type: Array,
      required: true
    },
    families: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    }
  },
  watch: {
    specialty: function (newSpecialty) {
      if (newSpecialty) this.family = this.getSpecialty(newSpecialty.id).family_id
      else this.family = null
    },
    users () {
      this.internalUsers = this.users
    }
  },
  computed: {
    isTeacher () {
      return this.jobType && (this.jobType === this.teacherId)
    },
    codeErrors () {
      const errors = []
      if (!this.$v.code.$dirty) return errors
      !this.$v.code.maxLength && errors.push('El codi ha de tenir com a màxim 4 caràcters.')
      !this.$v.code.required && errors.push('El codi és obligatori.')
      return errors
    },
    jobTypeErrors () {
      const errors = []
      if (!this.$v.jobType.$dirty) return errors
      this.$v.jobType.$error && errors.push('El tipus és obligatori.')
      return errors
    },
    specialtyErrors () {
      const errors = []
      if (!this.$v.specialty.$dirty) return errors
      this.$v.specialty.$error && errors.push('La especialitat és obligatoria si el tipus és professor/a.')
      return errors
    },
    orderErrors () {
      const errors = []
      if (!this.$v.order.$dirty) return errors
      this.$v.order.$error && errors.push('Cal indicar un ordre (enter positiu)')
      return errors
    }
  },
  methods: {
    getSpecialty (specialtyId) {
      return this.specialties.find(specialty => specialty.id === specialtyId)
    },
    add (close) {
      close = close || false
      if (!this.$v.$invalid) {
        this.adding = true
        this.$store.dispatch(actions.STORE_JOB, {
          type: this.jobType,
          code: this.code,
          family: this.family,
          specialty: this.specialty.id,
          holder: this.holder,
          order: this.order,
          notes: this.notes
        }).then(response => {
          this.adding = false
          this.showMessage('Plaça afegida correctament')
          this.clear()
          if (close) this.dialog = false
        }).catch(error => {
          this.adding = false
          if (error.status === 422) this.mapErrors(error.data.errors)
          this.showError(error)
        })
      } else {
        this.$v.$touch()
      }
    },
    mapErrors (errors) {
      this.error = true
      Object.values(errors).forEach(error => {
        this.errors.push(error)
      })
    },
    clear () {
      axios.get('/api/v1/jobs/nextAvailableCode').then(response => {
        this.code = response.data
      }).catch(error => {
        this.showError(error)
      })
      axios.get('/api/v1/available-users').then(response => {
        this.internalUsers = response.data
      }).catch(error => {
        this.showError(error)
      })
      this.jobType = this.teacherId
      this.specialty = null
      this.family = null
      this.holder = null
      this.order = 1
      this.notes = ''
    }
  },
  created () {
    this.teacherId = this.jobTypes.find(jobType => jobType.name === 'Professor/a').id
    this.jobType = this.teacherId
  }
}
</script>
