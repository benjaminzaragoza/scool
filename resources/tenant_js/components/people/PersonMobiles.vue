<template>
    <span style="display: flex;align-items: center;">
        <json-dialog-component
                v-if="person.other_mobiles"
                small
                title="Altres mòbils"
                btn-class="ma-0"
                icon="info"
                icon-color="success"
                :json="person.other_mobiles">
        </json-dialog-component>
        <v-tooltip bottom style="max-width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
            <span slot="activator">{{ mobile }}</span>
            <span>{{ mobile }}</span>
        </v-tooltip>
    </span>
</template>

<script>
import JsonDialogComponent from '../ui/JsonDialogComponent'

export default {
  name: 'PersonMobiles',
  components: {
    'json-dialog-component': JsonDialogComponent
  },
  props: {
    mobile: {},
    person: {
      type: Object,
      required: true
    }
  }
}
</script>
