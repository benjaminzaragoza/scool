<template>
    <span id="incidents_component">
        <floating-add v-model="dialog" title="Nova incidència">
            <incident-add @close="dialog = false"></incident-add>
        </floating-add>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12>
                    <incidents-list :incidents="incidents"></incidents-list>
                </v-flex>
            </v-layout>
        </v-container>
    </span>
</template>

<script>
export default {
  name: 'Incidents',
  data () {
    return {
      dialog: false
    }
  },
  props: {
    incidents: {
      type: Array,
      default: function () {
        return undefined
      }
    }
  }
}
</script>
