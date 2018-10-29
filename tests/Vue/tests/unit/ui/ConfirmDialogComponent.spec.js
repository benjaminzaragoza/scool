/* eslint-disable no-unused-expressions */
import Vue from 'vue'
import Vuetify from 'vuetify'
import ConfirmDialogComponent from '../../../../../resources/tenant_js/components/ui/ConfirmDialogComponent'
import { shallowMount, mount, Wrapper } from '@vue/test-utils'
import TestHelpers from '../helpers'
import Vuex from 'vuex'
import sinon from 'sinon'
import * as getter from '../../../../../resources/tenant_js/store/getter-types'
import { expect } from 'chai'

Vue.use(Vuex)
Vue.use(Vuetify)
Vue.config.silent = true

describe('ConfirmDialogComponent.vue', () => {
  let storeShow
  let storeNotShow
  let gettersNotShow
  let gettersShow

  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    gettersNotShow = {
      [ getter.CONFIRM_DIALOG_SHOW ]: sinon.stub()
    }
    let stubReturnsTrue = sinon.stub()
    stubReturnsTrue.returns(true)
    gettersShow = {
      [ getter.CONFIRM_DIALOG_SHOW ]: stubReturnsTrue
    }
    storeNotShow = new Vuex.Store({
      state: {},
      getters: gettersNotShow
    })
    storeShow = new Vuex.Store({
      state: {},
      getters: gettersShow
    })
  })

  it('shows_vdialog', () => {
    let wrapper = shallowMount(ConfirmDialogComponent, { store: storeShow })
    wrapper.assertContains('vdialog-stub')
    wrapper.assertContains('vcard-stub')
    expect(gettersShow.CONFIRM_DIALOG_SHOW.calledOnce).to.be.true
  })

  it('shows_vdialog_with_custom_message_title_and_confirm_text', () => {
    document.body.innerHTML = ''

    let wrapper = mount(ConfirmDialogComponent, {
      propsData: {
        message: 'Aquesta acció no es pot desfer.',
        title: 'Esteu segurs?',
        confirmText: 'Confirmar'
      },
      store: storeShow
    })
    expect(gettersShow.CONFIRM_DIALOG_SHOW.calledOnce).to.be.true
    wrapper.seeText('Aquesta acció no es pot desfer.')
    wrapper.seeText('Esteu segurs?')
    wrapper.seeText('Confirmar')
  })

  it('not_shows_vdialog', () => {
    let wrapper = shallowMount(ConfirmDialogComponent, { store: storeNotShow })
    wrapper.assertNotContains('vdialog-stub')
    expect(gettersNotShow.CONFIRM_DIALOG_SHOW.calledOnce).to.be.true
  })
})
