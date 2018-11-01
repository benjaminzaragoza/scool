<template>
    <form>
        <v-switch v-show="existing"
                  :label="newUser ? 'Compte de Google (sense associar a usuari)' : 'Crear per a un usuari existent'"
                  v-model="newUser"
        ></v-switch>
        <select-user v-if="!newUser" :users="users" v-model="user" :item-value="null"></select-user>
        <v-container v-else fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md6>
                    <v-text-field
                            label="Nom"
                            v-model="givenName"
                            :error-messages="givenNameErrors"
                            @input="$v.givenName.$touch()"
                            @blur="$v.givenName.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            label="Cognoms"
                            v-model="familyName"
                            :error-messages="familyNameErrors"
                            @input="$v.familyName.$touch()"
                            @blur="$v.familyName.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            label="Correu electrònic corporatiu"
                            v-model="primaryEmail"
                            :error-messages="primaryEmailErrors"
                            @input="$v.primaryEmail.$touch()"
                            @blur="$v.primaryEmail.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            label="Telèfon mòbil"
                            v-model="mobile"
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            :label="'Correu electrònic personal (no utilitzeu ' + tenant.email_domain +')'"
                            v-model="secondaryEmail"
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-container>

        <v-btn color="primary" @click="create" :disabled="creating" :loading="creating">Crear</v-btn>
    </form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import withSnackbar from '../../mixins/withSnackbar'
import { required, email } from 'vuelidate/lib/validators'
import axios from 'axios'
import hasTenantInfo from '../../mixins/hasTenantInfo'
import SelectUser from '../../users/UsersSelectComponent'

export default {
  name: 'GoogleUserAddFormComponent',
  components: {
    'select-user': SelectUser
  },
  mixins: [validationMixin, withSnackbar, hasTenantInfo],
  validations: {
    givenName: { required },
    familyName: { required },
    primaryEmail: { required, email }
  },
  data () {
    return {
      givenName: '',
      familyName: '',
      primaryEmail: '',
      mobile: '',
      secondaryEmail: '',
      creating: false,
      errors: [],
      newUser: true,
      users: [],
      user: null
    }
  },
  props: {
    existing: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    clear () {
      this.givenName = ''
      this.primaryEmail = ''
      this.familyName = ''
      this.mobile = ''
      this.secondaryEmail = ''
    },
    givenNameErrors () {
      const givenNameErrors = []
      if (!this.$v.givenName.$dirty) return givenNameErrors
      !this.$v.givenName.required && givenNameErrors.push('El nom és obligatori.')
      this.errors['givenName'] && givenNameErrors.push(this.errors['givenNameErrors'])
      return givenNameErrors
    },
    familyNameErrors () {
      const familyNameErrors = []
      if (!this.$v.familyName.$dirty) return familyNameErrors
      !this.$v.familyName.required && familyNameErrors.push('El cognom és obligatori.')
      this.errors['familyName'] && familyNameErrors.push(this.errors['familyName'])
      return familyNameErrors
    },
    primaryEmailErrors () {
      const primaryEmailErrors = []
      if (!this.$v.primaryEmail.$dirty) return primaryEmailErrors
      !this.$v.primaryEmail.email && primaryEmailErrors.push('El correu electrònic ha de ser vàlid')
      !this.$v.primaryEmail.required && primaryEmailErrors.push('El correu electrònic és obligatori.')
      this.errors['primaryEmail'] && primaryEmailErrors.push(this.errors['primaryEmail'])
      return primaryEmailErrors
    }
  },
  watch: {
    // whenever question changes, this function will run
    newUser: function (newUser) {
      if (newUser === false && this.users.length === 0) this.fetchUsers()
    }
  },
  methods: {
    fetchUsers () {
      axios.get('/api/v1/users').then(response => {
        this.users = response.data
      }).catch(error => {
        this.showError(error)
      })
    },
    create () {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.creating = true
        axios.post('/api/v1/gsuite/users', {
          givenName: this.givenName,
          familyName: this.familyName,
          primaryEmail: this.primaryEmail,
          mobile: this.mobile,
          secondaryEmail: this.secondaryEmail
        }).then(response => {
          this.creating = false
          this.$emit('created', response.data)
          this.showMessage('Usuari creat correctament')
        }).catch(error => {
          this.creating = false
          console.log(error)
          this.showError(error)
        })
      } else {
        this.$v.$touch()
      }
    }
  }
}
</script>
