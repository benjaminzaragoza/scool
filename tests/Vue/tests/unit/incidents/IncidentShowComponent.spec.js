/* eslint-disable no-unused-expressions */
import { expect } from 'chai'
import { shallowMount, mount, Wrapper } from '@vue/test-utils'
import IncidentShowComponent from '../../../../../resources/tenant_js/components/incidents/IncidentShowComponent'
import Vue from 'vue'
import Vuetify from 'vuetify'
import TestHelpers from '../helpers.js'
import sinon from 'sinon'

Vue.use(Vuetify)
Vue.config.silent = true

describe('IncidentShowComponent.vue', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
  })

  it('shows_a_vcard', () => {
    const wrapper = shallowMount(IncidentShowComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24",
          user: {
            hashid: 'Mx'
          }
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
          subject: "No funciona res a l'aula 24",
          comments: [],
          user: {
            hashid: 'Mx'
          }
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
          subject: "No funciona res a l'aula 24",
          user: {
            hashid: 'Mx'
          }
        }
      }
    })
    wrapper.click('#incident_1_show_close_button')
    wrapper.assertEmitted('close')
  })

  it('not_show_comments_list_when_no_comments', () => {
    const wrapper = mount(IncidentShowComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24",
          user: {
            hashid: 'Mx'
          }
        }
      }
    })
    wrapper.assertNotContains('#incident_1_comments')
  })

  it('not_show_comments_list_when_comments_are_void', () => {
    const wrapper = mount(IncidentShowComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24",
          user: {
            hashid: 'Mx'
          },
          comments: []
        }
      }
    })
    wrapper.assertNotContains('#incident_1_comments')
  })

  it('shows_add_comment', () => {
    const wrapper = mount(IncidentShowComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24",
          user: {
            hashid: 'Mx'
          }
        }
      }
    })
    wrapper.assertContains('#add_reply_button')
  })

  it('shows_comments', () => {
    let hasRole = sinon.spy()
    const wrapper = mount(IncidentShowComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24",
          comments: [
            {
              id: 1,
              body: 'Si us plau podeu aportar informació més detallada?',
              user_id: 1,
              user: {
                name: 'Carles Puigdemont'
              }
            },
            {
              id: 2,
              body: "NO s'encen el ordinador 1 de la fila 3",
              user_id: 2,
              user: {
                name: 'Pepe Pardo Jeans'
              }
            },
            {
              id: 3,
              body: 'Ok. Solucionat! Tanquem la incidència',
              user_id: 1,
              user: {
                name: 'Carles Puigdemont'
              }
            }
          ],
          user: {
            hashid: 'Mx'
          }
        }
      },
      mocks: {
        $hasRole: hasRole
      }
    })
    wrapper.assertContains('#incident_1_comments')
    const comment1 = wrapper.find('#incident_1_comment_1')
    comment1.seeText('Si us plau podeu aportar informació més detallada?')
    comment1.seeText('Carles Puigdemont')
    const comment2 = wrapper.find('#incident_1_comment_2')
    comment2.seeText("NO s'encen el ordinador 1 de la fila 3")
    comment2.seeText('Pepe Pardo Jeans')
    const comment3 = wrapper.find('#incident_1_comment_3')
    comment3.seeText('Ok. Solucionat! Tanquem la incidència')
    comment1.seeText('Carles Puigdemont')
    expect(hasRole.called).to.be.true
  })
})
