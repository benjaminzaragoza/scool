/* eslint-disable no-unused-expressions */
import Confirm from '../../../../../resources/tenant_js/plugins/confirm'
import { expect } from 'chai'
import { createLocalVue } from '@vue/test-utils'

describe('confirm.js', () => {
  let localVue
  beforeEach(() => {
    localVue = createLocalVue()
    localVue.use(Confirm)
  })

  it('confirm_function', () => {
    expect(localVue.prototype.$confirm).to.be.a('function')
  })
})
