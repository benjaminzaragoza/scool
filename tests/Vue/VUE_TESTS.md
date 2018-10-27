# Steps to create Vue tests (tests/Vue folder)

```
vue create vue
mv vue Vue
```

Editeu el nom del "paquet" al fitxer package.json:

```
{
  "name": "vue-tests",
```

Install dependencies:

```
npm install --save-dev vue vuex vuetify sinon
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

Modify **tests/Vue/package.json** test:unit script to :

```
    "test:unit": "vue-cli-service test:unit --require tests/unit/setup.js"
```

Create file setup.js with:

```javascript
global.axios = require('axios') // Node global
window.axios = require('axios') // Browser
```

IMPORTANT!!!!!:

Carpeta tests/Vue ha de tenir la seva propia instal·lació local de paquets (sembla que no ho necessiti pq sinó ho té ho 
busca en les carpetes pare però aleshores hi ha errors: [Vuetify] Multiple instances of Vue detected)

```
npm install vuetify vuex sinon
npm install --save-dev moxios axios
```

IMPORTANT 2: DON'T USE localVue!!!!!!!!!!!!!!!

# Vuex


# Troubleshooting

## SyntaxError: The string did not match the expected pattern using Moxios/Aios


Cal tenir en compte que tant el projecte (arrel) com la carpeta de testos (Vue o tests/Vue) han de tenir els paquets instal·lats en local:

```
cd tests/Vue
npm install --save-dev moxios axios
``` 

Això és un problema que passa generalemnt amb treballar en objectes globals

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
   
Important que el projecte que utilitzem (tests/Vue) està dins d'un projecte Laravel amb el seu propi node_modules.

Assegureu-vos que el tests utilitza paquets locals i per tant que estan instal·lats al node_modules i package.json del paquet!!!!

```
cd /tests/Vue
npm install --save-dev vue vuex vuetify sinon
```   

## window.requestAnimationFrame is not a function

Put on setup.js:

```javascript
// https://github.com/vuejs/vue-test-utils/issues/974
global.requestAnimationFrame = cb => cb()
window.requestAnimationFrame = cb => cb()
```

## [Vue warn]: Error in getter for watcher "isDark": "TypeError: Cannot read property 'dark' of undefined"

Disable vue warns

## Cannot read property 't' of undefined when testing components with v-data-table #4861

Don't use localVue, instead use:

```javascript
import Vue from 'vue'
...
Vue.use(Vuetify)
```

https://github.com/vuetifyjs/vuetify/issues/4861

## [Vuetify] Unable to locate target [data-app]

Put on **setup.js**:

```javascript
// Avoid warning: [Vuetify] Unable to locate target [data-app]
const el = document.createElement('div')
el.setAttribute('data-app', true)
document.body.appendChild(el)
```
