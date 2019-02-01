<template>
    <v-card>
        <v-card-text>
            <v-switch
                    label="Autogenerar la paraula de pas"
                    v-model="autogenerate"
            ></v-switch>
            <v-text-field v-if="!autogenerate"
                    v-model="password"
                    :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    label="Paraula de pas"
                    hint="Com a mínim 6 caràcters"
                    counter
                    @click:append="showPassword = !showPassword"
            ></v-text-field>
            <v-switch
                    label="Forçar un canvi de paraula de pas al pròxim login de l'usuari"
                    v-model="force"
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
                    :disabled="loading"
                    @click="change"
            >
                Canviar
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    // TODO
import passwordGenerator from 'generate-password'

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
  data () {
    return {
      loading: false,
      autogenerate: true,
      password: '',
      showPassword: false,
      force: false,
      syncGoogle: true,
      syncMoodle: true,
      syncLdap: true
    }
  },
  methods: {
    change () {
      console.log('TODO')
    }
  }
}
</script>
