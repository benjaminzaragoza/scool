/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { mount } from '@vue/test-utils'
import IncidentsListComponent from '../../../../../resources/tenant_js/components/incidents/IncidentsListComponent'
import Vue from 'vue'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import sinon from 'sinon'
import Snackbar from '../../../../../resources/tenant_js/plugins/snackbar'

Vue.use(Vuex)
Vue.use(Vuetify)
Vue.use(Snackbar)
Vue.config.silent = true

describe('IncidentsListComponent.vue', () => {
  let getters
  let emptyGetters
  let actions
  let mutations
  let store
  let emptyStore
  let sampleIncidents = [
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
      username: 'Carles Puigdemont',
      subject: 'No funciona PC1 Aula 32',
      description: 'HEY HEY HEY'
    }
  ]

  beforeEach(() => {
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
  })

  it('shows_tasks', () => {
    const wrapper = mount(IncidentsListComponent, {
      propsData: {
        incidents: sampleIncidents
      },
      store
    })
    expect(mutations.SET_INCIDENTS.calledOnce).to.be.true

    let incidentRow1 = wrapper.find('tr#incident_row_1')
    let incidentRow2 = wrapper.find('tr#incident_row_2')
    let incidentRow3 = wrapper.find('tr#incident_row_3')

    expect(incidentRow1.text()).to.contains('1')
    expect(incidentRow1.text()).to.contains('Pepe Pardo Jeans')
    expect(incidentRow1.text()).to.contains('No funciona PC1 Aula 30')
    expect(incidentRow1.text()).to.contains('Bla bla bla')

    expect(incidentRow2.text()).to.contains('2')
    expect(incidentRow2.text()).to.contains('Pepa Parda Jeans')
    expect(incidentRow2.text()).to.contains('No funciona PC2 Aula 31')
    expect(incidentRow2.text()).to.contains('JO JO JO')

    expect(incidentRow3.text()).to.contains('3')
    expect(incidentRow3.text()).to.contains('Carles Puigdemont')
    expect(incidentRow3.text()).to.contains('No funciona PC1 Aula 32')
    expect(incidentRow3.text()).to.contains('HEY HEY HEY')
  })

  it('gets_incidents_from_api_when_no_incidents_prop_is_given', () => {
    const wrapper = mount(IncidentsListComponent, { store })
    expect(actions.SET_INCIDENTS.calledOnce).to.be.true

    let incidentRow1 = wrapper.find('tr#incident_row_1')
    let incidentRow2 = wrapper.find('tr#incident_row_2')
    let incidentRow3 = wrapper.find('tr#incident_row_3')

    expect(incidentRow1.text()).to.contains('1')
    expect(incidentRow1.text()).to.contains('Pepe Pardo Jeans')
    expect(incidentRow1.text()).to.contains('No funciona PC1 Aula 30')
    expect(incidentRow1.text()).to.contains('Bla bla bla')

    expect(incidentRow2.text()).to.contains('2')
    expect(incidentRow2.text()).to.contains('Pepa Parda Jeans')
    expect(incidentRow2.text()).to.contains('No funciona PC2 Aula 31')
    expect(incidentRow2.text()).to.contains('JO JO JO')

    expect(incidentRow3.text()).to.contains('3')
    expect(incidentRow3.text()).to.contains('Carles Puigdemont')
    expect(incidentRow3.text()).to.contains('No funciona PC1 Aula 32')
    expect(incidentRow3.text()).to.contains('HEY HEY HEY')
  })

  it('watch_for_changes_in_incidents_prop', () => {
    mount(IncidentsListComponent, { store })
    expect(actions.SET_INCIDENTS.calledOnce).to.be.true
  })

  it('shows_no_data_available_when_no_incidents_are_provided', () => {
    store.getters = {
      incidents: function () {
        return []
      }
    }
    const wrapper = mount(IncidentsListComponent, {
      propsData: {
        incidents: []
      },
      store: emptyStore
    })
    expect(mutations.SET_INCIDENTS.calledOnce).to.be.true
    expect(wrapper.text()).contains('No hi han dades disponibles')
  })

  it('refresh_incidents', (done) => {
    let showMessage = sinon.spy()

    const wrapper = mount(IncidentsListComponent, {
      mocks: {
        $snackbar: {
          showMessage
        }
      },
      store
    })
    wrapper.find('#incidents_refresh_button').trigger('click')
    expect(actions.SET_INCIDENTS.calledOnce).to.be.true

    wrapper.vm.$nextTick(() => {
      // expect(wrapper.vm.$snackbar.showMessage.called).to.be.true
      expect(showMessage.called).to.be.true
      done()
    })
  })
})
