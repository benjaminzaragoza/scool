<template>
    <locality-complex-field
            v-model="dataBirthplace"
            :required="required"
            postal-code-label="Codi Postal Naixement"
            locality-label="Localitat de Naixement"
            province-label="Província de Naixement"
            @input="input"
            @blur="blur"
    ></locality-complex-field>
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
      dataBirthplace: null
    }
  },
  model: {
    prop: 'birthplace',
    event: 'input'
  },
  props: {
    birthplace: {},
    required: {
      type: Boolean,
      default: false
    }
  },
  watch: {
    birthplace (birthplace) {
      this.dataBirthplace = birthplace
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataBirthplace)
    },
    blur () {
      this.$emit('blur', this.dataBirthplace)
    }
  }
}
</script>
