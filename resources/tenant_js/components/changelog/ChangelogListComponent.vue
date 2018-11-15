<template>
    <span>
        <v-toolbar color="blue darken-3">
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile href="/jobs/sheet_holders" target="_blank">
                        <v-list-tile-title>TODO 0 Estadístiques</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Registre de canvis</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-btn flat class="white--text" @click="realTime=!realTime" >
                <span class="mr-1">Temps real</span> <v-icon v-if="realTime">check_box</v-icon><v-icon v-else>check_box_outline_blank</v-icon>
            </v-btn>

            <fullscreen-dialog
                    :flat="false"
                    class="white--text"
                    icon="settings"
                    v-model="settingsDialog"
                    color="blue darken-3"
                    title="Canviar la configuració del registre de canvis">
                        <changelog-settings module="changelog" @close="settingsDialog = false"></changelog-settings>
            </fullscreen-dialog>

            <v-btn id="incidents_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                <v-icon>refresh</v-icon>
            </v-btn>
        </v-toolbar>
        <v-card>
              <v-container fluid>
                <v-timeline dense clipped>
                    <v-slide-x-transition group>
                        <v-timeline-item
                        v-for="log in dataLogs"
                        :key="log.id"
                        class="mb-3"
                        :color="log.color"
                        :icon="log.icon"
                        small
                        >
                          <v-layout justify-space-between>
                            <v-flex xs2 text-xs-left align-self-center>
                                <user-avatar class="mr-2" :hash-id="log.user.hashid"
                                             :alt="log.user.name"
                                             v-if="log.user.hashid"
                                ></user-avatar>
                                <span :title="log.user.email">{{log.user.name}}</span>
                            </v-flex>
                              <v-flex xs1 text-xs-left align-self-center>
                                <span :title="log.time">{{ log.human_time }}</span>
                            </v-flex>
                            <v-flex xs7 v-html="log.text" text-xs-left align-self-center></v-flex>
                            <v-flex xs1 text-xs-left align-self-center>
                                <v-icon :title="'Mòdul ' + log.module.text">{{ log.module.icon }}</v-icon>
                            </v-flex>
                            <v-flex xs1 text-xs-left align-self-center>
                                <v-icon :title="'Acció: ' + log.action.text">{{ log.action.icon }}</v-icon>
                            </v-flex>
                          </v-layout>
                        </v-timeline-item>
                    </v-slide-x-transition>
                </v-timeline>
              </v-container>
        </v-card>
    </span>
</template>

<script>
import FullScreenDialog from '../ui/FullScreenDialog'
import ChangelogSettings from './ChangelogSettingsComponent'
import UserAvatar from '../ui/UserAvatarComponent'

export default {
  name: 'ChangelogList',
  components: {
    'fullscreen-dialog': FullScreenDialog,
    'changelog-settings': ChangelogSettings,
    'user-avatar': UserAvatar
  },
  data () {
    return {
      settingsDialog: false,
      refreshing: false,
      dataLogs: this.logs,
      realTime: true
    }
  },
  props: {
    logs: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    }
  },
  methods: {
    refresh () {
      this.fetch()
    },
    fetch () {
      this.refreshing = true
      window.axios.get('/api/v1/changelog').then(response => {
        this.$snackbar.showMessage('Registre de canvis actualitzat correctament')
        this.dataLogs = response.data
        this.refreshing = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.refreshing = false
      })
    }
  }
}
</script>
