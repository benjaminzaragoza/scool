/* eslint-disable no-unused-expressions */
import { shallowMount, Wrapper } from '@vue/test-utils'
import Vue from 'vue'
import Vuetify from 'vuetify'
import InlineTextFieldEditDialog from '../../../../../resources/tenant_js/components/ui/InlineTextFieldEditDialog'
import TestHelpers from '../helpers.js'

describe('InlineTextFieldEditDialog.vue', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    Vue.use(Vuetify)
  })

  it.only('shows_default_slot_and_not_show_dialog', () => {
    let wrapper = shallowMount(InlineTextFieldEditDialog, {
      dataProps: {
        field: 'subject'
      }
    })
    wrapper.seeText('prova')
    wrapper.assertNotVisible('veditdialog-stub')
  })
})
