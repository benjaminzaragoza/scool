/* eslint-disable no-unused-expressions */
import { mount, shallowMount, Wrapper } from '@vue/test-utils'
import ReplyDeleteComponent from '../../../../../resources/tenant_js/components/replies/ReplyDeleteComponent'
import Vue from 'vue'
import Vuetify from 'vuetify'
import TestHelpers from '../helpers.js'
import moxios from 'moxios'
import { expect } from 'chai'
import sinon from 'sinon'

Vue.use(Vuetify)
Vue.config.silent = true

describe('ReplyDeleteComponent.vue', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    moxios.install(window.axios)
  })

  afterEach(function () {
    moxios.uninstall(window.axios)
  })

  it('shows_a_button_icon', () => {
    const wrapper = shallowMount(ReplyDeleteComponent, {
      propsData: {
        repliable: {
          id: 1,
          api_uri: 'incidents'
        },
        reply: {
          id: 1
        }
      }
    })
    wrapper.assertContains('vbtn-stub')
    wrapper.assertContains('vbtn-stub[icon="true"]')
  })

  it('deletes_a_reply', (done) => {
    window.user = {
      id: 1
    }

    moxios.stubRequest('/api/v1/incidents/1/replies/1', {
      status: 200,
      response: {
        id: 1,
        body: 'Si us plau podeu proporcionar més informació?',
        user_id: window.user.id
      }
    })

    const wrapper = mount(ReplyDeleteComponent, {
      propsData: {
        repliable: {
          id: 1,
          api_uri: 'incidents'
        },
        reply: {
          id: 1
        }
      }
    })

    wrapper.click('#reply_1_delete_button')

    moxios.wait(function () {
      const event = wrapper.assertEmitted('deleted')
      expect(event[0][0].id).equals(1)
      expect(event[0][0].body).equals('Si us plau podeu proporcionar més informació?')
      expect(event[0][0].user_id).equals(1)
      done()
    })
  })

  it('shows_error_when_deleting_incorrect_reply', (done) => {
    let showError = sinon.spy()

    window.user = {
      id: 1
    }

    moxios.stubRequest('/api/v1/incidents/1/replies/1', {
      status: 422
    })

    const wrapper = mount(ReplyDeleteComponent, {
      propsData: {
        repliable: {
          id: 1,
          api_uri: 'incidents'
        },
        reply: {
          id: 1
        }
      },
      mocks: {
        $snackbar: {
          showError
        }
      }
    })
    wrapper.click('#reply_1_delete_button')

    moxios.wait(function () {
      wrapper.assertNotEmitted('added')
      expect(showError.called).to.be.true
      done()
    })
  })
})
