<template>
    <v-btn v-if="alt" flat :title="title" class="ma-0"
           :loading="loading" :disabled="loading" :id="'close_incident_' + incident.id" @click="toggle" >
        {{ text }}
        <v-icon v-text="icon" right dark></v-icon>
    </v-btn>
    <v-btn v-else flat :color="color" icon :title="title" class="ma-0"
           :loading="loading" :disabled="loading" :id="'close_incident_' + incident.id" @click="toggle">
        <v-icon v-text="icon"></v-icon>
    </v-btn>
</template>

<script>
import * as actions from '../../store/action-types'

export default {
  name: 'IncidentClose',
  data () {
    return {
      close: true,
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
  computed: {
    text () {
      return this.close ? 'Tancar' : 'Obrir'
    },
    icon () {
      return this.close ? 'lock' : 'lock_open'
    },
    color () {
      return this.close ? 'success' : 'purple'
    },
    title () {
      return this.close ? 'Tancar la incidència' : 'Obrir la incidència'
    },
    action () {
      return this.close ? actions.CLOSE_INCIDENT : actions.OPEN_INCIDENT
    },
    actionMessage () {
      return this.close ? 'Incidència tancada correctament' : 'Incidència oberta correctament'
    }
  },
  watch: {
    incident (newIncident) {
      if (newIncident.closed_at) this.close = false
      else this.close = true
    }
  },
  model: {
    prop: 'incident',
    event: 'toggle'
  },
  methods: {
    toggle () {
      this.loading = true
      this.$emit('before')
      this.$store.dispatch(this.action, this.incident).then(response => {
        this.$snackbar.showMessage(this.actionMessage)
        this.loading = false
        this.close = !this.close
        this.$emit('toggle', this.close)
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    }
  },
  created () {
    if (this.incident.closed_at) this.close = false
  }
}
</script>
