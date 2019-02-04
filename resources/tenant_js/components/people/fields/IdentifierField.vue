<template>
    <span>
        <v-text-field
                name="identifier"
                label="DNI/NIE/Passaport"
                v-model="dataIdentifier"
                :error-messages="identifierErrors"
                @input="input"
                @blur="blur"
                required
        ></v-text-field>
    </span>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default {
  'name': 'IdentifierTypeField',
  mixins: [validationMixin],
  validations: {
    dataIdentifier: { required }
  },
  data () {
    return {
      dataIdentifier: this.identifier
    }
  },
  model: {
    prop: 'identifier',
    event: 'input'
  },
  props: {
    identifier: {},
    invalid: {}
  },
  computed: {
    identifierErrors () {
      const errors = []
      if (!this.$v.dataIdentifier.$dirty) return errors
      !this.$v.dataIdentifier.required && errors.push('El identificador és obligatori.')
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
    input () {
      this.$v.dataIdentifier.$touch()
      this.$emit('input', this.dataIdentifier)
    },
    blur () {
      this.$v.dataIdentifier.$touch()
      this.$emit('blur', this.dataIdentifier)
    }
  }
}
</script>
