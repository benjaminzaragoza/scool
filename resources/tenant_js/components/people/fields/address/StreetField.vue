<template>
    <v-text-field
            name="street"
            label="Adreça"
            hint="P.ex. C/ Alcanyiz o Avg/ Generalitat"
            v-model="dataStreet"
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
  name: 'StreetField',
  mixins: [validationMixin],
  validations: {
    dataStreet: { required }
  },
  data () {
    return {
      dataStreet: this.street
    }
  },
  model: {
    prop: 'street',
    event: 'input'
  },
  props: {
    street: {},
    required: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.dataStreet.$dirty) return errors
      if (this.required) !this.$v.dataStreet.required && errors.push('El carrer és obligatòri.')
      return errors
    }
  },
  watch: {
    street (street) {
      this.dataStreet = street
    }
  },
  methods: {
    input () {
      this.$v.dataStreet.$touch()
      this.$emit('input', this.dataStreet)
    },
    blur () {
      this.$v.dataStreet.$touch()
      this.$emit('blur', this.dataStreet)
    }
  }
}
</script>
