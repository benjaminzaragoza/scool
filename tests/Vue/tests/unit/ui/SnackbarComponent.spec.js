/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { shallowMount } from '@vue/test-utils'
import SnackBarComponent from '../../../../../resources/tenant_js/components/ui/SnackBarComponent'
import Vue from 'vue'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import sinon from 'sinon'

Vue.use(Vuex)
Vue.use(Vuetify)

describe('SnackBarComponent.vue', () => {
  let getters
  let actions
  let store

  beforeEach(() => {
    actions = {
      SET_SNACKBAR_SHOW: sinon.stub()
    }
    getters = {
      snackbarTimeout: sinon.stub(),
      snackbarColor: sinon.stub(),
      snackbarShow: sinon.stub(),
      snackbarText: () => { return 'TEXT PRINCIPAL' },
      snackbarSubtext: () => { return 'TEXT SECUNDARI' }
    }
    store = new Vuex.Store({
      state: {},
      getters,
      actions
    })
  })

  it('check_default_timetout', () => {
    const wrapper = shallowMount(SnackBarComponent, { store })
    expect(wrapper.html()).contains('timeout="6000"')
  })

  it('renders_texts', () => {
    const wrapper = shallowMount(SnackBarComponent, { store })
    expect(wrapper.text()).to.include('TEXT PRINCIPAL')
    expect(wrapper.text()).to.include('TEXT SECUNDARI')
  })

  it('closes', () => {
    const wrapper = shallowMount(SnackBarComponent, { store })
    wrapper.find('vbtn-stub').trigger('click')
    expect(actions.SET_SNACKBAR_SHOW.calledOnce).to.be.true
  })
})
