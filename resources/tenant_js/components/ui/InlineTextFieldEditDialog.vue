<template>
    <v-edit-dialog
            :return-value.sync="value"
            lazy
            @save="save"
    > <span class="limit" :title="value">{{ value }}</span>
        <v-text-field
                v-focus
                v-model="value"
                slot="input"
                :label="this.label"
                single-line
                :rules="valueRules"
        ></v-text-field>
    </v-edit-dialog>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  name: 'InlineTextFieldEditDialog',
  mixins: [validationMixin],
  validations: {
    value: { required }
  },
  data () {
    return {
      valueRules: [
        v => !!v || ' is required'
      ],
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
    },
    label: {
      type: String,
      default: ''
    }
  },
  watch: {
    object (newValue) {
      this.value = newValue[this.field]
    },
    field (newValue) {
      this.value = this.object[newValue]
    }
  },
  methods: {
    url () {
      return '/api/v1/' + this.object.api_uri + '/' + this.object.id + '/' + this.field
    },
    save () {
      if (!this.$v.$invalid) {
        window.axios.put(this.url(), {
          subject: this.value
        }).then(() => {
          this.$emit('save', this.value)
        }).catch(error => {
          console.log(error)
          this.$snackbar.showError(error)
        })
      }
    }
  }
}
</script>

<style scoped>
    .limit {
        max-width: 400px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
