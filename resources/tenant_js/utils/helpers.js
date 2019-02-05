export default {
  formatDate (date) {
    if (!date) return null
    try {
      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    } catch (error) {
      return null
    }
  },
  unformatDate (date) {
    if (!date) return null
    try {
      const [day, month, year] = date.split('/')
      return `${year}-${month}-${day}`
    } catch (error) {
      return null
    }
  }
}
