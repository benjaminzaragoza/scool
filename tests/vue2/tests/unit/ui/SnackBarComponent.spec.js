/* eslint-disable no-undef */
import { mount } from '@vue/test-utils'
import SnackBarComponent from '../../../../../resources/assets/tenant_js/components/ui/SnackBarComponent.vue'
import Vuetify from 'vuetify'
import Vue from 'vue'
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

    Vue.use(Vuex)

    let getters = {
    }

    let store = new Vuex.Store({
      state: {},
      getters
    })

    wrp = mount(SnackBarComponent, { store })
  })

  it('check_default_state', () => {
    expect(wrp.vm.subject).toBe('')
  })

  it('shows_add_form', () => {

  })
})
