/* eslint-disable no-unused-expressions */
import { shallowMount, Wrapper } from '@vue/test-utils'
import Vue from 'vue'
import Vuetify from 'vuetify'
import FullScreenDialog from '../../../../../resources/tenant_js/components/ui/FullScreenDialog'
import TestHelpers from '../helpers.js'

describe('FullScreenDialog.vue', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    Vue.use(Vuetify)
  })

  it('shows_button_but_not_dialog', () => {
    let wrapper = shallowMount(FullScreenDialog)
    wrapper.assertContains('vbtn-stub')
    wrapper.assertNotContains('vdialog-stub')
  })

  it('shows_dialog', () => {
    const wrapper = shallowMount(FullScreenDialog, {
      propsData: {
        dialog: true
      }
    })
    wrapper.assertContains('vdialog-stub')
  })

  it('watches_for_changes_in_dialog_prop', () => {
    let wrapper = shallowMount(FullScreenDialog)
    wrapper.assertContains('vbtn-stub')
    wrapper.assertNotContains('vdialog-stub')

    wrapper.setProps({ dialog: true })
    wrapper.assertContains('vdialog-stub')
  })
})
