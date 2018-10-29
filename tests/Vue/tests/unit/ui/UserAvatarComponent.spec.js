/* eslint-disable no-unused-expressions */
import { shallowMount, Wrapper } from '@vue/test-utils'
import Vue from 'vue'
import Vuetify from 'vuetify'
import UserAvatarComponent from '../../../../../resources/tenant_js/components/ui/UserAvatarComponent'
import TestHelpers from '../helpers.js'

Vue.use(Vuetify)

describe('UserAvatarComponent.vue', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
  })

  it('shows_v_avatar', () => {
    let wrapper = shallowMount(UserAvatarComponent, {
      propsData: {
        alt: 'Alt de prova',
        hashId: 232
      }
    })
    wrapper.assertContains('vavatar-stub')
  })
})
