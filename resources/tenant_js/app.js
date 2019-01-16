
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import ca from './i18n/ca'
import FullCalendar from 'vue-full-calendar'
import store from './store'
import * as mutations from './store/mutation-types'
import snackbar from './plugins/snackbar'
import permissions from './plugins/permissions'
import confirm from './plugins/confirm/index.js'

import AppComponent from './components/App.vue'
import Vue from 'vue'
import Vuetify from 'vuetify'
import VueTimeago from 'vue-timeago'
import './bootstrap'
import TreeView from 'vue-json-tree-view'

window.Vue = Vue
window.Vue.use(snackbar)
window.Vue.use(permissions)
window.Vue.use(confirm)
window.Vue.use(TreeView)
window.Vue.use(VueTimeago, {
  locale: 'ca', // Default locale
  locales: {
    'ca': require('date-fns/locale/ca')
  }
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.Vue.component('login-button', require('./components/LoginButtonComponent.vue'))
window.Vue.component('register-button', require('./components/RegisterButtonComponent.vue'))
window.Vue.component('remember-password', require('./components/RememberPasswordComponent.vue'))
window.Vue.component('reset-password', require('./components/ResetPasswordComponent.vue'))
window.Vue.component('snackbar', require('./components/ui/SnackBarComponent.vue'))
window.Vue.component('gravatar', require('./components/GravatarComponent.vue'))
window.Vue.component('users-dashboard', require('./components/users/UsersDashboardComponent.vue'))
window.Vue.component('users-list', require('./components/users/UsersListComponent.vue'))
window.Vue.component('user-add', require('./components/users/UserAddComponent.vue'))

window.Vue.component('jobs-list', require('./components/jobs/JobsListComponent.vue'))
window.Vue.component('job-add', require('./components/jobs/JobAddComponent.vue'))
window.Vue.component('jobs-list-by-family', require('./components/jobs/JobsListByFamilyComponent.vue'))
window.Vue.component('jobs-list-by-specialty', require('./components/jobs/JobsListBySpecialtyComponent.vue'))
window.Vue.component('pending-teacher-form', require('./components/teachers/PendingTeacherForm.vue'))
window.Vue.component('pending-teacher-add-warnings', require('./components/teachers/PendingTeacherAddWarningsComponent.vue'))
window.Vue.component('pending-teachers', require('./components/teachers/PendingTeachersComponent.vue'))

window.Vue.component('teachers', require('./components/teachers/TeachersComponent.vue'))
window.Vue.component('teacher-add', require('./components/teachers/TeacherAddComponent.vue'))

window.Vue.component('teachers-photos', require('./components/teachers/TeachersPhotosComponent.vue'))

window.Vue.component('impersonate-user', require('./components/admin/ImpersonateUserComponent.vue'))

window.Vue.component('logout-button', require('./components/auth/LogoutButtonComponent.vue'))
window.Vue.component('teacher-profile', require('./components/teachers/TeacherProfileComponent.vue'))

window.Vue.component('teacher-profile', require('./components/teachers/TeacherProfileComponent.vue'))

window.Vue.component('show-teacher-icon', require('./components/teachers/ShowTeacherIconComponent.vue'))

window.Vue.component('audit-log', require('./components/auditlog/AuditLogComponent.vue'))

window.Vue.component('jobs-sheet', require('./components/jobs/JobsSheetComponent.vue'))
window.Vue.component('jobs-sheet-holder', require('./components/jobs/JobsSheetHoldersComponent.vue'))

window.Vue.component('donut', require('./components/charts/DonutComponent.vue'))
window.Vue.component('bar', require('./components/charts/BarComponent.vue'))

// Lessons
window.Vue.component('lessons-manager', require('./components/lessons/LessonsManagerComponent.vue'))

// Docs/media
window.Vue.component('model-docs', require('./components/docs/ModelDocsComponent.vue'))

// People/Personal data (person)
window.Vue.component('people', require('./components/people/PeopleComponent.vue'))

// Google groups
window.Vue.component('google-groups', require('./components/google/groups/GoogleGroupsComponent.vue'))
window.Vue.component('google-group-add', require('./components/google/groups/GoogleGroupAddComponent.vue'))

// Google users
window.Vue.component('google-users', require('./components/google/users/GoogleUsersComponent.vue'))
window.Vue.component('google-user-add', require('./components/google/users/GoogleUserAddComponent.vue'))

// Ldap users
window.Vue.component('ldap-users', require('./components/ldap/users/LdapUsersComponent.vue'))
window.Vue.component('ldap-user-add', require('./components/ldap/users/LdapUserAddComponent.vue'))

// Moodle users
window.Vue.component('moodle-users', require('./components/moodle/users/MoodleUsersComponent.vue'))

// Incidents
window.Vue.component('incidents', require('./components/incidents/IncidentsComponent.vue'))
window.Vue.component('incidents-list', require('./components/incidents/IncidentsListComponent.vue'))
window.Vue.component('incident-add', require('./components/incidents/IncidentAddComponent.vue'))

// Changelog
window.Vue.component('changelog', require('./components/changelog/ChangelogComponent.vue'))

// UI
window.Vue.component('floating-add', require('./components/ui/FloatingAddComponent'))

// GIT
window.Vue.component('git-info', require('./components/git/GitInfoComponent'))

// Curriculum
window.Vue.component('curriculum', require('./components/curriculum/CurriculumComponent'))
window.Vue.component('curriculum-public', require('./components/curriculum/CurriculumPublicComponent'))
window.Vue.component('curriculum-study-public', require('./components/curriculum/CurriculumStudyPublic'))
window.Vue.component('subjects', require('./components/curriculum/subjects/SubjectsComponent'))
window.Vue.component('subject-groups', require('./components/curriculum/subjectGroups/SubjectGroupsComponent'))

// Positions
window.Vue.component('positions', require('./components/positions/PositionsComponent'))
window.Vue.component('dashboard-positions', require('./components/positions/DashboardPositions'))

//
window.Vue.component('material-stats-card', require('./components/ui/MaterialStatsCard'))
window.Vue.component('material-card', require('./components/ui/MaterialCard'))
window.Vue.component('helper-offset', require('./components/helper/Offset'))

window.Vuetify = Vuetify

window.Vue.use(window.Vuetify, {
  lang: {
    locales: { ca },
    current: 'ca'
  },
  theme: {
    primary: {
      base: '#2680C2',
      lighten1: '#4098D7',
      lighten2: '#62B0E8',
      lighten3: '#84C5F4',
      lighten4: '#B6E0FE',
      lighten5: '#DCEEFB',
      darken1: '#186FAF',
      darken2: '#0F609B',
      darken3: '#0A558C',
      darken4: '#003E6B'
    },
    secondary: {
      base: '#2CB1BC',
      lighten1: '#38BEC9',
      lighten2: '#54D1DB',
      lighten3: '#87EAF2',
      lighten4: '#BEF8FD',
      lighten5: '#E0FCFF',
      darken1: '#14919B',
      darken2: '#0E7C86',
      darken3: '#0A6C74',
      darken4: '#044E54'
    },
    accent: {
      base: '#F0B429',
      lighten1: '#F7C948',
      lighten2: '#FADB5F',
      lighten3: '#FCE588',
      lighten4: '#FFF3C4',
      lighten5: '#FFFBEA',
      darken1: '#DE911D',
      darken2: '#CB6E17',
      darken3: '#B44D12',
      darken4: '#8D2B0B'
    },
    error: {
      base: '#BA2525',
      lighten1: '#D64545',
      lighten2: '#E66A6A',
      lighten3: '#F29B9B',
      lighten4: '#FACDCD',
      lighten5: '#FFEEEE',
      darken1: '#A61B1B',
      darken2: '#911111',
      darken3: '#780A0A',
      darken4: '#610404'
    },
    // Taken from palete 3
    success: {
      base: '#27AB83',
      lighten1: '#3EBD93',
      lighten2: '#65D6AD',
      lighten3: '#8EEDC7',
      lighten4: '#C6F7E2',
      lighten5: '#EFFCF6',
      darken1: '#199473',
      darken2: '#147D64',
      darken3: '#0C6B58',
      darken4: '#014D40'
    },
    grey: {
      base: '#627D98',
      lighten1: '#829AB1',
      lighten2: '#9FB3C8',
      lighten3: '#BCCCDC',
      lighten4: '#D9E2EC',
      lighten5: '#F0F4F8',
      darken1: '#486581',
      darken2: '#334E68',
      darken3: '#243B53',
      darken4: '#102A43'
    }
  }
})
window.Vue.use(FullCalendar)

if (window.user) {
  store.commit(mutations.USER, window.user)
  store.commit(mutations.LOGGED, true)
}

window.Vue.directive('focus', {
  inserted: function (el) {
    el.focus()
  }
})

// eslint-disable-next-line no-unused-vars
const app = new window.Vue(AppComponent)
