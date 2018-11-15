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
                        v-for="log in logs"
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
      logs: [
        {
          id: 1,
          text: "Ha creat la incidència <a target='_blank' href='/incidents/1'>No funciona res a l'aula 23</a>",
          action: {
            name: 'store',
            text: 'Creació',
            icon: 'add'
          },
          time: '11:45:34 23/10/2018',
          human_time: '1 minut abans dasasd asd sad',
          timestamp: 123123312,
          module: {
            name: 'Incidents',
            text: 'Incidències',
            icon: 'build'
          },
          user: {
            id: 1,
            name: 'Sergi Tur Badenas',
            email: 'sergiturbadenas@gmail.com',
            hashid: 'Og1'
          },
          icon: 'home',
          color: 'teal'
        },
        {
          id: 2,
          text: "Ha modificat la incidència <a target='_blank' href='incidents/1'>No funciona res a l'aula 23</a>",
          action: {
            name: 'update',
            text: 'Edició',
            icon: 'edit'
          },
          time: '11:45:34 23/10/2018',
          human_time: '1 minut abans',
          timestamp: 123123312,
          module: {
            name: 'Incidents',
            text: 'Incidències',
            icon: 'build'
          },
          user: {
            id: 1,
            name: 'Jordi Varas',
            email: 'sergiturbadenas@gmail.com',
            hashid: 'ARy'
          },
          icon: 'home'
        },
        {
          id: 3,
          text: "Ha eliminat la incidència <a target='_blank' href='incidents/1'>No funciona res a l'aula 23</a>",
          action: {
            name: 'destroy',
            text: 'Eliminació',
            icon: 'remove'
          },
          time: '11:45:34 23/10/2018',
          human_time: '1 minut abans',
          timestamp: 123123312,
          module: {
            name: 'Incidents',
            text: 'Incidències',
            icon: 'build'
          },
          user: {
            id: 1,
            name: 'Sergi Tur Badenas',
            email: 'sergiturbadenas@gmail.com'
          },
          color: 'teal'
        },
        {
          id: 4,
          text: 'hey lorem ipsum facto asdawe asd asd asd asd asd asd aseasdfas das dasd asd asd asd asd asd asd asd asd asd asd asd sad asd ',
          action: {
            name: 'update',
            text: 'Edició',
            icon: 'edit'
          },
          time: '11:45:34 23/10/2018',
          human_time: '1 minut abans',
          timestamp: 123123312,
          module: {
            name: 'Incidents',
            text: 'Incidències',
            icon: 'build'
          },
          user: {
            id: 1,
            name: 'Sergi Tur Badenas',
            email: 'sergiturbadenas@gmail.com'
          },
          icon: 'home',
          color: 'teal'
        }
      ]
    }
  },
  methods: {
    refresh (message = true) {
      this.fetch(message)
    },
    fetch (message = true) {
      this.refreshing = true
      // this.$store.dispatch(actions.SET_INCIDENTS).then(response => {
      //   if (message) this.$snackbar.showMessage('Incidències actualitzades correctament')
      //   this.refreshing = false
      // }).catch(error => {
      //   this.$snackbar.showError(error)
      //   this.refreshing = false
      // })
    }
  }
}
</script>
