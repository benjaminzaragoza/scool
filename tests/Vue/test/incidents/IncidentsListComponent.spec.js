import { mount } from '@vue/test-utils'
import IncidentsListComponent from '../../../../resources/assets/tenant_js/components/incidents/IncidentsListComponent.vue'
import Vuetify from 'vuetify'
import Vue from 'vue'

describe('IncidentsListComponent.vue', () => {
  let wrp

  beforeEach(() => {
    // Vue.use(VueRouter)
    Vue.use(Vuetify)

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
      }
    })
  })

  it('check_default_state', () => {
    expect(wrp.vm.refreshing).toBe(false)
    expect(wrp.vm.search).toBe('')
  })

  it('renders_incidents', () => {
    expect(wrp.html()).toContain('Sergi Tur Badenas')
    expect(wrp.html()).toContain('Pepe Pardo Jeans')
    expect(wrp.html()).toContain('No funciona pc1 aula 20')
    expect(wrp.html()).toContain('No funciona pc2 aula 25')
    expect(wrp.html()).toContain('No funciona projector Sala Mestral')
    expect(wrp.html()).toContain('bla bla bla')
    expect(wrp.html()).toContain('jor jor jor')
    expect(wrp.html()).toContain('hey hey hey')
  })
})
