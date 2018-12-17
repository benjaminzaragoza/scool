import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_STUDIES ] (state, studies) {
    state.studies = studies
  }
}
