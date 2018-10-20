import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/incidents')
  },
  store (incident) {
    return axios.post('/api/v1/incidents', {
      'subject': incident.subject,
      'description': incident.description
    })
  }
}
