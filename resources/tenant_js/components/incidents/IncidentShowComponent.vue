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
            <span class="hidden-sm-and-down">
                <v-btn v-if="incident.closed_at" flat>
                    <v-icon left dark>lock</v-icon>
                    Tancada
                </v-btn>
                <v-btn v-else flat>
                    <v-icon left dark>lock_open</v-icon>
                    Oberta
                </v-btn>
                {{ incident.id }}
            </span>
            <v-toolbar-title>
                <inline-text-field-edit-dialog :object="incident" field="subject" label="Títol" @save="refresh"></inline-text-field-edit-dialog>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <incident-close :incident="incident" v-can:close="incident" :alt="true" class="hidden-sm-and-down" @before="$emit('close')"></incident-close>
                <incident-delete :incident="incident" v-role="'IncidentsManager'" :alt="true" @before="$emit('close')" class="hidden-sm-and-down"></incident-delete>
                <v-btn dark flat @click.native="$emit('close')" class="hidden-sm-and-down">
                    Sortir
                    <v-icon right dark>exit_to_app</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-container text-md-center class="pb-0 pt-1">
            <v-expansion-panel class="mb-3 mt-2" :value="show">
                <v-expansion-panel-content>
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
                                    <v-list-tile-action>
                                        <v-icon v-if="incident.closed_at">lock</v-icon>
                                        <v-icon v-else>lock_open</v-icon>
                                    </v-list-tile-action>
                                    <v-list-tile-content>
                                        <v-list-tile-title v-html="state"></v-list-tile-title>
                                        <v-list-tile-sub-title>Estat
                                        </v-list-tile-sub-title>
                                    </v-list-tile-content>
                                </v-list-tile>
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <incident-tags @refresh="refresh(false)" :incident="incident" :tags="dataTags" ></incident-tags>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Etiquetes
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
                                <v-list-tile avatar>
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <incident-assignees @refresh="refresh" :assignees="incident.assignees" :incident="incident" :incident-users="incidentUsers"></incident-assignees>
                                        </v-list-tile-title>
                                        <v-list-tile-sub-title>Assignada a
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
            <v-layout>
                <v-flex md12>

                    <v-expansion-panel class="mb-3 mt-2" :value="0">
                        <v-expansion-panel-content>
                            <div slot="header" class="font-weight-medium">Descripció</div>
                            <v-layout row wrap>
                                <v-flex class="ma-3 white-space-pre-wrap">
                                    <inline-text-area-edit-dialog :object="incident" field="description" label="Descripció" @save="refresh" :limit="false"></inline-text-area-edit-dialog>
                                </v-flex>
                            </v-layout>
                        </v-expansion-panel-content>
                    </v-expansion-panel>

                </v-flex>
            </v-layout>
        </v-container>
        <incident-comments :incident="incident" @close="close"></incident-comments>
    </v-card>
</template>

<script>
import IncidentCommentsComponent from './IncidentCommentsComponent'
import IncidentCloseComponent from './IncidentCloseComponent'
import IncidentDeleteComponent from './IncidentDeleteComponent'
import UserAvatar from '../ui/UserAvatarComponent'
import InlineTextAreaEditDialog from '../ui/InlineTextAreaEditDialog'
import InlineTextFieldEditDialog from '../ui/InlineTextFieldEditDialog'
import IncidentTagsComponent from './IncidentTagsComponent'
import IncidentAssigneesComponent from './IncidentAssigneesComponent'
import * as actions from '../../store/action-types'

export default {
  name: 'IncidentShow',
  components: {
    'incident-comments': IncidentCommentsComponent,
    'user-avatar': UserAvatar,
    'incident-close': IncidentCloseComponent,
    'incident-delete': IncidentDeleteComponent,
    'inline-text-area-edit-dialog': InlineTextAreaEditDialog,
    'inline-text-field-edit-dialog': InlineTextFieldEditDialog,
    'incident-tags': IncidentTagsComponent,
    'incident-assignees': IncidentAssigneesComponent
  },
  data () {
    return {
      show: this.showData ? 0 : null,
      dataTags: this.tags
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
  },
  computed: {
    state () {
      if (this.incident.closed_at) return 'Tancada <span title="' + this.incident.formatted_closed_at + '">' + this.incident.formatted_closed_at_diff + '</span> per <span title="' + this.incident.closer.email + '">' + this.incident.closer.name + '</span>'
      return 'Oberta'
    }
  },
  methods: {
    refresh () {
      this.$store.dispatch(actions.SET_INCIDENTS).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    close () {
      this.$emit('close')
    }
  }
}
</script>

<style scoped>
    .white-space-pre-wrap {
        white-space: pre-wrap;
    }
</style>
