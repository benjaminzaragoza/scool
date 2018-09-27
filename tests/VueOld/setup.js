require('jsdom-global')();

global.expect = require('expect')
global.axios = require('axios');
global.Vue = require('vue');
global.bus = new Vue();

const VueLoaderPlugin = require('vue-loader/lib/plugin')

module.exports = {
  // ...
  plugins: [
    new VueLoaderPlugin()
  ]
}