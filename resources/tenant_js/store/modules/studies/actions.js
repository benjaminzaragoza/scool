import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/studies'

export default {
  [ actions.SET_STUDIES ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_STUDIES, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.ADD_STUDY ] (context, incident) {
    return new Promise((resolve, reject) => {
      api.store(incident).then(response => {
        context.commit(mutations.ADD_STUDY, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
}
