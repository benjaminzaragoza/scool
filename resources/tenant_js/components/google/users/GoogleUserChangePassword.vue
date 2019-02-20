<template>
    <span>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition"
                  @keydown.esc.stop.prevent="close">
            <v-toolbar color="primary">
                <v-btn icon dark @click.native="close">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title">Canviar paraula de pas usuari Google</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                     Usuari de Google:
                                    <v-tooltip bottom>
                                        <v-avatar size="32" slot="activator">
                                                    <img v-if="user.thumbnailPhotoUrl" :src="user.thumbnailPhotoUrl">
                                                    <img v-else src="/img/default.png" alt="photo per defecte">
                                                </v-avatar>
                                        <span>{{ user.id }}</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <a slot="activator" target="_blank" :href="'https://admin.google.com/u/3/ac/users/' + user.id">{{ user.primaryEmail }}</a>
                                        <span>{{ user.primaryEmail }}</span>
                                    </v-tooltip>
                                     <form>
                                        <v-text-field
                                                v-model="password"
                                                :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                                                :type="showPassword ? 'text' : 'password'"
                                                name="password"
                                                label="Paraula de pas"
                                                :error-messages="passwordErrors"
                                                @input="$v.password.$touch()"
                                                @blur="$v.password.$touch()"
                                                @click:append="showPassword = !showPassword"
                                        ></v-text-field>
                                         <v-btn @click="changePassword"
                                                id="change_google_user_password_button"
                                                color="primary"
                                                class="white--text"
                                                :loading="loading"
                                                :disabled="loading || invalid">Canviar paraula de pas a Google</v-btn>
                                        <v-btn @click="close()"
                                               id="close_button"
                                               color="error"
                                               class="white--text"
                                        >Tancar</v-btn>
                                     </form>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
        <v-btn icon title="Canviar paraula de pas" flat color="teal" @click="dialog=true" class="ma-0">
            <v-icon>vpn_key</v-icon>
        </v-btn>
    </span>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

export default {
  name: 'GoogleUserChangePassword',
  validations: {
    password: { required }
  },
  mixins: [validationMixin],
  data () {
    return {
      loading: false,
      dialog: false,
      password: '',
      showPassword: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  computed: {
    invalid () {
      if (!this.password) return true
      return false
    },
    passwordErrors () {
      const passwordErrors = []
      if (!this.$v.password.$dirty) return passwordErrors
      !this.$v.password.required && passwordErrors.push('La paraula de pas és obligatòria.')
      return passwordErrors
    }
  },
  methods: {
    close () {
      this.dialog = false
      this.$emit('close')
    },
    changePassword () {
      this.loading = true
      window.axios.put('/api/v1/gsuite/users/' + this.user.primaryEmail + '/password', {
        'password': this.password
      }).then(() => {
        this.$snackbar.showMessage('Paraula de pas canviada correctament')
        this.loading = false
        this.close()
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    }
  }
}
</script>
