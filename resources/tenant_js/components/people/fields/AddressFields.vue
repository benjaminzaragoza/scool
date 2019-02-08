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
import Vue from 'vue'

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
      if (!address) this.clean()
      else this.dataAddress = address
    }
  },
  methods: {
    clean () {
      this.dataAddress = null
      this.street = null
      this.number = null
      this.floor = null
      this.floorNumber = null
    },
    dirty () {
      if (this.street != null) return true
      if (this.number != null) return true
      if (this.floor != null) return true
      if (this.floorNumber != null) return true
      return false
    },
    set (item) {
      if (this[item]) {
        if (!this.dataAddress) this.dataAddress = {}
        // this.dataAddress[item] = this[item]
        // https://vuejs.org/v2/api/#Vue-set
        // https://vuejs.org/v2/guide/reactivity.html
        // this.$set(this.someObject, 'b', 2)
        Vue.set(this.dataAddress, item, this[item])
      } else {
        if (this.dataAddress) {
          Vue.set(this.dataAddress, item, null)
          delete this.dataAddress[item]
        }
      }
    },
    checkDataAddress () {
      if (!this.dataAddress) return false
      if (this.dataAddress) {
        if (window._.isEmpty(this.dataAddress)) return false
      }
      return true
    },
    updateDataAddress () {
      this.set('street')
      this.set('number')
      this.set('floor')
      this.set('floorNumber')
      if (!this.checkDataAddress()) this.$emit('input', null)
      else this.$emit('input', this.dataAddress)
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
