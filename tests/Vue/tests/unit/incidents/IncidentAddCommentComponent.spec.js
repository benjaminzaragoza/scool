import { shallowMount, mount, Wrapper } from '@vue/test-utils'
import IncidentAddCommentComponent from '../../../../../resources/tenant_js/components/incidents/IncidentAddCommentComponent'
import TestHelpers from '../helpers'
import { expect } from 'chai'

describe('IncidentAddCommentComponent', () => {
  beforeEach(() => {
    Object.assign(Wrapper.prototype, TestHelpers)
  })

  it('shows_a_vcard', () => {
    const wrapper = shallowMount(IncidentAddCommentComponent, {
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
    const wrapper = mount(IncidentAddCommentComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24",
          comments: []
        }
      }
    })
    expect(wrapper.find('#incident_1_add_comment_toolbar').text()).contains("No funciona res a l'aula 24")
  })

  it('emit_close_event_when_click_on_close_button', () => {
    const wrapper = mount(IncidentAddCommentComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24"
        }
      }
    })
    wrapper.click('#incident_1_add_comment_close_button')
    wrapper.assertEmitted('close')
  })

  it('not_show_comments_list_when_no_comments', () => {
    const wrapper = mount(IncidentAddCommentComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24"
        }
      }
    })
    wrapper.assertNotContains('#incident_1_comments')
  })

  it('not_show_comments_list_when_comments_are_void', () => {
    const wrapper = mount(IncidentAddCommentComponent, {
      propsData: {
        incident: {
          id: 1,
          subject: "No funciona res a l'aula 24",
          comments: []
        }
      }
    })
    wrapper.assertNotContains('#incident_1_comments')
  })
})
