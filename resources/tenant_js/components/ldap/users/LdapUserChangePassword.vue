<template>
    <span>
        <v-dialog v-if="dialog" v-model="dialog"
                  :fullscreen="$vuetify.breakpoint.smAndDown" :hide-overlay="$vuetify.breakpoint.smAndDown"
                  transition="dialog-bottom-transition"
                  @keydown.esc.stop.prevent="close">
            <v-toolbar color="primary">
                <v-btn icon dark @click.native="close">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title">Canviar paraula de pas usuari Ldap</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <v-tooltip bottom>
                                        <v-avatar v-if="user.jpegphoto" slot="activator"
                                                  size="45"
                                                  color="grey lighten-4"
                                        >
                                            <img  :src="user.jpegphoto" :alt="user.cn"/>
                                        </v-avatar>
                                        <span>{{ user.dn }}</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <span slot="activator">{{ user.cn }}</span>
                                        <span>{{ user.dn }}</span>
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
                                                :disabled="loading || invalid">Canviar paraula de pas a Ldap</v-btn>
                                        <v-btn @click="close()"
                                               id="close_button"
                                               color="error"
                                               class="white--text"
                                        >Tancar</v-btn>
                                     </form>
                                    <v-alert
                                            :value="true"
                                            type="warning"
                                            dismissible
                                    >
                                        Aquesta operació NOMÉS canviarà la paraula de pas a Ldap. Per canviar la resta de paraules de pas (usuari local, Google, Ldap...) cal anar a:
                                        <span v-if="user.employeenumber">
                                            <a target="_blank" :href="'/users/password/' + user.employeenumber">Mòdul de gestió d'usuaris</a>
                                        </span>
                                        <span v-else>
                                            <a target="_blank" href="/users">Mòdul de gestió d'usuaris</a>.
                                        Aquest usuari Ldap no té usuari local associat.
                                        </span>
                                  </v-alert>
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
import { required, minLength } from 'vuelidate/lib/validators'

export default {
  name: 'LdapUserChangePassword',
  validations: {
    password: { required, minLength: minLength(6) }
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
      !this.$v.password.minLength && passwordErrors.push('El password ha de tenir com a mínim 6 caràcters.')
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
      window.axios.put('/api/v1/ldap/users/' + this.user.uid + '/password', {
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
