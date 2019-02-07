<template>
    <v-text-field
            prepend-icon="phone"
            label="Telèfon Fix"
            v-model="dataPhone"
            :required="required"
            :tabindex="tabIndex"
            placeholder="977 40 45 78"
            :error-messages="phoneErrors"
            @input="input"
            @blur="blur"
    ></v-text-field>
</template>

<script>
import { required, numeric, maxLength } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default {
  name: 'TelephoneField',
  mixins: [validationMixin],
  validations: {
    dataPhone: { required, numeric, maxLength: maxLength(9) }
  },
  data () {
    return {
      dataPhone: this.phone
    }
  },
  model: {
    prop: 'phone',
    event: 'input'
  },
  props: {
    phone: {},
    invalid: {},
    tabIndex: {
      default: null
    },
    required: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    phoneErrors () {
      const errors = []
      if (!this.$v.dataPhone.$dirty) return errors
      !this.$v.dataPhone.numeric && errors.push('El telèfon ha de ser un número.')
      !this.$v.dataPhone.maxLength && errors.push('El número màxim de números és 9')
      if (this.required) !this.$v.dataPhone.required && errors.push('El mòbil és obligatori.')
      return errors
    }
  },
  watch: {
    phone (phone) {
      this.dataPhone = phone
    }
  },
  methods: {
    input () {
      this.$v.dataPhone.$touch()
      this.$emit('input', this.dataPhone)
    },
    blur () {
      this.$v.dataPhone.$touch()
      this.$emit('blur', this.dataPhone)
    }
  }
}

</script>
