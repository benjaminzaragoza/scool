<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-data-table
                            class="px-0 mb-2 hidden-sm-and-down"
                            :headers="headers"
                            :items="filteredIncidents"
                            :search="search"
                            item-key="id"
                            no-results-text="No s'ha trobat cap registre coincident"
                            no-data-text="No hi han dades disponibles"
                            rows-per-page-text="Incidències per pàgina"
                    >
                        <template slot="items" slot-scope="{item: incident}">
                            <tr>
                                <td class="text-xs-left" v-html="incident.id"></td>
                                <td class="text-xs-left" v-html="incident.user_id"></td>
                                <td class="text-xs-left" v-html="incident.subject"></td>
                                <td class="text-xs-left" v-html="incident.description"></td>
                                <td class="text-xs-left">
                                    TODO ACTIONS
                                </td>
                            </tr>
                        </template>
                    </v-data-table>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
  export default {
    data () {
      return {
        search: ''
      }
    },
    props: {
      incidents: {
        type: Array,
        required: true
      }
    },
    computed: {
      filteredIncidents: function () {
        return this.incidents
      },
      headers () {
        let headers = []
        headers.push({text: 'Id', align: 'left', value: 'id'})
        // if (this.showJobTypeHeader) {
        //   headers.push({text: 'Tipus', value: 'type'})
        // }
        headers.push({text: 'Usuari', value: 'user_id'})
        headers.push({text: 'Títol', value: 'subject'})
        headers.push({text: 'Description', value: 'description'})
        headers.push({text: 'Accions', sortable: false})
        return headers
      }
    }
  }
</script>
