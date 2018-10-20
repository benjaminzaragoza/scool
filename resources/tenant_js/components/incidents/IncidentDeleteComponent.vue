<template>
    <v-btn flat color="error" icon title="Eliminar la incidència"
           :loading="loading" :disabled="loading" :id="'delete_incident_' + incident.id" @click="remove">
        <v-icon>delete</v-icon>
    </v-btn>
</template>

<script>
import * as actions from '../../store/action-types'

export default {
  name: 'IncidentDelete',
  data () {
    return {
      loading: false
    }
  },
  props: {
    incident: {
      type: Object,
      required: true
    }
  },
  methods: {
    remove () {
      this.loading = true
      this.$store.dispatch(actions.DELETE_INCIDENT, this.incident).then(response => {
        this.$snackbar.showMessage('Incidència eliminada correctament')
        this.loading = false
      }).catch(error => {
        console.log(error)
        this.$snackbar.showError(error)
        this.loading = false
      })
    }
  }
}
</script>
