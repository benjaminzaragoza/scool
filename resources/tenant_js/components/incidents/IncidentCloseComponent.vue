<template>
    <v-btn flat :color="color" icon :title="title"
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
    }
  },
  computed: {
    icon () {
      return this.close ? 'close' : 'folder_open'
    },
    color () {
      return this.close ? 'error' : 'success'
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
  methods: {
    toggle () {
      this.loading = true
      this.$store.dispatch(this.action, this.incident).then(response => {
        this.$snackbar.showMessage(this.actionMessage)
        this.loading = false
        this.close = !this.close
      }).catch(error => {
        console.log(error)
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
