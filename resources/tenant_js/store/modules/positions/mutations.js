import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_POSITIONS ] (state, positions) {
    state.positions = positions
  }
}
