<template>
    <form>
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
        <v-btn id="add_reply_button" :disabled="adding" :loading="adding" block color="teal" dark class="mt-0" @click="add">Afegir</v-btn>
    </form>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required } from 'vuelidate/lib/validators'

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
    }
  },
  methods: {
    uri () {
      return '/api/v1/' + this.repliable.api_uri + '/' + this.repliable.id + '/replies'
    },
    add () {
      this.$v.newReply.$touch()
      console.log('INVALID:')
      console.log(this.$v.$invalid)
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
