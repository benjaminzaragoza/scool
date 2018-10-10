import { mount, config } from '@vue/test-utils'
import IncidentsListComponent from '../../../../../resources/assets/tenant_js/components/incidents/IncidentsListComponent.vue'
import Vuetify from 'vuetify'
import Vue from 'vue'
import { expect } from 'chai'

describe('IncidentsListComponent.vue', () => {
  let wrp

  beforeEach(() => {
    // Silent ALL Warnings: $listeners is readonly.
    // Silent ALL Warnings: [Vue-warn] warnings about props mutation are thrown when using vuetify with test-utils
    // https://github.com/vuejs/vue-test-utils/issues/534
    // https://github.com/vuejs/vue-test-utils/issues/532
    // It will be solved in Vue 2.6 https://github.com/vuejs/vue/pull/8240

    Vue.use(Vuetify)
    Vue.config.silent = true
    config.silent = true

    wrp = mount(IncidentsListComponent, {
      propsData: {
        incidents: [
          {
            id: 1,
            user_id: 1,
            username: 'Sergi Tur Badenas',
            subject: 'No funciona pc1 aula 20',
            description: 'bla bla bla'
          },
          {
            id: 2,
            user_id: 1,
            username: 'Sergi Tur Badenas',
            subject: 'No funciona pc2 aula 25',
            description: 'jor jor jor'
          },
          {
            id: 2,
            user_id: 2,
            username: 'Pepe Pardo Jeans',
            subject: 'No funciona projector Sala Mestral',
            description: 'hey hey hey'
          }
        ]
      },
      sync: false // DOES NOT SILENT ANYTHING TODO REMOVE
    })
  })

  it('check_default_state', () => {
    expect(wrp.vm.refreshing).to.equal(false)
    expect(wrp.vm.search).to.equal('')
  })

  it('renders_incidents', () => {
    expect(wrp.html()).contain('Sergi Tur Badenas')
    expect(wrp.html()).contain('Pepe Pardo Jeans')
    expect(wrp.html()).contain('No funciona pc1 aula 20')
    expect(wrp.html()).contain('No funciona pc2 aula 25')
    expect(wrp.html()).contain('No funciona projector Sala Mestral')
    expect(wrp.html()).contain('bla bla bla')
    expect(wrp.html()).contain('jor jor jor')
    expect(wrp.html()).contain('hey hey hey')
  })
})
