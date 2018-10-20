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
  },
  close (incident) {
    return axios.post('/api/v1/closed_incidents/' + incident.id)
  },
  open (incident) {
    return axios.delete('/api/v1/closed_incidents/' + incident.id)
  }
}
