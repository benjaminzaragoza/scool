import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import permissions from '../../../api/permissions'

export default {
  [ actions.FETCH_PERMISSIONS ] (context) {
    return new Promise((resolve, reject) => {
      permissions.fetch().then(response => {
        context.commit(mutations.SET_PERMISSIONS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.UPDATE_PERMISSION ] (context, permission) {
    return new Promise((resolve, reject) => {
      permissions.update(permission).then(response => {
        context.commit(mutations.USER, permission)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.STORE_PERMISSION ] (context, permission) {
    return new Promise((resolve, reject) => {
      permissions.store(permission).then(response => {
        context.commit(mutations.ADD_PERMISSION, response.data.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.DELETE_PERMISSION ] (context, permission) {
    return new Promise((resolve, reject) => {
      permissions.delete(permission).then(response => {
        context.commit(mutations.DELETE_PERMISSION, permission)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
