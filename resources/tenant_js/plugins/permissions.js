const disappear = (el, modifiers) => {
  const hidden = modifiers && modifiers.hidden
  const disabled = modifiers && modifiers.disabled
  if (hidden) {
    el.firstElementChild.style.display = 'none'
    return true
  }
  if (disabled) {
    el.firstElementChild.disabled = true
    return true
  }
  el.innerHTML = ''
}

const can = (permission, resource = null) => {
  const user = window.Laravel && window.Laravel.user
  const userPermissions = window.Laravel && window.Laravel.user && window.Laravel.user.permissions

  if (resource instanceof Object) {
    if (user.id === resource.user_id) {
      return true
    }
  }
  if (userPermissions) {
    if (userPermissions.indexOf(permission) === -1) return false
    return true
  } else return false
}

export default {
  install (Vue, options) {
    // <delete-task-button v-can:delete="task"></delete-task-icon>
    // <delete-task-button v-can="delete.task"></delete-task-icon>
    // <delete-task-button v-can.disabled="delete.task"></delete-task-icon>
    // <delete-task-button v-can.hidden="delete.task"></delete-task-icon>

    Vue.directive('can', {
      bind (el, binding, vnode, oldVnode) {
        const action = binding.arg
        const resource = binding.value
        let permission

        if (resource instanceof Object) permission = binding.expression + '.' + action
        else permission = binding.expression
        if (!can(permission, resource)) disappear(el, binding.modifiers)
      }
    })
    // If authorID id is equal to current userId permission is always granted
    Vue.prototype.$can = can
  }
}
