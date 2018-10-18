import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import tenants from '../../../api/tenants'

export default {
  [ actions.SET_TENANTS ] (context, tenants) {
    context.commit(mutations.SET_TENANTS, tenants)
  },
  [ actions.GET_TENANTS ] (context) {
    return new Promise((resolve, reject) => {
      tenants.fetch().then(response => {
        context.commit(mutations.SET_TENANTS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
