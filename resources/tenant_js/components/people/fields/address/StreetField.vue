<template>
    <v-text-field
            name="street"
            label="Adreça"
            hint="P.ex. C/ Alcanyiz o Avg/ Generalitat"
            v-model="street"
            :error-messages="streetErrors"
            :counter="255"
            @input="$v.street.$touch()"
            @blur="$v.street.$touch()"
            required
    ></v-text-field>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
export default {
  name: 'StreetField',
  mixins: [validationMixin],
  validations: {
    street: { required }
  },
  data () {
    return {
      street: null
    }
  },
  computed: {
    streetErrors () {
      const errors = []
      if (!this.$v.street.$dirty) return errors
      !this.$v.street.required && errors.push('El carrer és obligatòri.')
      return errors
    }
  }
}
</script>
