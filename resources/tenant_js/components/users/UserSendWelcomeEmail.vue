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
        <span>Enviar email de benvinguda</span>
    </v-tooltip>
</template>

<script>
import api from './api/emails/user_emails'

export default {
  name: 'UserSendWelcomeEmail',
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
      let res = await this.$confirm("Voleu tornar a enviar l'email de benvinguda a l'usuari?", { title: 'Esteu segurs?', buttonTrueText: 'Enviar' })
      if (res) {
        this.loading = true
        api.sendWelcomeEmail(this.user).then(response => {
          this.$snackbar.showMessage('Email enviat correctament')
          this.loading = false
        }).catch(error => {
          this.$snackbar.showError(error)
          this.loading = false
        })
      }
    }
  }
}
</script>
