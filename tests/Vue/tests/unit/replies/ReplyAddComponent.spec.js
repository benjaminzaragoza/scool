/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { mount, Wrapper } from '@vue/test-utils'
import ReplyAddComponent from '../../../../../resources/tenant_js/components/replies/ReplyAddComponent'
import Vue from 'vue'
import Vuetify from 'vuetify'
import TestHelpers from '../helpers.js'
import moxios from 'moxios'
import sinon from 'sinon'

Vue.use(Vuetify)
Vue.config.silent = true

describe('ReplyAddComponent.vue', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    moxios.install(window.axios)
  })

  afterEach(function () {
    moxios.uninstall(window.axios)
  })

  it('shows_a_form', () => {
    let hasRole = sinon.spy()
    const wrapper = mount(ReplyAddComponent, {
      mocks: {
        $hasRole: hasRole
      }
    })
    expect(wrapper.element).to.be.a('HTMLFormElement')
    wrapper.assertContains("textarea[name='body']")
    expect(hasRole.called).to.be.true
  })

  it('adds_a_reply', (done) => {
    let hasRole = sinon.spy()

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
      },
      mocks: {
        $hasRole: hasRole
      }
    })
    wrapper.type('[name="body"]', 'Si us plau podeu proporcionar més informació?')
    wrapper.click('#add_reply_button')

    moxios.wait(function () {
      const event = wrapper.assertEmitted('added')
      expect(event[0][0].id).equals(1)
      expect(event[0][0].body).equals('Si us plau podeu proporcionar més informació?')
      expect(event[0][0].user_id).equals(1)
      expect(hasRole.called).to.be.true
      done()
    })
  })
})
