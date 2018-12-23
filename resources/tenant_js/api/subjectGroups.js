import axios from 'axios'

export default {
  fetch () {
    return axios.get('/api/v1/subjectGroups')
  },
  store (subjectGroup) {
    return axios.post('/api/v1/subjectGroups', {
      'number': subjectGroup.number,
      'name': subjectGroup.name,
      'shortname': subjectGroup.shortname,
      'code': subjectGroup.code,
      'study_id': subjectGroup.study_id,
      'hours': subjectGroup.hours,
      'free_hours': subjectGroup.free_hours,
      'week_hours': subjectGroup.week_hours,
      'type': subjectGroup.type,
      'start': subjectGroup.start,
      'end': subjectGroup.end
    })
  },
}
