import * as mutations from '../../mutation-types'

export default {
  [ mutations.SET_FAMILIES ] (state, families) {
    state.families = families
  }
}
