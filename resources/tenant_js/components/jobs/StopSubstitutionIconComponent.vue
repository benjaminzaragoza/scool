<template>
    <v-btn title="Finalitzar substitució actual" icon class="mx-0" @click="finishSubstitution()" :disabled="!activeSubstituteExists || finishingSubstitution" :loading="finishingSubstitution">
        <v-icon>stop</v-icon>
    </v-btn>
</template>

<script>
  import moment from 'moment'
  import axios from 'axios'
  import withSnackbar from '../mixins/withSnackbar'

  export default {
    name: 'StopSubstitutionIconComponent',
    mixins: [withSnackbar],
    data () {
      return {
        finishingSubstitution: false
      }
    },
    props: {
      job: {
        type: Object,
        required: true
      }
    },
    computed: {
      activeSubstituteExists () {
        if (this.job.substitutes.filter(substitute => substitute.end_at == null).length > 0) return true
        if (this.job.substitutes.filter(substitute => {
          return moment(substitute.end_at).isAfter(moment())
        }).length > 0) return true
        return false
      },
      activeSubstitute () {
        let activeSubstitutes = this.job.substitutes.filter(substitute => substitute.end_at == null)
        if (activeSubstitutes.length > 0) return activeSubstitutes[0]
        activeSubstitutes = this.job.substitutes.filter(substitute => {
          return moment(substitute.end_at).isAfter(moment())
        })
        if (activeSubstitutes.length > 0) return activeSubstitutes[0]
        return null
      }
    },
    methods: {
      finishSubstitution () {
        this.finishingSubstitution = true
        axios.put('/api/v1/job/' + this.job.id + '/substitution', {
          user_id: this.activeSubstitute.id,
          end_at: moment().format('YYYY-MM-DD hh:mm:ss')
        }).then(response => {
          this.finishingSubstitution = false
          this.showMessage('Substitució finalitzada correctament')
          this.$emit('change')
        }).catch(error => {
          this.finishingSubstitution = false
          console.log(error)
          this.showError(error)
        })
      }
    }
  }
</script>
