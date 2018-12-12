/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import Echo from 'laravel-echo'

window._ = require('lodash')
window.axios = require('axios')

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

let userHeader = document.head.querySelector('meta[name="user"]')
window.user = null
if (userHeader) if (userHeader.content) window.user = JSON.parse(userHeader.content)

let scoolMenuHeader = document.head.querySelector('meta[name="scool_menu"]')
window.scool_menu = null
if (scoolMenuHeader) if (scoolMenuHeader.content) window.scool_menu = JSON.parse(scoolMenuHeader.content)

let tenantHeader = document.head.querySelector('meta[name="tenant"]')
window.tenant = null
if (tenantHeader) if (tenantHeader.content) window.tenant = JSON.parse(tenantHeader.content)

let gitHeader = document.head.querySelector('meta[name="git"]')
window.git = null
if (gitHeader) if (gitHeader.content) window.git = JSON.parse(gitHeader.content)

window.Pusher = require('pusher-js')

if (window.tenant) {
  if (window.tenant.pusher_app_key) {
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: window.tenant.pusher_app_key,
      wsHost: window.location.hostname,
      wsPort: 6001,
      wssPort: 6001,
      disableStats: true,
      encrypted: false
    })
  }
}
