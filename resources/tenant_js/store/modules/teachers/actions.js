import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import pending from '../../../api/pendingTeachers'
import api from '../../../api/teachers'

export default {
  [ actions.GET_TEACHERS ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_TEACHERS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.GET_PENDING_TEACHERS ] (context) {
    return new Promise((resolve, reject) => {
      pending.fetch().then(response => {
        context.commit(mutations.SET_PENDING_TEACHERS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.REMOVE_PENDING_TEACHER ] (context, teacher) {
    return new Promise((resolve, reject) => {
      pending.delete(teacher).then(response => {
        context.commit(mutations.REMOVE_PENDING_TEACHER, teacher)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
