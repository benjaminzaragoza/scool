<template>
    <v-menu offset-y>
        <v-badge slot="activator" left overlap color="error" class="ml-3 mr-2">
            <span slot="badge" v-text="amount"></span>
            <v-btn icon color="white">
                <v-icon :large="large" color="primary">notifications</v-icon>
            </v-btn>
        </v-badge>
        <v-list>
            <v-list-tile
                    v-for="(dataNotification, index) in dataNotifications"
                    :key="index"
                    @click=""
            >
                <v-list-tile-title style="max-width: 450px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ dataNotification.title }}</v-list-tile-title>
            </v-list-tile>
        </v-list>
    </v-menu>

</template>

<script>
export default {
  name: 'NotificationsWidget',
  data () {
    return {
      dataNotifications: this.notifications
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
    this.dataNotifications = [
      {
        title: 'Notification 1'
      },
      {
        title: 'Notification 2'
      },
      {
        title: 'Notification 3'
      },
      {
        title: 'Notification 4'
      },
      {
        title: 'Notification 4 amb un text més llarg que un dia sense pa i bla bla bla bla bla bla bla bla bla bla bla bla Notification 4 amb un text més llarg que un dia sense pa i bla l bla bla bla bla bla bla bla bla bla bla bla'
      }
    ]
  }
}
</script>
