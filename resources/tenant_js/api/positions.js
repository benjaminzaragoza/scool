import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/positions')
  },
  store (position) {
    return axios.post('/api/v1/studies', {
      'name': position.name,
      'shortname': position.shortname,
      'code': position.code
    })
  },
  delete (position) {
    return axios.delete('/api/v1/studies/' + position.id)
  }
}
