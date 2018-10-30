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
                                <v-list-tile-title> {{ incident.user_name + ' - ( ' + incident.user_email +' )'}}</v-list-tile-title>
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
                <v-list three-line subheader>
                    <v-subheader>Comentaris</v-subheader>
                </v-list>
                <v-flex md12>
                    <reply-add :repliable="incident" @added="addComment"></reply-add>
                </v-flex>
                <v-flex md12>
                    <v-list two-line
                            :id="'incident_' + incident.id + '_comments'"
                            subheader
                            v-if="incident.comments && incident.comments.length > 0">
                        <span :id="'incident_' + incident.id + '_comment_' + comment.id" avatar v-for="comment in incident.comments" :key="comment.id">
                            <v-list-tile>
                                <v-list-tile-avatar>
                                    <user-avatar :hash-id="comment.user.hashid"
                                                 :alt="comment.user.name"
                                                 v-if="comment.user.hashid"
                                    ></user-avatar>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-sub-title><span :title="comment.user.email + ' - ' + comment.user_id">{{comment.user.name}}</span> &#8226; a year ago</v-list-tile-sub-title>
                                    <v-list-tile-title :title="comment.body">{{ comment.body }}</v-list-tile-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <reply-delete v-role="'IncidentsManager'" :repliable="incident" :reply="comment" @deleted="deletedComment"></reply-delete>
                                </v-list-tile-action>
                            </v-list-tile>
                            <v-divider></v-divider>
                        </span>
                    </v-list>
                </v-flex>
            </v-layout>
        </v-container>
        </v-card>
</template>

<script>
import ReplyAddComponent from '../replies/ReplyAddComponent'
import ReplyDeleteComponent from '../replies/ReplyDeleteComponent'
import * as actions from '../../store/action-types'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'IncidentShowComponent',
  components: {
    'reply-add': ReplyAddComponent,
    'reply-delete': ReplyDeleteComponent,
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
      if (this.incident.closed_at) return 'Tancada el ' + this.incident.formatted_closed_at
      return 'Oberta'
    }
  },
  methods: {
    refresh_incidents (message) {
      this.$store.dispatch(actions.SET_INCIDENTS).then(() => {
        this.$snackbar.showMessage(message)
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    addComment () {
      this.refresh_incidents('Comentari afegit correctament')
    },
    deletedComment () {
      this.refresh_incidents('Comentari eliminat correctament')
    }
  }
}
</script>

<style>
    .v-list--two-line .v-list__tile {
        height: auto;
        min-height: 72px;
    }

    .v-list__tile__title {
        height: auto;
        min-height: 24px;
        white-space: pre-wrap;
    }

    .v-list--two-line .v-list__tile__avatar {
        margin-top: 9px;
        align-self: flex-start;
    }
</style>
