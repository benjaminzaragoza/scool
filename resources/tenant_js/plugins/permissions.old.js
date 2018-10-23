export default {
  install (Vue, options) {
    // "can" directive accept string with single permission or object containing "permission", and "authorId"
    // https://vuejs.org/v2/guide/custom-directive.html#Directive-Hook-Arguments

    Vue.directive('can', {
      bind (el, binding, vnode, oldVnode) {
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
