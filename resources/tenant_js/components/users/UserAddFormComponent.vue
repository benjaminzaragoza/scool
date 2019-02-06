<template>
    <form>
        <v-switch v-show="existing"
                :label="newUser ? 'Nou usuari' : 'Escollir un usuari existent'"
                v-model="newUser"
        ></v-switch>
        <select-user v-if="!newUser" :users="users" v-model="user" :item-value="null"></select-user>
        <v-container v-else fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md2>
                    <user-types-select
                        :user-types="userTypes"
                        v-model="dataUserType"
                        :error-messages="userTypeErrors"
                        @input="$v.dataUserType.$touch()"
                        @blur="$v.dataUserType.$touch()"
                        :tabindex="1"
                    ></user-types-select>
                </v-flex>
                <v-flex md3>
                    <v-text-field
                            label="Nom"
                            v-model="givenName"
                            :error-messages="givenNameErrors"
                            @input="$v.givenName.$touch()"
                            @blur="givenNameBlur()"
                            tabindex="2"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md2>
                    <v-text-field
                            label="Cognom1"
                            v-model="sn1"
                            :error-messages="sn1Errors"
                            @input="$v.sn1.$touch()"
                            @blur="sn1Blur()"
                            tabindex="3"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md2>
                    <v-text-field
                            label="Cognom2"
                            v-model="sn2"
                            @blur="sn2Blur()"
                            required
                            tabindex="4"
                    ></v-text-field>
                </v-flex>
                <v-flex md3>
                    <v-text-field
                            label="Usuari"
                            v-model="username"
                            readonly
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md3>
                    <v-text-field
                            :label="'Correu electrònic personal (no utilitzeu ' + tenant.email_domain +')'"
                            v-model="personalEmail"
                            :error-messages="emailErrors"
                            @input="inputEmail()"
                            @blur="personalEmailBlur()"
                            :disabled="loadingProposedUser"
                            :loading="loadingProposedUser"
                            required
                            tabindex="5"
                    ></v-text-field>
                </v-flex>
                <v-flex md1>
                    <mobile-field v-model="mobile" tab-index="6"></mobile-field>
                </v-flex>
                <v-flex md2>
                    <v-switch
                            :label="welcomeEmail ? 'Email de benvinguda' : 'NO enviar email'"
                            v-model="welcomeEmail"
                    ></v-switch>
                </v-flex>
                <v-flex md2>
                    <v-switch
                            :label="moodleUser ? 'Crear usuari Moodle' : 'NO crear usuari Moodle'"
                            v-model="moodleUser"
                    ></v-switch>
                </v-flex>
                <v-flex md2>
                    <v-switch
                            :label="googleUser ? 'Crear usuari de Google' : 'NO crear usuari de Google'"
                            v-model="googleUser"
                    ></v-switch>
                </v-flex>
                <v-flex md2>
                    <v-switch
                            :label="ldapUser ? 'Crear usuari Ldap (Samba, Moodle...)' : 'NO crear usuari Ldap'"
                            v-model="ldapUser"
                    ></v-switch>
                </v-flex>
            </v-layout>
        </v-container>

        <v-btn v-if="!newUser" color="primary" @click="select">Seleccionar</v-btn>
        <template v-else>
            <v-btn
                    v-if="!user"
                    color="primary"
                    @click.native="create"
                    :disabled="creating || $v.$invalid"
                    :loading="creating"
                >Crear usuari
            </v-btn>
            <template v-else>
                <v-btn color="error" @click.native="remove" :disabled="deleting" :loading="deleting" flat>Eliminar</v-btn>
                <v-btn color="primary" @click.native="$emit('created', this.user)">Continuar</v-btn>
            </template>
        </template>
    </form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import SendsWelcomeEmail from './mixins/SendsWelcomeEmail'
import { required, email } from 'vuelidate/lib/validators'
import * as actions from '../../store/action-types'
import SelectUser from './UsersSelectComponent'
import UserTypesSelect from './UserTypesSelect'
import hasTenantInfo from '../mixins/hasTenantInfo'
import MobileField from '../people/fields/MobileField'

