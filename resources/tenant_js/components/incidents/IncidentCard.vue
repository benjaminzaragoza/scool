<template>
    <v-card class="elevation-3" v-if="!closed" style="height:100%;">
        <v-toolbar dense color="white" class="elevation-0">
            <v-toolbar-items>
                <changelog-loggable :loggable="incident" ></changelog-loggable>
                <incident-show-icon
                        :incident="incident"
                        v-role="'Incidents'"
                        :tags="tags"
                        :incident-users="incidentUsers">
                </incident-show-icon>
                <incident-close
                        v-if="$can('close',incident) || $hasRole('IncidentsManager')"
                        v-model="incident"
                        @toggle="refresh">
                </incident-close>
                <incident-delete :incident="incident" v-if="$hasRole('IncidentsManager')"></incident-delete>
            </v-toolbar-items>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn class="ma-0" icon @click.native="closed=true;$emit('close')">
                    <v-icon color="grey">close</v-icon>
                </v-btn>
                <v-btn class="ma-0" v-if="!minified" icon @click.native="minified=true;$emit('minified')">
                    <v-icon color="grey">remove</v-icon>
                </v-btn>
                <v-btn class="ma-0" v-else icon @click.native="minified=false;$emit('maxified')">
                    <v-icon color="grey">add</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-divider></v-divider>
        <v-container fluid grid-list-xs v-if="!minified">
            <v-layout row wrap>
                <v-flex xs4 align-top>
                    <user-avatar class="mr-2" :hash-id="dataIncident.user_hashid"
                                 :alt="dataIncident.user_name"
                                 v-if="dataIncident.user_hashid"
                                 color="grey lighten-4"
                                 size="135"
                    ></user-avatar>
                </v-flex>
                <v-flex xs8>
                    <v-tooltip left>
                        <h1 slot="activator"
                            class="grey--text text--darken-3 headline font-weight-black"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                            v-text="dataIncident.subject">
                        </h1>
                        <span>  {{ dataIncident.id }} - {{ dataIncident.subject }}</span>
                    </v-tooltip>

                    <h2
                            class="pink--text text--lighten-2 font-weight-bold"
                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <v-tooltip left>
                            <span slot="activator" v-text="dataIncident.description"></span>
                            <span v-text="dataIncident.description"></span>
                        </v-tooltip>
                    </h2>
                    <incident-tags
                            @refresh="refresh(false)"
                            :incident="dataIncident"
                            :tags="tags" >
                    </incident-tags>
                    <incident-assignees
                            @refresh="refresh"
                            :assignees="dataIncident.assignees"
                            :incident="dataIncident"
                            :incident-users="incidentUsers">
                    </incident-assignees>
                    <p class="grey--text text--darken-2 mt-2">
                        <template v-if="dataIncident.created_at">
                            Incidència creada
                            <v-tooltip bottom>
                                <span slot="activator" class="font-weight-bold">{{ dataIncident.formatted_created_at_diff }}</span>
                                <span>{{ dataIncident.formatted_created_at}}</span>
                            </v-tooltip> per {{ dataIncident.user_name }}.
                        </template>
                        <template v-if="dataIncident.updated_at"> Ultima modificació
                            <v-tooltip bottom>
                                <span slot="activator" class="font-weight-bold">{{ dataIncident.formatted_updated_at_diff }}</span>
                                <span>{{ dataIncident.formatted_updated_at}}</span>
                            </v-tooltip>
                        </template>
                    </p>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>

<script>
import ChangelogLoggable from '../changelog/ChangelogLoggable'
import UserAvatar from '../ui/UserAvatarComponent'
import IncidentShowIconComponent from './IncidentShowIconComponent'
import IncidentCloseComponent from './IncidentCloseComponent'
import IncidentDeleteComponent from './IncidentDeleteComponent'
import IncidentTagsComponent from './IncidentTagsComponent'
import IncidentAssigneesComponent from './IncidentAssigneesComponent'

export default {
  name: 'IncidentCard',
  components: {
    'user-avatar': UserAvatar,
    'changelog-loggable': ChangelogLoggable,
    'incident-show-icon': IncidentShowIconComponent,
    'incident-close': IncidentCloseComponent,
    'incident-delete': IncidentDeleteComponent,
    'incident-tags': IncidentTagsComponent,
    'incident-assignees': IncidentAssigneesComponent
  },
  data () {
    return {
      dataIncident: this.incident,
      closed: false,
      minified: false
    }
  },
  props: {
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
      required: true
    }
  },
  watch: {
    incident (incident) {
      this.dataIncident = incident
    }
  },
  methods: {
    refresh (message) {
      this.$emit('refresh', message)
    }
  }
}
</script>
