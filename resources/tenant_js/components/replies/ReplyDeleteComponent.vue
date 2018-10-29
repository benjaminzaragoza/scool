<template>
    <v-btn :id="'reply_' + reply.id + '_delete_button'" flat icon color="error" :disabled="deleting" :loading="deleting" @click="remove">
        <v-icon>delete</v-icon>
    </v-btn>
</template>

<script>
export default {
  name: 'ReplyDeleteComponent',
  data () {
    return {
      deleting: false
    }
  },
  props: {
    repliable: {
      type: Object,
      required: true
    },
    reply: {
      type: Object,
      required: true
    }
  },
  methods: {
    uri () {
      return '/api/v1/' + this.repliable.api_uri + '/' + this.repliable.id + '/replies/' + this.reply.id
    },
    async remove () {
      let res = await this.$confirm('Aquest canvi no es pot desfer.', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.deleting = true
        window.axios.delete(this.uri()).then((response) => {
          this.$emit('deleted', response.data)
          this.deleting = false
        }).catch((error) => {
          this.$snackbar.showError(error)
          this.deleting = false
        })
      }
    }
  }
}
</script>
