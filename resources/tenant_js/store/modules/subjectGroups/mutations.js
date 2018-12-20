import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_SUBJECT_GROUPS ] (state, subjectGroups) {
    state.subjectGroups = subjectGroups
  }
}
