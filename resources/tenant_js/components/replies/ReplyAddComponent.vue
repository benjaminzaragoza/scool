<template>
    <form>
        <v-tabs :v-model="1" slider-color="teal">
            <v-tab :key="1" ripple> Text </v-tab>
            <v-tab :key="2" ripple> Previsualitació</v-tab>
            <v-tab-item :key="1">
                <v-card flat>
                    <v-card-text>
                        <v-textarea
                                outline
                                label="Afegiu un nou comentari"
                                v-model="newReply"
                                rows="4"
                                name="body"
                                class="mb-0"
                                @keyup.ctrl.enter="add"
                                :error-messages="bodyErrors"
                                required
                                @input="$v.newReply.$touch()"
                                @blur="$v.newReply.$touch()">
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
        <span v-if="$hasRole('IncidentsManager')">
            <v-btn id="add_reply_button" :disabled="adding" :loading="adding" color="primary" dark class="mt-0" @click="add">Afegir</v-btn>
            <v-btn id="add_reply_button" :disabled="addingAndClosing" :loading="addingAndClosing" color="teal" dark class="mt-0" @click="addAndClose()">Afegir i tancar</v-btn>
        </span>
        <span v-else>
            <v-btn id="add_reply_button" :disabled="adding" :loading="adding" block color="teal" dark class="mt-0" @click="add">Afegir</v-btn>
        </span>
    </form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import marked from 'marked'
import * as actions from '../../store/action-types'

export default {
  mixins: [validationMixin],
  validations: {
    newReply: { required }
  },
  name: 'ReplyAddComponent',
  data () {
    return {
      newReply: '',
      adding: false,
      addingAndClosing: false
    }
  },
  props: {
    repliable: {
      type: Object,
      required: true
    }
  },
  computed: {
    bodyErrors () {
      const errors = []
      if (!this.$v.newReply.$dirty) return errors
      !this.$v.newReply.required && errors.push('Cal indicar un text per afegir un comentari')
      return errors
    },
    compiledMarkdown: function () {
      return marked(this.newReply, { sanitize: true })
    }
  },
  methods: {
    uri () {
      return '/api/v1/' + this.repliable.api_uri + '/' + this.repliable.id + '/replies'
    },
    add () {
      this.$v.newReply.$touch()
      if (this.$v.$invalid) return
      this.adding = true
      window.axios.post(this.uri(), {
        body: this.newReply
      }).then((response) => {
        this.$emit('added', response.data)
        this.adding = false
        this.newReply = ''
        this.$nextTick(() => { this.$v.$reset() })
      }).catch((error) => {
        this.$snackbar.showError(error)
        this.adding = false
      })
    },
    addAndClose () {
      this.$v.newReply.$touch()
      if (this.$v.$invalid) return
      this.addingAndClosing = true
      window.axios.post(this.uri(), {
        body: this.newReply
      }).then((response) => {
        this.$emit('added', response.data)
        this.newReply = ''
        this.$nextTick(() => { this.$v.$reset() })
        this.$store.dispatch(actions.CLOSE_INCIDENT, this.repliable).then(response => {
          this.addingAndClosing = false
          this.$emit('close')
        }).catch(error => {
          this.$snackbar.showError(error)
          this.addingAndClosing = false
        })
      }).catch((error) => {
        this.$snackbar.showError(error)
        this.addingAndClosing = false
      })
    }
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
