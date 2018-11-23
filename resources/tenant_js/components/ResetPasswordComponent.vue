<template>
    <v-dialog v-model="showResetPassword" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline">Canviar la paraula de pas</span>
            </v-card-title>
            <v-card-text>
                <v-form v-model="valid" ref="resetPasswordForm">
                    <v-text-field
                            label="Correu electrònic"
                            v-model="internalEmail"
                            :rules="emailRules"
                            required
                    ></v-text-field>
                    <v-text-field
                            name="password"
                            label="Paraula de pas"
                            v-model="password"
                            :rules="passwordRules"
                            hint="At least 6 characters"
                            min="6"
                            type="password"
                            required
                    ></v-text-field>
                    <v-text-field
                            name="passwordConfirmation"
                            label="Confirmació de la paraula de pas"
                            v-model="passwordConfirmation"
                            :rules="passwordRules"
                            hint="At least 6 characters"
                            min="6"
                            type="password"
                            required
                    ></v-text-field>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click.native="showResetPassword = false">Tancar</v-btn>
                <v-btn
                        :loading="loading"
                        flat
                        :color="done ? 'green' : 'blue'"
                        @click.native="reset"
                >
                    <v-icon v-if="done">done</v-icon>
                    &nbsp;
                    <template v-if="!done">Canviar</template>
                    <template v-else>Fet</template>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import * as actions from '../store/action-types'
import sleep from '../utils/sleep'
import withSnackbar from './mixins/withSnackbar'
export default {
  mixins: [withSnackbar],
  data () {
    return {
      errors: [],
      internalAction: this.action,
      internalEmail: this.email,
      loading: false,
      done: false,
      emailRules: [
        (v) => !!v || 'El email és obligatori',
        (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'S\'ha d\'indicar un email vàlid',
        (v) => {
          if (this.errors && this.errors.email) {
            let result = this.errors.email[0]
            this.errors = []
            return result
          }
          return true
        }
      ],
      password: '',
      passwordConfirmation: '',
      passwordRules: [
        (v) => !!v || 'La paraula de pas és obligatòria',
        (v) => v.length >= 6 || 'La paraula de pas ha de tenir com a mínim 6 caràcters',
        (v) => {
          if (this.errors && this.errors.password) {
            let result = this.errors.password[0]
            this.errors = []
            return result
          }
          return true
        }
      ],
      valid: false
    }
  },
  props: {
    action: {
      type: String,
      default: null
    },
    token: {
      type: String,
      default: null
    },
    email: {
      type: String,
      default: null
    }
  },
  computed: {
    showResetPassword: {
      get () {
        return this.internalAction && (this.internalAction === 'reset_password')
      },
      set (value) {
        if (value) this.internalAction = 'reset_password'
        else this.internalAction = null
      }
    }
  },
  methods: {
    formatResultErrors (data) {
      if (data.errors) this.errors = data.errors
      else if (data.email) this.errors = { email: [data.email] }
    },
    reset () {
      if (this.$refs.resetPasswordForm.validate()) {
        const user = {
          'email': this.internalEmail,
          'password': this.password,
          'password_confirmation': this.passwordConfirmation,
          'token': this.token
        }
        this.loading = true
        this.$store.dispatch(actions.RESET_PASSWORD, user).then(response => {
          this.loading = false
          this.done = true
          sleep(4000).then(() => {
            this.showResetPassword = false
            window.location = '/home'
          })
        }).catch(error => {
          this.loading = false
          if (error.status === 422) {
            this.showError('Les dades no són correctes')
            console.log(error.data.errors)
          } else {
            this.showError(error)
          }
          this.formatResultErrors(error.data)
          this.$refs.resetPasswordForm.validate()
        }).then(() => {
          this.loading = false
        })
      }
    }
  }
}
</script>
