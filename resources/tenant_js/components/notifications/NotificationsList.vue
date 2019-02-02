<template>
    <v-card>
        <v-toolbar color="primary" dense>
            <v-menu bottom>
                <v-btn slot="activator" icon dark>
                    <v-icon>more_vert</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile href="/changelog/module/notifications" target="_blank">
                        <v-list-tile-title>Mostrar historial de notificacions (registre de canvis)</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile href="/users" target="_blank">
                        <v-list-tile-title>Gestionar usuaris</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-toolbar-title class="white--text title">Notificacions</v-toolbar-title>
            <v-spacer></v-spacer>

            <v-tooltip bottom>
                <v-btn slot="activator" id="incidents_help_button" icon class="white--text" href="http://docs.scool.cat/docs/users" target="_blank">
                    <v-icon>help</v-icon>
                </v-btn>
                <span>Ajuda</span>
            </v-tooltip>

            <v-tooltip bottom>
                <v-btn slot="activator" icon class="white--text" @click="settings">
                    <v-icon>settings</v-icon>
                </v-btn>
                <span>Configuració</span>
            </v-tooltip>

            <v-tooltip bottom>
                <v-btn slot="activator" id="users_refresh_button" icon class="white--text" @click="refresh" :loading="refreshing" :disabled="refreshing">
                    <v-icon>refresh</v-icon>
                </v-btn>
                <span>Actualitzar</span>
            </v-tooltip>
        </v-toolbar>
        <v-card-title>
            <v-layout>
                <v-flex xs9 style="align-self: flex-end;">
                    <v-layout>
                        <v-flex xs2 class="text-sm-left" style="align-self: center;">
                            FILTERS HERE TODO
                        </v-flex>
                        <v-flex xs10>
                            <v-layout>
                                <v-flex xs4>
                                    FILTERS HERE TODO 2
                                </v-flex>
                                <v-flex xs8>
                                    FILTERS HERE TODO 3
                                </v-flex>
                            </v-layout>
                        </v-flex>
                    </v-layout>
                </v-flex>
                <v-flex xs3>
                    <v-text-field
                            append-icon="search"
                            label="Buscar"
                            single-line
                            hide-details
                            v-model="search"
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-card-title>
        <div id="massive_actions" v-if="selected.length > 0" style="text-align: left;">
            <notifications-delete-multiple :notifications="selected" @deleted="selected=[];refresh(false)"></notifications-delete-multiple>
        </div>
        <v-data-table
                v-model="selected"
                select-all
                class="px-0 mb-5 hidden-sm-and-down"
                :headers="headers"
                :items="filteredNotifications"
                :search="search"
                item-key="id"
                disable-initial-sort
                no-results-text="No s'ha trobat cap registre coincident"
                no-data-text="No hi han dades disponibles"
                rows-per-page-text="Notificacions per pàgina"
                :rows-per-page-items="[5,10,25,50,100,200,500,1000,{'text':'Tots','value':-1}]"
        >
            <template slot="items" slot-scope="props">
                <tr>
                    <td>
                        <v-checkbox
                                v-model="props.selected"
                                primary
                                hide-details
                        ></v-checkbox>
                    </td>
                    <td class="text-xs-left cell">
                        {{ props.item.id }}
                    </td>
                    <td class="text-xs-left cell">
                        {{ props.item.type }}
                    </td>
                    <td class="text-xs-left cell">
                        USER NOTIFICATION AVATAR HERE: {{ props.item.notifiable_id }}
                        <!--<user-avatar :hash-id="props.item.hashid"-->
                        <!--:alt="props.item.name"-->
                        <!--:user="props.item"-->
                        <!--:editable="true"-->
                        <!--:removable="true"-->
                        <!--&gt;</user-avatar>-->
                    </td>
                    <td class="text-xs-left cell" style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ props.item.notifiable_type }}
                    </td>
                    <td class="text-xs-left cell" style="max-width: 175px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ props.item.data }}
                    </td>
                    <td class="text-xs-left cell" style="max-width: 125px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ props.item.read_at }}
                    </td>
                    <td class="text-xs-left cell" :title="props.item.formatted_created_at">
                        <v-tooltip bottom>
                            <span slot="activator">{{ props.item.formatted_created_at_diff }}</span>
                            <span>{{ props.item.formatted_created_at }}</span>
                        </v-tooltip>
                    </td>
                    <td class="text-xs-left cell" :title="props.item.formatted_updated_at">
                        <v-tooltip bottom>
                            <span slot="activator">{{ props.item.formatted_updated_at_diff }}</span>
                            <span>{{ props.item.formatted_updated_at }}</span>
                        </v-tooltip>
                    </td>
                    <td class="text-xs-left cell">
                        ACTIONS TODO
                        <!--<user-changelog :user="props.item" class="ma-0"></user-changelog>-->
                        <!--<show-user-icon :user="props.item" :users="users"></show-user-icon>-->
                        <!--<user-emails :user="props.item"></user-emails>-->
                        <!--<user-personal-data-icon-link :user="props.item" class="ma-0"></user-personal-data-icon-link>-->
                        <!--<user-password :user="props.item" class="ma-0"></user-password>-->
                        <!--&lt;!&ndash; TODO &ndash;&gt;-->
                        <!--&lt;!&ndash;<user-check :user="props.item"></user-check>&ndash;&gt;-->
                        <!--&lt;!&ndash;<user-send-welcome-email :user="props.item"></user-send-welcome-email>&ndash;&gt;-->
                        <!--&lt;!&ndash;<user-send-reset-password-email :user="props.item"></user-send-reset-password-email>&ndash;&gt;-->
                        <!--&lt;!&ndash;<user-send-confirmation-email :user="props.item"></user-send-confirmation-email>&ndash;&gt;-->
                        <!--<user-delete :user="props.item"></user-delete>-->
                    </td>
                </tr>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
