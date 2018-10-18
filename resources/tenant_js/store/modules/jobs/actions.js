import * as mutations from '../../mutation-types'
import * as actions from '../../action-types'
import api from '../../../api/jobs'

export default {
  [ actions.GET_JOBS ] (context) {
    return new Promise((resolve, reject) => {
      api.fetch().then(response => {
        context.commit(mutations.SET_JOBS, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.STORE_JOB ] (context, job) {
    return new Promise((resolve, reject) => {
      api.store(job).then(response => {
        context.dispatch(actions.GET_JOBS)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.EDIT_JOB ] (context, job) {
    return new Promise((resolve, reject) => {
      api.update(job).then(response => {
        context.dispatch(actions.GET_JOBS)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.DELETE_JOB ] (context, job) {
    return new Promise((resolve, reject) => {
      api.delete(job).then(response => {
        context.commit(mutations.DELETE_JOB, job)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
