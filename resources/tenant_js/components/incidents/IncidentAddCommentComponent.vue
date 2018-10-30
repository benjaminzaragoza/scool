<template>
    <v-card>
        <v-toolbar dark color="primary" :id="'incident_' + incident.id + '_show_toolbar'">
            <v-btn :id="'incident_' + incident.id + '_show_close_button'" icon dark @click.native="$emit('close')">
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title> Incidència: {{ incident.subject }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn dark flat @click.native="$emit('close')">Guardar</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-container text-md-center class="pb-0">
            <v-layout row wrap>
                <v-flex md12>
                    <v-textarea
                            outline
                            name="input-7-4"
                            label="Descripció"
                            :value="incident.description"
                    ></v-textarea>
                </v-flex>
            </v-layout>
        </v-container>
        <v-divider></v-divider>
        <incident-comments :incident="incident"></incident-comments>
    </v-card>
</template>

<script>
import IncidentCommentsComponent from './IncidentCommentsComponent'

export default {
  name: 'IncidentAddCommentComponent',
  components: {
    'incident-comments': IncidentCommentsComponent
  },
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
  }
}
</script>
