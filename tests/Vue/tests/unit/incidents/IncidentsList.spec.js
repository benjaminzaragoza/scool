/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { shallowMount, mount } from '@vue/test-utils'
import IncidentsListComponent from '../../../../../resources/tenant_js/components/incidents/IncidentsListComponent'
import Vue from 'vue'
// import Vuex from 'vuex'
import Vuetify from 'vuetify'
// import sinon from 'sinon'

// Vue.use(Vuex)
Vue.use(Vuetify)

describe.only('IncidentsListComponent.vue', () => {
  // let getters
  // let actions
  // let store

  beforeEach(() => {
    // actions = {
    //   SET_SNACKBAR_SHOW: sinon.stub()
    // }
    // getters = {
    //   snackbarTimeout: sinon.stub(),
    //   snackbarColor: sinon.stub(),
    //   snackbarShow: sinon.stub(),
    //   snackbarText: () => { return 'TEXT PRINCIPAL' },
    //   snackbarSubtext: () => { return 'TEXT SECUNDARI' }
    // }
    // store = new Vuex.Store({
    //   state: {},
    //   getters,
    //   actions
    // })
  })

  it.only('shows_tasks', () => {
    const wrapper = mount(IncidentsListComponent, {
      propsData: {
        incidents: [
          {
            id: 1,
            username: 'Pepe Pardo Jeans',
            subject: 'No funciona PC1 Aula 30',
            description: 'Bla bla bla'
          },
          {
            id: 2,
            username: 'Pepa Parda Jeans',
            subject: 'No funciona PC2 Aula 31',
            description: 'JO JO JO'
          },
          {
            id: 3,
            username: 'CArles puigdemont',
            subject: 'No funciona PC1 Aula 32',
            description: 'HEY HEY HEY'
          }
        ]
      }
    })
    console.log('TEXT:')
    console.log(wrapper.text())
    console.log('HTML:')
    console.log(wrapper.html())

    expect(wrapper.text()).to.contains('Pepe Pardo Jeans')

    expect(wrapper.text()).to.not.contains('No hi han dades disponibles')
  })

  it('shows_no_data_available_when_no_incidents_are_provided', () => {
    const wrapper = mount(IncidentsListComponent)
    expect(wrapper.text()).contains('No hi han dades disponibles')
  })

  it('shows_no_data_available_when_no_incidents_are_provided_2', () => {
    const wrapper = mount(IncidentsListComponent, {
      propsData: {
        incidents: []
      }
    })
    expect(wrapper.text()).contains('No hi han dades disponibles')
  })

  // TODO
  it.skip('todo3', () => {
    const wrapper = shallowMount(IncidentsListComponent)
    expect(wrapper.text()).contains('todo1')
  })
})
