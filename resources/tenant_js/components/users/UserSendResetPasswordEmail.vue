<template>
    <v-tooltip bottom>
        <v-btn  slot="activator"
                icon
                style="margin: 0px"
                :loading="loading"
                :disabled="loading"
                @click="send">
            <v-icon color="primary">email</v-icon>
        </v-btn>
        <span>Enviar email restauració paraula de pas</span>
    </v-tooltip>
</template>

<script>
import api from './api/emails/user_emails'

export default {
  name: 'UserSendResetPasswordEmail',
  data () {
    return {
      loading: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  methods: {
    async send () {
      let res = await this.$confirm('Voleu enviar email per canviar paraula de pas?', { title: 'Esteu segurs?', buttonTrueText: 'Enviar' })
      if (res) {
        this.sendResetPasswordEmail()
      }
    },
    sendResetPasswordEmail () {
      this.loading = true
      api.sendResetPasswordEmail(this.user).then(response => {
        this.loading = false
        this.showMessage()
        this.$snackbar.showMessage(`Correu electrònic enviat correctament`)
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    }
  }
}
</script>
