/* eslint-disable no-unused-expressions */
import { shallowMount, mount, Wrapper } from '@vue/test-utils'
import IncidentDeleteComponent from '../../../../../resources/tenant_js/components/incidents/IncidentDeleteComponent'
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

describe('IncidentDeleteComponent.vue', () => {
  let store
  let storeError
  let actions
  let actionsError

  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    let deleteIncidentStub = sinon.stub()
    deleteIncidentStub.resolves({})
    actions = {
      DELETE_INCIDENT: deleteIncidentStub
    }
    let deleteIncidentStubError = sinon.stub()
    deleteIncidentStubError.rejects({})
    actionsError = {
      DELETE_INCIDENT: deleteIncidentStubError
    }
    store = new Vuex.Store({
      state: {},
      actions
    })
    storeError = new Vuex.Store({
      state: {},
      actions: actionsError
    })
  })

  it('shows_a_component_to_delete', () => {
    const wrapper = shallowMount(IncidentDeleteComponent, {
      propsData: {
        incident: {
          id: 1
        }
      }
    })
    wrapper.assertContains('#delete_incident_1')
  })

  it('deletes_incident', (done) => {
    let showMessage = sinon.spy()
    let confirm = sinon.stub().resolves(true)

    const wrapper = mount(IncidentDeleteComponent, {
      propsData: {
        incident: {
          id: 1
        }
      },
      mocks: {
        $snackbar: {
          showMessage
        },
        $confirm: confirm
      },
      store
    })
    wrapper.click('#delete_incident_1')

    setTimeout(() => {
      expect(actions.DELETE_INCIDENT.calledOnce).to.be.true
      expect(showMessage.called).to.be.true
      done()
    },
    20)
  })

  it('shows_error_when_deletes_incident', (done) => {
    let showError = sinon.spy()
    let confirm = sinon.stub().resolves(true)

    const wrapper = mount(IncidentDeleteComponent, {
      propsData: {
        incident: {
          id: 1
        }
      },
      mocks: {
        $snackbar: {
          showError
        },
        $confirm: confirm
      },
      store: storeError
    })
    wrapper.click('#delete_incident_1')

    setTimeout(() => {
      expect(showError.called).to.be.true
      done()
    },
    20)
  })
})
