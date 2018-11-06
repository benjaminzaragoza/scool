/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { mount, Wrapper } from '@vue/test-utils'
import IncidentsListComponent from '../../../../../resources/tenant_js/components/incidents/IncidentsListComponent'
import Vue from 'vue'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import sinon from 'sinon'
import Snackbar from '../../../../../resources/tenant_js/plugins/snackbar'
import TestHelpers from '../helpers.js'

Vue.use(Vuex)
Vue.use(Vuetify)
Vue.use(Snackbar)
Vue.config.silent = true

describe('IncidentsListComponent.vue', () => {
  let getters
  let emptyGetters
  let actions
  let actionsError
  let mutations
  let store
  let emptyStore
  let errorStore
  let sampleIncidents = [
    {
      id: 1,
      user_name: 'Pepe Pardo Jeans',
      user_id: 1,
      user_email: 'pepepardo@jeans.com',
      user: {
        hashid: 'MX'
      },
      subject: 'No funciona PC1 Aula 30',
      description: 'Bla bla bla',
      closed_at: null
    },
    {
      id: 2,
      user_name: 'Pepa Parda Jeans',
      user_id: 2,
      user_email: 'pepaparda@jeans.com',
      user: {
        hashid: 'RX'
      },
      subject: 'No funciona PC2 Aula 31',
      description: 'JO JO JO',
      closed_at: null
    },
    {
      id: 3,
      user_name: 'Carles Puigdemont',
      user_id: 3,
      user_email: 'carles@puigdemont.cat',
      user: {
        hashid: 'RX'
      },
      subject: 'No funciona PC1 Aula 32',
      description: 'HEY HEY HEY',
      closed_at: {},
      formatted_closed_at: '2018:12:08 14:23:24',
      formatted_closed_at_diff: '1 segon abans'
    }
  ]

  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
    getters = {
      incidents: function () {
        return sampleIncidents
      }
    }
    emptyGetters = {
      incidents: function () {
        return []
      }
    }
    let setIncidentsActionStub = sinon.stub()
    setIncidentsActionStub.returns({ data: sampleIncidents })
    actions = {
      SET_INCIDENTS: setIncidentsActionStub
    }
    let setIncidentsMutationStub = sinon.stub()
    mutations = {
      SET_INCIDENTS: setIncidentsMutationStub
    }
    store = new Vuex.Store({
      state: {},
      getters,
      actions,
      mutations
    })
    emptyStore = new Vuex.Store({
      state: {},
      emptyGetters,
      actions,
      mutations
    })
    let setIncidentsActionStubRejects = sinon.stub()
    setIncidentsActionStubRejects.rejects({})
    actionsError = {
      SET_INCIDENTS: setIncidentsActionStubRejects
    }
    errorStore = new Vuex.Store({
      state: {},
      emptyGetters,
      actions: actionsError,
      mutations
    })
  })

  it('shows_tasks', (done) => {
    let can = sinon.spy()
    let hasRole = sinon.spy()
    const wrapper = mount(IncidentsListComponent, {
      propsData: {
        incidents: sampleIncidents
      },
      store,
      sync: false, // https://github.com/vuejs/vue-test-utils/issues/829
      mocks: {
        $can: can,
        $hasRole: hasRole
      }
    })
    expect(mutations.SET_INCIDENTS.calledOnce).to.be.true

    setTimeout(() => {
      let incidentRow1 = wrapper.find('tr#incident_row_1')
      let incidentRow2 = wrapper.find('tr#incident_row_2')
      // let incidentRow3 = wrapper.find('tr#incident_row_3') -> not showed because is closed

      incidentRow1.seeText('1')
      incidentRow1.seeText('Pepe Pardo Jeans')
      incidentRow1.seeHtml('pepepardo@jeans.com')
      incidentRow1.seeText('No funciona PC1 Aula 30')
      incidentRow1.seeText('Bla bla bla')

      incidentRow2.seeText('2')
      incidentRow2.seeText('Pepa Parda Jeans')
      incidentRow2.seeHtml('pepaparda@jeans.com')
      incidentRow2.seeText('No funciona PC2 Aula 31')
      incidentRow2.seeText('JO JO JO')

      // incidentRow3.seeText('3')
      // incidentRow3.seeText('Carles Puigdemont')
      // incidentRow3.seeHtml('carles@puigdemont.cat')
      // incidentRow3.seeText('No funciona PC1 Aula 32')
      // incidentRow3.seeText('HEY HEY HEY')
      // incidentRow3.seeText('1 segon abans')
      done()
    },
    50)
  })

  it('gets_incidents_from_api_when_no_incidents_prop_is_given', (done) => {
    let can = sinon.spy()
    let hasRole = sinon.spy()
    const wrapper = mount(IncidentsListComponent, {
      store,
      sync: false,
      mocks: {
        $can: can,
        $hasRole: hasRole
      }
    })
    expect(actions.SET_INCIDENTS.calledOnce).to.be.true

    setTimeout(() => {
      let incidentRow1 = wrapper.find('tr#incident_row_1')
      let incidentRow2 = wrapper.find('tr#incident_row_2')
      // let incidentRow3 = wrapper.find('tr#incident_row_3')

      incidentRow1.seeText('1')
      incidentRow1.seeText('Pepe Pardo Jeans')
      incidentRow1.seeText('No funciona PC1 Aula 30')
      incidentRow1.seeText('Bla bla bla')

      incidentRow2.seeText('2')
      incidentRow2.seeText('Pepa Parda Jeans')
      incidentRow2.seeText('No funciona PC2 Aula 31')
      incidentRow2.seeText('JO JO JO')

      // incidentRow3.seeText('3')
      // incidentRow3.seeText('Carles Puigdemont')
      // incidentRow3.seeText('No funciona PC1 Aula 32')
      // incidentRow3.seeText('HEY HEY HEY')
      done()
    },
    50)
  })

  it('watch_for_changes_in_incidents_prop', () => {
    let can = sinon.spy()
    let hasRole = sinon.spy()
    mount(IncidentsListComponent, {
      store,
      sync: false,
      mocks: {
        $can: can,
        $hasRole: hasRole
      }
    })
    expect(actions.SET_INCIDENTS.calledOnce).to.be.true
  })

  it('shows_no_data_available_when_no_incidents_are_provided', () => {
    let can = sinon.spy()
    let hasRole = sinon.spy()
    store.getters = {
      incidents: function () {
        return []
      },
      sync: false
    }
    const wrapper = mount(IncidentsListComponent, {
      propsData: {
        incidents: []
      },
      store: emptyStore,
      mocks: {
        $can: can,
        $hasRole: hasRole
      }
    })
    expect(mutations.SET_INCIDENTS.calledOnce).to.be.true
    wrapper.seeText('No hi han dades disponibles')
  })

  it('refresh_incidents', (done) => {
    let showMessage = sinon.spy()

    const wrapper = mount(IncidentsListComponent, {
      mocks: {
        $snackbar: {
          showMessage
        }
      },
      store,
      sync: false
    })
    wrapper.click('#incidents_refresh_button')
    expect(actions.SET_INCIDENTS.calledOnce).to.be.true

    wrapper.vm.$nextTick(() => {
      // expect(wrapper.vm.$snackbar.showMessage.called).to.be.true
      expect(showMessage.called).to.be.true
      done()
    })
  })

  it('refresh_incidents_shows_error', (done) => {
    let showError = sinon.spy()

    const wrapper = mount(IncidentsListComponent, {
      mocks: {
        $snackbar: {
          showError
        }
      },
      store: errorStore,
      sync: false
    })
    wrapper.click('#incidents_refresh_button')
    expect(actionsError.SET_INCIDENTS.calledOnce).to.be.true

    setTimeout(() => {
      expect(showError.called).to.be.true
      done()
    },
    50)
    // DOES NOT WORK WITH NEXT TICK
    // wrapper.vm.$nextTick(() => {
    //   // expect(wrapper.vm.$snackbar.showError.called).to.be.true
    //   console.log('ARRIBA QUI 1')
    //   expect(showError.called).to.be.true
    //   console.log('ARRIBA QUI 2')
    //   done()
    // })
  })

  it('computes_open_and_closed_an_all_incidents', () => {
    const wrapper = mount(IncidentsListComponent, {
      propsData: {
        incidents: sampleIncidents
      },
      store,
      sync: false // https://github.com/vuejs/vue-test-utils/issues/829
    })
    expect(wrapper.vm.openIncidents.length).equals(2)
    expect(wrapper.vm.closedIncidents.length).equals(1)
    expect(wrapper.vm.dataIncidents.length).equals(3)
  })

  it('computes_creators', () => {
    let can = sinon.spy()
    let hasRole = sinon.spy()
    const wrapper = mount(IncidentsListComponent, {
      propsData: {
        incidents: sampleIncidents
      },
      store,
      sync: false, // https://github.com/vuejs/vue-test-utils/issues/829
      mocks: {
        $can: can,
        $hasRole: hasRole
      }
    })

    expect(wrapper.vm.creators[0].name).equals('Pepe Pardo Jeans')
    expect(wrapper.vm.creators[0].email).equals('pepepardo@jeans.com')
    expect(wrapper.vm.creators[0].hashid).equals('MX')
    expect(wrapper.vm.creators[1].name).equals('Pepa Parda Jeans')
    expect(wrapper.vm.creators[1].email).equals('pepaparda@jeans.com')
    expect(wrapper.vm.creators[1].hashid).equals('RX')
    expect(wrapper.vm.creators[2].name).equals('Carles Puigdemont')
    expect(wrapper.vm.creators[2].email).equals('carles@puigdemont.cat')
    expect(wrapper.vm.creators[2].hashid).equals('RX')

    expect(wrapper.vm.creators.length).equals(3)
  })
})
