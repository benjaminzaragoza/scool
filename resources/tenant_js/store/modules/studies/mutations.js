import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_STUDIES ] (state, studies) {
    state.studies = studies
  },
  [ mutations.ADD_STUDY ] (state, study) {
    state.studies.push(study)
  },
  [ mutations.DELETE_STUDY ] (state, study) {
    let studyInState = state.studies.find((element) => {
      return element.id === study.id
    })
    state.studies.splice(state.studies.indexOf(studyInState), 1)
  },
  [ mutations.SET_STUDIES_TAGS ] (state, tags) {
    state.tags = tags
  }
}
