/* eslint-disable no-unused-expressions */
import { shallowMount, Wrapper } from '@vue/test-utils'
import Vue from 'vue'
import Vuetify from 'vuetify'
import FloatingAddComponent from '../../../../../resources/tenant_js/components/ui/FloatingAddComponent'
import TestHelpers from '../helpers.js'

describe('FloatingAddComponent.vue', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    Vue.use(Vuetify)
  })

  it('shows_button_but_not_dialog', () => {
    let wrapper = shallowMount(FloatingAddComponent)
    wrapper.assertContains('vbtn-stub')
    wrapper.assertNotVisible('vdialog-stub')
  })

  it('shows_dialog', () => {
    const wrapper = shallowMount(FloatingAddComponent, {
      propsData: {
        dialog: true
      }
    })
    wrapper.assertContains('vdialog-stub')
  })

  it('watches_for_changes_in_dialog_prop', () => {
    let wrapper = shallowMount(FloatingAddComponent)
    wrapper.assertContains('vbtn-stub')
    wrapper.assertNotVisible('vdialog-stub')

    wrapper.setProps({ dialog: true })
    wrapper.assertContains('vdialog-stub')
  })
})
