import * as types from '../../mutation-types'

export default {
  [ types.SET_PENDING_TEACHERS ] (state, pendingTeachers) {
    state.pendingTeachers = pendingTeachers
  },
  [ types.REMOVE_PENDING_TEACHER ] (state, teacher) {
    state.pendingTeachers.splice(state.pendingTeachers.indexOf(teacher), 1)
  },
  [ types.SET_TEACHERS ] (state, teachers) {
    state.teachers = teachers
  },
  [ types.DELETE_TEACHER ] (state, teacher) {
    state.teachers.splice(state.teachers.indexOf(teacher), 1)
  }
}
