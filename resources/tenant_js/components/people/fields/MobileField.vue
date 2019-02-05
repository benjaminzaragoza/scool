<template>
    <span>
        <v-text-field
                name="mobile"
                label="Mòbil"
                v-model="dataMobile"
                :error-messages="mobileErrors"
                @input="input"
                @blur="blur"
                required
        ></v-text-field>
    </span>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default {
  'name': 'MobileField',
  mixins: [validationMixin],
  validations: {
    mobile: { required }
  },
  data () {
    return {
      dataMobile: this.mobile,
      loading: false
    }
  },
  model: {
    prop: 'mobile',
    event: 'input'
  },
  props: {
    mobile: {},
    invalid: {}
  },
  computed: {
    mobileErrors () {
      const errors = []
      if (!this.$v.mobile.$dirty) return errors
      !this.$v.mobile.required && errors.push('El mòbil és obligatori.')
      return errors
    },
    invalidForm () {
      return this.$v.$invalid
    }
  },
  watch: {
    invalidForm (invalidForm) {
      this.$emit('update:invalid', invalidForm)
    }
  },
  methods: {
    input () {
      this.$v.mobile.$touch()
      this.$emit('input', this.dataMobile)
    },
    blur () {
      this.$v.mobile.$touch()
      this.$emit('blur', this.dataMobile)
    }
  }
}
</script>
