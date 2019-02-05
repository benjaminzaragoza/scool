<template>
    <v-select
            name="civilStatus"
            label="Estat cívil"
            :required="required"
            clearable
            :error-messages="errors"
            @input="input"
            @blur="blur"
            :items="dataCivilStatuses"
            v-model="dataCivilStatus"
    ></v-select>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
export default {
  name: 'GenderField',
  mixins: [validationMixin],
  validations: {
    dataCivilStatus: { required }
  },
  data () {
    return {
      dataCivilStatus: null,
      dataCivilStatuses: null
    }
  },
  model: {
    prop: 'civilStatus',
    event: 'input'
  },
  props: {
    civilStatuses: {
      type: Array
    },
    required: {
      type: Boolean,
      default: false
    },
    civilStatus: {},
    invalid: {}
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.dataCivilStatus.$dirty) return errors
      !this.$v.dataCivilStatus.required && errors.push('El esta cívil és obligatori.')
      return errors
    }
  },
  methods: {
    touch () {
      if (this.required) this.$v.dataCivilStatus.$touch()
    },
    input () {
      this.touch()
      this.$emit('input', this.dataCivilStatus)
    },
    blur () {
      this.touch()
      this.$emit('blur', this.dataCivilStatus)
    }
  },
  created () {
    if (this.civilStatus) this.dataCivilStatuses = this.civilStatus
    else this.dataCivilStatuses = ['Solter/a', 'Casat/da', 'Separat/da', 'Divorciat/da', 'Vidu/a']
  }
}
</script>
