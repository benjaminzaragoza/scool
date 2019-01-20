<template>
    <v-card class="elevation-3">
        <v-card-text>
            <user-avatar :hash-id="user.hashid"
                         :alt="user.name"
                         :user="user"
            ></user-avatar>
            <span class="font-weight-medium">{{ user.name }}</span>:

            <v-container fluid grid-list-md text-xs-center>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-text-field
                                label="Email"
                                v-model="email"
                                :error-messages="emailErrors"
                                @input="$v.email.$touch()"
                                @blur="emailBlur()"
                                required
                        ></v-text-field>
                    </v-flex>
                </v-layout>
            </v-container>

        </v-card-text>
        <v-card-actions>
            <v-btn color="primary" @click="save" :disabled="loading || $v.$invalid" :loading="loading">
                <v-icon>save</v-icon> Guardar</v-btn>
            <v-btn flat @click="close">
                <v-icon small>close</v-icon> Sortir</v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import UserAvatar from '../ui/UserAvatarComponent'
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'

export default {
  name: 'UserMailManage',
  mixins: [validationMixin],
  components: {
    'user-avatar': UserAvatar
  },
  validations: {
    email: { required, email }
  },
  data () {
    return {
      loading: false,
      email: this.user.email,
      errors: []
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  computed: {
    emailErrors () {
      const emailErrors = []
      if (!this.$v.email.$dirty) return emailErrors
      !this.$v.email.required && emailErrors.push('El email és obligatori.')
      !this.$v.email.email && emailErrors.push('El camp email ha de ser un correu electrònic vàlid.')
      this.errors['email'] && emailErrors.push(this.errors['email'])
      return emailErrors
    }
  },
  methods: {
    close () {
      this.$emit('close')
    },
    save () {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.loading = true
        window.axios.put('/api/v1/users/' + this.user.id + '/email', {
          email: this.email
        }).then(response => {
          this.loading = false
          this.$v.$reset()
          this.$emit('saved', this.user)
        }).catch(error => {
          if (error && error.status === 422) {
            this.errors = error.data && error.data.errors
            this.loading = false
            this.$snackbar.showError(error)
          }
        })
      }
    }

  }
}
</script>
