
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('login-button', require('./components/LoginButtonComponent.vue'));
Vue.component('register-button', require('./components/RegisterButtonComponent.vue'));
Vue.component('remember-password', require('./components/RememberPasswordComponent.vue'));
Vue.component('reset-password', require('./components/ResetPasswordComponent.vue'));
Vue.component('snackbar', require('./components/SnackBarComponent.vue'));
Vue.component('gravatar', require('./components/GravatarComponent.vue'));
Vue.component('users-dashboard', require('./components/users/UsersDashboardComponent.vue'));
Vue.component('users-list', require('./components/users/UsersListComponent.vue'));
Vue.component('user-add', require('./components/users/UserAddComponent.vue'));

Vue.component('jobs-list', require('./components/jobs/JobsListComponent.vue'));
Vue.component('job-add', require('./components/jobs/JobAddComponent.vue'));
Vue.component('jobs-list-by-family', require('./components/jobs/JobsListByFamilyComponent.vue'));
Vue.component('jobs-list-by-specialty', require('./components/jobs/JobsListBySpecialtyComponent.vue'));
Vue.component('pending-teacher-form', require('./components/teachers/PendingTeacherForm.vue'));
Vue.component('pending-teacher-add-warnings', require('./components/teachers/PendingTeacherAddWarningsComponent.vue'));
Vue.component('pending-teachers', require('./components/teachers/PendingTeachersComponent.vue'));

Vue.component('teachers', require('./components/teachers/TeachersComponent.vue'));
Vue.component('teacher-add', require('./components/teachers/TeacherAddComponent.vue'));

Vue.component('teachers-photos', require('./components/teachers/TeachersPhotosComponent.vue'));

Vue.component('impersonate-user', require('./components/admin/ImpersonateUserComponent.vue'));

Vue.component('logout-button', require('./components/auth/LogoutButtonComponent.vue'));
Vue.component('teacher-profile', require('./components/teachers/TeacherProfileComponent.vue'));

Vue.component('teacher-profile', require('./components/teachers/TeacherProfileComponent.vue'));

Vue.component('show-teacher-icon', require('./components/teachers/ShowTeacherIconComponent.vue'));

Vue.component('audit-log', require('./components/auditlog/AuditLogComponent.vue'));

Vue.component('jobs-sheet', require('./components/jobs/JobsSheetComponent.vue'));
Vue.component('jobs-sheet-holder', require('./components/jobs/JobsSheetHoldersComponent.vue'));

Vue.component('donut', require('./components/charts/DonutComponent.vue'));
Vue.component('bar', require('./components/charts/BarComponent.vue'));

// Lessons
Vue.component('lessons-manager', require('./components/lessons/LessonsManagerComponent.vue'));

// Docs/media
Vue.component('model-docs', require('./components/docs/ModelDocsComponent.vue'));

// People/Personal data (person)
Vue.component('people', require('./components/people/PeopleComponent.vue'));

// Google groups
Vue.component('google-groups', require('./components/google/groups/GoogleGroupsComponent.vue'));
Vue.component('google-group-add', require('./components/google/groups/GoogleGroupAddComponent.vue'));

// Google users
Vue.component('google-users', require('./components/google/users/GoogleUsersComponent.vue'));
Vue.component('google-user-add', require('./components/google/users/GoogleUserAddComponent.vue'));

// Ldap users
Vue.component('ldap-users', require('./components/ldap/users/LdapUsersComponent.vue'));
Vue.component('ldap-user-add', require('./components/ldap/users/LdapUserAddComponent.vue'));

// Incidents
Vue.component('incidents-list', require('./components/incidents/IncidentsListComponent.vue'));
Vue.component('incident-add', require('./components/incidents/IncidentAddComponent.vue'));

//UI
Vue.component('floating-add', require('./components/ui/FloatingAddComponent'));

window.Vuetify = require('vuetify');

import ca from 'vuetify/src/locale/ca.ts'

Vue.use(Vuetify,{
  lang: {
    locales: { ca },
    current: 'ca'
  }
})

import FullCalendar from 'vue-full-calendar'
Vue.use(FullCalendar)

import store from './store'
import * as actions from './store/action-types'
import * as mutations from './store/mutation-types'

import { mapGetters } from 'vuex'
import withSnackbar from './components/mixins/withSnackbar'

if (window.user) {
  store.commit(mutations.USER,  user)
  store.commit(mutations.LOGGED, true)
}

const app = new Vue({
  el: '#app',
  store,
  mixins: [ withSnackbar ],
  data: () => ({
    drawer: null,
    drawerRight: false,
    editingUser: false,
    changingPassword: false,
    confirmingEmail: false,
    updatingUser: false,
    items: window.scool_menu
  }),
  computed: {
    ...mapGetters({
      user: 'user'
    })
  },
  methods: {
    editUser () {
      this.editingUser = true
      this.$nextTick(this.$refs.email.focus)
    },
    updateUser () {
      this.updatingUser = true
      this.$store.dispatch(actions.UPDATE_USER, this.user).then(response => {
        this.showMessage('User modified ok!')
      }).catch(error => {
        console.dir(error)
        this.showError(error)
      }).then(() => {
        this.editingUser = false
        this.updatingUser = false
      })
    },
    updateEmail (email) {
      this.$store.commit(mutations.USER, {...this.user, email})
    },
    updateName (name) {
      this.$store.commit(mutations.USER, {...this.user, name})
    },
    toogleRightDrawer () {
      this.drawerRight = !this.drawerRight
    },
    checkRoles (item) {
      if (item.role) {
        return this.$store.getters.roles.find(function (role) {
          return role == item.role // eslint-disable-line
        })
      }
      return true
    },
    menuItemSelected (item) {
      if (item.href) {
        if (item.new) {
          window.open(item.href)
        } else {
          window.location.href = item.href
        }
      }
    },
    changePassword () {
      this.changingPassword = true
      this.$store.dispatch(actions.REMEMBER_PASSWORD, this.user.email).then(response => {
        this.showMessage(`Correu electrònic enviat per canviar la paraula de pas`)
      }).catch(error => {
        console.dir(error)
        this.showError(error)
      }).then(() => {
        this.changingPassword = false
      })
    },
    confirmEmail () {
      this.confirmingEmail = true
      this.$store.dispatch(actions.CONFIRM_EMAIL).then(response => {
        this.showMessage(`Correu electrònic enviat per tal de confirmar el email`)
      }).catch(error => {
        console.dir(error)
        this.showError(error)
      }).then(() => {
        this.confirmingEmail = false
      })
    }
  },
  created () {
    this.isEmailVerified = window.user.email_verified_at
  }
})