import NotificationsDeleteMultiple from './NotificationsDeleteMultiple'

var filterNames = [
  {
    id: 1,
    name: 'Email confirmat',
    function: 'confirmedEmail'
  }
]

var filters = {
  all: function (users) {
    return users
  }
  // byUserType: function (users, userType) {
  //   return users ? users.filter(function (user) {
  //     return user.user_type_id === userType
  //   }) : []
  // }
}

export default {
  name: 'NotificationsList',
  components: {
    'notifications-delete-multiple': NotificationsDeleteMultiple
  },
  data () {
    return {
      selected: [],
      search: '',
      internalNotifications: this.notifications,
      refreshing: false,
      headers: [
        { text: 'Uuid', align: 'left', value: 'id' },
        { text: 'Type', value: 'type' },
        { text: 'Notificat a', value: 'notifiable_id' },
        { text: 'Tipus Notificat', value: 'notifiable_type' },
        { text: 'Dades', value: 'data' },
        { text: 'Llegida', value: 'read_at' },
        { text: 'Data creació', value: 'created_at_timestamp' },
        { text: 'Data actualització', value: 'updated_at_timestamp' },
        { text: 'Accions', sortable: false }
      ]
    }
  },
  props: {
    notifications: {
      type: Array,
      required: true
    }
  },
  computed: {
    filteredNotifications: function () {
      let filteredNotifications = this.internalNotifications
      // if (this.userType) filteredNotifications = filters['byUserType'](this.internalNotifications, this.userType)
      // if (this.selectedRoles.length > 0) filteredNotifications = filters['byRoles'](this.internalNotifications, this.selectedRoles)
      // if (this.selectedFilters.length > 0) {
      //   this.selectedFilters.forEach(filter => {
      //     filteredNotifications = filters[filter.function](this.internalNotifications)
      //   })
      // }
      return filteredNotifications
    }
  },
  methods: {
    refresh () {
      this.refreshing = true
      window.axios.get('/api/v1/notifications').then(() => {
        this.refreshing = false
        this.$snackbar.showMessage('Notificacions actualitzades correctament')
      }).catch(error => {
        this.refreshing = false
        this.$snackbar.showError(error)
      })
    },
    settings () {
      console.log('settings TODO') // TODO
    }
  }
}
</script>
