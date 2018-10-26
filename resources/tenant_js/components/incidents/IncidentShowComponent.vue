<template>
    <v-card>
            <v-toolbar dark color="primary" :id="'incident_' + incident.id + '_show_toolbar'">
                <v-btn :id="'incident_' + incident.id + '_show_close_button'" icon dark @click.native="$emit('close')">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title v-html="incident.subject"></v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark flat @click.native="$emit('close')">Guardar</v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-list three-line subheader>
                <v-subheader>User Controls</v-subheader>
                <v-list-tile avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>Content filtering</v-list-tile-title>
                        <v-list-tile-sub-title>Set the content filtering level to restrict apps that can be downloaded
                        </v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar>
                    <v-list-tile-content>
                        <v-list-tile-title>Password</v-list-tile-title>
                        <v-list-tile-sub-title>Require password for purchase or use password to restrict purchase
                        </v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
            <v-divider></v-divider>
            <v-list three-line subheader>
                <v-subheader>General</v-subheader>
                <v-list-tile avatar>
                    <v-list-tile-action>
                        TODO
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Notifications</v-list-tile-title>
                        <v-list-tile-sub-title>Notify me about updates to apps or games that I downloaded
                        </v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar>
                    <v-list-tile-action>
                        dasd
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Sound</v-list-tile-title>
                        <v-list-tile-sub-title>Auto-update apps at any time. Data charges may apply
                        </v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile avatar>
                    <v-list-tile-action>
                        TODO
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Auto-add widgets</v-list-tile-title>
                        <v-list-tile-sub-title>Automatically add home screen widgets</v-list-tile-sub-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
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
  }
}
</script>
