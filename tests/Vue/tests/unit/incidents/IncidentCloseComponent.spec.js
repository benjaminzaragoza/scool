/* eslint-disable no-unused-expressions */
import { shallowMount, mount, Wrapper } from '@vue/test-utils'
import IncidentCloseComponent from '../../../../../resources/tenant_js/components/incidents/IncidentCloseComponent'
import Vue from 'vue'
import Vuetify from 'vuetify'
import Snackbar from '../../../../../resources/tenant_js/plugins/snackbar'
import TestHelpers from '../helpers.js'
import Vuex from 'vuex'
import sinon from 'sinon'
import { expect } from 'chai'

Vue.use(Vuex)
Vue.use(Vuetify)
Vue.use(Snackbar)
Vue.config.silent = true

describe('IncidentCloseComponent.vue', () => {
  let store
  let actions

  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    let closeIncindentStub = sinon.stub()
    closeIncindentStub.resolves({})
    let openIncindentStub = sinon.stub()
    openIncindentStub.resolves({})
    actions = {
      CLOSE_INCIDENT: closeIncindentStub,
      OPEN_INCIDENT: openIncindentStub
    }
    store = new Vuex.Store({
      state: {},
      actions
    })
  })

  it('shows_a_component_to_close', () => {
    const wrapper = shallowMount(IncidentCloseComponent, {
      propsData: {
        incident: {
          id: 1
        }
      }
    })
    wrapper.assertContains('#close_incident_1')
  })

  it('closes_incident', (done) => {
    let showMessage = sinon.spy()

    const wrapper = mount(IncidentCloseComponent, {
      propsData: {
        incident: {
          id: 1
        }
      },
      mocks: {
        $snackbar: {
          showMessage
        }
      },
      store
    })
    wrapper.click('#close_incident_1')
    expect(actions.CLOSE_INCIDENT.calledOnce).to.be.true

    setTimeout(() => {
      expect(showMessage.called).to.be.true
      done()
    },
    20)
  })

  it.only('opens_incident', (done) => {
    let showMessage = sinon.spy()

    const wrapper = mount(IncidentCloseComponent, {
      propsData: {
        incident: {
          id: 1
        }
      },
      mocks: {
        $snackbar: {
          showMessage
        }
      },
      store
    })
    wrapper.click('#close_incident_1')
    expect(actions.CLOSE_INCIDENT.calledOnce).to.be.true

    setTimeout(() => {
      expect(showMessage.called).to.be.true
      done()
    },
    20)
  })
})
