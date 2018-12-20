import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_DEPARTMENTS ] (state, departments) {
    state.departments = departments
  }
}
