import Vue from 'vue'
import Vuex from 'vuex'
import users from './modules/users'
import tenants from './modules/tenants'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  modules: {
    users,
    tenants
  },
  strict: debug
})
