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
            <person-notes-field v-model="notes"></person-notes-field>
            <other-mobiles-field v-model="otherMobiles" :required="required" :validate="validate"></other-mobiles-field>
            <other-telephones-field v-model="otherTelephones" :required="required" :validate="validate"></other-telephones-field>
            <other-emails-field v-model="otherEmails" :required="required" :validate="validate"></other-emails-field>
            <other-identifiers-field v-model="otherIdentifiers" :required="required" :validate="validate"></other-identifiers-field>
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
                    <telephone-field v-model="telephone" :required="required" :validate="validate"></telephone-field>
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
                    <civil-status-field v-model="civilStatus" :required="required" :validate="validate"></civil-status-field>
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
        Data Form:
        {{ dataForm }}
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
import TelephoneField from './fields/TelephoneField'
import OtherTelephonesField from './fields/OtherTelephonesField'
import OtherMobilesField from './fields/OtherMobilesField'
import PersonNotesField from './fields/PersonNotesField'
import OtherEmailsField from './fields/OtherEmailsField'
import OtherIdentifiersField from './fields/OtherIdentifiersField'

export default {
  name: 'UserAddPersonForm',
  components: {
    'full-identifier-field': FullIdentifierField,
    'telephone-field': TelephoneField,
    'other-telephones-field': OtherTelephonesField,
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
      otherMobiles: null,
      telephone: null,
      otherTelephones: null,
      otherEmails: null,
      otherIdentifiers: null,
      gender: null,
      birthdate: null,
      birthplace: null,
      civilStatus: null,
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
    otherMobiles () {
      this.updateDataForm()
    },
    telephone () {
      this.updateDataForm()
    },
    otherTelephones () {
      this.updateDataForm()
    },
    otherEmails () {
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
    civilStatus () {
      this.updateDataForm()
    },
    address () {
      this.updateDataForm()
    },
    notes () {
      this.updateDataForm()
    }
  },
  methods: {
    updateDataForm () {
      this.user ? (this.dataForm['email'] = this.user.email) : delete this.dataForm['email']
      this.otherEmails ? (this.dataForm['other_emails'] = this.otherEmails) : delete this.dataForm['other_emails']
      this.identifier ? (this.dataForm['identifier'] = this.identifier) : delete this.dataForm['identifier']
      this.otherIdentifiers ? (this.dataForm['other_identifiers'] = this.otherIdentifiers) : delete this.dataForm['other_identifiers']
      this.mobile ? (this.dataForm['mobile'] = this.mobile) : delete this.dataForm['mobile']
      this.otherMobiles ? (this.dataForm['otherMobiles'] = this.otherMobiles) : delete this.dataForm['otherMobiles']
      this.telephone ? (this.dataForm['phone'] = this.telephone) : delete this.dataForm['phone']
      this.otherTelephones ? (this.dataForm['other_phones'] = this.otherTelephones) : delete this.dataForm['other_phones']
      this.gender ? (this.dataForm['gender'] = this.gender) : delete this.dataForm['gender']
      this.birthdate ? (this.dataForm['birthdate'] = this.birthdate) : delete this.dataForm['birthdate']
      this.birthplace ? (this.dataForm['birthplace'] = this.birthplace) : delete this.dataForm['birthplace']
      this.civilStatus ? (this.dataForm['civil_status'] = this.civilStatus) : delete this.dataForm['civil_status']
      this.address ? (this.dataForm['address'] = this.address) : delete this.dataForm['address']
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
