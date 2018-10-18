import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import users from '../../../api/google_users'

export default {
  [ actions.FETCH_GOOGLE_USERS ] (context) {
    return new Promise((resolve, reject) => {
      users.fetch().then(response => {
        context.commit(mutations.SET_USERS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.STORE_GOOGLE_USER ] (context, user) {
    return new Promise((resolve, reject) => {
      users.store(user).then(response => {
        context.commit(mutations.ADD_USER, response.data.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
