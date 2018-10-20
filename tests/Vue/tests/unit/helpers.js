/* eslint-disable no-undef,no-unused-expressions */
import { expect } from 'chai'

export default {
  assertContains: function (selector) {
    expect(this.contains(selector)).to.be.true
  },
  seeHtml: function (text, selector) {
    let wrap = selector ? this.find(selector) : this
    expect(wrap.html()).contains(text)
  },
  seeText: function (text, selector) {
    let wrap = selector ? this.find(selector) : this
    expect(wrap.text()).contains(text)
  },
  assertEmitted: function (event) {
    expect(this.emitted()[event]).toBeTruthy()
  },
  assertEventContains: function (event, key, value) {
    expect(this.emitted()[event][key]).toBe(value)
  },
  type: function (selector, text) {
    let node = this.find(selector)
    node.element.value = text
    node.trigger('input')
  },
  click: function (selector) {
    this.find(selector).trigger('click')
  }
}
