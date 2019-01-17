import * as types from '../../mutation-types'

export default {
  [ types.SET_PERMISSIONS ] (state, permissions) {
    state.permissions = permissions
  }
}
