# Laravel (laravel mix) i Vue

# Requeriments
```
yarn add --dev vue-test-utils mocha mocha-webpack jsdom jsdom-global expect moxios
```
# Vuetify

Cal definir que el Vue utilitzi Vuetify:

```javascript
import { mount, createLocalVue } from '@vue/test-utils';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';
import Foo from '../src/Foo.vue';

describe('Foo', function () {
  let wrp;

  const routes = [
    { path: '/items/:item_id/edit', name: 'item-edit' }
  ];

  const router = new VueRouter({ routes });

  beforeEach(() => {

    const localVue = createLocalVue();
    localVue.use(VueRouter);
    localVue.use(Vuetify);
    

    wrp = mount(Foo, {
      localVue: localVue,
      router,
    });
  });

  // `it' and `expect's ready to go now.
});
```

# Recursos
- https://nick-basile.com/blog/post/testing-a-vuejs-and-laravel-todo-app
- https://nick-basile.com/blog/post/testing-a-vuejs-and-laravel-todo-app
- https://laracasts.com/series/testing-vue

# Vue loader

yarn add vue-loader
https://vue-loader.vuejs.org/migrating.html#importing-sfcs-from-dependenciesb