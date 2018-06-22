<template>
    <form>
        <v-container fluid grid-list-md text-xs-center>
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
            </v-layout>
        </v-container>
        <v-text-field
                label="Correu electrònic"
                v-model="email"
                :error-messages="emailErrors"
                @input="inputEmail()"
                @blur="$v.email.$touch()"
                :disabled="loadingProposedUser"
                :loading="loadingProposedUser"
                required
        ></v-text-field>
        <v-btn v-if="!user" color="primary" @click.native="create" :disabled="creating" :loading="creating">Crear usuari</v-btn>
        <template v-else>
            <v-btn color="error" @click.native="remove" :disabled="deleting" :loading="deleting">Eliminar</v-btn>
            <v-btn color="primary" @click.native="$emit('created', this.user)">Continuar</v-btn>
        </template>
    </form>
</template>

<script>
  import axios from 'axios'
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../mixins/withSnackbar'
  import { required, email } from 'vuelidate/lib/validators'
  import * as actions from '../../store/action-types'

  export default {
    mixins: [validationMixin, withSnackbar],
    name: 'UserAddFormComponent',
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
        password: '',
        errors: [],
        creating: false,
        deleting: false,
        loadingProposedUser: false
      }
    },
    props: {
      userType: {
        type: String,
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
      create () {
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.creating = true
          this.$store.dispatch(actions.STORE_USER_PERSON, {
            givenName: this.givenName,
            sn1: this.sn1,
            sn2: this.sn2,
            email: this.email,
            type: this.userType
          }).then(response => {
            this.creating = false
            this.user = response.data
            this.$v.$reset()
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
