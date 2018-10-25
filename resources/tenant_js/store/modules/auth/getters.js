export default {
  logged: state => state.logged,
  token: state => state.token,
  user: state => state.user,
  userRoles: state => state.user ? state.user.roles : []
}
