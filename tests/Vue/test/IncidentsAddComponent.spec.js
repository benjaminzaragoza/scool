import { mount } from '@vue/test-utils'
import IncidentAdd from '../../../resources/assets/tenant_js/components/incidents/IncidentAddComponent.vue'
import Vuetify from 'vuetify'
import Vue from 'vue'
// import Vuex from 'vuex'

describe('IncidentAddComponent.vue', () => {
  let wrp

  beforeEach(() => {
    Vue.use(Vuetify)
    // Vue.use(Vuex)
    wrp = mount(IncidentAdd)
  })

  it('default_is_not_showed_by_default', () => {
    expect(wrp.vm.dialog).toBe(false)
    expect(wrp.vm.subject).toBe('')
    expect(wrp.vm.description).toBe('')
    expect(wrp.vm.adding).toBe(false)
  })
})
