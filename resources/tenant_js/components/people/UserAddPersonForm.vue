<template>
    <form>
        <span style="display:inline-flex;">
            <v-switch
                    label="Activar validació"
                    v-model="validate"
                    class="mr-4"
            ></v-switch>
            <v-switch
                    label="Camps requerits"
                    v-model="required"
                    class="mr-4"
            ></v-switch>
            <v-btn flat class="grey--text" @click="clean">Buidar camps</v-btn>
            <person-notes-field v-model="notes"></person-notes-field>
            <other-mobiles-field v-model="other_mobiles" :required="required" :validate="validate"></other-mobiles-field>
            <other-phones-field v-model="other_phones" :required="required" :validate="validate"></other-phones-field>
            <other-emails-field v-model="other_emails" :required="required" :validate="validate"></other-emails-field>
            <other-identifiers-field v-model="other_identifiers" :required="required" :validate="validate"></other-identifiers-field>
        </span>

        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md2>
                    <full-identifier-field v-model="identifier" :required="required" :validate="validate"></full-identifier-field>
                </v-flex>
                <v-flex md1>
                    <mobile-field v-model="mobile" :invalid.sync="mobileInvalid" :required="required" :validate="validate"></mobile-field>
                </v-flex>
                <v-flex md1>
                    <phone-field v-model="phone" :required="required" :validate="validate"></phone-field>
                </v-flex>
                <v-flex md1>
                    <gender-field v-model="gender" :required="required" :validate="validate"></gender-field>
                </v-flex>
                <v-flex md1>
                    <birthdate-field v-model="birthdate" :required="required" :validate="validate"></birthdate-field>
                </v-flex>
                <v-flex md5>
                    <birthplace-field v-model="birthplace" :required="required" :validate="validate"></birthplace-field>
                </v-flex>
                <v-flex md1>
                    <civil-status-field v-model="civil_status" :required="required" :validate="validate"></civil-status-field>
                </v-flex>
            </v-layout>
            <address-fields v-model="address" :invalid.sync="addressInvalid" :required="required" :validate="validate"></address-fields>
        </v-container>

        <v-btn flat @click="$emit('close')">
            <v-icon class="mr-2">exit_to_app</v-icon>Tancar
        </v-btn>
        <v-btn flat @click="$emit('back')">
            <v-icon class="mr-2">arrow_back</v-icon>Endarrera
        </v-btn>
        <v-btn flat @click="$emit('forward')">
            <v-icon class="mr-2">arrow_forward</v-icon>Següent
        </v-btn>
        <v-btn color="primary" @click="save" :loading="saving" :disabled="saving">
            <v-icon class="mr-2">save</v-icon>Guardar
        </v-btn>
    </form>
</template>

<script>

import FullIdentifierField from './fields/FullIdentifierField'
import MobileField from './fields/MobileField'
import AddressFields from './fields/AddressFields'
import BirthdateField from './fields/BirthdateField'
import BirthplaceField from './fields/BirthplaceField'
import GenderField from './fields/GenderField'
import CivilStatusField from './fields/CivilStatusField'
import PhoneField from './fields/PhoneField'
import OtherPhonesField from './fields/OtherPhonesField'
import OtherMobilesField from './fields/OtherMobilesField'
import PersonNotesField from './fields/PersonNotesField'
import OtherEmailsField from './fields/OtherEmailsField'
import OtherIdentifiersField from './fields/OtherIdentifiersField'

