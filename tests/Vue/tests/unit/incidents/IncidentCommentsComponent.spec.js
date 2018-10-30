import { expect } from 'chai'
import { shallowMount } from '@vue/test-utils'
import IncidentCommentsComponent from '../../../../../resources/tenant_js/components/incidents/IncidentCommentsComponent'

describe.only('IncidentCommentsComponent.vue', () => {
  it('todo', () => {
    const wrapper = shallowMount(IncidentCommentsComponent)
    expect(wrapper.text()).to.include('')
  })
})
