<template>
    <v-menu offset-y>
            <v-btn  title="Enviar emails"
                    slot="activator"
                    icon
                    style="margin: 0px"
                    :loading="loading"
                    :disabled="loading">
                <v-icon color="primary">email</v-icon>
            </v-btn>
        <v-list>
            <v-list-tile @click="send('sendWelcomeEmail')" >
                <v-list-tile-title>Benvinguda</v-list-tile-title>
            </v-list-tile>
            <v-list-tile @click="send('sendResetPasswordEmail')" >
                <v-list-tile-title>Restauració paraula de pas</v-list-tile-title>
            </v-list-tile>
            <v-list-tile @click="send('sendConfirmationEmail')" >
                <v-list-tile-title>Confirmació email personal</v-list-tile-title>
            </v-list-tile>
        </v-list>
    </v-menu>
</template>

<script>
import api from './api/emails/user_emails'
export default {
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
    send (method) {
      this.loading = true
      api[method](this.user).then(() => {
        this.loading = false
        this.$snackbar.showMessage('Missatge enviat correctament')
      }).catch(error => {
        this.loading = false
        this.$snackbar.showError(error)
      })
    }
  }
}
</script>
