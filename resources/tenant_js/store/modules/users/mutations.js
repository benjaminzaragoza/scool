import * as types from '../../mutation-types'

export default {
  [ types.SELECTED_USER ] (state, user) {
    state.selected_user = user
  },
  [ types.SET_USERS ] (state, users) {
    state.users = users
  },
  [ types.ADD_USER ] (state, user) {
    state.users.push(user)
  },
  [ types.EDIT_USER ] (state, user) {
    state.users.push(user)
  },
  [ types.DELETE_USER ] (state, user) {
    let userInState = state.users.find((element) => {
      return element.id === user.id
    })
    state.users.splice(state.users.indexOf(userInState), 1)
  }
}
