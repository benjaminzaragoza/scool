import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/courses'

export default {
  [ actions.SET_COURSES ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_COURSES, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
