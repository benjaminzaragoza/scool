import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/users')
  },
  update (user) {
    return axios.put('/api/v1/user', {
      'name': user.name,
      'email': user.email
    })
  },
  store (user) {
    return axios.post('/api/v1/users', {
      'name': user.name,
      'email': user.email,
      'password': user.password,
      'roles': user.roles,
      'type': user.type
    })
  },
  storeUserPerson (user) {
    return axios.post('/api/v1/user_person', {
      'givenName': user.givenName,
      'sn1': user.sn1,
      'sn2': user.sn2,
      'email': user.email,
      'user_type_id': user.user_type_id,
      'role': user.role
    })
  },
  delete (user) {
    return axios.delete('/api/v1/users/' + user.id)
  },
  deleteUserPerson (user) {
    return axios.delete('/api/v1/user_person/' + user)
  }
}
