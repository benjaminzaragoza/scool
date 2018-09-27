import { shallowMount, mount, createLocalVue } from '@vue/test-utils'
import BirthDateComponent from '../../../resources/assets/tenant_js/components/ui/BirthDateInputComponent.vue'
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

    it('todo', () => {
        // const items = ['', '']
        // const wrapper = shallowMount(List, {
        //     propsData: { items }
        // })
        // expect(wrapper.findAll('li')).toHaveLength(items.length)
        // const msg = 'Hello'
        // console.log(wrp.element)
        // console.log(wrp.html())
        // expect(wrp.contains('div')).toBe(/true)


        // console.log(wrp.vm)
        expect(wrp.html()).toContain('AAA')
    })
})
