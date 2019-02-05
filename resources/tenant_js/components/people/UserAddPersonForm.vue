<template>
    <form>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md1>
                    <identifier-type-field v-model="identifierType" :invalid.sync="identifierTypeInvalid"></identifier-type-field>
                </v-flex>
                <v-flex md2>
                    <identifier-field v-model="identifier" :invalid.sync="identifierInvalid"></identifier-field>
                </v-flex>
                <v-flex md2>
                    <mobile-field v-model="mobile" :invalid.sync="mobileInvalid"></mobile-field>
                </v-flex>
                <v-flex md2>
                    <address-fields v-model="address" :invalid.sync="addressInvalid"></address-fields>
                </v-flex>
                <v-flex md3>
                    DATA DE NAIXEMENT
                </v-flex>
                <v-flex md2>
                    LLOC DE NAIXEMENT
                </v-flex>
                <v-flex md2>
                    SEXE
                </v-flex>
                <v-flex md3>
                    ESTAT CIVIL
                </v-flex>
                <v-flex md3>
                    TELEFON | TELEFONS
                </v-flex>
                <v-flex md1>
                    MOBIL | ALTRES MOBILS
                </v-flex>
                <v-flex md2>
                    NOTES
                </v-flex>
            </v-layout>
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
import AddressField from './fields/AddressFields'

export default {
  name: 'UserAddPersonForm',
  components: {
    'identifier-type-field': IdentifierTypeField,
    'identifier-field': IdentifierField,
    'mobile-field': MobileField,
    'address-field': AddressField
  },
  data () {
    return {
      identifierType: null,
      identifierTypeInvalid: true,
      identifier: null,
      identifierInvalid: true,
      mobile: null,
      mobileInvalid: true,
      address: null,
      addressInvalid: true,
      saving: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
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
