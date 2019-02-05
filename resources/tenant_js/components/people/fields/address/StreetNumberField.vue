<template>
    <v-text-field
            name="number"
            label="Número"
            v-model="number"
            :error-messages="errors"
            @input="$v.number.$touch()"
            @blur="$v.number.$touch()"
            required
    ></v-text-field>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
export default {
  name: 'StreetNumberField',
  mixins: [validationMixin],
  validations: {
    number: { required }
  },
  data () {
    return {
      number: ''
    }
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.number.$dirty) return errors
      !this.$v.number.required && errors.push('El número de carrer és obligatòri.')
      return errors
    }
  }
}
</script>
