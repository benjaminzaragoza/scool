<template>
    <v-layout row wrap>
        <v-flex md4>
            <street-field v-model="street" :invalid.sync="streetInvalid" :required="required"></street-field>
        </v-flex>
        <v-flex md1>
            <street-number-field v-model="number" :invalid.sync="numberInvalid" :required="required"></street-number-field>
        </v-flex>
        <v-flex md1>
            <street-floor-field v-model="floor" :invalid.sync="floorInvalid"></street-floor-field>
        </v-flex>
        <v-flex md1>
            <street-floor-number-field v-model="floorNumber" :invalid.sync="floorNumberInvalid"></street-floor-number-field>
        </v-flex>
        <v-flex md5>
            <locality-complex-field v-model="locality" :required="required"></locality-complex-field>
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
      dataAddress: {},
      street: null,
      streetInvalid: true,
      number: null,
      numberInvalid: true,
      floor: null,
      floorInvalid: true,
      floorNumber: null,
      floorNumberInvalid: true,
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
    address: {},
    mobile: {},
    invalid: {},
    required: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    invalidForm () {
      // TODO
      // return this.$v.$invalid
      return false
    }
  },
  watch: {
    street () {
      this.updateDataAddress()
    },
    number () {
      this.updateDataAddress()
    },
    floor () {
      this.updateDataAddress()
    },
    floorNumber () {
      this.updateDataAddress()
    },
    invalidForm (invalidForm) {
      this.$emit('update:invalid', invalidForm)
    },
    address (address) {
      console.log('address wath at AdressFields:')
      console.log(address)
      this.dataAddress = address
    }
  },
  methods: {
    updateDataAddress () {
      if (this.dataAddress) {
        this.street ? (this.dataAddress['street'] = this.street) : delete this.dataAddress['street']
        this.number ? (this.dataAddress['number'] = this.number) : delete this.dataAddress['number']
        this.floor ? (this.dataAddress['floor'] = this.floor) : delete this.dataAddress['floor']
        this.floorNumber ? (this.dataAddress['floorNumber'] = this.floorNumber) : delete this.dataAddress['floorNumber']
        this.$emit('input', this.dataAddress)
      } else {
        this.$emit('input', null)
      }
    },
    input () {
      this.$emit('input', this.dataAddress)
    },
    blur () {
      this.$emit('blur', this.dataAddress)
    }
  },
  created () {
    if (this.address) {
      this.dataAddress = this.address
      if (this.address.street) this.street = this.address.street
      if (this.address.number) this.number = this.address.number
      if (this.address.floor) this.floor = this.address.floor
      if (this.address.floorNumber) this.floorNumber = this.address.floorNumber
    }
  }
}
</script>
