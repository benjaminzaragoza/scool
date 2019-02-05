<template>
    <v-combobox
            name="postalcode"
            label="Codi postal"

            :loading="loading"
            cache-items
            required
            autocomplete
            clearable
            :error-messages="errors"
            @input="$v.postalcode.$touch()"
            @blur="$v.postalcode.$touch()"
            :items="postalCodes"
            :search-input.sync="searchPostalCodes"
            v-model="postalcode"
    ></v-combobox>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  name: 'PostalcodeField',
  mixins: [validationMixin],
  validations: {
    postalcode: { required }
  },
  data () {
    return {
      postalcode: null,
      postalCodes: [],
      loading: false,
      searchPostalCodes: null
    }
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.postalcode.$dirty) return errors
      !this.$v.postalcode.required && errors.push('El codi postal és obligatòri.')
      return errors
    }
  }
}
</script>
