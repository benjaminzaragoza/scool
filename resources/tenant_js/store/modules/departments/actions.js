import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/departments'

export default {
  [ actions.SET_DEPARTMENTS ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_DEPARTMENTS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
