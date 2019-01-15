<template>
    <v-btn color="primary" flat title="Enviar email" icon @click="send" :loading="loading" :disabled="loading || position.users.length === 0">
        <v-icon>email</v-icon>
    </v-btn>
</template>

<script>
export default {
  name: 'PositionSendEmail',
  data () {
    return {
      loading: false
    }
  },
  props: {
    position: {
      type: Object,
      required: true
    }
  },
  methods: {
    async send () {
      let res = await this.$confirm("Segur que voleu tornar a enviar l'email al usuari/usuaris?", { title: 'Esteu segurs?', buttonTrueText: 'Enviar' })
      if (res) {
        this.loading = true
        window.axios.get('/api/v1/positions/' + this.position.id + '/email/send').then((response) => {
          this.$snackbar.showMessage('Email enviat correctament')
          this.loading = false
        }).catch((error) => {
          this.$snackbar.showError(error)
          this.loading = false
        })
      }
    }
  }
}
</script>
