/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { shallowMount, mount, Wrapper } from '@vue/test-utils'
import IncidentShowComponent from '../../../../../resources/tenant_js/components/incidents/IncidentShowComponent'
import Vue from 'vue'
import Vuetify from 'vuetify'
import TestHelpers from '../helpers.js'

Vue.use(Vuetify)
Vue.config.silent = true

describe('IncidentsShowComponent.vue', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
  })

  it('shows_a_vcard', () => {
    const wrapper = shallowMount(IncidentShowComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24"
        }
      }
    })
    wrapper.assertContains('vcard-stub')
  })

  it('shows_incident_subject_on_toolbar', () => {
    const wrapper = mount(IncidentShowComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24"
        }
      }
    })
    expect(wrapper.find('#incident_1_show_toolbar').text()).contains("No funciona res a l'aula 24")
  })

  it('emit_close_event_when_click_on_close_button', () => {
    const wrapper = mount(IncidentShowComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24"
        }
      }
    })
    wrapper.click('#incident_1_show_close_button')
    wrapper.assertEmitted('close')
  })
})
