import * as types from '../../mutation-types'

export default {
  [ types.SET_GOOGLE_USERS ] (state, users) {
    state.googleUsers = users
  },
  [ types.ADD_GOOGLE_USER ] (state, user) {
    state.googleUsers.push(user)
  },
  [ types.DELETE_GOOGLE_USER ] (state, user) {
    state.googleUsers.splice(state.googleUsers.indexOf(user), 1)
  }
}
