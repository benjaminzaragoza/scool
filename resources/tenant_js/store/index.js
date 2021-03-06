import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth'
import snackbar from './modules/ui/snackbar'
import confirm from './modules/ui/confirm'
import users from './modules/users'
import roles from './modules/roles'
import permissions from './modules/permissions'
import googleUsers from './modules/google_users'
import staff from './modules/jobs'
import teachers from './modules/teachers'
import incidents from './modules/incidents'
import departments from './modules/departments'
import families from './modules/families'
import courses from './modules/courses'
import studies from './modules/studies'
import subjects from './modules/subjects'
import subjectGroups from './modules/subjectGroups'
import positions from './modules/positions'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
  modules: {
    auth,
    snackbar,
    confirm,
    users,
    roles,
    permissions,
    staff,
    teachers,
    googleUsers,
    incidents,
    departments,
    families,
    studies,
    courses,
    subjects,
    subjectGroups,
    positions
  },
  strict: debug
})
