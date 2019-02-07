<template>
    <v-text-field
            name="dataNumber"
            label="Número"
            v-model="dataNumber"
            :error-messages="errors"
            @input="$v.dataNumber.$touch()"
            @blur="$v.dataNumber.$touch()"
            :required="required"
    ></v-text-field>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
export default {
  name: 'StreetNumberField',
  mixins: [validationMixin],
  validations: {
    dataNumber: { required }
  },
  data () {
    return {
      dataNumber: this.number
    }
  },
  model: {
    prop: 'number',
    event: 'input'
  },
  props: {
    number: {},
    required: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.dataNumber.$dirty) return errors
      if (this.required) !this.$v.dataNumber.required && errors.push('El número de carrer és obligatòri.')
      return errors
    }
  },
  watch: {
    number (number) {
      this.dataNumber = number
    }
  },
  methods: {
    input () {
      this.$v.dataNumber.$touch()
      this.$emit('input', this.dataNumber)
    },
    blur () {
      this.$v.dataNumber.$touch()
      this.$emit('blur', this.dataNumber)
    }
  }

}
</script>
