<template>
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
           id="add_incident_button"
           color="teal"
           class="white--text"
           :loading="adding"
           :disabled="adding"
        >Afegir</v-btn>
        <v-btn @click="add(true)"
           id="add_and_close_incident_button"
           color="primary"
           class="white--text"
           :loading="adding"
           :disabled="adding"
        >Afegir i tancar</v-btn>
    </form>
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
        // eslint-disable-next-line no-undef
        axios.post('/api/v1/incidents', {
          subject: this.subject,
          description: this.description
        }).then(response => {
          this.$emit('added', response.data)
          this.showMessage('Incidència afegida correctament')
        }).catch(error => {
          console.log(error)
        })
      }
    }
  }
</script>
