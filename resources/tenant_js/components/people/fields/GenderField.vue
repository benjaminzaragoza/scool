<template>
    <v-select
            name="gender"
            label="Sexe"
            :required="required"
            clearable
            :error-messages="errors"
            @input="input"
            @blur="blur"
            :items="dataGenders"
            v-model="dataGender"
    ></v-select>
</template>

<script>
import { required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
export default {
  name: 'GenderField',
  mixins: [validationMixin],
  validations: {
    dataGender: { required }
  },
  data () {
    return {
      dataGender: null,
      dataGenders: null
    }
  },
  model: {
    prop: 'gender',
    event: 'input'
  },
  props: {
    genders: {
      type: Array
    },
    required: {
      type: Boolean,
      default: false
    },
    gender: {},
    invalid: {}
  },
  computed: {
    errors () {
      const errors = []
      if (!this.$v.dataGender.$dirty) return errors
      !this.$v.dataGender.required && errors.push('El sexe és obligatori.')
      return errors
    }
  },
  methods: {
    touch () {
      if (this.required) this.$v.dataGender.$touch()
    },
    input () {
      this.touch()
      this.$emit('input', this.dataGender)
    },
    blur () {
      this.touch()
      this.$emit('blur', this.dataGender)
    }
  },
  created () {
    if (this.genders) this.dataGenders = this.genders
    else this.dataGenders = ['Home', 'Dona']
  }
}
</script>
