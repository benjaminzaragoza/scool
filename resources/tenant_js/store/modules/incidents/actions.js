import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/incidents'

export default {
  [ actions.SET_INCIDENTS ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_INCIDENTS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },

  [ actions.ADD_INCIDENT ] (context, incident) {
    return new Promise((resolve, reject) => {
      api.store(incident).then(response => {
        context.commit(mutations.ADD_INCIDENT, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
