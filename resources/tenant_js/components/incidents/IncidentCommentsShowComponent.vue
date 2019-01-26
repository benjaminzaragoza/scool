<template>
    <span>
        <v-tooltip bottom>
            <v-btn slot="activator" flat icon color="teal" class="ma-0" @click="dialog=true">
                <v-badge left overlap color="teal">
                  <span slot="badge" v-text="badge"></span>
                  <v-icon large>chat_bubble_outline</v-icon>
                </v-badge>
            </v-btn>
            <span>Mostra els comentaris</span>
        </v-tooltip>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition"
                  @keydown.esc.stop.prevent="dialog=false">
            <incident-show :show-data="false" @close="dialog=false" :incident="incident" v-role="'Incidents'" :tags="tags" :incident-users="incidentUsers"></incident-show>
        </v-dialog>
    </span>
</template>

<script>
import IncidentShowComponent from './IncidentShowComponent'
export default {
  name: 'IncidentCommentsShow',
  components: {
    'incident-show': IncidentShowComponent
  },
  data () {
    return {
      dialog: false
    }
  },
  props: {
    badge: {
      type: Number,
      required: true
    },
    incident: {
      type: Object,
      required: true
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
