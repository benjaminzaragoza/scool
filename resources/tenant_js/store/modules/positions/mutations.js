import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_POSITIONS ] (state, positions) {
    state.positions = positions
  },
  [ mutations.ADD_POSITION ] (state, position) {
    state.positions.push(position)
  },
  [ mutations.DELETE_POSITION ] (state, position) {
    let positionInState = state.positions.find((element) => {
      return element.id === position.id
    })
    state.positions.splice(state.positions.indexOf(positionInState), 1)
  }
}
