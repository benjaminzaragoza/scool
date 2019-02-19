
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
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import 'typeface-roboto/index.css'
import 'typeface-montserrat/index.css'
import '@fortawesome/fontawesome-free/css/all.css'
import 'font-awesome/css/font-awesome.min.css'
import AppComponent from './components/App.vue'
import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import VueTimeago from 'vue-timeago'
import './bootstrap'
import TreeView from 'vue-json-tree-view'
import LoginButtonComponent from './components/LoginButtonComponent.vue'
import RegisterButtonComponent from './components/RegisterButtonComponent.vue'
import RememberPasswordComponent from './components/RememberPasswordComponent.vue'
import ResetPasswordComponent from './components/ResetPasswordComponent.vue'
import SnackBarComponent from './components/ui/SnackBarComponent.vue'
import GravatarComponent from './components/GravatarComponent.vue'
import UsersDashboardComponent from './components/users/UsersDashboardComponent.vue'
import UsersComponent from './components/users/UsersComponent.vue'
import RolesComponent from './components/users/roles/RolesComponent.vue'
import PermissionsComponent from './components/users/permissions/PermissionsComponent.vue'
import UserProfile from './components/users/UserProfile.vue'
import JobsListComponent from './components/jobs/JobsListComponent.vue'
import JobAddComponent from './components/jobs/JobAddComponent.vue'
import JobsListByFamilyComponent from './components/jobs/JobsListByFamilyComponent.vue'
import JobsListBySpecialtyComponent from './components/jobs/JobsListBySpecialtyComponent.vue'
import JobsSheetComponent from './components/jobs/JobsSheetComponent.vue'
import JobsSheetHoldersComponent from './components/jobs/JobsSheetHoldersComponent.vue'
import PendingTeacherForm from './components/teachers/PendingTeacherForm.vue'
import PendingTeacherAddWarningsComponent from './components/teachers/PendingTeacherAddWarningsComponent.vue'
import PendingTeachersComponent from './components/teachers/PendingTeachersComponent.vue'
import TeachersComponent from './components/teachers/TeachersComponent.vue'
import TeacherAddComponent from './components/teachers/TeacherAddComponent.vue'
import TeachersPhotosComponent from './components/teachers/TeachersPhotosComponent.vue'
import ShowTeacherIconComponent from './components/teachers/ShowTeacherIconComponent.vue'
import TeacherProfileComponent from './components/teachers/TeacherProfileComponent.vue'
import TeachersWelcome from './components/welcome/TeachersWelcome.vue'
import ImpersonateUserComponent from './components/admin/ImpersonateUserComponent.vue'
import AuditLogComponent from './components/auditlog/AuditLogComponent.vue'
import LogoutButtonComponent from './components/auth/LogoutButtonComponent.vue'
import DonutComponent from './components/charts/DonutComponent.vue'
import BarComponent from './components/charts/BarComponent.vue'
import LessonsManagerComponent from './components/lessons/LessonsManagerComponent.vue'
import ModelDocsComponent from './components/docs/ModelDocsComponent.vue'
import PeopleComponent from './components/people/PeopleComponent.vue'
import GoogleGroupsComponent from './components/google/groups/GoogleGroupsComponent.vue'
import GoogleGroupAddComponent from './components/google/groups/GoogleGroupAddComponent.vue'
import GoogleUsersComponent from './components/google/users/GoogleUsersComponent.vue'
import GoogleUserAddComponent from './components/google/users/GoogleUserAddComponent.vue'
import MoodleUsersComponent from './components/moodle/users/MoodleUsersComponent'
import LdapUsersComponent from './components/ldap/users/LdapUsersComponent.vue'
import LdapUserAddComponent from './components/ldap/users/LdapUserAddComponent.vue'
import IncidentsComponent from './components/incidents/IncidentsComponent.vue'
import IncidentsListComponent from './components/incidents/IncidentsListComponent.vue'
import IncidentAddComponent from './components/incidents/IncidentAddComponent.vue'
import ChangelogComponent from './components/changelog/ChangelogComponent.vue'
import FloatingAddComponent from './components/ui/FloatingAddComponent.vue'
import CurriculumComponent from './components/curriculum/CurriculumComponent.vue'
import CurriculumPublicComponent from './components/curriculum/CurriculumPublicComponent.vue'
import CurriculumStudyPublic from './components/curriculum/CurriculumStudyPublic.vue'
import SubjectsComponent from './components/curriculum/subjects/SubjectsComponent.vue'
import SubjectGroupsComponent from './components/curriculum/subjectGroups/SubjectGroupsComponent.vue'
import PositionsComponent from './components/positions/PositionsComponent.vue'
import DashboardPositions from './components/positions/DashboardPositions.vue'
import MaterialStatsCard from './components/ui/MaterialStatsCard.vue'
import MaterialCard from './components/ui/MaterialCard.vue'
import Offset from './components/helper/Offset.vue'
import Welcome from './components/welcome/Welcome.vue'
import Nagigation from './components/ui/Nagigation.vue'
import NotificationsWidget from './components/notifications/NotificationsWidget.vue'
import Notifications from './components/notifications/Notifications.vue'
import ShareFab from './components/ui/ShareFab.vue'
import ServiceWorker from './components/serviceworker/ServiceWorker.vue'
import ImgWebp from './components/ui/ImgWebp.vue'
import VParallaxWebp from './components/ui/VParallaxWebp.vue'
import GitInfoComponent from './components/git/GitInfoComponent'
import DateFnsLocalCa from 'date-fns/locale/ca'
window.Vue = Vue
window.Vue.use(snackbar)
window.Vue.use(permissions)
window.Vue.use(confirm)
window.Vue.use(TreeView)
window.Vue.use(VueTimeago, {
  locale: 'ca', // Default locale
  locales: {
    'ca': DateFnsLocalCa
  }
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.Vue.component('login-button', LoginButtonComponent)
window.Vue.component('register-button', RegisterButtonComponent)
window.Vue.component('remember-password', RememberPasswordComponent)
window.Vue.component('reset-password', ResetPasswordComponent)
window.Vue.component('snackbar', SnackBarComponent)
window.Vue.component('gravatar', GravatarComponent)
window.Vue.component('users-dashboard', UsersDashboardComponent)

window.Vue.component('users', UsersComponent)

window.Vue.component('roles', RolesComponent)
window.Vue.component('permissions', PermissionsComponent)

window.Vue.component('jobs-list', JobsListComponent)
window.Vue.component('job-add', JobAddComponent)
window.Vue.component('jobs-list-by-family', JobsListByFamilyComponent)
window.Vue.component('jobs-list-by-specialty', JobsListBySpecialtyComponent)
window.Vue.component('pending-teacher-form', PendingTeacherForm)
window.Vue.component('pending-teacher-add-warnings', PendingTeacherAddWarningsComponent)
window.Vue.component('pending-teachers', PendingTeachersComponent)

window.Vue.component('teachers', TeachersComponent)
window.Vue.component('teacher-add', TeacherAddComponent)

window.Vue.component('teachers-photos', TeachersPhotosComponent)

window.Vue.component('impersonate-user', ImpersonateUserComponent)

window.Vue.component('logout-button', LogoutButtonComponent)

window.Vue.component('teacher-profile', TeacherProfileComponent)

window.Vue.component('show-teacher-icon', ShowTeacherIconComponent)

window.Vue.component('audit-log', AuditLogComponent)

window.Vue.component('jobs-sheet', JobsSheetComponent)
window.Vue.component('jobs-sheet-holder', JobsSheetHoldersComponent)

window.Vue.component('donut', DonutComponent)
window.Vue.component('bar', BarComponent)

// Lessons
window.Vue.component('lessons-manager', LessonsManagerComponent)

// Docs/media
window.Vue.component('model-docs', ModelDocsComponent)

// People/Personal data (person)
window.Vue.component('people', PeopleComponent)

// Google groups
window.Vue.component('google-groups', GoogleGroupsComponent)
window.Vue.component('google-group-add', GoogleGroupAddComponent)

// Google users
window.Vue.component('google-users', GoogleUsersComponent)
window.Vue.component('google-user-add', GoogleUserAddComponent)

// Ldap users
window.Vue.component('ldap-users', LdapUsersComponent)
window.Vue.component('ldap-user-add', LdapUserAddComponent)

// Moodle users
window.Vue.component('moodle-users', MoodleUsersComponent)

// Incidents
window.Vue.component('incidents', IncidentsComponent)
window.Vue.component('incidents-list', IncidentsListComponent)
window.Vue.component('incident-add', IncidentAddComponent)

// Changelog
window.Vue.component('changelog', ChangelogComponent)

// UI
window.Vue.component('floating-add', FloatingAddComponent)

// GIT
window.Vue.component('git-info', GitInfoComponent)

// Curriculum
window.Vue.component('curriculum', CurriculumComponent)
window.Vue.component('curriculum-public', CurriculumPublicComponent)
window.Vue.component('curriculum-study-public', CurriculumStudyPublic)
window.Vue.component('subjects', SubjectsComponent)
window.Vue.component('subject-groups', SubjectGroupsComponent)

// Positions
window.Vue.component('positions', PositionsComponent)
window.Vue.component('dashboard-positions', DashboardPositions)

//
window.Vue.component('material-stats-card', MaterialStatsCard)
window.Vue.component('material-card', MaterialCard)
window.Vue.component('helper-offset', Offset)

window.Vue.component('welcome', Welcome)
window.Vue.component('teachers-welcome', TeachersWelcome)

// MAIN WIDGETS
window.Vue.component('navigation', Nagigation)
window.Vue.component('notifications-widget', NotificationsWidget)

// Notifications
window.Vue.component('notifications', Notifications)

// User Profile
window.Vue.component('user-profile', UserProfile)

// share-fab
window.Vue.component('share-fab', ShareFab)

// Service workers
window.Vue.component('service-worker', ServiceWorker)

// Images
window.Vue.component('img-webp', ImgWebp)
window.Vue.component('v-parallax-webp', VParallaxWebp)

window.Vuetify = Vuetify

// TODO
// import colors from './colors' export default {
//
// }

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
  },
  iconfont: ['fa', 'fa4', 'md']
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
