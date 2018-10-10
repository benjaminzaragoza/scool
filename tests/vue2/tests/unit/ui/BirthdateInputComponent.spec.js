import { shallowMount, mount, createLocalVue } from '@vue/test-utils'
import BirthDateComponent from '../../../../../resources/assets/tenant_js/components/ui/BirthDateInputComponent.vue'
import Vuetify from 'vuetify'

describe('BirthdateInputComponent.vue', () => {
    let wrp

    beforeEach(() => {

        const localVue = createLocalVue()
        localVue.use(Vuetify)

        wrp = mount(BirthDateComponent, {
            localVue: localVue
        })
    })

    it('check_default_label', () => {
      expect(wrp.vm.$refs.text.label).toBe('Data de naixement')
    })

  it('can_change_label', () => {
    wrp.setProps({ label: 'prova' })
    expect(wrp.vm.$refs.text.label).toBe('prova')
  })
})
