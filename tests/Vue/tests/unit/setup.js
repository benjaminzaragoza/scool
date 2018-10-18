global.axios = require('axios') // Node global
window.axios = require('axios') // Browser

// global.Vue = require('vue') // Vue global
// global.Vue.config.silent = true

// Avoid warning: [Vuetify] Unable to locate target [data-app]
const el = document.createElement('div')
el.setAttribute('data-app', true)
document.body.appendChild(el)

// https://github.com/vuejs/vue-test-utils/issues/974
global.requestAnimationFrame = cb => cb()
window.requestAnimationFrame = cb => cb()
