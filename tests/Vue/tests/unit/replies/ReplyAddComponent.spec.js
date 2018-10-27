/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { mount, Wrapper } from '@vue/test-utils'
import ReplyAddComponent from '../../../../../resources/tenant_js/components/replies/ReplyAddComponent'
import Vue from 'vue'
import Vuetify from 'vuetify'
import TestHelpers from '../helpers.js'
import moxios from 'moxios'

Vue.use(Vuetify)
Vue.config.silent = true

describe.only('ReplyAddComponent.vue', () => {
  beforeEach(() => {
    console.log('beforeEach')
    Object.assign(Wrapper.prototype, TestHelpers)
    moxios.install(window.axios)
    // moxios.install(global.axios)
  })

  afterEach(function () {
    console.log('afterEach')
    moxios.uninstall(window.axios)
    // moxios.uninstall(global.axios)
  })

  it('shows_a_form', () => {
    const wrapper = mount(ReplyAddComponent)
    expect(wrapper.element).to.be.a('HTMLFormElement')
    wrapper.assertContains("textarea[name='body']")
  })

  it.only('adds_a_reply', (done) => {
    window.user = {
      id: 1
    }

    moxios.stubRequest('/api/v1/incidents/1/replies', {
      status: 200,
      response: {
        id: 1,
        body: 'Si us plau podeu proporcionar més informació?',
        user_id: window.user.id
      }
    })

    const wrapper = mount(ReplyAddComponent, {
      propsData: {
        repliable: {
          id: 1,
          api_uri: 'incidents'
        }
      }
    })
    wrapper.type('[name="body"]', 'Si us plau podeu proporcionar més informació?')
    wrapper.click('#add_reply_button')

    moxios.wait(function () {
      const event = wrapper.assertEmitted('added')
      console.log(event)
      done()
    })
  })
})
