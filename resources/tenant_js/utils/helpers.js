import moment from 'moment'
export default {
  validateDate (date) {
    return moment(date, 'YYYY-MM-DD', true).isValid()
  },
  formatDate (date, separator = '-') {
    if (!date) return null
    try {
      const [year, month, day] = date.split(separator)
      return `${day}${separator}${month}${separator}${year}`
    } catch (error) {
      return null
    }
  },
  unformatDate (date, separator = '-') {
    if (!date) return null
    try {
      const [day, month, year] = date.split(separator)
      return `${year}${separator}${month}${separator}${day}`
    } catch (error) {
      return null
    }
  },
  validateDNI (dni) {
    let numero, lt, letra
    let regexpdni = /^[XYZ]?\d{5,8}[A-Z]$/

    dni = dni.toUpperCase()

    if (regexpdni.test(dni) === true) {
      numero = dni.substr(0, dni.length - 1)
      numero = numero.replace('X', 0)
      numero = numero.replace('Y', 1)
      numero = numero.replace('Z', 2)
      lt = dni.substr(dni.length - 1, 1)
      numero = numero % 23
      letra = 'TRWAGMYFPDXBNJZSQVHLCKET'
      letra = letra.substring(numero, numero + 1)
      if (letra !== lt) return false
      else return true
    } else return false
  }
}
