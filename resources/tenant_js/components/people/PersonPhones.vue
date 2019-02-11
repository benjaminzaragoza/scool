<template>
    <span style="display: flex;align-items: center;">
        <json-dialog-component
                v-if="person.other_phones"
                small
                title="Altres telefòns"
                btn-class="ma-0"
                icon="info"
                icon-color="success"
                :json="person.other_phones">
        </json-dialog-component>
        <v-tooltip bottom style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            <span slot="activator">{{ phone }}</span>
            <span>{{ phone }}</span>
        </v-tooltip>
    </span>
</template>

<script>
import JsonDialogComponent from '../ui/JsonDialogComponent'

export default {
  name: 'PersonPhones',
  components: {
    'json-dialog-component': JsonDialogComponent
  },
  props: {
    phone: {},
    person: {
      type: Object,
      required: true
    }
  }
}
</script>
