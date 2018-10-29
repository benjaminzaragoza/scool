import * as mutation from '../../../mutation-types'

export default {
  [ mutation.SET_CONFIRM_DIALOG_SHOW ] (state, show) {
    state.show = show
  }
}
