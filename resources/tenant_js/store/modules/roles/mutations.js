import * as types from '../../mutation-types'

export default {
  [ types.SET_ROLES ] (state, roles) {
    state.roles = roles
  }
}
