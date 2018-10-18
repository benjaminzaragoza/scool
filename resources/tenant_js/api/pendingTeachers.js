import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/pending_teachers')
  },
  delete (teacher) {
    return axios.delete('/api/v1/pending_teacher/' + teacher.id)
  }
}
