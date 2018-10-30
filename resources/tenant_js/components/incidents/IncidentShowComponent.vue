<template>
    <v-card>
        <v-toolbar dark color="primary" :id="'incident_' + incident.id + '_show_toolbar'">
            <v-btn :id="'incident_' + incident.id + '_show_close_button'" icon dark @click.native="$emit('close')">
                <v-icon>close</v-icon>
            </v-btn>
            <user-avatar class="mr-2" :hash-id="incident.user.hashid"
                         :alt="incident.user.name"
                         v-if="incident.user.hashid"
            ></user-avatar>
            <v-icon v-if="incident.closed_at" color="red" title="Tancada">lock</v-icon>
            <v-icon v-else color="green" title="Oberta">lock_open</v-icon>
            <v-toolbar-title>{{ incident.subject }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn dark flat @click.native="$emit('close')">Guardar</v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-container text-md-center class="pb-0 pt-1">
            <v-expansion-panel class="mb-1" expandable>
                <v-expansion-panel-content :value="true">
                    <div slot="header" class="font-weight-medium">Dades de la incidència</div>
                    <v-layout row wrap>
                        <v-flex md8>
                            <v-list three-line subheader>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>{{ incident.user_name }} - <a target="_blank" :href="'https://mail.google.com/mail/?view=cm&fs=1&to=' + incident.user_email">{{incident.user_email}}</a>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Creada per
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title v-html="state"></v-list-tile-title>
                                        <v-list-tile-sub-title>Estat
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                        <v-flex md4>
                            <v-list three-line subheader>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title :title="incident.formatted_created_at" v-text="incident.formatted_created_at_diff"></v-list-tile-title>
                                        <v-list-tile-sub-title>Data de creació
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title :title="incident.formatted_updated_at" v-text="incident.formatted_created_at_diff">Password</v-list-tile-title>
                                        <v-list-tile-sub-title>Data de modificació
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                            </v-list>
                        </v-flex>
                    </v-layout>
                </v-expansion-panel-content>
            </v-expansion-panel>
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
        <incident-comments :incident="incident"></incident-comments>
    </v-card>
</template>

<script>
import IncidentCommentsComponent from './IncidentCommentsComponent'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'IncidentShowComponent',
  components: {
    'incident-comments': IncidentCommentsComponent,
    'user-avatar': UserAvatar
  },
  props: {
    incident: {
      type: Object,
      required: true
    }
  },
  computed: {
    state () {
      if (this.incident.closed_at) return 'Tancada <span title="' + this.incident.formatted_closed_at + '">' + this.incident.formatted_closed_at_diff + '</span>'
      return 'Oberta'
    }
  }
}
</script>
