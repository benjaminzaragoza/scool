<template>
    <form>
        <v-container fluid grid-list-md text-xs-center>
            <v-layout row wrap>
            <v-flex xs12>
                <v-text-field
                        ref="subject_field"
                        v-focus
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
            <v-flex xs12>
                <v-tabs :v-model="1" slider-color="secondary">
                    <v-tab :key="1" ripple> Descripció </v-tab>
                    <v-tab :key="2" ripple> Previsualitació</v-tab>
                    <v-tab-item :key="1">
                    <v-card flat>
                        <v-card-text>
                            <v-textarea
                                    v-model="description"
                                    name="description"
                                    hint="Descripció de la incidència. Mireu de ser el més concisos possibles evitant descripcions genèriques tipus no funciona. "
                                    :error-messages="descriptionErrors"
                                    @input="$v.description.$touch()"
                                    @blur="$v.description.$touch()"
                            ></v-textarea>
                            <a class="link" href="https://guides.github.com/features/mastering-markdown/" target="_blank">
                                <svg viewBox="0 0 16 16" version="1.1" width="16" height="16" aria-hidden="true"><path fill-rule="evenodd" d="M14.85 3H1.15C.52 3 0 3.52 0 4.15v7.69C0 12.48.52 13 1.15 13h13.69c.64 0 1.15-.52 1.15-1.15v-7.7C16 3.52 15.48 3 14.85 3zM9 11H7V8L5.5 9.92 4 8v3H2V5h2l1.5 2L7 5h2v6zm2.99.5L9.5 8H11V5h2v3h1.5l-2.51 3.5z"></path></svg>
                                &nbsp;Podeu utilitzar el format wiki Markdown
                            </a>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                    <v-tab-item :key="2" v-html="compiledMarkdown" style="text-align: left!important;" class="mt-3"></v-tab-item>
                </v-tabs>
            </v-flex>
        </v-layout>
        </v-container>
        <template v-if="$hasRole('IncidentsManager')">
            <v-btn @click="add(false)"
                   id="add_incident_button"
                   color="secondary"
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
        </template>
        <template v-else>
            <v-btn @click="add(true)"
                   id="add_and_close_incident_button"
                   color="primary"
                   class="white--text"
                   :loading="adding"
                   :disabled="adding"
            >Afegir</v-btn>
        </template>
        <v-btn @click="close()"
               id="close_button"
               color="error"
               class="white--text"
        >Tancar</v-btn>
    </form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import * as actions from '../../store/action-types'
import marked from 'marked'

export default {
  name: 'IncidentAdd',
  mixins: [validationMixin],
  validations: {
    subject: { required },
    description: { required }
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
    },
    compiledMarkdown: function () {
      return marked(this.description, { sanitize: true })
    }
  },
  methods: {
    close () {
      this.$emit('close')
    },
    add (close = false) {
      if (!this.$v.$invalid) {
        this.adding = true
        this.$store.dispatch(actions.ADD_INCIDENT, {
          subject: this.subject,
          description: this.description
        }).then(response => {
          this.$snackbar.showMessage('Incidència creada correctament')
          this.adding = false
          this.$emit('added', response.data)
          if (close) this.close()
        }).catch(error => {
          this.$snackbar.showError(error)
          this.adding = false
        })
      }
    }
  },
  mounted () {
    this.$nextTick(this.$refs.subject_field.focus)
  }
}
</script>

<style scoped>
    .link {
      text-decoration: none;
      color: inherit;
      display: inline-flex;
      align-self: center;
    }
    .link:hover {
        text-decoration: none;
        color: #1976d2;
    }
    .link svg {
        fill: currentColor;
        height: 1em;
        width: 1em;
        top: .125em;
        position: relative;
    }
</style>
