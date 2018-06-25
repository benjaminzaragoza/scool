<template>
    <span>
        <v-switch
                :label="substitute ? 'Substitut' : 'Propietari'"
                v-model="substitute"
        ></v-switch>
        <date-picker v-if="substitute"
                     v-model="start_date"
                     label="Data d'inici"
                     name="start_date"
                     :error-messages="startDateErrors"
                     @input="$v.start_date.$touch()"
                     @blur="$v.start_date.$touch()"
                     required></date-picker>
        <jobs-select
                required
                :only-availables="!substitute"
                :job="job"
                :error-messages="jobErrors"
                @input="input($event)"
                @blur="$v.job.$touch()"
                @empty="empty"
                :jobs="jobs"
                label="Escolliu la plaça a assignar"></jobs-select>
        Codi de professor: mostrar el codi que s'assignarà (next available code) i permetre canviar?
        Departament: segons la plaça escollida es marca un departament per defecte
        <v-btn color="primary" @click="assign" :disabled="assigning" :loading="assigning">Assignar</v-btn>
        <v-btn color="error" @click="$emit('back')">Tornar endarrera</v-btn>
    </span>
</template>

<script>
  import DatePicker from '../ui/DatePicker'
  import moment from 'moment'
  import JobsSelect from './JobsSelectComponent.vue'
  import DepartmentsSelect from '../curriculum/departments/DepartmentsSelectComponent'
  import axios from 'axios'
  import withSnackbar from '../mixins/withSnackbar'
  import { validationMixin } from 'vuelidate'
  import { required, requiredIf } from 'vuelidate/lib/validators'

  export default {
    name: 'AssignJobToUserComponent',
    mixins: [withSnackbar, validationMixin],
    components: {
      'date-picker': DatePicker,
      'jobs-select': JobsSelect,
      'departments-select': DepartmentsSelect
    },
    validations: {
      start_date: {requiredIf: requiredIf((component) => {
        return component.substitute
      })},
      job: { required }
    },
    data () {
      return {
        start_date: moment(new Date()).format('YYYY-MM-DD'),
        job: {},
        substitute: true,
        assigning: false
      }
    },
    props: {
      user: {},
      jobs: {
        type: Array,
        required: true
      }
    },
    computed: {
      jobErrors () {
        const errors = []
        if (!this.$v.job.$dirty) return errors
        !this.$v.job.required && errors.push('Cal escollir una plaça a assignar')
        return errors
      },
      startDateErrors () {
        const errors = []
        if (!this.$v.start_date.$dirty) return errors
        this.$v.start_date.$error && errors.push("La data d'inici és obligatòria pels substituts")
        return errors
      }
    },
    methods: {
      empty () {
        this.showError('No hi ha cap plaça lliure per assignar')
      },
      assign () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.assigning = true
          axios.post('/api/v1/employee', {
            user_id: this.user,
            job_id: this.job.id,
            holder: !this.substitute,
            start_at: this.start_date
          }).then(response => {
            this.assigning = false
            this.showMessage('Plaça assignada correctament')
            this.$emit('assigned', response.data)
          }).catch(error => {
            this.assigning = false
            console.log(error)
            this.showError(error)
          })
        } else {
          this.$v.$touch()
        }
      },
      input (e) {
        this.$v.job.$touch()
        this.job = e
        this.$emit('input', e)
      }
    }
  }
</script>
