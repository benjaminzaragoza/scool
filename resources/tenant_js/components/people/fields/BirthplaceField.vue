<template>
    <locality-complex-field
            v-model="dataBirthdate"
            :required="required"
            postal-code-label="Codi Postal Naixement"
            locality-label="Localitat de Naixement"
            province-label="Província de Naixement"
            @input="input"></locality-complex-field>
</template>

<script>
import LocalityComplexField from './address/LocalityComplexField'
export default {
  name: 'BirthplaceField',
  components: {
    'locality-complex-field': LocalityComplexField
  },
  data () {
    return {
      dataBirthdate: null
    }
  },
  model: {
    prop: 'birthdate',
    event: 'input'
  },
  props: {
    required: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataBirthdate)
    }
  }
}
</script>
