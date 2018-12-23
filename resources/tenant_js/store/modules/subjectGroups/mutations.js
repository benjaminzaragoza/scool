import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_SUBJECT_GROUPS ] (state, subjectGroups) {
    state.subjectGroups = subjectGroups
  },
  [ mutations.ADD_SUBJECT_GROUP ] (state, subjectGroup) {
    state.subjectGroups.push(subjectGroup)
  }
}
