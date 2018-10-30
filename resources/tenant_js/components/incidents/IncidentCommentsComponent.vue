<template>
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
                                <v-list-tile-avatar class="comment-avatar">
                                    <user-avatar :hash-id="comment.user.hashid"
                                                 :alt="comment.user.name"
                                                 v-if="comment.user.hashid"
                                    ></user-avatar>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-sub-title><span :title="comment.user.email + ' - ' + comment.user_id">{{comment.user.name}}</span> &#8226; a year ago</v-list-tile-sub-title>
                                    <v-list-tile-title :title="comment.body" class="height-auto-24-pre-wrap">{{ comment.body }}</v-list-tile-title>
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
</template>

<script>
import ReplyAddComponent from '../replies/ReplyAddComponent'
import ReplyDeleteComponent from '../replies/ReplyDeleteComponent'
import UserAvatar from '../ui/UserAvatarComponent'
import * as actions from '../../store/action-types'

export default {
  name: 'IncidentCommentsComponent',
  components: {
    'reply-add': ReplyAddComponent,
    'reply-delete': ReplyDeleteComponent,
    'user-avatar': UserAvatar
  },
  data () {
    return {
      value: ''
    }
  },
  props: {
    incident: {
      type: Object,
      required: true
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
