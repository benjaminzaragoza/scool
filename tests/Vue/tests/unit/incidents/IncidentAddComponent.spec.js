/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { mount, Wrapper } from '@vue/test-utils'
import IncidentsAddComponent from '../../../../../resources/tenant_js/components/incidents/IncidentAddComponent'
import Vue from 'vue'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import Snackbar from '../../../../../resources/tenant_js/plugins/snackbar'
import TestHelpers from '../helpers.js'
import sinon from 'sinon'

Vue.use(Vuex)
Vue.use(Vuetify)
Vue.use(Snackbar)
Vue.config.silent = true

describe('IncidentsAddComponent.vue', () => {
  let store
  let actions
  let sampleIncident = {
    id: 1,
    username: 'Pepe Pardo Jeans',
    subject: 'No funciona PC1 Aula 30',
    description: 'Bla bla bla'
  }

  beforeEach(() => {
    let addIncindentStub = sinon.stub()
    addIncindentStub.resolves({ data: sampleIncident })
    actions = {
      ADD_INCIDENT: addIncindentStub
    }
    Object.assign(Wrapper.prototype, TestHelpers)
    store = new Vuex.Store({
      state: {},
      actions
    })
  })

  it('shows_a_form', () => {
    const wrapper = mount(IncidentsAddComponent)
    expect(wrapper.element).to.be.a('HTMLFormElement')
    wrapper.assertContains("input[name='subject']")
    wrapper.assertContains("textarea[name='description']")
  })

  it('contains_add_buttons', () => {
    const wrapper = mount(IncidentsAddComponent)
    wrapper.assertContains('#add_incident_button')
    wrapper.assertContains('#add_and_close_incident_button')
  })

  it('contains_close_button', () => {
    const wrapper = mount(IncidentsAddComponent)
    wrapper.assertContains('#close_button')
  })

  it('closes', () => {
    const wrapper = mount(IncidentsAddComponent)
    wrapper.click('#close_button')
  })

  it('adds_incidents', (done) => {
    let showMessage = sinon.spy()

    const wrapper = mount(IncidentsAddComponent, {
      mocks: {
        $snackbar: {
          showMessage
        }
      },
      store })
    wrapper.type("input[name='subject']", 'No funciona PC1 Aula 30')
    wrapper.type("textarea[name='description']", 'Bla bla bla')
    wrapper.click('#add_incident_button')
    expect(actions.ADD_INCIDENT.getCall(0).args[1].subject).equals('No funciona PC1 Aula 30')
    expect(actions.ADD_INCIDENT.getCall(0).args[1].description).equals('Bla bla bla')

    setTimeout(() => {
      expect(wrapper.emitted().added).not.to.be.undefined
      expect(showMessage.called).to.be.true
      done()
    },
    20)
  })

  it('adds_incidents_and_close', (done) => {
    let showMessage = sinon.spy()

    const wrapper = mount(IncidentsAddComponent, {
      mocks: {
        $snackbar: {
          showMessage
        }
      },
      store })
    wrapper.type("input[name='subject']", 'No funciona PC1 Aula 30')
    wrapper.type("textarea[name='description']", 'Bla bla bla')
    wrapper.click('#add_and_close_incident_button')
    expect(actions.ADD_INCIDENT.calledOnce).to.be.true
    expect(actions.ADD_INCIDENT.getCall(0).args[1].subject).equals('No funciona PC1 Aula 30')
    expect(actions.ADD_INCIDENT.getCall(0).args[1].description).equals('Bla bla bla')

    setTimeout(() => {
      expect(wrapper.emitted().added).not.to.be.undefined
      expect(wrapper.emitted().close).not.to.be.undefined
      expect(showMessage.called).to.be.true
      done()
    },
    20)
  })
})
