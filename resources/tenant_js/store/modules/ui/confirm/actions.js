import * as actions from '../../../action-types'
import * as mutations from '../../../mutation-types'

export default {
  [ actions.SET_CONFIRM_DIALOG_SHOW ] (context, show) {
    context.commit(mutations.SET_CONFIRM_DIALOG_SHOW, show)
  }
}
