<template>
    <span>
        <v-edit-dialog
                :return-value.sync="value"
                lazy
                @save="save"
        > <span :class="className" :title="value">{{ value }}</span>
            <v-text-field
                    v-focus
                    v-model="value"
                    slot="input"
                    :label="this.label"
                    single-line
                    :rules="valueRules"
                    :return-masked-value="returnMaskedValue"
                    :mask="mask"
                    :placeholder="placeholder"
                    :hint="hint"
            ></v-text-field>
            <v-tooltip bottom v-if="!value" slot="default">
                <v-btn slot="activator" icon small flat color="primary"><v-icon small>add</v-icon></v-btn>
                <span>Afegir</span>
            </v-tooltip>
        </v-edit-dialog>
    </span>
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
      prova: false,
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
    returnMaskedValue: {
      type: Boolean,
      default: false
    },
    mask: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: ''
    },
    hint: {
      type: String,
      default: ''
    },
    className: {
      type: String,
      default: 'limit'
    },
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
    open () {
      console.log('TODO')
      this.prova = true
    },
    url () {
      return '/api/v1/' + this.object.api_uri + '/' + this.object.id + '/' + this.field
    },
    save () {
      if (!this.$v.$invalid) {
        window.axios.put(this.url(), {
          [this.field]: this.value
        }).then(() => {
          this.$emit('save', this.object)
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
        max-width: 400px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .limit200 {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .limit150 {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .limit100 {
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
