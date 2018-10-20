import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_INCIDENTS ] (state, incidents) {
    state.incidents = incidents
  },
  [ mutations.ADD_INCIDENT ] (state, incident) {
    state.incidents = state.incidents.splice(0, 0, incident)
  }
}
