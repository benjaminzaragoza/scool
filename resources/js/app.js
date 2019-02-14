
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import store from './store'
import * as mutations from './store/mutation-types'
import * as actions from './store/action-types'
import './bootstrap'
import Vue from 'vue'
import TenantsComponent from './components/TenantsComponent.vue'
import Push from './components/PushComponent.vue'

window.Vue = Vue

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.Vue.component('tenants', TenantsComponent)
window.Vue.component('push', Push)

if (window.user) {
  store.commit(mutations.LOGGED_USER, window.user)
} else {
  store.dispatch(actions.LOGGED_USER)
}

// eslint-disable-next-line no-unused-vars
const app = new window.Vue({
  el: '#app',
  store
})
