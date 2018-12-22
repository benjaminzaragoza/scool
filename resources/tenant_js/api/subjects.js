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
      'number': subject.number,
      'study_id': subject.study_id,
      'subject_group_id': subject.subject_group_id,
      'course_id': subject.course_id,
      'hours': subject.hours,
      'start': subject.start,
      'end': subject.end
    })
  },
  delete (subject) {
    return axios.delete('/api/v1/subjects/' + subject.id)
  }
}
