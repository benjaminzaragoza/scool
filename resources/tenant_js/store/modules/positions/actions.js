import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/positions'

export default {
  [ actions.SET_POSITIONS ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_POSITIONS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
