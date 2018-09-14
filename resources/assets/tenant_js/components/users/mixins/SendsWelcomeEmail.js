import * as actions from '../../../store/action-types'

export default {
  data () {
    return {
      sendingWelcomeEmail: false
    }
  },
  methods: {
    sendWelcomeEmail (user) {
      this.sendingWelcomeEmail = true
      this.$store.dispatch(actions.WELCOME_EMAIL, user).then(response => {
        this.showMessage(`Correu electrònic enviat correctament`)
      }).catch(error => {
        console.dir(error)
        this.showError(error)
      }).then(() => {
        this.sendingWelcomeEmail = false
      })
    }
  }
}
