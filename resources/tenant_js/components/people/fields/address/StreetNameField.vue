<template>
    <v-text-field
            name="street_name"
            label="Adreça"
            hint="P.ex. C/ Alcanyiz o Avg/ Generalitat"
            v-model="dataStreetName"
            :error-messages="errors"
            @input="input"
            @blur="blur"
            :required="required"
    ></v-text-field>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
export default {
  name: 'StreetNameField',
  mixins: [validationMixin],
  validations: {
    dataStreetName: { required }
  },
  data () {
    return {
      dataStreetName: this.streetName
    }
  },
  model: {
    prop: 'streetName',
    event: 'input'
  },
  props: {
    streetName: {},
    required: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.dataStreetName.$dirty) return errors
      if (this.required) !this.$v.dataStreetName.required && errors.push('El carrer és obligatòri.')
      return errors
    }
  },
  watch: {
    streetName (streetName) {
      this.dataStreetName = streetName
    }
  },
  methods: {
    input () {
      this.$v.dataStreetName.$touch()
      this.$emit('input', this.dataStreetName)
    },
    blur () {
      this.$v.dataStreetName.$touch()
      this.$emit('blur', this.dataStreetName)
    }
  }
}
</script>
