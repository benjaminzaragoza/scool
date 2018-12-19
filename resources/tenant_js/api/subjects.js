import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/subjects')
  },
  store (subject) {
    return axios.post('/api/v1/subjects', {
      'name': subject.name,
      'shortname': subject.shortname,
      'code': subject.code,
      'family': subject.family,
      'department': subject.department
    })
  },
  delete (subject) {
    return axios.delete('/api/v1/subjects/' + subject.id)
  }
}
