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
        <v-container text-md-center class="pb-0 pt-1">
            <v-layout row wrap>
                <v-flex md8>
                    <v-list three-line subheader>
                        <v-subheader>Dades</v-subheader>
                        <v-list-tile avatar>
                            <v-list-tile-content>
                                <v-list-tile-title> {{ incident.username + ' - ( ' + incident.user_email +' )'}}</v-list-tile-title>
                                <v-list-tile-sub-title>Creada per
                                </v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile avatar>
                            <v-list-tile-content>
                                <v-list-tile-title v-text="state"></v-list-tile-title>
                                <v-list-tile-sub-title>Estat
                                </v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-flex>
                <v-flex md4>
                    <v-list three-line subheader>
                        <v-subheader>Altres dades</v-subheader>
                        <v-list-tile avatar>
                            <v-list-tile-content>
                                <v-list-tile-title v-text="incident.formatted_created_at"></v-list-tile-title>
                                <v-list-tile-sub-title>Data de creació
                                </v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile avatar>
                            <v-list-tile-content>
                                <v-list-tile-title v-text="incident.formatted_updated_at">Password</v-list-tile-title>
                                <v-list-tile-sub-title>Data de modificació
                                </v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-flex>
            </v-layout>
        </v-container>
        <v-divider></v-divider>
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
        <v-container text-md-center class="pb-0 pt-1">
            <v-layout row wrap>
                <v-flex md12>
                    <v-list three-line subheader>
                        <v-subheader>Comentaris</v-subheader>
                        <v-list-tile avatar>
                            <v-list-tile-content>
                                <v-list-tile-title> TODO COMENTRAI 1</v-list-tile-title>
                                <v-list-tile-sub-title>das
                                </v-list-tile-sub-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-flex>
            </v-layout>
        </v-container>
        </v-card>
</template>

<script>
export default {
  name: 'IncidentShowComponent',
  data () {
    return {
      todo: false
    }
  },
  props: {
    incident: {
      type: Object,
      required: true
    }
  },
  computed: {
    state () {
      if (this.incident.closed_at) return 'Tancada el ' + this.incident.formatted_closed_at
      return 'Oberta'
    }
  }
}
</script>
