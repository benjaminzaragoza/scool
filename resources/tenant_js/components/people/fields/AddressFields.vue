<template>
    <v-layout row wrap>
        <v-flex md4>
            <street-field v-model="street" :invalid.sync="streetInvalid"></street-field>
        </v-flex>
        <v-flex md1>
            <street-number-field v-model="streetNumber" :invalid.sync="streetNumberInvalid"></street-number-field>
        </v-flex>
        <v-flex md1>
            <street-floor-field v-model="streetFloor" :invalid.sync="streetFloorInvalid"></street-floor-field>
        </v-flex>
        <v-flex md1>
            <street-floor-number-field v-model="streetFloorNumber" :invalid.sync="streetFloorNumberInvalid"></street-floor-number-field>
        </v-flex>
        <v-flex md5>
            <locality-complex-field v-model="locality"></locality-complex-field>
        </v-flex>
    </v-layout>
</template>

<script>
import StreetField from './address/StreetField'
import StreetNumberField from './address/StreetNumberField'
import StreetFloorField from './address/StreetFloorField'
import StreetFloorNumberField from './address/StreetFloorNumberField'
import LocalityComplexField from './address/LocalityComplexField'

export default {
  name: 'AddressFields',
  components: {
    'street-field': StreetField,
    'street-number-field': StreetNumberField,
    'street-floor-field': StreetFloorField,
    'street-floor-number-field': StreetFloorNumberField,
    'locality-complex-field': LocalityComplexField
  },
  data () {
    return {
      dataAddress: this.address,
      street: null,
      streetInvalid: true,
      streetNumber: null,
      streetNumberInvalid: true,
      streetFloor: null,
      streetFloorInvalid: true,
      streetFloorNumber: null,
      streetFloorNumberInvalid: true,
      postalcode: null,
      postalcodeInvalid: true,
      locality: null,
      localityInvalid: true,
      province: null,
      provinceInvalid: true
    }
  },
  model: {
    prop: 'address',
    event: 'input'
  },
  props: {
    mobile: {},
    invalid: {}
  },
  computed: {
    invalidForm () {
      // TODO
      // return this.$v.$invalid
    }
  },
  watch: {
    invalidForm (invalidForm) {
      this.$emit('update:invalid', invalidForm)
    }
  },
  methods: {
    input () {
      this.$emit('input', this.dataAddress)
    },
    blur () {
      this.$emit('blur', this.dataAddress)
    }
  }
}
</script>
