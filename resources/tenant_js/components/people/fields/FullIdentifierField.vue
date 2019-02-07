<template>
    <v-layout row wrap>
        <v-flex md6>
            <v-select
                    name="identifierType"
                    label="Tipus id"
                    :required="required"
                    clearable
                    :error-messages="identifierTypeErrors"
                    @input="inputIdentifierType"
                    @blur="blurIdentifierType"
                    :items="dataIdentifierTypes"
                    v-model="dataIdentifierType"
                    :loading="loading"
                    item-text="name"
                    return-object
            ></v-select>
        </v-flex>
        <v-flex md6>
            <v-text-field
                    prepend-icon="credit_card"
                    name="identifier"
                    label="DNI/NIE/Passaport"
                    v-model="dataIdentifier"
                    :error-messages="identifierErrors"
                    @input="inputIdentifier"
                    @blur="blurIdentifier"
                    required
            ></v-text-field>
        </v-flex>
    </v-layout>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
import helpers from '../../../utils/helpers'

function dniValidator (value) {
  if (value) return helpers.validateDNI(value)
  return false
}

export default {
  name: 'FullIdentifierField',
  mixins: [validationMixin],
  validations: {
    dataIdentifierType: { required },
    dataIdentifier: { required, dniValidator }
  },
  data () {
    return {
      loading: false,
      dataIdentifierTypes: [],
      dataIdentifierType: null,
      dataIdentifier: null,
      dataIdentifierObject: {}
    }
  },
  model: {
    prop: 'identifierObject',
    event: 'input'
  },
  props: {
    identifierObject: {},
    invalid: {},
    identifierTypes: {
      type: Array
    },
    required: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    identifierTypeErrors () {
      const errors = []
      if (!this.$v.dataIdentifierType.$dirty) return errors
      !this.$v.dataIdentifierType.required && errors.push("El tipus d'identificador és obligatori.")
      return errors
    },
    identifierErrors () {
      const errors = []
      if (!this.$v.dataIdentifier.$dirty) return errors
      !this.$v.dataIdentifier.required && errors.push('El identificador és obligatori.')
      if (this.dataIdentifierType === 'NIF') !this.$v.dataIdentifier.dniValidator && errors.push('El DNI és incorrecte!')
      return errors
    }
  },
  watch: {
    dataIdentifierType (dataIdentifierType) {
      if (!dataIdentifierType) this.$v.$reset()
      this.setIdentifierObject()
    },
    dataIdentifier () {
      this.setIdentifierObject()
    }
  },
  methods: {
    setIdentifierObject () {
      this.dataIdentifierType ? (this.dataIdentifierObject['type_id'] = this.dataIdentifierType.id) : delete this.dataIdentifierObject['identifierType']
      this.dataIdentifier ? (this.dataIdentifierObject['value'] = this.dataIdentifier) : delete this.dataIdentifierObject['identifier']
      this.$emit('input', this.dataIdentifierObject)
    },
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
    inputIdentifierType () {
      if (this.required) this.$v.dataIdentifierType.$touch()
      this.$emit('input', this.dataIdentifierType)
    },
    blurIdentifierType () {
      if (this.required) this.$v.dataIdentifierType.$touch()
      this.$emit('blur', this.dataIdentifierType)
    },
    inputIdentifier () {
      if (this.required || this.dataIdentifierType) this.$v.dataIdentifier.$touch()
      this.$emit('input', this.dataIdentifier)
    },
    blurIdentifier () {
      if (this.required || this.dataIdentifierType) this.$v.dataIdentifier.$touch()
      this.$emit('blur', this.dataIdentifier)
    }
  },
  created () {
    if (this.identifierTypes) this.dataIdentifierTypes = this.identifierTypes
    else this.fetchIdentifierTypes()
  }
}
</script>
