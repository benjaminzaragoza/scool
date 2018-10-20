import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_INCIDENTS ] (state, incidents) {
    state.incidents = incidents
  },
  [ mutations.ADD_INCIDENT ] (state, incident) {
    state.incidents.push(incident)
  },
  [ mutations.CLOSE_INCIDENT ] (state, incident) {
    let incidentInState = state.incidents.find((element) => {
      return element.id === incident.id
    })
    incidentInState.closed_at = incident.closed_at
    incidentInState.closed_at_timestamp = incident.closed_at_timestamp
    incidentInState.formatted_closed_at = incident.formatted_closed_at
  },
  [ mutations.OPEN_INCIDENT ] (state, incident) {
    let incidentInState = state.incidents.find((element) => {
      return element.id === incident.id
    })
    incidentInState.closed_at = null
    incidentInState.closed_at_timestamp = null
    incidentInState.formatted_closed_at = null
  },
  [ mutations.DELETE_INCIDENT ] (state, incident) {
    let incidentInState = state.incidents.find((element) => {
      return element.id === incident.id
    })
    state.incidents.splice(state.incidents.indexOf(incidentInState), 1)
  }
}
