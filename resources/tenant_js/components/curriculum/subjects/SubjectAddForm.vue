<template>
    <form>
        <study-select v-model="study" :studies="studies"></study-select>
        <subject-group-select v-model="subjectGroup" :subject-groups="subjectGroups"></subject-group-select>
        <courses-select v-model="course" :courses="courses"></courses-select>
    </form>
</template>

<script>
import StudySelect from '../studies/StudySelect'
import SubjectGroupSelect from './SubjectGroupsSelect'
import CoursesSelect from '../courses/SubjectGroupsSelect'
export default {
  name: 'SubjectAddForm',
  components: {
    'study-select': StudySelect,
    'subject-group-select': SubjectGroupSelect,
    'courses-select': CoursesSelect
  },
  data () {
    return {
      study: null,
      subjectGroup: null,
      course: null
    }
  },
  props: {
    studies: {
      type: Array,
      required: true
    },
    subjectGroups: {
      type: Array,
      required: true
    },
    courses: {
      type: Array,
      required: true
    }
  }
}
</script>
