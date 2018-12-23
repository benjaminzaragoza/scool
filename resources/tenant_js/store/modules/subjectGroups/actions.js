import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/subjectGroups'

export default {
  [ actions.SET_SUBJECT_GROUPS ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_SUBJECT_GROUPS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.ADD_SUBJECT_GROUP ] (context, subject) {
    return new Promise((resolve, reject) => {
      api.store(subject).then(response => {
        context.commit(mutations.ADD_SUBJECT_GROUP, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
}
