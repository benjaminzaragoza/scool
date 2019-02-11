<template>
    <v-layout row wrap>
        <v-flex md3>
            <v-combobox
                    prepend-icon="location_on"
                    name="postalcode"
                    :label="postalCodeLabel"
                    :loading="loading"
                    placeholder="43500"
                    hint="Codi postal 5 dígits"
                    cache-items
                    required
                    autocomplete
                    clearable
                    :error-messages="postalcodeErrors"
                    @input="touchPostalCode"
                    @blur="touchPostalCode"
                    :items="postalCodes"
                    :search-input.sync="searchPostalCodes"
                    v-model="postalcode"
            ></v-combobox>
        </v-flex>
        <v-flex md6>
            <v-combobox
                    prepend-icon="location_city"
                    name="locality"
                    :label="localityLabel"
                    tabindex = "-1"
                    item-text="name"
                    :loading="loading"
                    cache-items
                    required
                    autocomplete
                    clearable
                    :items="localities"
                    :search-input.sync="searchLocalities"
                    :error-messages="localityErrors"
                    v-model="locality"
                    @input="touchLocality"
                    @blur="touchLocality"
            ></v-combobox>
        </v-flex>
        <v-flex md3>
            <v-combobox
                    name="province"
                    :label="provinceLabel"
                    tabindex = "-1"
                    item-text="name"
                    autocomplete
                    :loading="loading"
                    cache-items
                    required
                    clearable
                    :items="provinces"
                    :search-input.sync="searchProvinces"
                    v-model="province"
                    :error-messages="provinceErrors"
                    @input="touchProvince"
                    @blur="touchProvince"
            ></v-combobox>
        </v-flex>
    </v-layout>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default {
  name: 'LocalityComplexField',
  mixins: [ validationMixin ],
  validations: {
    locality: { required },
    postalcode: { required },
    province: { required }
  },
  data () {
    return {
      loading: false,
      postalcode: null,
      postalCodes: [],
      searchPostalCodes: null,
      locality: null,
      localities: [],
      searchLocalities: null,
      province: null,
      provinces: [],
      searchProvinces: null,
      dataLocalityObject: null
    }
  },
  model: {
    prop: 'localityObject',
    event: 'input'
  },
  props: {
    localityObject: {},
    required: {
      type: Boolean,
      default: false
    },
    postalCodeLabel: {
      type: String,
      default: 'Codi Postal'
    },
    localityLabel: {
      type: String,
      default: 'Localitat'
    },
    provinceLabel: {
      type: String,
      default: 'Província'
    }
  },
  computed: {
    postalcodeErrors () {
      const errors = []
      if (!this.$v.postalcode.$dirty) return errors
      !this.$v.postalcode.required && errors.push('El codi postal és obligatòri.')
      return errors
    },
    localityErrors () {
      const errors = []
      if (!this.$v.locality.$dirty) return errors
      !this.$v.locality.required && errors.push('El poble/ciutat és obligatòri.')
      return errors
    },
    provinceErrors () {
      const errors = []
      if (!this.$v.province.$dirty) return errors
      !this.$v.province.required && errors.push('La província és obligatòria.')
      return errors
    }
  },
  watch: {
    searchPostalCodes (val) {
      val && (val.length > 1) && this.queryPostalCodes(val)
    },
    searchLocalities (val) {
      val && (val.length > 1) && this.queryLocalities(val)
    },
    searchProvinces (val) {
      val && (val.length > 1) && this.queryProvinces(val)
    },
    postalcode: function (postalcode) {
      this.setLocality(postalcode)
      this.setProvince(postalcode)
      this.setLocalityObject()
    },
    province: function () {
      this.setLocalityObject()
    },
    locality: function () {
      this.setLocalityObject()
    },
    localityObject (localityObject) {
      this.dataLocalityObject = localityObject
      if (localityObject === null) {
        this.locality = null
        this.postalcode = null
        this.province = null
      }
    }
  },
  methods: {
    checkIsVoid () {
      if (!this.postalcode && !this.locality && !this.province) {
        return true
      }
      return false
    },
    setLocalityObject () {
      if (this.checkIsVoid()) {
        this.dataLocalityObject = null
      } else {
        this.dataLocalityObject = {
          postalcode: this.postalcode,
          locality: this.locality,
          province: this.province
        }
      }
      this.$emit('input', this.dataLocalityObject)
    },
    setLocality (postalCode) {
      if (postalCode) {
        let foundLocality = this.allLocalities.find(locality => {
          return locality.postalcode === postalCode
        })
        if (foundLocality) {
          this.localities.push(foundLocality)
          this.locality = foundLocality
        }
      }
    },
    setProvince (postalCode) {
      if (postalCode) {
        let foundProvince = this.allProvinces.find(province => {
          return postalCode.startsWith(province.postal_code_prefix)
        })
        if (foundProvince) {
          this.provinces.push(foundProvince)
          this.province = foundProvince
        }
      }
    },
    queryPostalCodes (v) {
      this.postalCodes = this.allPostalCodes.filter(postalCode => {
        return (postalCode || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
      })
    },
    queryLocalities (v) {
      this.localities = this.allLocalities.filter(locality => {
        return (locality.name || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
      })
    },
    queryProvinces (v) {
      this.provinces = this.allProvinces.filter(province => {
        return (province.name || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
      })
    },
    touchPostalCode () {
      if (this.required) this.$v.postalcode.$touch()
    },
    touchLocality () {
      if (this.required) this.$v.locality.$touch()
    },
    touchProvince () {
      if (this.required) this.$v.province.$touch()
    },
    fetchAllProvinces () {
      return new Promise((resolve, reject) => {
        this.loading = true
        window.axios.get('/api/v1/provinces').then(response => {
          this.allProvinces = response.data
          this.loading = false
          resolve(response)
        }).catch(error => {
          this.loading = false
          this.showError(error)
          reject(error)
        })
      })
    },
    fetchAllLocalities () {
      return new Promise((resolve, reject) => {
        this.loading = true
        window.axios.get('/api/v1/localities').then(response => {
          this.allLocalities = response.data
          this.allPostalCodes = [...new Set(this.allLocalities.map(locality => locality['postalcode']))] // Remove duplicates
          this.loading = false
          this.fillTipicalLocaties()
          resolve(response)
        }).catch(error => {
          this.loading = false
          this.$snackbar.showError(error)
          reject(error)
        })
      })
    },
    fillTipicalLocaties () {
      this.localities = this.allLocalities.filter(locality => {
        return locality.postalcode.startsWith('43')
      })
    }
  },
  created () {
    this.fetchAllProvinces().then(() => {
      this.fetchAllLocalities().then(() => {})
    })
  }
}
</script>
