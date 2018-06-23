<template>
    <span>
        <v-switch
                :label="substitute ? 'Substitut' : 'Propietari'"
                v-model="substitute"
        ></v-switch>
        <date-picker v-if="substitute" v-model="start_date" label="Data d'inici" name="start_date"></date-picker>
        <jobs-select
                :only-availables="!substitute"
                :job="job"
                @input="input($event)"
                :jobs="jobs"
                label="Escolliu la plaça a assignar"></jobs-select>
        <v-btn color="primary" @click="assign">Assignar</v-btn>
        <v-btn color="error" @click="$emit('back')">Tornar endarrera</v-btn>
    </span>
</template>

<script>
  import DatePicker from '../ui/DatePicker'
  import moment from 'moment'
  import JobsSelect from './JobsSelectComponent.vue'

  export default {
    name: 'AssignJobToUserComponent',
    components: {
      'date-picker': DatePicker,
      'jobs-select': JobsSelect
    },
    data () {
      return {
        start_date: moment(new Date()).format('YYYY-MM-DD'),
        job: {},
        administrativeStatus: null,
        substitute: true
      }
    },
    props: {
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
        console.log('ASSIGN TODO')
      },
      input (e) {
        this.job = e
        this.$emit('input', e)
      }
    }
  }
</script>
