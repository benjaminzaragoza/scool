<template>
    <v-menu
            ref="menu"
            lazy
            :close-on-content-click="false"
            v-model="menu"
            transition="scale-transition"
            offset-y
            full-width
            :nudge-right="40"
            min-width="290px"
    >
        <v-text-field
                name="formattedBirthdate"
                hint="format DD/MM/AAAA"
                persistent-hint
                slot="activator"
                label="Data de naixement"
                :value="formattedBirthdate" @change.native="formattedBirthdate = $event.target.value"
                :error-messages="birthdateErrors"
                @input="$v.birthdate.$touch()"
                @blur="$v.birthdate.$touch()"
                prepend-icon="event"
        ></v-text-field>
        <v-date-picker
                ref="picker"
                locale="ca"
                :value="birthdate" @change.native="birthdate = $event.target.value"
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
    birthdate: { required }
  },
  data () {
    return {
      birthdate: '',
      menu: false
    }
  },
  computed: {
    formattedBirthdate: {
      get: () => {
        return helpers.formatDate(this.birthdate)
      },
      set: (value) =>  {
        this.birthdate = helpers.unformatDate(value)
      }
    },
    birthdateErrors () {
      const errors = []
      if (!this.$v.birthdate.$dirty) return errors
      !this.$v.birthdate.required && errors.push('La data de naixement és obligatòria.')
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
      this.$refs.menu.save(date)
    }
  }
}
</script>
