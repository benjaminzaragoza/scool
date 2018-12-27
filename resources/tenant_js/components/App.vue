<script>
import * as actions from '../store/action-types'
import * as mutations from '../store/mutation-types'
import withSnackbar from '../components/mixins/withSnackbar'
import store from '../store'
import { mapGetters } from 'vuex'

export default {
  el: '#app',
  store,
  mixins: [ withSnackbar ],
  data: () => ({
    drawer: null,
    drawerRight: false,
    editingUser: false,
    changingPassword: false,
    confirmingEmail: false,
    updatingUser: false,
    items: window.scool_menu
  }),
  computed: {
    ...mapGetters({
      user: 'user'
    })
  },
  methods: {
    editUser () {
      this.editingUser = true
      this.$nextTick(this.$refs.email.focus)
    },
    updateUser () {
      this.updatingUser = true
      this.$store.dispatch(actions.UPDATE_USER, this.user).then(() => {
        this.showMessage('User modified ok!')
      }).catch(error => {
        console.dir(error)
        this.showError(error)
      }).then(() => {
        this.editingUser = false
        this.updatingUser = false
      })
    },
    updateEmail (email) {
      this.$store.commit(mutations.USER, { ...this.user, email })
    },
    updateName (name) {
      this.$store.commit(mutations.USER, { ...this.user, name })
    },
    toogleRightDrawer () {
      this.drawerRight = !this.drawerRight
    },
    checkRoles (item) {
      if (item.role) {
        return this.$store.getters.userRoles.find(function (role) {
            return role == item.role // eslint-disable-line
        })
      }
      return true
    },
    changePassword () {
      this.changingPassword = true
      this.$store.dispatch(actions.REMEMBER_PASSWORD, this.user.email).then(() => {
        this.showMessage(`Correu electrònic enviat per canviar la paraula de pas`)
      }).catch(error => {
        console.dir(error)
        this.showError(error)
      }).then(() => {
        this.changingPassword = false
      })
    },
    confirmEmail () {
      this.confirmingEmail = true
      this.$store.dispatch(actions.CONFIRM_EMAIL).then(() => {
        this.showMessage(`Correu electrònic enviat per tal de confirmar el email`)
      }).catch(error => {
        console.dir(error)
        this.showError(error)
      }).then(() => {
        this.confirmingEmail = false
      })
    }
  },
  created () {
    this.isEmailVerified = window.user && window.user.email_verified_at
  }
}
</script>
