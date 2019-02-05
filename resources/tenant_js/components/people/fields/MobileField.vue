<template>
    <v-text-field
            label="Telèfon mòbil"
            v-model="dataMobile"
            required
            :tabindex="tabIndex"
            mask="+34 ###-###-###"
            placeholder="+34 666777888"
            hint="9 números seguits sense codi de país"
            :return-masked-value="false"
            @input="input"
            @blur="blur"
    ></v-text-field>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'

export default {
  'name': 'MobileField',
  mixins: [validationMixin],
  validations: {
    dataMobile: { required }
  },
  data () {
    return {
      dataMobile: this.mobile,
      loading: false
    }
  },
  // model: {
  //   prop: 'mobile',
  //   event: 'input'
  // },
  props: {
    mobile: {},
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
    mobileErrors () {
      const errors = []
      if (!this.$v.dataMobile.$dirty) return errors
      !this.$v.dataMobile.required && errors.push('El mòbil és obligatori.')
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
      this.$v.dataMobile.$touch()
      this.$emit('input', this.dataMobile)
    },
    blur () {
      this.$v.dataMobile.$touch()
      this.$emit('blur', this.dataMobile)
    }
  }
}
</script>
