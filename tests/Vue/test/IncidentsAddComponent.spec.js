import { mount } from '@vue/test-utils'
import IncidentAdd from '../../../resources/assets/tenant_js/components/incidents/IncidentAddComponent.vue'
import Vuetify from 'vuetify'
import Vue from 'vue'

describe('IncidentAddComponent.vue', () => {
  let wrp

  beforeEach(() => {
    Vue.use(Vuetify)
    wrp = mount(IncidentAdd)
  })

  it('check_default_state', () => {
    expect(wrp.vm.dialog).toBe(false)
    expect(wrp.vm.subject).toBe('')
    expect(wrp.vm.description).toBe('')
    expect(wrp.vm.adding).toBe(false)
  })
})
