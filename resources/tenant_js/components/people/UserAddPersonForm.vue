<template>
    <form>
        <span style="display:inline-flex;">
            <v-switch
                    label="Activar validació"
                    v-model="validate"
                    class="mr-4"
            ></v-switch>
            <v-switch
                    label="Camps requirits"
                    v-model="required"
            ></v-switch>
        </span>

        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md2>
                    <full-identifier-field v-model="identifier" :required="required"></full-identifier-field>
                </v-flex>
                <v-flex md1>
                    <mobile-field v-model="mobile" :invalid.sync="mobileInvalid"></mobile-field>
                </v-flex>
                <v-flex md1>
                    <birthdate-field v-model="birthdate"></birthdate-field>
                </v-flex>
                <v-flex md1>
                    <birthplace-field v-model="birthplace"></birthplace-field>
                </v-flex>
                <v-flex md1>
                    <gender-field v-model="gender"></gender-field>
                </v-flex>
                <v-flex md1>
                    <civil-status-field v-model="civilStatus"></civil-status-field>
                </v-flex>
                <v-flex md1>
                    <telephone-field v-model="telephone"></telephone-field>
                    <other-telephones-field v-model="otherTelephones"></other-telephones-field>
                </v-flex>
                <v-flex md1>
                    <mobile-field v-model="mobile"></mobile-field>
                    <other-mobiles-field v-model="otherMobiles"></other-mobiles-field>
                </v-flex>
                <v-flex md1>
                    <person-notes-field v-model="notes"></person-notes-field>
                </v-flex>
            </v-layout>
            <address-fields v-model="address" :invalid.sync="addressInvalid" :required="validate"></address-fields>
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
        <v-btn color="primary" @click="save" :loading="saving" :disabled="saving || invalid">
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

export default {
  name: 'UserAddPersonForm',
  components: {
    'full-identifier-field': FullIdentifierField,
    'address-fields': AddressFields,
    'birthdate-field': BirthdateField,
    'birthplace-field': BirthplaceField,
    'gender-field': GenderField,
    'civil-status-field': CivilStatusField,
    'telephone-field': TelephoneField,
    'other-telephones-field': OtherTelephonesField,
    'mobile-field': MobileField,
    'other-mobiles-field': OtherMobilesField,
    'person-notes-field': PersonNotesField
  },
  data () {
    return {
      dataForm: {},
      validate: true,
      required: false,
      identifierType: null,
      identifierTypeInvalid: true,
      identifier: null,
      identifierInvalid: true,
      gender: null,
      notes: null,
      civilStatus: null,
      telephone: null,
      otherTelephones: null,
      mobile: null,
      mobileInvalid: true,
      otherMobiles: null,
      address: null,
      addressInvalid: true,
      birthdate: null,
      birthplace: null,
      saving: false
    }
  },
  props: {
    user: {}
  },
  computed: {
    invalid () {
      if (
        (!this.identifierTypeInvalid && this.identifierType) &&
        (!this.identifierInvalid && this.identifier) &&
        (!this.mobileInvalid && this.mobile)
      ) {
        return false
      }
      return true
    }
  },
  watch: {
    gender () {
      this.updateDataForm()
    },
    civilStatus () {
      this.updateDataForm()
    }
  },
  methods: {
    updateDataForm () {
      this.gender ? (this.dataForm['gender'] = this.gender) : delete this.dataForm['gender']
      this.civilStatus ? (this.dataForm['civilStatus'] = this.civilStatus) : delete this.dataForm['civilStatus']
    },
    save () {
      console.log('TODO SAVE')
      const personalData = {
        'identifierType': this.identifierType
      }
      console.log('personalData:')
      console.log(personalData)
      this.$emit('forward')
    }
  }
}
</script>
