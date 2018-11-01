<template>
    <v-btn v-if="alt" flat title="Eliminar la incidència"  class="ma-0"
           :loading="loading" :disabled="loading" :id="'delete_incident_' + incident.id" @click="remove" >
        Eliminar
        <v-icon right dark>delete</v-icon>
    </v-btn>
    <v-btn v-else flat color="error" icon title="Eliminar la incidència" class="ma-0"
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
    },
    alt: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    async remove () {
      let res = await this.$confirm('Les incidències esborrades no es poden recuperar.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.$emit('before')
        this.loading = true
        this.$store.dispatch(actions.DELETE_INCIDENT, this.incident).then(response => {
          this.$snackbar.showMessage('Incidència eliminada correctament')
          this.loading = false
        }).catch(error => {
          this.$snackbar.showError(error)
          this.loading = false
        })
      }
    }
  }
}
</script>
