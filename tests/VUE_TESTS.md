# Steps to create Vue tests (tests/Vue folder)

```
vue create vue
mv vue Vue
```

Select:
- Manually select features
- Babel/Eslint/Unit Testing
- Eslint standard confing
- Lint on save
- Mocha + chai
- Dedicated config files
- No

Create **vue.config.js** file in tests/Vue folder:

```javascript
const path = require('path')
module.exports = {
  chainWebpack: config => {
    config.module
      .rule('eslint')
      .use('eslint-loader')
      .tap(options => {
        options.configFile = path.resolve(__dirname, '.eslintrc.js')
        return options
      })
  },
  css: {
    loaderOptions: {
      postcss: {
        config: {
          path: __dirname
        }
      }
    }
  }
}
```

Modify **tests/Vue/pakage.json** test:unit script to :

```
    "test:unit": "vue-cli-service test:unit --require tests/unit/setup.js"
```

Create file setup.js with:

```javascript
global.axios = require('axios') // Node global
window.axios = require('axios') // Browser
```

# Vuex


# Troubleshooting

## VUE WARN "TypeError: Cannot read property 'getters' of undefined"

Problem related with Vuex Store. Vuex has to be mocked/stubbed. See example:

```javascript
import { expect } from 'chai'
import { shallowMount, createLocalVue } from '@vue/test-utils'
import SnackBarComponent from '../../../../../resources/tenant_js/components/ui/SnackBarComponent'
import Vuex from 'vuex'
import sinon from 'sinon'

const localVue = createLocalVue()
localVue.use(Vuex)
// Vue.use(Vuex)

describe('SnackBarComponent.vue', () => {
  let actions
  let getters
  let store

  beforeEach(() => {
    getters = {
      snackbarTimeout: sinon.stub(),
      snackbarColor: sinon.stub(),
      snackbarShow: sinon.stub(),
      snackbarText: sinon.stub(),
      snackbarSubtext: sinon.stub()
    }
    actions = {
      actionClick: sinon.stub(),
      actionInput: sinon.stub()
    }
    store = new Vuex.Store({
      state: {},
      actions,
      getters
    })
  })

  it.only('todo', () => {
    const wrapper = shallowMount(SnackBarComponent, { store, localVue })
    expect(wrapper.text()).to.include('todo')
  })
})
```

NOTE: We use sinon to make stubs
- https://eddyerburgh.me/mock-vuex-in-vue-unit-tests
- https://vue-test-utils.vuejs.org/guides/using-with-vuex.html

# [vuex] unknown getter: snackbarTimeout

Vuex related. Normalment hem fet mock de la store i no em mockejat el getter/action/mutation que indica l'error. Cal fer l'stub del mètode:

```javascript
describe('SnackBarComponent.vue', () => {
  let getters
  let store

  beforeEach(() => {
    getters = {
      snackbarColor: sinon.stub(),
      snackbarShow: sinon.stub(),
      snackbarText: sinon.stub(),
      snackbarSubtext: sinon.stub()
    }
    store = new Vuex.Store({
      state: {},
      getters
    })
  })

  it.only('todo', () => {
    const wrapper = shallowMount(SnackBarComponent, { store, localVue })
    expect(wrapper.text()).to.include('todo')
  })
})
```

## Unknown custom element: <v-snackbar> - did you register the component correctly? For recursive components, make sure to provide the "name" option.

Vuetify is not configured/installed in tests

```javascript
import Vuetify from 'vuetify'
...

const localVue = createLocalVue()
localVue.use(Vuetify)
// Vue.use(Vuetify)

  it.only('todo', () => {
    const wrapper = shallowMount(SnackBarComponent, { localVue })
    expect(wrapper.text()).to.include('todo')
  })
```


## [Vuetify] Multiple instances of Vue detected If you're seeing "$attrs is readonly", it's caused by this

- See https://github.com/vuetifyjs/vuetify/issues/4068
   
