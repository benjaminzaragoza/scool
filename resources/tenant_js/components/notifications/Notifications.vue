<template>
    <span>
        <user-notifications-list :notifications="userNotifications"></user-notifications-list>

        <v-container grid-list-md text-xs-center>
            <v-layout row wrap fluid>
                <v-flex xs12>
                    <simple-notification-send-card :users="users"></simple-notification-send-card>
                </v-flex>
                <v-flex xs12>
                    <notifications-list :users="users" :notifications="notifications"></notifications-list>
                </v-flex>
            </v-layout>
        </v-container>

    </span>
</template>

<script>
import UserNotificationsList from './UserNotificationsList'
import SimpleNotificationSendCard from './SimpleNotificationSendCard'
import NotificationsList from './NotificationsList'
export default {
  name: 'Notifications',
  components: {
    'user-notifications-list': UserNotificationsList,
    'simple-notification-send-card': SimpleNotificationSendCard,
    'notifications-list': NotificationsList
  },
  props: {
    notifications: {
      type: Array,
      required: true
    },
    userNotifications: {
      type: Array,
      required: true
    },
    users: {
      type: Array,
      required: true
    }
  }
}
</script>
