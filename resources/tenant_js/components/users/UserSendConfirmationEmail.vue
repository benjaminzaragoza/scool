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
        <span>Enviar email confirmació correu electrònic personal</span>
    </v-tooltip>
</template>

<script>
import * as actions from '../../store/action-types'

export default {
  name: 'UserSendConfirmationEmail',
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
      let res = await this.$confirm("Voleu enviar email per confirmar correu electrònic personal de l'usuari?", { title: 'Esteu segurs?', buttonTrueText: 'Enviar' })
      if (res) {
        this.sendEmailConfirmation(this.user)
      }
    },
    sendEmailConfirmation (user) {
      this.loading = true
      this.$store.dispatch(actions.CONFIRM_USER_EMAIL, user).then(response => {
        this.$snackbar.showMessage('Correu electrònic enviat correctament')
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      }).then(() => {
        this.loading = false
      })
    }
  }
}
</script>
