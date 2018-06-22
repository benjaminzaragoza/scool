<template>
    <form>
        <v-text-field
                label="Nom"
                v-model="name"
                :error-messages="nameErrors"
                :counter="255"
                @input="nameInput()"
                @blur="$v.name.$touch()"
                required
        ></v-text-field>
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
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../mixins/withSnackbar'
  import { required, maxLength, email } from 'vuelidate/lib/validators'

  export default {
    mixins: [validationMixin, withSnackbar],
    name: 'UserAddFormComponent',
    validations: {
      name: { required, maxLength: maxLength(255) },
      email: { required, email }
    },
    data () {
      return {
        name: '',
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
      suggestedUserName () {

      },
      nameErrors () {
        const nameErrors = []
        if (!this.$v.name.$dirty) return nameErrors
        !this.$v.name.maxLength && nameErrors.push('El nom ha de tenir com a màxim 255 caràcters.')
        !this.$v.name.required && nameErrors.push('El nom és obligatori.')
        this.errors['name'] && nameErrors.push(this.errors['name'])
        return nameErrors
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
      nameInput () {
        console.log('name: ' + this.name)
        this.email = this.suggestedUserName + '@iesebre.com'
        this.$v.name.$touch()
      },
      create () {
        console.log('Create user')
        this.$v.$touch()
        if (!this.$v.$invalid) {
          this.creating = true

          // this.$store.dispatch(actions.STORE_USER, {
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
