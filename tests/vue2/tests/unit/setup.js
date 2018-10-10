/* eslint-disable no-unused-vars,no-undef */
// setup JSDOM
// https://github.com/vuejs/vue-cli/pull/2573
require('jsdom-global')(undefined, { pretendToBeVisual: true })

// make expect available globally
// global.expect = require('expect')
global.expect = require('chai')

// https://github.com/vuejs/vue-test-utils/issues/974
global.requestAnimationFrame = cb => cb()

// TODO: https://github.com/vuejs/vue-test-utils/issues/936
window.Date = Date

// Avoid warning: [Vuetify] Unable to locate target [data-app]
const el = document.createElement('div')
el.setAttribute('data-app', true)
document.body.appendChild(el)

// https://github.com/eddyerburgh/avoriaz/issues/77
// Avoid error: TypeError: Cannot read property 'split' of undefined
const { getComputedStyle } = window
window.getComputedStyle = function getComputedStyleStub (el) {
  return {
    ...getComputedStyle(el),
    transitionDelay: '',
    transitionDuration: '',
    animationDelay: '',
    animationDuration: ''
  }
}

global.axios = require('axios')

