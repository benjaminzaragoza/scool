import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_INCIDENTS ] (state, incidents) {
    state.incidents = incidents
  }
}
