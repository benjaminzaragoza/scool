<template>
    <span style="display: flex;align-items: center;">
        <json-dialog-component
                v-if="person.other_emails"
                small
                title="Altres correus electrònics"
                btn-class="ma-0"
                icon="info"
                icon-color="success"
                :json="person.other_emails">
        </json-dialog-component>
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
import JsonDialogComponent from '../ui/JsonDialogComponent'

export default {
  name: 'PersonEmails',
  components: {
    'json-dialog-component': JsonDialogComponent
  },
  props: {
    email: {},
    person: {
      type: Object,
      required: true
    }
  }
}
</script>
