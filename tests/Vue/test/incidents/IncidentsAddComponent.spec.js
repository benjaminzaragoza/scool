import { mount } from '@vue/test-utils'
import IncidentAdd from '../../../../resources/assets/tenant_js/components/incidents/IncidentAddComponent.vue'
import Vuetify from 'vuetify'
import Vue from 'vue'
import moxios from 'moxios'

describe('IncidentAddComponent.vue', () => {
  let wrp

  beforeEach(() => {
    Vue.use(Vuetify)
    wrp = mount(IncidentAdd)
    moxios.install(axios)
  })

  afterEach(function () {
    // import and pass your custom axios instance to this method
    moxios.uninstall(axios)
  })

  it('check_default_state', () => {
    expect(wrp.vm.subject).toBe('')
    expect(wrp.vm.description).toBe('')
    expect(wrp.vm.adding).toBe(false)
  })

  it('shows_add_form', () => {
    contains('form')
    contains("input[name='subject']")
    contains("textarea[name='description']")
    contains('button#add_incident_button')
    contains('button#add_and_close_incident_button')
  })

  it('adds_an_incident', () => {
    type("input[name='subject']", 'No funciona Pc2 Aula 34')
    type("textarea[name='description']", 'Bla bla bla bla')



    // MOCKING AXIOS
    moxios.stubRequest('/api/v1/incidents', {
      status: 200,
      response: {
        subject: 'HEY',
        description: 'JORL'
      }
    })

    click('#add_incident_button')

    // Assert
    // 0) Test adding state changes temporarily
    // 1) subject and description are emptied
    // 2) Show a message (after waiting for axios promise to finish)
  })

  it('adds_and_close_an_incident', () => {
    type("input[name='subject']", 'No funciona Pc2 Aula 34')
    type("textarea[name='description']", 'Bla bla bla bla')

    click('#add_and_close_incident_button')

    // Assert
    // 1) subject and description are emptied
    // 2) Show a message (after waiting for axios promise to finish)
    // 3) Emit close event to allow parent component (dialog) to be closed
  })

  let contains = (selector) => {
    expect(wrp.contains(selector)).toBe(true)
  }

  let see = (text, selector) => {
    let wrap = selector ? wrp.find(selector) : wrp
    expect(wrap.html()).toContain(text)
  }

  let type = (selector, text) => {
    let node = wrp.find(selector)
    node.element.value = text
    node.trigger('input')
  }

  let click = selector => {
    wrp.find(selector).trigger('click')
  }

})
