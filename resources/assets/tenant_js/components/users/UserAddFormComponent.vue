<template>
    <form>
        <v-switch v-show="existing"
                :label="newUser ? 'Nou usuari' : 'Escollir un usuari existent'"
                v-model="newUser"
        ></v-switch>
        <select-user v-if="!newUser" :users="users" v-model="user" :item-value="null"></select-user>
        <v-container v-else fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md4>
                    <v-text-field
                            label="Nom"
                            v-model="givenName"
                            :error-messages="givenNameErrors"
                            @input="$v.givenName.$touch()"
                            @blur="$v.givenName.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md4>
                    <v-text-field
                            label="Cognom1"
                            v-model="sn1"
                            :error-messages="sn1Errors"
                            @input="$v.sn1.$touch()"
                            @blur="sn1Blur()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md4>
                    <v-text-field
                            label="Cognom2"
                            v-model="sn2"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md3>
                    <v-text-field
                            :label="'Correu electrònic personal (no utilitzeu ' + tenant.email_domain +')'"
                            v-model="email"
                            :error-messages="emailErrors"
                            @input="inputEmail()"
                            @blur="$v.email.$touch()"
                            :disabled="loadingProposedUser"
                            :loading="loadingProposedUser"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md3>
                    <v-text-field
                            label="Telèfon mòbil"
                            v-model="mobile"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md2>
                    <v-switch
                            :label="welcomeEmail ? 'Email de benvinguda' : 'NO enviar email'"
                            v-model="welcomeEmail"
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
            <v-btn v-if="!user" color="primary" @click.native="create" :disabled="creating" :loading="creating">Crear usuari</v-btn>
            <template v-else>
                <v-btn color="error" @click.native="remove" :disabled="deleting" :loading="deleting">Eliminar</v-btn>
                <v-btn color="primary" @click.native="$emit('created', this.user)">Continuar</v-btn>
            </template>
        </template>
    </form>
</template>

<script>
  import axios from 'axios'
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../mixins/withSnackbar'
  import SendsWelcomeEmail from './mixins/SendsWelcomeEmail'
  import { required, email } from 'vuelidate/lib/validators'
  import * as actions from '../../store/action-types'
  import SelectUser from './UsersSelectComponent'
  import hasTenantInfo from '../mixins/hasTenantInfo'

  export default {
    mixins: [validationMixin, withSnackbar, hasTenantInfo, SendsWelcomeEmail],
    name: 'UserAddFormComponent',
    components: {
      'select-user': SelectUser
    },
    validations: {
      givenName: { required },
      sn1: { required },
      email: { required, email }
    },
    data () {
      return {
        user: null,
        username: '',
        givenName: '',
        sn1: '',
        sn2: '',
        email: '',
        mobile: '',
        password: '',
        errors: [],
        creating: false,
        deleting: false,
        loadingProposedUser: false,
        newUser: true,
        welcomeEmail: true,
        googleUser: true,
        ldapUser: true
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
      userType: {
        required: false
      },
      role: {
        required: false
      }
    },
    computed: {
      clear () {
        this.givenName = ''
        this.sn1 = ''
        this.sn2 = ''
        this.email = ''
      },
      name () {
        return (this.givenName.trim() + ' ' + this.sn1.trim() + ' ' + this.sn2.trim()).trim()
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
        if (!this.$v.email.$dirty) return emailErrors
        !this.$v.email.email && emailErrors.push('El correu electrònic ha de ser vàlid')
        !this.$v.email.required && emailErrors.push('El correu electrònic és obligatori.')
        this.errors['email'] && emailErrors.push(this.errors['email'])
        return emailErrors
      }
    },
    methods: {
      remove () {
        this.removing = true
        if (this.user) {
          this.$store.dispatch(actions.DELETE_USER_PERSON, this.user.id).then(response => {
            this.removing = false
            this.user = response.data
            this.$v.$reset()
            this.$emit('deleted', this.user)
            this.showMessage('Usuari eliminat correctament')
          }).catch(error => {
            this.removing = false
            this.showError(error)
          }).then(() => {
            this.removing = false
          })
        }
      },
      inputEmail () {
        this.errors['email'] = ''
        this.$v.sn1.$touch()
      },
      sn1Blur () {
        this.$v.sn1.$touch()
        this.proposeFreeUserName(this.givenName, this.sn1)
      },
      proposeFreeUserName (name, sn1) {
        this.loadingProposedUser = true
        if (name && sn1) {
          axios.get('/api/v1/proposeFreeUserName/' + name + '/' + sn1).then(response => {
            this.loadingProposedUser = false
            this.username = response.data
            this.email = this.username + '@' + window.tenant.email_domain
          }).catch(error => {
            this.loadingProposedUser = false
            console.log(error)
            this.showError(error)
          })
        }
      },
      select () {
        if (this.user) this.$emit('created', this.user)
        else this.showError('Cal seleccionar un usuari!')
      },
      createGoogleUser () {
        console.log('TODO create Google User')
      },
      createLdapUser () {
        console.log('TODO create Ldap User')
      },
      create () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.creating = true
          this.$store.dispatch(actions.STORE_USER_PERSON, {
            givenName: this.givenName,
            sn1: this.sn1,
            sn2: this.sn2,
            email: this.email,
            mobile: this.mobile,
            user_type_id: this.userType,
            role: this.role
          }).then(response => {
            this.creating = false
            this.user = response.data
            this.$v.$reset()
            this.welcomeEmail && this.sendWelcomeEmail(this.user)
            this.googleUser && this.createGoogleUser(this.user)
            this.ldapUser && this.createLdapUser(this.user)
            this.$emit('created', this.user)
          }).catch(error => {
            if (error && error.status === 422) {
              this.errors = error.data && error.data.errors
              this.creating = false
              this.showError(error)
            }
          }).then(() => {
            this.creating = false
          })
        }
      }
    }
  }
</script>
