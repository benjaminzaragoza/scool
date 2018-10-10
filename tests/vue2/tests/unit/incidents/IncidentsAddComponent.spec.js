/* eslint-disable no-undef */
import { mount, Wrapper } from '@vue/test-utils'
import IncidentAdd from '../../../../../resources/assets/tenant_js/components/incidents/IncidentAddComponent.vue'
import Vuetify from 'vuetify'
import Vue from 'vue'
import moxios from 'moxios'
import sinon from 'sinon'
import Vuex from 'vuex'
import TestHelpers from '../helpers.js'

describe('IncidentAddComponent.vue', () => {
  let wrp

  beforeEach(() => {
    Vue.use(Vuetify)
    // Silent ALL Warnings: $listeners is readonly.
    // Silent ALL Warnings: [Vue-warn] warnings about props mutation are thrown when using vuetify with test-utils
    // https://github.com/vuejs/vue-test-utils/issues/534
    // https://github.com/vuejs/vue-test-utils/issues/532
    // It will be solved in Vue 2.6 https://github.com/vuejs/vue/pull/8240
    Vue.config.silent = true
    // Assign test helpers methods to wrapper
    Object.assign(Wrapper.prototype, TestHelpers)
    wrp = mount(IncidentAdd)
    moxios.install(axios)
  })

  afterEach(function () {
    moxios.uninstall(axios)
  })

  it('check_default_state', () => {
    expect(wrp.vm.subject).toBe('')
    expect(wrp.vm.description).toBe('')
    expect(wrp.vm.adding).toBe(false)
  })

  it('shows_add_form', () => {
    wrp.assertContains('form')
    wrp.assertContains("input[name='subject']")
    wrp.assertContains("textarea[name='description']")
    wrp.assertContains('button#add_incident_button')
    wrp.assertContains('button#add_and_close_incident_button')
  })

  it('adds_an_incident', (done) => {
    let actions = {
      actionClick: sinon.stub()
    }

    let mutations = {
      SET_SNACKBAR_SHOW: sinon.stub(),
      SET_SNACKBAR_COLOR: sinon.stub(),
      SET_SNACKBAR_TEXT: sinon.stub(),
      SET_SNACKBAR_SUBTEXT: sinon.stub()
    }

    let getters = {
      snackbarTimeout: () => { return 6000 }
    }

    Vue.use(Vuex)
    let store = new Vuex.Store({
      state: {},
      getters,
      mutations,
      actions
    })

    wrp = mount(IncidentAdd, { store })

    moxios.stubRequest('/api/v1/incidents', {
      status: 200,
      response: {
        subject: 'No funciona PC1 Aula 34',
        description: 'bla bla bla bla'
      }
    })

    wrp.type("input[name='subject']", 'No funciona Pc2 Aula 34')
    wrp.type("textarea[name='description']", 'Bla bla bla bla')

    wrp.click('#add_incident_button')

    moxios.wait(() => {
      // see('Incidència afegida correctament')
      // TODO snackbar is changed using VUEX!!! Testing Vuex
      // this.$store.commit(mutations.SET_SNACKBAR_SHOW, true)
      // this.$store.commit(mutations.SET_SNACKBAR_COLOR, color || 'error')
      // if (typeof message === 'string') {
      //   this.$store.commit(mutations.SET_SNACKBAR_TEXT, message)

      wrp.assertEmitted('added')

      // console.log('actionClick: ' + actions.actionClick.calledOnce)
      // expect(actions.actionClick.calledOnce).toBe(true)

      expect(wrp.emitted().added[0][0].subject).toBe('No funciona PC1 Aula 34')
      expect(wrp.emitted().added[0][0].description).toBe('bla bla bla bla')
      done()
    })
  })

  it('adds_and_close_an_incident', () => {
    wrp.type("input[name='subject']", 'No funciona Pc2 Aula 34')
    wrp.type("textarea[name='description']", 'Bla bla bla bla')

    wrp.click('#add_and_close_incident_button')
  })
})
