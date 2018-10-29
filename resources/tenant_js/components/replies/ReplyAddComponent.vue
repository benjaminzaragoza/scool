<template>
    <form>
        <v-textarea
                outline
                label="Afegiu un nou comentari"
                v-model="newReply"
                rows="4"
                name="body"
                class="mb-0"
                @keyup.ctrl.enter="add">
        ></v-textarea>
        <v-btn id="add_reply_button" :disabled="adding" :loading="adding" block color="teal" dark class="mt-0" @click="add">Afegir</v-btn>
    </form>
</template>

<script>
export default {
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
  methods: {
    uri () {
      return '/api/v1/' + this.repliable.api_uri + '/' + this.repliable.id + '/replies'
    },
    add () {
      this.adding = true
      window.axios.post(this.uri(), {
        body: this.newReply
      }).then((response) => {
        this.$emit('added', response.data)
        this.adding = false
        this.newReply = ''
      }).catch((error) => {
        this.$snackbar.showError(error)
        this.adding = false
      })
    }
  }
}
</script>
