import * as types from '../../mutation-types'

export default {
  [ types.SET_JOBS ] (state, jobs) {
    state.jobs = jobs
  },
  [ types.ADD_JOB ] (state, job) {
    state.jobs.push(job)
  },
  [ types.DELETE_JOB ] (state, job) {
    state.jobs.splice(state.jobs.indexOf(job), 1)
  }
}
