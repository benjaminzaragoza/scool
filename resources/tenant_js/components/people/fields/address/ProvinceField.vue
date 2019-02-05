<template>
    <v-combobox
            name="province"
            label="Província"
            tabindex = "-1"
            item-text="name"
            autocomplete
            :loading="fetching"
            cache-items
            required
            clearable
            :items="provinces"
            :search-input.sync="searchProvinces"
            v-model="province"
            :error-messages="errors"
            @input="$v.province.$touch()"
            @blur="$v.province.$touch()"
    ></v-combobox>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default {
  name: 'ProvinceField',
  mixins: [ validationMixin ],
  validations: {
    province: { required }
  },
  data () {
    return {
      fetching: false,
      provinces: [],
      searchProvinces: null,
      province: ''
    }
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.province.$dirty) return errors
      !this.$v.province.required && errors.push('La província és obligatòria.')
      return errors
    }
  },
  watch: {
    searchProvinces (val) {
      val && (val.length > 1) && this.queryProvinces(val)
    }
  },
  methods: {
    queryProvinces (v) {
      this.provinces = this.allProvinces.filter(province => {
        return (province.name || '').toLowerCase().indexOf((v || '').toLowerCase()) > -1
      })
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
    fetchAllProvinces () {
      return new Promise((resolve, reject) => {
        this.fetching = true
        window.axios.get('/api/v1/provinces').then(response => {
          this.allProvinces = response.data
          this.fetching = false
          resolve(response)
        }).catch(error => {
          this.fetching = false
          this.$snackbar.showError(error)
          reject(error)
        })
      })
    }
  }
}
</script>
