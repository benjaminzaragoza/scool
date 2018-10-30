<template>
    <v-card>
        <v-toolbar dark color="primary" :id="'incident_' + incident.id + '_add_comment_toolbar'">
            <v-btn :id="'incident_' + incident.id + '_add_comment_close_button'" icon dark @click.native="$emit('close')">
                <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title> Incidència: {{ incident.subject }}</v-toolbar-title>
        </v-toolbar>
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
export default {
  name: 'IncidentAddCommentComponent',
  data () {
    return {
      loading: false
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
