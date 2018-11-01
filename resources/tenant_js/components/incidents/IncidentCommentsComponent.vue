<template>
    <v-container text-md-center class="pb-0 pt-1">
        <v-layout row wrap>
            <v-flex md8>
                <v-list three-line subheader>
                    <v-subheader>
                        Comentaris
                    </v-subheader>
                </v-list>
            </v-flex>
            <v-flex md4>
                <v-switch
                        :label="orderCommentByDateAsc ? 'Els últims comentaris primer' : 'Els primers comentaris primer'"
                        v-model="orderCommentByDateAsc"
                ></v-switch>
            </v-flex>

            <v-flex md12>
                <reply-add :repliable="incident" @added="addComment"></reply-add>
            </v-flex>
            <v-flex md12>
                <v-list two-line
                        :id="'incident_' + incident.id + '_comments'"
                        subheader
                        v-if="incident.comments && incident.comments.length > 0">
                        <span :id="'incident_' + incident.id + '_comment_' + comment.id" avatar v-for="comment in orderedComments" :key="comment.id">
                            <v-list-tile>
                                <v-list-tile-avatar class="comment-avatar">
                                    <user-avatar :hash-id="comment.user.hashid"
                                                 :alt="comment.user.name"
                                                 v-if="comment.user.hashid"
                                    ></user-avatar>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-sub-title><span :title="comment.user.email + ' - ' + comment.user_id">{{comment.user.name}}</span> &#8226; <span :title="comment.formatted_created_at">{{comment.formatted_created_at_diff}}</span></v-list-tile-sub-title>
                                    <v-list-tile-title :title="comment.body" class="height-auto-24-pre-wrap">
                                        <inline-text-area-edit-dialog v-if="$hasRole('IncidentsManager')" :object="comment" field="body" label="Comentari" @save="refresh('Comentari actualitzat correctament')" :limit="false"></inline-text-area-edit-dialog>
                                        <span v-else v-text="comment.body"></span>
                                    </v-list-tile-title>
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
import InlineTextAreaEditDialog from '../ui/InlineTextAreaEditDialog'
import * as actions from '../../store/action-types'

let compareCommentBytimestamp = (a, b) => {
  if (a.created_at_timestamp < b.created_at_timestamp) { return -1 }
  if (a.created_at_timestamp > b.created_at_timestamp) { return 1 }
  return 0
}

export default {
  name: 'IncidentCommentsComponent',
  components: {
    'reply-add': ReplyAddComponent,
    'reply-delete': ReplyDeleteComponent,
    'user-avatar': UserAvatar,
    'inline-text-area-edit-dialog': InlineTextAreaEditDialog
  },
  data () {
    return {
      value: '',
      orderCommentByDateAsc: false
    }
  },
  computed: {
    orderedComments () {
      if (!this.orderCommentByDateAsc) return this.incident.comments.concat().sort(compareCommentBytimestamp)
      return this.incident.comments.concat().sort(compareCommentBytimestamp).reverse()
    }
  },
  props: {
    incident: {
      type: Object,
      required: true
    }
  },
  methods: {
    refresh (message) {
      this.$store.dispatch(actions.SET_INCIDENTS).then(() => {
        this.$snackbar.showMessage(message)
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    addComment () {
      this.refresh('Comentari afegit correctament')
    },
    deletedComment () {
      this.refresh('Comentari eliminat correctament')
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
