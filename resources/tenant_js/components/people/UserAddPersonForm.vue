<template>
    <form>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md1>
                    <identifier-type-field v-model="identifierType" :invalid.sync="identifierTypeInvalid"></identifier-type-field>
                </v-flex>
                <v-flex md1>
                    <identifier-field v-model="identifier" :invalid.sync="identifierInvalid"></identifier-field>
                </v-flex>
                <v-flex md1>
                    <mobile-field v-model="mobile" :invalid.sync="mobileInvalid"></mobile-field>
                </v-flex>
                <v-flex md1>
                    <birthdate-field></birthdate-field>
                </v-flex>
                <v-flex md1>
                    <birthplace-field></birthplace-field>
                </v-flex>
                <v-flex md1>
                    <gender-field v-model="gender"></gender-field>
                </v-flex>
                <v-flex md1>
                    <civil-status-field v-model="civilStatus"></civil-status-field>
                </v-flex>
                <v-flex md1>
                    <telephone-field></telephone-field>
                    <other-telephones-field></other-telephones-field>
                </v-flex>
                <v-flex md1>
                    <mobile-field></mobile-field>
                    <other-mobiles-field></other-mobiles-field>
                </v-flex>
                <v-flex md1>
                    <person-notes-field></person-notes-field>
                </v-flex>
            </v-layout>
            <address-fields v-model="address" :invalid.sync="addressInvalid"></address-fields>
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
    </form>
</template>

<script>

import IdentifierTypeField from './fields/IdentifierTypeField'
import IdentifierField from './fields/IdentifierField'
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
    'identifier-type-field': IdentifierTypeField,
    'identifier-field': IdentifierField,
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
      identifierType: null,
      identifierTypeInvalid: true,
      identifier: null,
      identifierInvalid: true,
      mobile: null,
      mobileInvalid: true,
      gender: null,
      civilStatus: null,
      address: null,
      addressInvalid: true,
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
  methods: {
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
