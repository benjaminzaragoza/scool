<template>
    <v-layout row wrap>
        <v-flex md4>
            <street-name-field v-model="name" :invalid.sync="streetInvalid" :required="required"></street-name-field>
        </v-flex>
        <v-flex md1>
            <street-number-field v-model="number" :invalid.sync="numberInvalid" :required="required"></street-number-field>
        </v-flex>
        <v-flex md1>
            <street-floor-field v-model="floor" :invalid.sync="floorInvalid"></street-floor-field>
        </v-flex>
        <v-flex md1>
            <street-floor-number-field v-model="floor_number" :invalid.sync="floor_numberInvalid"></street-floor-number-field>
        </v-flex>
        <v-flex md5>
            <locality-complex-field v-model="locality" :required="required"></locality-complex-field>
        </v-flex>
    </v-layout>
</template>

<script>
import StreetNameField from './address/StreetNameField'
import StreetNumberField from './address/StreetNumberField'
import StreetFloorField from './address/StreetFloorField'
import StreetFloorNumberField from './address/StreetFloorNumberField'
import LocalityComplexField from './address/LocalityComplexField'
import Vue from 'vue'

export default {
  name: 'AddressFields',
  components: {
    'street-name-field': StreetNameField,
    'street-number-field': StreetNumberField,
    'street-floor-field': StreetFloorField,
    'street-floor-number-field': StreetFloorNumberField,
    'locality-complex-field': LocalityComplexField
  },
  data () {
    return {
      dataAddress: {},
      name: null,
      streetInvalid: true,
      number: null,
      numberInvalid: true,
      floor: null,
      floorInvalid: true,
      floor_number: null,
      floor_numberInvalid: true,
      postalcode: null,
      postalcodeInvalid: true,
      locality: null,
      localityInvalid: true
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
    name () {
      this.updateDataAddress()
    },
    number () {
      this.updateDataAddress()
    },
    floor () {
      this.updateDataAddress()
    },
    floor_number () {
      this.updateDataAddress()
    },
    locality () {
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
      this.name = null
      this.number = null
      this.floor = null
      this.floor_number = null
      this.locality = null
    },
    dirty () {
      if (this.name != null) return true
      if (this.number != null) return true
      if (this.floor != null) return true
      if (this.floor_number != null) return true
      return false
    },
    set (item) {
      if (this[item]) {
        if (!this.dataAddress) this.dataAddress = {}
        // this.dataAddress[item] = this[item]
        // https://vuejs.org/v2/api/#Vue-set
        // https://vuejs.org/v2/guide/reactivity.html
        // this.$set(this.someObject, 'b', 2)
        this.$set(this.dataAddress, item, this[item])
      } else {
        if (this.dataAddress) {
          this.$set(this.dataAddress, item, null)
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
    setLocationId () {
      if (this.locality) {
        if (this.locality.locality) {
          this.$set(this.dataAddress, 'location_id', this.locality.locality.id)
        }
      } else this.$set(this.dataAddress, 'location_id', null)
    },
    setProvinceId () {
      if (this.locality) {
        if (this.locality.province) {
          this.$set(this.dataAddress, 'province_id', this.locality.province.id)
        }
      } else this.$set(this.dataAddress, 'province_id', null)
    },
    updateDataAddress () {
      this.set('name')
      this.set('number')
      this.set('floor')
      this.set('floor_number')
      this.set('locality')
      this.setLocationId()
      this.setLocationId()
      this.setProvinceId()
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
      if (this.address.name) this.name = this.address.name
      if (this.address.number) this.number = this.address.number
      if (this.address.floor) this.floor = this.address.floor
      if (this.address.floor_number) this.floor_number = this.address.floor_number
      if (this.address.locality) this.locality = this.address.locality
    }
  }
}
</script>
