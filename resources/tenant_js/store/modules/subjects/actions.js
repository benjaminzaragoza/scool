import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/subjects'

export default {
  [ actions.SET_SUBJECTS ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_SUBJECTS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.ADD_SUBJECT ] (context, subject) {
    return new Promise((resolve, reject) => {
      api.store(subject).then(response => {
        context.commit(mutations.ADD_SUBJECT, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.DELETE_SUBJECT ] (context, subject) {
    return new Promise((resolve, reject) => {
      api.delete(subject).then(response => {
        context.commit(mutations.DELETE_SUBJECT, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
