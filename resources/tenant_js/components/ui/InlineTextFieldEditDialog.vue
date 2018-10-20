<template>
    <v-edit-dialog
            :return-value.sync="value"
            lazy
            @save="save"
    > {{ value }}
        <v-text-field
                v-model="value"
                slot="input"
                label="Edit"
                single-line
                counter
        ></v-text-field>
    </v-edit-dialog>
</template>

<script>
export default {
  name: 'InlineTextFieldEditDialog',
  data () {
    return {
      value: this.object[this.field]
    }
  },
  model: {
    prop: 'object',
    event: 'save'
  },
  props: {
    object: {
      type: Object,
      required: true
    },
    field: {
      type: String,
      required: true
    }
  },
  methods: {
    url () {
      return '/api/v1/' + this.object.api_uri + '/' + this.object.id + '/' + this.field
    },
    save () {
      console.log('URL')
      console.log(this.url())
      console.log('TODO SAVE TO DATABASE')
      window.axios.patch(this.url(),{}).then(response => {
        console.log(response)
      }).catch(error => {
        console.log(error)
      })
      this.$emit('save', this.value)
    }
  }
}
</script>
