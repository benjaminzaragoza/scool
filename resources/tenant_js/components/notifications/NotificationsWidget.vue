<template>
    <v-menu offset-y>
        <v-badge slot="activator" left overlap color="error" class="ml-3 mr-2">
            <span slot="badge" v-text="amount"></span>
            <v-btn icon color="white" :loading="loading" :disabled="loading">
                <v-icon :large="large" color="primary">notifications</v-icon>
            </v-btn>
        </v-badge>
        <v-list>
            <v-list-tile v-if="dataNotifications.length > 0">
                <v-list-tile-title>Teniu {{ dataNotifications.length }} notificacions pendents: </v-list-tile-title>
            </v-list-tile>
            <v-divider></v-divider>
            <v-list-tile v-if="dataNotifications.length > 0"
                    v-for="(notification, index) in dataNotifications"
                    :key="index"
                    @click="markAsReaded(notification)"
            >
                <v-list-tile-title style="max-width: 450px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ notification.data.title }}</v-list-tile-title>
            </v-list-tile>
            <v-list-tile v-else>
                <v-list-tile-title>No hi ha cap notificació pendent de llegir</v-list-tile-title>
            </v-list-tile>
            <v-divider></v-divider>
            <v-list-tile>
                <v-list-tile-title class="caption">
                    <a href="/notifications">Veure totes</a> |
                    <a href="#" @click="markAllAsReaded">Marcar totes com a llegides</a> |
                    <a href="#" @click="refresh(true)">Actualitzar</a>
                </v-list-tile-title>
            </v-list-tile>
        </v-list>
    </v-menu>

</template>

<script>
export default {
  name: 'NotificationsWidget',
  data () {
    return {
      dataNotifications: [],
      loading: false
    }
  },
  props: {
    notifications: {
      type: Array,
      required: false
    }
  },
  computed: {
    large () {
      if (this.dataNotifications) return this.dataNotifications.length > 0
      return false
    },
    amount () {
      if (this.dataNotifications) return this.dataNotifications.length
      return 0
    }
  },
  methods: {
    refresh (message = false) {
      this.loading = true
      window.axios.get('/api/v1/user/unread_notifications/').then((response) => {
        this.dataNotifications = response.data
        this.loading = false
        if (message) this.$snackbar.showMessage('Notificacions actualitzades correctament')
      }).catch(error => {
        this.loading = false
        this.$snackbar.showError(error)
      })
    },
    markAsReaded (notification) {
      this.loading = true
      window.axios.delete('/api/v1/user/unread_notifications/' + notification.id).then(() => {
        this.loading = false
        this.refresh()
      }).catch(error => {
        this.loading = false
        this.$snackbar(error)
      })
    },
    markAllAsReaded () {
      this.loading = true
      this.loading = true
      window.axios.delete('/api/v1/user/unread_notifications/all').then(() => {
        this.loading = false
        this.refresh()
      }).catch(error => {
        this.loading = false
        this.$snackbar(error)
      })
    }
  },
  created () {
    if (this.notifications) {
      this.dataNotifications = this.notifications
    } else {
      this.loading = true
      window.axios.get('/api/v1/user/notifications').then((response) => {
        this.dataNotifications = response.data
        this.loading = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    }
  }
}
</script>
