<template>
    <span id="incidents_component">
        <floating-add :show="dataIncidents.length > 0" v-model="dialog" title="Nova incidència">
            <incident-add @close="dialog = false"></incident-add>
        </floating-add>
        <span v-if="dataIncidents.length > 0">
            <v-container fluid grid-list-md text-xs-center>
                <v-layout row wrap>
                    <v-flex xs12>
                        <incidents-list :incidents="dataIncidents" :incident="incident" :incident-users="incidentUsers" :manager-users="managerUsers" :tags="tags"></incidents-list>
                    </v-flex>
                </v-layout>
            </v-container>
        </span>
        <span v-else>
            <v-container grid-list-md text-xs-center>
                <v-layout row wrap>
                  <v-flex xs12 md6 offset-md3 class="mt-3">
                      <div class="grey lighten-3" style="width: 240px;height: 240px;padding: 60px;border-radius: 50%;display: inline-flex;">
                            <svg :style="'width:120px;height:120px;fill: ' + this.$vuetify.theme.primary.base + ';'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M6.47 9.8A5 5 0 0 1 .2 3.22l3.95 3.95 2.82-2.83L3.03.41a5 5 0 0 1 6.4 6.68l10 10-2.83 2.83L6.47 9.8z"/></svg>
                      </div>
                  </v-flex>
                  <v-flex xs12 md4 offset-md4 class="mt-3">
                      <h1 class="display-1 grey--text text--darken-2"><strong>Uups!</strong> No hi ha cap incidència... teniu el luxe 😛 de poder ser els primers en:</h1>
                  </v-flex>
                  <v-flex xs12 md6 offset-md3 class="mt-3">
                      <v-btn large color="accent" @click="dialog = true">
                          <v-icon left dark>add</v-icon>
                          Crear una incidència
                      </v-btn>
                  </v-flex>
                </v-layout>
              </v-container>
        </span>

    </span>
</template>

<script>
  import * as mutations from '../../store/mutation-types'

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
        required: true
      },
      incident: {
        type: Object,
        default: function () {
          return undefined
        }
      },
      incidentUsers: {
        type: Array,
        default: function () {
          return []
        }
      },
      managerUsers: {
        type: Array,
        default: function () {
          return []
        }
      },
      tags: {
        type: Array,
        default: function () {
          return []
        }
      }
    },
    computed: {
      dataIncidents () {
        return this.$store.getters.incidents
      }
    },
    created () {
      this.$store.commit(mutations.SET_INCIDENTS, this.incidents)
    }
  }
</script>
