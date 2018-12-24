import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_SUBJECT_GROUPS ] (state, subjectGroups) {
    state.subjectGroups = subjectGroups
  },
  [ mutations.ADD_SUBJECT_GROUP ] (state, subjectGroup) {
    state.subjectGroups.push(subjectGroup)
  },
  [ mutations.SET_SUBJECT_GROUP_TAGS ] (state, subjectGroupTags) {
    state.subjectGroupTags = subjectGroupTags
  },
  [ mutations.DELETE_SUBJECT_GROUP ] (state, subjectGroup) {
    let subjectGroupInState = state.subjectGroups.find((element) => {
      return element.id === subjectGroup.id
    })
    state.subjectGroups.splice(state.subjectGroups.indexOf(subjectGroupInState), 1)
  }
}
