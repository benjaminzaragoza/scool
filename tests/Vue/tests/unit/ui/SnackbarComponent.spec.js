/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { shallowMount, createLocalVue } from '@vue/test-utils'
import SnackBarComponent from '../../../../../resources/tenant_js/components/ui/SnackBarComponent'
import Vuex from 'vuex'
import sinon from 'sinon'
import Vuetify from 'vuetify'

const localVue = createLocalVue()
localVue.config.silent = true

localVue.use(Vuex)
localVue.use(Vuetify)

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

  it('renders_texts', () => {
    const wrapper = shallowMount(SnackBarComponent, { store, localVue })
    expect(wrapper.text()).to.include('TEXT PRINCIPAL')
    expect(wrapper.text()).to.include('TEXT SECUNDARI')
  })

  it('closes', () => {
    const wrapper = shallowMount(SnackBarComponent, { store, localVue })
    wrapper.find('vbtn-stub').trigger('click')
    expect(actions.SET_SNACKBAR_SHOW.calledOnce).to.be.true
  })
})
