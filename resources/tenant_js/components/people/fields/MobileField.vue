<template>
    <span style="display: inline-flex;align-items: baseline;">
        <v-icon style="align-self: normal;">smartphone</v-icon>
        <span class="subheading font-weight-bold mr-2" >+34</span>
        <v-text-field
                label="Telèfon mòbil"
                v-model="dataMobile"
                :required="required"
                :tabindex="tabIndex"
                mask="###-###-###"
                placeholder="666777888"
                hint="9 números seguits sense codi de país"
                :return-masked-value="false"
                :error-messages="mobileErrors"
                @input="input"
                @blur="blur"
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
    dataMobile: { required }
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
    },
    mobile (mobile) {
      this.dataMobile = mobile
    }
  },
  methods: {
    input () {
      if (this.required) this.$v.dataMobile.$touch()
      this.$emit('input', this.dataMobile)
    },
    blur () {
      if (this.required) this.$v.dataMobile.$touch()
      this.$emit('blur', this.dataMobile)
    }
  }
}
</script>
