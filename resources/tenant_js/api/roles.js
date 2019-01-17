import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/roles')
  },
  update (role) {
    return axios.put('/api/v1/role', {
      'name': role.name,
      'guard_name': role.email
    })
  },
  store (role) {
    return axios.post('/api/v1/roles', {
      'name': role.name,
      'guard_name': role.guard_name
    })
  },
  delete (role) {
    return axios.delete('/api/v1/roles/' + role.id)
  }
}
