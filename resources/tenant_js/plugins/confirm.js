const confirm = (message = 'Aquesta acció no es pot desfer.', title = 'Esteu segurs?', confirmText = 'Confirmar') => {

}

export default {
  install (Vue, options) {
    Vue.prototype.$confirm = confirm
  }
}
