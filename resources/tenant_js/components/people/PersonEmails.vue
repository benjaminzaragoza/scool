<template>
    <span style="display: flex;align-items: center;">
        <v-btn icon color="success" flat small @click="showEmails" style="display: inline">
            <v-icon small>info</v-icon>
        </v-btn>
        <v-tooltip bottom style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            <a
                    slot="activator"
                    target="_blank" :href="'https://mail.google.com/mail/?view=cm&fs=1&to=' + email"
                    v-text="email"></a>
            <span>{{ email }}</span>
        </v-tooltip>
    </span>
</template>

<script>
export default {
  name: 'PersonEmails',
  props: {
    email: {},
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    showEmails () {
      'TODO show other emails'
    }
  }
}
</script>
