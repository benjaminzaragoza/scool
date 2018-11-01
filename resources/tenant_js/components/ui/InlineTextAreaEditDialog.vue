<template>
    <v-edit-dialog
            full-width
            :return-value.sync="value"
            lazy
            @save="save"
            large
    > <span :class="{ limit: limit }" v-html="markedValue"></span>
        <v-textarea
                v-focus
                slot="input"
                v-model="value"
                :rules="valueRules"
                :label="label"
                clearable
                rows="15"
                cols="75"
                cancel-text="Cancel·lar"
                save-text="Guardar"
        ></v-textarea>
    </v-edit-dialog>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import marked from 'marked'

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
  computed: {
    markedValue () {
      return marked(this.value, { sanitize: true })
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
    },
    limit: {
      type: Boolean,
      default: true
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
        const data = {}
        data[this.field] = this.value
        window.axios.put(this.url(), data).then(() => {
          this.$emit('save', this.value)
        }).catch(error => {
          this.$snackbar.showError(error)
        })
      }
    }
  }
}
</script>

<style scoped>
    .limit {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
