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
    const wrapper = mount(ReplyAddComponent)
    expect(wrapper.element).to.be.a('HTMLFormElement')
    wrapper.assertContains("textarea[name='body']")
  })

  it('adds_a_reply', (done) => {
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
      expect(event[0][0].id).equals(1)
      expect(event[0][0].body).equals('Si us plau podeu proporcionar més informació?')
      expect(event[0][0].user_id).equals(1)
      done()
    })
  })

  it('shows_error_when_adding_incorrect_reply', (done) => {
    let showError = sinon.spy()

    window.user = {
      id: 1
    }

    moxios.stubRequest('/api/v1/incidents/1/replies', {
      status: 422
    })

    const wrapper = mount(ReplyAddComponent, {
      propsData: {
        repliable: {
          id: 1,
          api_uri: 'incidents'
        }
      },
      mocks: {
        $snackbar: {
          showError
        }
      }
    })
    wrapper.type('[name="body"]', '')
    wrapper.click('#add_reply_button')

    moxios.wait(function () {
      wrapper.assertNotEmitted('added')
      expect(showError.called).to.be.true
      done()
    })
  })
})
