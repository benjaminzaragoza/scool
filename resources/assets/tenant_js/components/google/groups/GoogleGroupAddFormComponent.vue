<template>
    <form>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex md6>
                    <v-text-field
                            label="Nom"
                            v-model="name"
                            :error-messages="nameErrors"
                            @input="$v.name.$touch()"
                            @blur="$v.name.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            label="Correu electrònic"
                            v-model="email"
                            :error-messages="emailErrors"
                            @input="inputEmail()"
                            @blur="$v.email.$touch()"
                            required
                    ></v-text-field>
                </v-flex>
                <v-flex md6>
                    <v-text-field
                            label="Descripció"
                            v-model="description"
                    ></v-text-field>
                </v-flex>
            </v-layout>
        </v-container>

        <v-btn color="primary" @click="create">Crear</v-btn>
    </form>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import withSnackbar from '../../mixins/withSnackbar'
  import { required, email } from 'vuelidate/lib/validators'
  import axios from 'axios'

  export default {
    name: 'GoogleGroupAddFormComponent',
    mixins: [validationMixin, withSnackbar],
    validations: {
      name: { required },
      email: { required, email }
    },
    data () {
      return {
        name: '',
        email: '',
        description: ''
      }
    },
    computed: {
      clear () {
        this.name = ''
        this.email = ''
        this.description = ''
      },
      nameErrors () {
        const nameErrors = []
        if (!this.$v.name.$dirty) return nameErrors
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
      create () {
        console.log('CREATE TODO')
        this.$emit('created', {})
      }
    }
  }
</script>
