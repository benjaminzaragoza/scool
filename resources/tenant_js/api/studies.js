import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/studies')
  },
  store (study) {
    return axios.post('/api/v1/studies', {
      'name': study.name,
      'shortname': study.shortname,
      'code': study.code,
      'family': study.family,
      'department': study.department
    })
  }
}
