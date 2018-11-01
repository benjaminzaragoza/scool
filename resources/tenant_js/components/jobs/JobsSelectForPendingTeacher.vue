<template>
    <jobs-select
                 :job="internalJob"
                 @input="input($event)"
                 :jobs="jobs"
                 label="Escolliu la plaça a assignar"></jobs-select>
</template>

<script>
import JobsSelect from './JobsSelectComponent.vue'

export default {
  name: 'JobsSelectForPendingTeacher',
  components: {
    'jobs-select': JobsSelect
  },
  data () {
    return {
      internalJob: this.job
    }
  },
  model: {
    prop: 'job',
    event: 'input'
  },
  props: {
    job: {},
    jobs: {
      type: Array,
      required: true
    },
    teacher: {
      type: Object,
      required: true
    }
  },
  watch: {
    teacher (newValue) {
      let foundJob = this.jobs.find((job) => {
        return job.active_user_code === newValue.code
      })
      if (foundJob) {
        this.internalJob = foundJob
        this.$emit('input', this.internalJob)
      }
    }
  },
  methods: {
    input (e) {
      this.internalJob = e
      this.$emit('input', e)
    }
  }
}
</script>
