import { mount } from '@vue/test-utils'
import Vue from 'vue'
import Vuetify from 'vuetify'
import FloatingAddComponent from '../../../../resources/assets/tenant_js/components/ui/FloatingAddComponent.vue'

describe('FloatingAddComponent.vue', () => {
  let wrp

  beforeEach(() => {
    Vue.use(Vuetify)
    wrp = mount(FloatingAddComponent)
  })

  it('check_default_state', () => {
    expect(wrp.vm.dialog).toBe(false)
  })

  it('shows_add_button', () => {
    expect(wrp.contains('button.v-btn')).toBe(true)
  })

})