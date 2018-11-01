<template>
    <v-container fluid grid-list-md text-xs-center>
        <v-layout row wrap>
            <v-flex xs12>
                <v-card>
                    <v-card-title class="blue darken-3 white--text"><h2>Afegeix un usuari</h2></v-card-title>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <form>
                                        <v-text-field
                                                label="Nom"
                                                v-model="name"
                                                :error-messages="nameErrors"
                                                :counter="255"
                                                @input="$v.name.$touch()"
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
                                        <v-text-field
                                                label="Paraula de pas (no és obligatoria)"
                                                v-model="password"
                                                type="password"
                                        ></v-text-field>
                                        <v-select
                                                @change="selectedUserType"
                                                :items="userTypes"
                                                v-model="userType"
                                                item-text="name"
                                                label="Tipus usuari"
                                                :clearable="true"
                                                single-line
                                        ></v-select>

                                        <v-select
                                                :items="roles"
                                                v-model="role"
                                                item-text="name"
                                                label="Rol"
                                                :clearable="true"
                                                single-line
                                                multiple
                                        ></v-select>

                                        <v-btn
                                                @click="createAndInvite"
                                                :disabled="$v.$invalid"
                                                :loading="creatingAndInviting">Crear i invitar</v-btn>
                                        <v-btn @click="create"
                                               :disabled="$v.$invalid"
                                               :loading="creating">Crear</v-btn>
                                        <v-btn @click="clear">Netejar</v-btn>
                                    </form>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, maxLength, email } from 'vuelidate/lib/validators'
import * as actions from '../../store/action-types'
import withSnackbar from '../mixins/withSnackbar'

export default {
  mixins: [validationMixin, withSnackbar],
  validations: {
    name: { required, maxLength: maxLength(255) },
    email: { required, email }
  },
  data () {
    return {
      name: '',
      email: '',
      password: '',
      userType: null,
      role: null,
      creating: false,
      creatingAndInviting: false,
      errors: []
    }
  },
  props: {
    roles: {
      type: Array,
      required: true
    }
  },
  computed: {
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
    selectedUserType (userType) {
      this.role = userType.roles
    },
    create () {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.creating = true

        this.$store.dispatch(actions.STORE_USER, {
          name: this.name,
          email: this.email,
          password: this.password,
          type: this.userType && this.userType.name,
          roles: this.role
        }).then(response => {
          this.creating = false
          this.clear()
          this.$v.$reset()
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
    },
    createAndInvite () {
      // TODO
    },
    clear () {
      this.$v.$reset()
      this.name = ''
      this.email = ''
      this.password = ''
      this.userType = null
      this.role = []
    }
  },
  created () {
    this.userTypes = [
      { name: 'Professor/a', roles: ['Professor'] },
      { name: 'Alumne/a', roles: ['Professor'] },
      { name: 'Personal de consergeria', roles: ['Conserge'] },
      { name: "Personal d'administració", roles: ['Administratiu'] },
      { name: 'Familiar', roles: ['Familiar'] }
    ]
  }
}
</script>
