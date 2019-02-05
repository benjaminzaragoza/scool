<template>
    <v-combobox
            name="locality"
            label="Localitat"
            tabindex = "-1"
            item-text="name"
            :loading="loading"
            cache-items
            required
            autocomplete
            clearable
            :items="localities"
            :search-input.sync="searchLocalities"
            :error-messages="errors"
            v-model="locality"
            @input="$v.locality.$touch()"
            @blur="$v.locality.$touch()"
    ></v-combobox>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default {
  name: 'LocalityField',
  mixins: [ validationMixin ],
  validations: {
    locality: { required }
  },
  data () {
    return {
      locality: null,
      loading: true,
      localities: [],
      searchLocalities: null
    }
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.locality.$dirty) return errors
      !this.$v.locality.required && errors.push('El poble/ciutat és obligatòri.')
      return errors
    }
  }
}
</script>
