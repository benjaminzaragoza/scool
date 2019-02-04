<template>
    <span>
        <v-select
                name="identifierType"
                label="Tipus id"
                required
                clearable
                :error-messages="identifierTypeErrors"
                @input="input"
                @blur="blur"
                :items="dataIdentifierTypes"
                v-model="dataIdentifierType"
                :loading="loading"
                item-text="name"
        ></v-select>
    </span>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default {
  'name': 'IdentifierTypeField',
  mixins: [validationMixin],
  validations: {
    identifierType: { required }
  },
  data () {
    return {
      dataIdentifierType: this.identifierType,
      dataIdentifierTypes: [],
      loading: false
    }
  },
  model: {
    prop: 'identifierType',
    event: 'input'
  },
  props: {
    identifierTypes: {
      type: Array
    },
    identifierType: {},
    invalid: {}
  },
  computed: {
    identifierTypeErrors () {
      const errors = []
      if (!this.$v.identifierType.$dirty) return errors
      !this.$v.identifierType.required && errors.push("El tipus d'identificador és obligatori.")
      return errors
    },
    invalidForm () {
      return this.$v.$invalid
    }
  },
  watch: {
    invalidForm (invalidForm) {
      this.$emit('update:invalid', invalidForm)
    }
  },
  methods: {
    fetchIdentifierTypes () {
      this.loading = true
      window.axios.get('/api/v1/identifier_types').then((response) => {
        this.dataIdentifierTypes = response.data
        this.loading = false
      }).catch(error => {
        this.loading = false
        this.$snackbar.showError(error)
      })
    },
    input () {
      this.$v.identifierType.$touch()
      this.$emit('input', this.dataIdentifierType)
    },
    blur () {
      this.$v.identifierType.$touch()
      this.$emit('blur', this.dataIdentifierType)
    }
  },
  created () {
    if (this.identifierTypes) this.dataIdentifierTypes = this.identifierTypes
    else this.fetchIdentifierTypes()
  }
}
</script>
