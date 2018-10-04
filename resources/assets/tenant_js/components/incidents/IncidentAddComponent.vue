<template>
    <span>
        <v-btn
                fab
                bottom
                right
                color="pink"
                dark
                fixed
                @click.stop="dialog = !dialog"
        >
            <v-icon>add</v-icon>
        </v-btn>
        <v-dialog v-model="dialog" v-if="dialog" fullscreen @keydown.esc="dialog = false">
            <v-toolbar color="blue darken-3">
                <v-btn icon dark @click.native="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text title">Informar nova incidència</v-toolbar-title>
            </v-toolbar>
            <v-card>
                    <v-card-text class="px-0 mb-2">
                        <v-container fluid grid-list-md text-xs-center>
                            <v-layout row wrap>
                                <v-flex xs12>
                                    <form>
                                        <v-container fluid grid-list-md text-xs-center>
                                            <v-layout row wrap>
                                                <v-flex md12>
                                                    <v-text-field
                                                            v-model="subject"
                                                            name="subject"
                                                            label="Títol"
                                                            :error-messages="subjectErrors"
                                                            @input="$v.subject.$touch()"
                                                            @blur="$v.subject.$touch()"
                                                            hint="El títol ha d'incloure la informació bàsica per entendre el problema o incidència que teniu"
                                                            autofocus
                                                    ></v-text-field>
                                                </v-flex>
                                                <v-flex md12>
                                                    <v-textarea
                                                            v-model="description"
                                                            name="description"
                                                            label="Descripció"
                                                            hint="Descripció de la incidència. Mireu de ser el més concisos possibles evitant descripcions genèriques tipus no funciona. "
                                                            :error-messages="descriptionErrors"
                                                            @input="$v.description.$touch()"
                                                            @blur="$v.description.$touch()"
                                                    ></v-textarea>
                                                </v-flex>
                                            </v-layout>
                                        </v-container>
                                        <v-btn @click="add(false)"
                                               color="teal"
                                               class="white--text"
                                               :loading="adding"
                                               :disabled="adding"
                                        >Afegir</v-btn>
                                        <v-btn @click="add(true)"
                                               color="primary"
                                               class="white--text"
                                               :loading="adding"
                                               :disabled="adding"
                                        >Afegir i tancar</v-btn>
                                    </form>
                                </v-flex>
                            </v-layout>
                        </v-container>
                    </v-card-text>
                </v-card>
        </v-dialog>
    </span>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { required } from 'vuelidate/lib/validators'
  import withSnackbar from '../mixins/withSnackbar'

  export default {
    mixins: [validationMixin, withSnackbar],
    validations: {
      subject: {required},
      description: {required}
    },
    data () {
      return {
        dialog: false,
        subject: '',
        description: '',
        adding: false
      }
    },
    computed: {
      subjectErrors () {
        const errors = []
        if (!this.$v.subject.$dirty) return errors
        !this.$v.subject.required && errors.push('És obligatori indicar un títol.')
        return errors
      },
      descriptionErrors () {
        const errors = []
        if (!this.$v.description.$dirty) return errors
        !this.$v.description.required && errors.push('És obligatori indicar una descripció.')
        return errors
      }
    },
    methods: {
      add () {
        console.log('TODO ADD')
      }
    }
  }
</script>
