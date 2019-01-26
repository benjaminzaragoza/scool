<template>
    <span>
        <v-tooltip bottom>
            <v-btn slot="activator" flat icon color="primary" class="ma-0" @click="dialog=true">
                <v-icon>visibility</v-icon>
            </v-btn>
            <span>Mostrar la incidència</span>
        </v-tooltip>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition"
                  @keydown.esc.stop.prevent="dialog=false">
            <incident-show :show-data="showData" @close="dialog=false" :incident="incident" v-role="'Incidents'" :tags="tags" :incident-users="incidentUsers"></incident-show>
        </v-dialog>
    </span>
</template>

<script>
import IncidentShowComponent from './IncidentShowComponent'
export default {
  name: 'IncidentShowIcon',
  components: {
    'incident-show': IncidentShowComponent
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    incident: {
      type: Object,
      required: true
    },
    showData: {
      type: Boolean,
      default: true
    },
    tags: {
      type: Array,
      required: true
    },
    incidentUsers: {
      type: Array,
      default: function () {
        return []
      }
    }
  }
}
</script>
