<template>
    <v-dialog v-model="dialog" @keydown.esc="dialog = false">
        <v-btn slot="activator" icon class="mx-0" >
            <v-icon color="teal">edit</v-icon>
        </v-btn>
        <v-card>
            <v-card-title>
                <span class="headline">Plaça ({{job.fullcode}})</span>
            </v-card-title>
            <v-card-text>
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
                                                    :users="users"
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
                            </form>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click.native="dialog = false">Cancel·lar</v-btn>
                <v-btn color="green darken-1" class="white--text" @click.native="edit" :disabled="editing" :loading="editing">Modificar</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
  import JobTypeSelect from './JobTypeSelectComponent.vue'
  import SpecialtySelect from '../specialties/SpecialtySelectComponent'
  import FamilySelect from '../families/FamilySelectComponent'
  import UserSelect from '../users/UsersSelectComponent.vue'
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../mixins/withSnackbar'
  import { required, maxLength, requiredIf, numeric } from 'vuelidate/lib/validators'
  import * as actions from '../../store/action-types'

  export default {
    name: 'JobEditIconComponent',
    mixins: [validationMixin, withSnackbar],
    components: {
      'job-type-select': JobTypeSelect,
      'specialty-select': SpecialtySelect,
      'family-select': FamilySelect,
      'user-select': UserSelect
    },
    validations: {
      code: {required, maxLength: maxLength(4)},
      jobType: {required},
      specialty: {requiredIf: requiredIf((component) => {
        return component.jobType === component.teacherId
      })},
      order: {required, numeric}
    },
    data () {
      return {
        dialog: false,
        internalJob: this.job,
        editing: false,
        code: this.job.code,
        jobType: this.job.type_id,
        specialty: this.job.specialty_id,
        family: this.job.family_id,
        holder: this.job.holder_id,
        order: this.job.order,
        notes: this.job.notes
      }
    },
    props: {
      job: {
        required: true
      },
      jobTypes: {
        type: Array,
        required: true
      },
      users: {
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
      }
    },
    watch: {
      specialty (newSpecialty) {
        if (newSpecialty) this.family = this.getSpecialty(newSpecialty).family_id
        else this.family = null
      },
      job (newJob) {
        this.internalJob = newJob
        this.code = newJob.code
        this.jobType = newJob.type_id
        this.specialty = newJob.specialty_id
        this.family = newJob.family_id
        this.holder = newJob.holder_id
        this.order = newJob.order
        this.notes = newJob.notes
      },
      jobType (newJobType) {
        console.log('new jobType:')
        console.log(newJobType)
        console.log(this.teacherId)
        if (newJobType !== this.teacherId) {
          console.log('Is NOTE TEACHER!')
          this.specialty = null
          this.family = null
        }
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
      familyErrors () {
        const errors = []
        if (!this.$v.family.$dirty) return errors
        this.$v.specialty.$error && errors.push('La família és obligatoria si el tipus és professor/a.')
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
      edit () {
        if (!this.$v.$invalid) {
          this.editing = true
          this.$store.dispatch(actions.EDIT_JOB, {
            id: this.job.id,
            type: this.jobType,
            code: this.code,
            family: this.family,
            specialty: this.specialty,
            holder: this.holder,
            order: this.order,
            notes: this.notes
          }).then(response => {
            this.editing = false
            this.dialog = false
            this.showMessage('Plaça modificada correctament')
          }).catch(error => {
            this.editing = false
            console.log(error)
            if (error.status === 422) this.mapErrors(error.data.errors)
            this.showError(error)
          })
        } else {
          this.$v.$touch()
        }
      }
    },
    created () {
      this.teacherId = this.jobTypes.find(jobType => jobType.name === 'Professor/a').id
    }
  }
</script>
