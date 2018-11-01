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
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item :key="2" v-html="compiledMarkdown" style="text-align: left!important;" class="mt-3"></v-tab-item>
        </v-tabs>
        <v-btn id="add_reply_button" :disabled="adding" :loading="adding" block color="teal" dark class="mt-0" @click="add">Afegir</v-btn>
    </form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'
import marked from 'marked'

export default {
  mixins: [validationMixin],
  validations: {
    newReply: { required }
  },
  name: 'ReplyAddComponent',
  data () {
    return {
      newReply: '',
      adding: false
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
    }
  }
}
</script>
