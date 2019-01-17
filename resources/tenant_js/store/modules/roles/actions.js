import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import roles from '../../../api/roles'

export default {
  [ actions.FETCH_ROLES ] (context) {
    return new Promise((resolve, reject) => {
      roles.fetch().then(response => {
        context.commit(mutations.SET_ROLES, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
