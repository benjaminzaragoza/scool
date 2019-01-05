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
  },
  [ actions.ADD_POSITION ] (context, incident) {
    return new Promise((resolve, reject) => {
      api.store(incident).then(response => {
        context.commit(mutations.ADD_POSITION, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  },
  [ actions.DELETE_POSITION ] (context, incident) {
    return new Promise((resolve, reject) => {
      api.delete(incident).then(response => {
        context.commit(mutations.DELETE_POSITION, response.data)
        resolve(response)
      }).catch(error => {
        reject(error)
      })
    })
  }
}
