<template>
    <v-menu
                ref="birthdateMenu"
                lazy
                :close-on-content-click="false"
                v-model="menu"
                transition="scale-transition"
                offset-y
                full-width
                :nudge-right="40"
                min-width="290px"
                :return-value.sync="dataBirthdate"
        >
        <v-text-field
                name="formattedBirthdate"
                hint="DD-MM-AAAA. Ex: 02-03-1978"
                persistent-hint
                mask="##-##-####"
                slot="activator"
                label="Data de naixement"
                :value="formattedBirthdate" @change.native="formattedBirthdate = $event.target.value"
                prepend-icon="event"
                :error-messages="dataBirthdateErrors"
                @input="input"
                @blur="blur"
        ></v-text-field>
        <v-date-picker
                ref="picker"
                locale="ca"
                :first-day-of-week="1"
                :value="dataBirthdate" @change.native="dataBirthdate = $event.target.value"
                @change="saveBirthdate"
                min="1900-01-01"
                :max="new Date().toISOString().substr(0, 10)"
        ></v-date-picker>
    </v-menu>
</template>
<script>
import { required } from 'vuelidate/lib/validators'
import helpers from '../../../utils/helpers'
import { validationMixin } from 'vuelidate'

export default {
  name: 'BirthdateField',
  mixins: [validationMixin],
  validations: {
    dataBirthdate: { required }
  },
  data () {
    return {
      dataBirthdate: '',
      menu: false
    }
  },
  model: {
    prop: 'birthdate',
    event: 'input'
  },
  props: {
    birthdate: {},
    required: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    formattedBirthdate: {
      get: function () {
        return helpers.formatDate(this.dataBirthdate)
      },
      set: function (value) {
        this.dataBirthdate = helpers.unformatDate(value)
      }
    },
    dataBirthdateErrors () {
      const errors = []
      if (!this.$v.dataBirthdate.$dirty) return errors
      !this.$v.dataBirthdate.required && errors.push('La data de naixement és obligatòria.')
      return errors
    }
  },
  watch: {
    menu (val) {
      val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
    }
  },
  methods: {
    saveBirthdate (date) {
      this.$refs.birthdateMenu.save(date)
    },
    input () {
      if (this.required) this.$v.dataBirthdate.$touch()
      this.$emit('input', this.dataBirthdate)
    },
    blur () {
      if (this.required) this.$v.dataBirthdate.$touch()
      this.$emit('blur', this.dataBirthdate)
    }
  }
}
</script>
