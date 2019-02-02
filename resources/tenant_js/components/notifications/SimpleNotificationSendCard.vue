<template>
    <v-card class="elevation-3" v-if="!closed">
        <v-toolbar dense color="white" class="elevation-0">
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon @click.native="closed=true;$emit('close')">
                    <v-icon color="grey">close</v-icon>
                </v-btn>
                <v-btn v-if="!minified" icon @click.native="minified=true;$emit('minified')">
                    <v-icon color="grey">remove</v-icon>
                </v-btn>
                <v-btn v-else icon @click.native="minified=false;$emit('maxified')">
                    <v-icon color="grey">add</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
        <v-container fluid grid-list-xs v-if="!minified">
            <simple-notification-send-form :users="users"></simple-notification-send-form>
        </v-container>
    </v-card>
</template>

<script>
import SimpleNotificationSendForm from './SimpleNotificationSendForm'
export default {
  name: 'SimpleNotificationSendCard',
  components: {
    'simple-notification-send-form': SimpleNotificationSendForm
  },
  data () {
    return {
      closed: false,
      minified: false
    }
  },
  props: {
    users: {
      type: Array,
      required: true
    }
  }
}
</script>
