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
                    <!--<span :id="'incident_' + incident.id + '_comments'" three-line subheader v-if="incident.comments && incident.comments.length > 0">-->
                        <!--<v-card :id="'incident_' + incident.id + '_comment_' + comment.id" avatar v-for="comment in incident.comments" :key="comment.id">-->
                            <!--<v-layout>-->
                                <!--<v-flex xs1>-->
                                    <!--<user-avatar :hash-id="comment.user.hashid"-->
                                                 <!--:alt="comment.user.name"-->
                                                 <!--v-if="comment.user.hashid"-->
                                    <!--&gt;</user-avatar>-->
                                <!--</v-flex>-->
                                <!--<v-flex xs10>-->
                                    <!--<v-card-title primary-title v-html="comment.body"></v-card-title>-->
                                    <!--<v-card-text v-html="comment.user.name"></v-card-text>-->
                                <!--</v-flex>-->
                                <!--<v-flex xs1>-->
                                    <!--<v-card-actions class="pa-3">-->
                                        <!--<v-icon>delete</v-icon>-->
                                    <!--</v-card-actions>-->
                                <!--</v-flex>-->
                            <!--</v-layout>-->
                        <!--</v-card>-->
                    <!--</span>-->

                    <!--<span :id="'incident_' + incident.id + '_comments'" >-->
                        <!--<v-card :id="'incident_' + incident.id + '_comment_' + comment.id"-->
                                <!--avatar-->
                                <!--hover-->
                                <!--raised-->
                                <!--tile-->
                                <!--v-for="comment in incident.comments"-->
                                <!--:key="comment.id"-->
                                <!--class="mb-1">-->
                            <!--<v-layout>-->
                                <!--<v-flex xs1 align-center>-->
                                    <!--<user-avatar :hash-id="comment.user.hashid"-->
                                                 <!--:alt="comment.user.name"-->
                                                 <!--v-if="comment.user.hashid"-->
                                    <!--&gt;</user-avatar>-->
                                <!--</v-flex>-->
                                <!--<v-flex xs10>-->
                                    <!--<v-card-title primary-title v-text="comment.body" style="border: solid 1px"></v-card-title>-->
                                <!--</v-flex>-->
                                <!--<v-flex xs1>-->
                                    <!--<v-card-actions><v-btn>Listen now</v-btn></v-card-actions>-->
                                <!--</v-flex>-->
                            <!--</v-layout>-->
                        <!--</v-card>-->
                    <!--</span>-->

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
                                    <v-icon>delete</v-icon>
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
import * as actions from '../../store/action-types'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'IncidentShowComponent',
  components: {
    'reply-add': ReplyAddComponent,
    'user-avatar': UserAvatar
  },
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
  },
  methods: {
    addComment () {
      this.$store.dispatch(actions.SET_INCIDENTS).then(() => {
        this.$snackbar.showMessage('Comentari afegit correctament')
      }).catch(error => {
        this.$snackbar.showError(error)
      })
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
