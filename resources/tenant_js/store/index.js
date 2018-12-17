import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth'
import snackbar from './modules/ui/snackbar'
import confirm from './modules/ui/confirm'
import users from './modules/users'
import googleUsers from './modules/google_users'
import staff from './modules/jobs'
import teachers from './modules/teachers'
import incidents from './modules/incidents'
import studies from './modules/studies'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  modules: {
    auth,
    snackbar,
    confirm,
    users,
    staff,
    teachers,
    googleUsers,
    incidents,
    studies
  },
  strict: debug
})
