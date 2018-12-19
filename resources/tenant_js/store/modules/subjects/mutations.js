import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_SUBJECTS ] (state, subjects) {
    state.subjects = subjects
  },
  [ mutations.ADD_SUBJECT ] (state, subject) {
    state.subjects.push(subject)
  },
  [ mutations.DELETE_SUBJECT ] (state, subject) {
    let subjectInState = state.subjects.find((element) => {
      return element.id === subject.id
    })
    state.subjects.splice(state.subjects.indexOf(subjectInState), 1)
  }
}