export default {
  name: 'UserAddForm',
  mixins: [validationMixin, hasTenantInfo, SendsWelcomeEmail],
  components: {
    'user-types-select': UserTypesSelect,
    'select-user': SelectUser,
    'mobile-field': MobileField
  },
  validations: {
    dataUserType: { required },
    givenName: { required },
    sn1: { required },
    personalEmail: { required, email }
  },
  data () {
    return {
      user: null,
      username: '',
      dataUserType: this.userType,
      givenName: '',
      sn1: '',
      sn2: '',
      email: '',
      personalEmail: '',
      mobile: '',
      password: '',
      errors: [],
      creating: false,
      deleting: false,
      loadingProposedUser: false,
      checkingPersonalEmail: false,
      checkingUsername: false,
      newUser: true,
      welcomeEmail: false,
      // welcomeEmail: true,
      // googleUser: true,
      googleUser: false,
      ldapUser: true,
      // moodleUser: true
      moodleUser: false
    }
  },
  props: {
    existing: {
      type: Boolean,
      default: false
    },
    users: {
      type: Array,
      required: true
    },
    userTypes: {
      type: Array,
      required: true
    },
    userType: {
      required: false
    },
    role: {
      required: false
    }
  },
  watch: {
    userType (userType) {
      this.dataUserType = userType
    },
    dataUserType (dataUserType) {
      this.googleUser = this.googleUsers[dataUserType]
      this.ldapUser = this.ldapUsers[dataUserType]
      this.moodleUser = this.moodleUsers[dataUserType]
    }
  },
  computed: {
    name () {
      return (this.givenName.trim() + ' ' + this.sn1.trim() + ' ' + this.sn2.trim()).trim()
    },
    userTypeErrors () {
      const userTypeErrors = []
      if (!this.$v.dataUserType.$dirty) return userTypeErrors
      !this.$v.dataUserType.required && userTypeErrors.push("El tipus d'usuari és obligatori.")
      this.errors['dataUserType'] && userTypeErrors.push(this.errors['dataUserType'])
      return userTypeErrors
    },
    givenNameErrors () {
      const givenNameErrors = []
      if (!this.$v.givenName.$dirty) return givenNameErrors
      !this.$v.givenName.required && givenNameErrors.push('El nom és obligatori.')
      this.errors['givenName'] && givenNameErrors.push(this.errors['givenName'])
      return givenNameErrors
    },
    sn1Errors () {
      const sn1Errors = []
      if (!this.$v.sn1.$dirty) return sn1Errors
      !this.$v.sn1.required && sn1Errors.push('El 1r cognom és obligatori.')
      this.errors['sn1'] && sn1Errors.push(this.errors['sn1'])
      return sn1Errors
    },
    emailErrors () {
      const emailErrors = []
      if (!this.$v.personalEmail.$dirty) return emailErrors
      !this.$v.personalEmail.email && emailErrors.push('El correu electrònic ha de ser vàlid')
      !this.$v.personalEmail.required && emailErrors.push('El correu electrònic és obligatori.')
      this.errors['email'] && emailErrors.push(this.errors['email'])
      return emailErrors
    }
  },
  methods: {
    clear () {
      this.givenName = ''
      this.sn1 = ''
      this.sn2 = ''
      this.email = ''
    },
    async remove () {
      let res = await this.$confirm('Esteu segurs que voleu eliminar aquest usuari?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removing = true
        if (this.user) {
          this.$store.dispatch(actions.DELETE_USER_PERSON, this.user.id).then(response => {
            this.removing = false
            this.user = response.data
            this.$v.$reset()
            this.$emit('deleted', this.user)
            this.$snackbar.showMessage('Usuari eliminat correctament')
          }).catch(error => {
            this.removing = false
            this.$snackbar.showError(error)
          }).then(() => {
            this.removing = false
          })
        }
      }
    },
    inputEmail () {
      this.errors['email'] = ''
      this.$v.personalEmail.$touch()
    },
    personalEmailBlur () {
      this.$v.personalEmail.$touch()
      if (!this.$v.personalEmail.$error) this.checkPersonalEmail()
    },
    checkPersonalEmail () {
      this.checkingPersonalEmail = true
      if (this.personalEmail) {
        window.axios.get('/api/v1/users/email/' + this.personalEmail).then(response => {
          this.checkingPersonalEmail = false
          this.username = response.data
          this.email = this.username + '@' + window.tenant.email_domain
          this.$snackbar.showMessage('Ja existeix un usuari amb aquest correu electrònic')
        }).catch(error => {
          this.checkingPersonalEmail = false
          if (error.status !== 404) this.$snackbar.showError(error)
        })
      }
    },
    checkUsername () {
      this.checkingUsername = true
      if (this.name) {
        window.axios.get('/api/v1/users/name/' + this.name).then(response => {
          this.checkingUsername = false
          this.$snackbar.showMessage('Vigileu! Ja existeix un usuari amb aquest nom i correu electrònic: ' + response.data.email + '. Potser no cal crear aquest usuari?')
        }).catch(error => {
          this.checkingUsername = false
          if (error.status !== 404) this.$snackbar.showError(error)
        })
      }
    },
    formatName (name) {
      const lc = name.toLowerCase()
      return lc.charAt(0).toUpperCase() + lc.slice(1)
    },
    givenNameBlur () {
      this.$v.givenName.$touch()
      this.givenName = this.formatName(this.givenName)
    },
    sn1Blur () {
      this.$v.sn1.$touch()
      this.sn1 = this.formatName(this.sn1)
      this.proposeFreeUserName(this.givenName, this.sn1)
    },
    sn2Blur () {
      this.sn2 = this.formatName(this.sn2)
      this.checkUsername()
    },
    proposeFreeUserName (name, sn1) {
      this.loadingProposedUser = true
      if (name && sn1) {
        window.axios.get('/api/v1/proposeFreeUserName/' + name + '/' + sn1).then(response => {
          this.loadingProposedUser = false
          this.username = response.data
          this.email = this.username + '@' + window.tenant.email_domain
        }).catch(error => {
          this.loadingProposedUser = false
          this.$snackbar.showError(error)
        })
      }
    },
    select () {
      if (this.user) this.$emit('created', this.user)
      else this.$snackbar.showError('Cal seleccionar un usuari!')
    },
    getFamilyName (sn1, sn2) {
      return sn2 ? sn1 + ' ' + sn2 : sn1
    },
    createMoodleUser () {
      window.axios.post('/api/v1/moodle/users', {
        'user': {
          'username': this.email,
          'firstname': this.givenName,
          'lastname': this.getFamilyName(this.sn1, this.sn2),
          'email': this.personalEmail,
          'createpassword': true,
          'idnumber': this.user.id
        }
      }).then((response) => {
        this.associateMoodleUserToLocalUser(response.data)
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    createGoogleUser () {
      window.axios.post('/api/v1/gsuite/users', {
        id: this.user.id,
        givenName: this.givenName,
        familyName: this.getFamilyName(this.sn1, this.sn2),
        primaryEmail: this.email,
        mobile: this.mobile,
        secondaryEmail: this.personalEmail
      }).then(response => {
        this.associateGoogleUserToLocalUser(response.data)
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    associateMoodleUserToLocalUser (moodleUser) {
      window.axios.post('/api/v1/user/' + this.user.id + '/moodle', {
        moodle_id: moodleUser.id
      }).then(() => {
        this.$snackbar.showMessage('Usuari Moodle associat correctament')
        this.$emit('moodleUserCreated', moodleUser)
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    associateGoogleUserToLocalUser (googleUser) {
      window.axios.post('/api/v1/user/' + this.user.id + '/gsuite', {
        google_id: googleUser.id,
        google_email: googleUser.primaryEmail
      }).then(() => {
        this.$snackbar.showMessage('Usuari Google associat correctament')
        this.$emit('googleUserCreated', googleUser)
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    },
    createLdapUser () {
      // TODO
      console.log('TODO create Ldap User')
    },
    create () {
      this.$v.$touch()
      if (this.personalEmail.endsWith(this.tenant.email_domain)) {
        this.$snackbar.showError('Cal indicar un email personal, no es pot utilitzar el domini ' + this.tenant.email_domain)
        return
      }
      if (!this.$v.$invalid) {
        this.creating = true
        this.$store.dispatch(actions.STORE_USER_PERSON, {
          givenName: this.givenName,
          sn1: this.sn1,
          sn2: this.sn2,
          email: this.personalEmail,
          mobile: this.mobile,
          user_type_id: this.dataUserType,
          role: this.role
        }).then(response => {
          this.creating = false
          this.user = response.data
          this.$v.$reset()
          this.welcomeEmail && this.sendWelcomeEmail(this.user)
          this.googleUser && this.createGoogleUser()
          this.moodleUser && this.createMoodleUser()
          this.ldapUser && this.createLdapUser(this.user)
          this.$emit('created', this.user)
        }).catch(error => {
          if (error && error.status === 422) {
            this.errors = error.data && error.data.errors
            this.creating = false
            this.$snackbar.showError(error)
          }
        }).then(() => {
          this.creating = false
        })
      }
    }
  },
  created () {
    // 1 Teacher // 2 Student // 3 Janitor // 4 Administratiu // 5 Familiars
    this.googleUsers = {
      1: true,
      2: true,
      3: true,
      4: true,
      5: false
    }
    this.ldapUsers = {
      1: true,
      2: true,
      3: true,
      4: true,
      5: false
    }
    this.moodleUsers = {
      1: true,
      2: true,
      3: false,
      4: false,
      5: false
    }
  }
}
</script>
