// setup JSDOM
require('jsdom-global')()

// TODO: https://github.com/vuejs/vue-test-utils/issues/936
window.Date = Date

// make expect available globally
global.expect = require('expect')
