<template>
    <v-menu offset-y>
        <v-badge slot="activator" left overlap color="error" class="ml-3 mr-2">
            <span slot="badge" v-text="amount"></span>
            <v-btn icon color="white" :loading="loading" :disabled="loading">
                <v-icon :large="large" color="primary">notifications</v-icon>
            </v-btn>
        </v-badge>
        <v-list>
            <v-list-tile v-if="dataNotifications.length > 0"
                    v-for="(dataNotification, index) in dataNotifications"
                    :key="index"
            >
                <v-list-tile-title style="max-width: 450px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ dataNotification.title }}</v-list-tile-title>
            </v-list-tile>
            <v-list-tile>
                <v-list-tile-title>No hi ha cap notificació pendent</v-list-tile-title>
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
