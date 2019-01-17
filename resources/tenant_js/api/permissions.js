import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/permissions')
  },
  update (permission) {
    return axios.put('/api/v1/permission', {
      'name': permission.name,
      'guard_name': permission.guard_name
    })
  },
  store (permission) {
    return axios.post('/api/v1/permissions', {
      'name': permission.name,
      'guard_name': permission.guard_name
    })
  },
  delete (permission) {
    return axios.delete('/api/v1/permissions/' + permission.id)
  }
}
