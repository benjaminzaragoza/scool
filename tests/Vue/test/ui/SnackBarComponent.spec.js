/* eslint-disable no-undef */
import { mount } from '@vue/test-utils'
import SnackBarComponent from '../../../../resources/assets/tenant_js/components/ui/SnackBarComponent'
import Vuetify from 'vuetify'
import Vue from 'vue'
import sinon from 'sinon'
import Vuex from 'vuex'

describe('SnackBarComponent.vue', () => {
  let wrp

  beforeEach(() => {
    Vue.use(Vuetify)
    // Silent ALL Warnings: $listeners is readonly.
    // Silent ALL Warnings: [Vue-warn] warnings about props mutation are thrown when using vuetify with test-utils
    // https://github.com/vuejs/vue-test-utils/issues/534
    // https://github.com/vuejs/vue-test-utils/issues/532
    // It will be solved in Vue 2.6 https://github.com/vuejs/vue/pull/8240
    Vue.config.silent = true
    wrp = mount(SnackBarComponent)
  })

  it('check_default_state', () => {
    expect(wrp.vm.subject).toBe('')
  })

  it('shows_add_form', () => {
    contains('form')
    contains("input[name='subject']")
    contains("textarea[name='description']")
    contains('button#add_incident_button')
    contains('button#add_and_close_incident_button')
  })

  it('closes', (done) => {

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

    wrp = mount(SnackBarComponent, { store })



    type("input[name='subject']", 'No funciona Pc2 Aula 34')
    type("textarea[name='description']", 'Bla bla bla bla')

    click('#add_incident_button')


  })



})
