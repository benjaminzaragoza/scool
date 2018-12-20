import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_COURSES ] (state, courses) {
    state.courses = courses
  }
}
