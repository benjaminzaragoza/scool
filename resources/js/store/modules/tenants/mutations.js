import * as types from '../../mutation-types'

export default {
  [ types.SET_TENANTS ] (state, tenants) {
    state.tenants = tenants
  },
  [ types.ADD_TENANT ] (state, tenant) {
    state.tenants.push(tenant)
  },
  [ types.REMOVE_TENANT ] (state, tenant) {
    state.tenants.splice(state.tenants.indexOf(tenant),1)
  }
}
