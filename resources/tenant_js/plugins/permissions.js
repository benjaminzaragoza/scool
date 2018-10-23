export default {
  install (Vue, options) {
    // "can" directive accept string with single permission or object containing "permission", and "authorId"
    // https://vuejs.org/v2/guide/custom-directive.html#Directive-Hook-Arguments
    // <delete-task-button v-can:delete="task"></delete-task-icon>

    Vue.directive('can', {
      bind (el, binding, vnode, oldVnode) {
        // https://vuejs.org/v2/guide/custom-directive.html#Directive-Hook-Arguments
        // el -> used to directly manipulate DOM -> Display none: el.style.display = 'none' (v-show)
        // v-if ->       el = null
        // opció disabled: el.disabled = true -> Bottons per exemple que es mostrin però no es poden fer click en ells

        // exemple <delete-task-button v-can:delete="task" :task="task"></delete-task-icon>
        // delete -> argument -> binding.arg
        // task -> value expression -> binding.value
        // modifiers: An object containing modifiers, if any. For example in v-my-directive.foo.bar, the modifiers object would be { foo: true, bar: true }.
        //vnode: https://github.com/vuejs/vue/blob/dev/src/core/vdom/vnode.js
        // TODO --> accedir a una variable amb el nom task dins del component delete-task-button:  resource/model = vnode.context[binding.value]
      // <incident-close :incident="incident"></incident-close>

        let permission
        if (binding.value instanceof Object) {
          if (binding.value.authorId === window.Laravel.userId) {
            return true
          }
          permission = binding.value.permission
        } else {
          permission = binding.value
        }
        if (window.Laravel.permissions.indexOf(permission) === -1) {
          el.style.display = 'none'
        }
      }
    })
    // If authorID id is equal to current userId permission is always granted
    Vue.prototype.$can = function (permission, authorId = false) {
      if (window.Laravel.userId === authorId) {
        return true
      }
      if (window.Laravel.permissions.indexOf(permission) !== -1) {
        return true
      }
    }
  }
}
