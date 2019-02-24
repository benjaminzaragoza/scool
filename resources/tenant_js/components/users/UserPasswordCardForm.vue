<template>
    <v-card>
        <v-card-text>
            <v-switch
                    label="Autogenerar la paraula de pas"
                    v-model="autogenerate"
            ></v-switch>
            <span style="display: flex;">
                <v-tooltip bottom>
                    <v-btn icon slot="activator" @click="generatePassword">
                        <v-icon>refresh</v-icon>
                    </v-btn>
                    <span>Regenerar la paraula de pas</span>
                </v-tooltip>
                <v-text-field
                        v-model="password"
                        :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                        :type="showPassword || autogenerate ? 'text' : 'password'"
                        name="password"
                        label="Paraula de pas"
                        hint="Com a mínim 6 caràcters"
                        counter
                        :error-messages="passwordErrors"
                        @input="$v.password.$touch()"
                        @blur="$v.password.$touch()"
                        :disabled="autogenerate"
                        @click:append="showPassword = !showPassword"
                ></v-text-field>
            </span>

            <v-switch
                    label="Forçar un canvi de paraula de pas al pròxim login de l'usuari"
                    v-model="force"
            ></v-switch>
            <v-switch
                    label="Enviar email"
                    v-model="email"
            ></v-switch>

            <v-switch
                    label="Canviar també paraula de pas de Moodle"
                    v-model="syncMoodle"
            ></v-switch>
            <v-switch
                    label="Canviar també paraula de pas de Ldap"
                    v-model="syncLdap"
            ></v-switch>
            <v-switch
                    label="Canviar també paraula de pas de Google"
                    v-model="syncGoogle"
            ></v-switch>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                    class="grey--text"
                    flat
                    @click="$emit('close')"
            >
                Cancel·lar
            </v-btn>
            <v-btn
                    color="primary"
                    :loading="loading"
                    :disabled="loading || invalid"
                    @click="change"
            >
                Canviar
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, minLength } from 'vuelidate/lib/validators'

// TODO
import generator from 'generate-password'

// var generator = require('generate-password');
//
// var password = generator.generate({
//   length: 10,
//   numbers: true
// });
//
// // 'uEyMTw32v9'
// console.log(password);

export default {
  name: 'UserPasswordCardForm',
  mixins: [validationMixin],
  validations: {
    password: { required, minLength: minLength(6) }
  },
  data () {
    return {
      loading: false,
      autogenerate: true,
      password: '',
      showPassword: false,
      force: false,
      email: true,
      syncGoogle: true,
      syncMoodle: true,
      syncLdap: true
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
    change () {
      console.log('TODO')
    },
    generatePassword () {
      this.password = generator.generate({ length: 10, numbers: true })
    }
  },
  created () {
    this.generatePassword()
  }
}
</script>
