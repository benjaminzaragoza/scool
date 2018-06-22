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
                @input="$v.email.$touch()"
                @blur="$v.email.$touch()"
                required
        ></v-text-field>
        <v-btn color="primary" @click.native="create">Crear usuari</v-btn>
    </form>
</template>

<script>
  import axios from 'axios'
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../mixins/withSnackbar'
  import { required, email } from 'vuelidate/lib/validators'

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
        username: '',
        givenName: '',
        sn1: '',
        sn2: '',
        email: '',
        password: '',
        errors: [],
        creating: false
      }
    },
    computed: {
      user () {
        return {
          name: this.name,
          email: this.email,
          password: this.password
        }
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
      sn1Blur () {
        this.$v.sn1.$touch()
        this.proposeFreeUserName(this.givenName, this.sn1)
      },
      proposeFreeUserName (name, sn1) {
        if (name && sn1) {
          axios.get('/api/v1/proposeFreeUserName/' + name + '/' + sn1).then(response => {
            this.loading = false
            this.username = response.data
            this.email = this.username + '@' + window.tenant.email_domain
          }).catch(error => {
            this.loading = false
            console.log(error)
            this.showError(error)
          })
        }
      },
      create () {
        console.log('Create user')
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.creating = true

          // ap1/v1/user_person
          // this.$store.dispatch(actions.STORE_USER_PERSON, {
          //   name: this.name,
          //   email: this.email,
          //   password: this.password,
          //   type: this.userType && this.userType.name,
          //   roles: this.role
          // }).then(response => {
          //   this.creating = false
          //   this.clear()
          //   this.$v.$reset()
          // }).catch(error => {
          //   if (error && error.status === 422) {
          //     this.errors = error.data && error.data.errors
          //     this.creating = false
          //     this.showError(error)
          //   }
          // }).then(() => {
          //   this.creating = false
          // })
          this.$emit('created', this.user)
        }
      }
    }
  }
</script>
