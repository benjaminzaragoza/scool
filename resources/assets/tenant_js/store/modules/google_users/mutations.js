import * as types from '../../mutation-types'

export default {
  [ types.SET_GOOGLE_USERS ] (state, users) {
    state.googleUsers = users
  },
  [ types.ADD_GOOGLE_USER ] (state, user) {
    state.googleUsers.push(user)
  }
}
