<template>
    <span>
        <v-dialog v-if="dialog" v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition"
          @keydown.esc.stop.prevent="close">
            <v-toolbar color="blue darken-3">
                <v-btn icon dark @click.native="close">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title">Canviar paraula de pas usuari Moodle</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                     <form>
                                        <v-checkbox
                                                label="Crear password de Moodle i enviar per correu electrònic"
                                                v-model="createpassword"
                                        ></v-checkbox>
                                        <v-text-field v-if="!createpassword"
                                                      v-model="password"
                                                      type="password"
                                                      name="password"
                                                      label="Paraula de pas"
                                        ></v-text-field>
                                         <v-btn @click="loading"
                                                id="add_moodle_user_button"
                                                color="primary"
                                                class="white--text"
                                                :loading="loading"
                                                :disabled="loading || invalid">Crear nou password i enviar</v-btn>
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
        <v-btn icon title="Canviar paraula de pas" flat color="teal" @click="dialog=true">
            <v-icon>vpn_key</v-icon>
        </v-btn>
    </span>
</template>

<script>
import FullScreenDialog from '../../ui/FullScreenDialog'

export default {
  name: 'MoodleUserChangePassword',
  components: {
    'fullscreen-dialog': FullScreenDialog
  },
  data () {
    return {
      loading: false,
      dialog: false,
      createpassword: true,
      password: ''
    }
  },
  methods: {
    close () {
      this.dialog = false
      this.$emit('close')
    }
  }
}
</script>
