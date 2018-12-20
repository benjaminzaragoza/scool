import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/families'

export default {
  [ actions.SET_FAMILIES ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_FAMILIES, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