export default {
  name: 'UserAddPersonForm',
  components: {
    'full-identifier-field': FullIdentifierField,
    'phone-field': PhoneField,
    'other-phones-field': OtherPhonesField,
    'other-emails-field': OtherEmailsField,
    'other-identifiers-field': OtherIdentifiersField,
    'mobile-field': MobileField,
    'other-mobiles-field': OtherMobilesField,
    'gender-field': GenderField,
    'birthdate-field': BirthdateField,
    'birthplace-field': BirthplaceField,
    'civil-status-field': CivilStatusField,
    'address-fields': AddressFields,
    'person-notes-field': PersonNotesField
  },
  data () {
    return {
      dataForm: {},
      validate: true,
      required: false,
      identifier: null,
      mobile: null,
      other_mobiles: null,
      phone: null,
      other_phones: null,
      other_emails: null,
      other_identifiers: null,
      gender: null,
      birthdate: null,
      birthplace: null,
      civil_status: null,
      address: null,
      notes: null,
      saving: false,
      identifierInvalid: true,
      mobileInvalid: true,
      addressInvalid: true
    }
  },
  props: {
    user: {}
  },
  computed: {
    invalid () {
      // TODO
      if (
        (!this.identifierInvalid && this.identifier) &&
        (!this.mobileInvalid && this.mobile)
      ) {
        return false
      }
      return true
    }
  },
  watch: {
    identifier () {
      this.updateDataForm()
    },
    mobile () {
      this.updateDataForm()
    },
    other_mobiles () {
      this.updateDataForm()
    },
    phone () {
      this.updateDataForm()
    },
    other_phones () {
      this.updateDataForm()
    },
    other_emails () {
      this.updateDataForm()
    },
    gender () {
      this.updateDataForm()
    },
    birthdate () {
      this.updateDataForm()
    },
    birthplace () {
      this.updateDataForm()
    },
    civil_status () {
      this.updateDataForm()
    },
    address: {
      handler: function () {
        this.updateDataForm()
      },
      deep: true
    },
    notes () {
      this.updateDataForm()
    }
  },
  methods: {
    clean () {
      this.identifier = null
      this.other_emails = null
      this.other_identifiers = null
      this.mobile = null
      this.other_mobiles = null
      this.phone = null
      this.other_phones = null
      this.gender = null
      this.birthdate = null
      this.birthplace = null
      this.civil_status = null
      this.address = null
      this.notes = null
      this.dataForm = {}
    },
    updateBirthplace () {
      if (this.birthplace) {
        if (this.birthplace.locality) {
          if ((typeof this.birthplace.locality) === 'string') {
            this.dataForm['birthplace'] = this.birthplace
            delete this.dataForm['birthplace_id']
          } else {
            this.dataForm['birthplace_id'] = this.birthplace.locality.id
            delete this.dataForm['birthplace']
          }
        } else {
          delete this.dataForm['birthplace_id']
          this.dataForm['birthplace'] = this.birthplace
        }
        if ((!this.birthplace.locality) && (!this.birthplace.postalcode) && (!this.birthplace.province)) {
          delete this.dataForm['birthplace']
        }
      } else {
        delete this.dataForm['birthplace']
      }
    },
    set (item) {
      if (this[item]) {
        if (!this.dataForm) this.dataForm = {}
        // this.dataForm[item] = this[item]
        // https://vuejs.org/v2/api/#Vue-set
        // https://vuejs.org/v2/guide/reactivity.html
        // this.$set(this.someObject, 'b', 2)
        this.$set(this.dataForm, item, this[item])
      } else {
        if (this.dataForm) {
          this.$set(this.dataForm, item, null)
          delete this.dataForm[item]
        }
      }
    },
    updateDataForm () {
      this.set('email')
      this.set('other_emails')
      this.set('identifier')
      this.set('other_identifiers')
      this.set('mobile')
      this.set('other_mobiles')
      this.set('phone')
      this.set('other_phones')
      this.set('gender')
      this.set('birthdate')
      this.set('civil_status')
      this.updateBirthplace()
      this.set('address')
      this.set('notes')
    },
    isValid () {
      return true
    },
    save () {
      if (this.isValid()) {
        this.saving = true
        window.axios.post('/api/v1/user/' + this.user.id + '/person', this.dataForm).then(() => {
          this.$snackbar.showMessage('Dades personals guardades correctament')
          this.saving = false
          this.$emit('forward')
        }).catch((error) => {
          this.$snackbar.showError(error)
          this.saving = false
        })
      } else {
        this.$snackbar.showError('El formulari no és vàlid encara. Ompliu correctament totes les dades')
      }
    }
  }
}
</script>
