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
                     required></date-picker>
        <jobs-select
                required
                :only-availables="!substitute"
                :job="job"
                @input="input($event)"
                :jobs="jobs"
                label="Escolliu la plaça a assignar"></jobs-select>
        <v-btn color="primary" @click="assign" :disabled="assigning" :loading="assigning">Assignar</v-btn>
        <v-btn color="error" @click="$emit('back')">Tornar endarrera</v-btn>
    </span>
</template>

<script>
  import DatePicker from '../ui/DatePicker'
  import moment from 'moment'
  import JobsSelect from './JobsSelectComponent.vue'
  import axios from 'axios'
  import withSnackbar from '../mixins/withSnackbar'
  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'

  export default {
    name: 'AssignJobToUserComponent',
    mixins: [withSnackbar, validationMixin],
    components: {
      'date-picker': DatePicker,
      'jobs-select': JobsSelect
    },
    validations: {
      start_date: { required },
      job: { required }
    },
    data () {
      return {
        start_date: moment(new Date()).format('YYYY-MM-DD'),
        job: {},
        administrativeStatus: null,
        substitute: true,
        assigning: false
      }
    },
    props: {
      user: {},
      jobs: {
        type: Array,
        required: true
      },
      administrativeStatuses: {
        type: Array,
        required: true
      }
    },
    methods: {
      assign () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.assigning = true
          axios.post('/api/v1/employee', {
            user_id: this.user,
            job_id: this.job_id,
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
          // if (this.$v.user.$dirty) {
          //   !this.$v.user.required && this.showError('Cal escollir un usuari com a substitut')
          // }
          this.$v.$touch()
        }
      },
      input (e) {
        this.job = e
        this.$emit('input', e)
      }
    }
  }
</script>
