<template>
    <span>{{study.department_name}}</span>
</template>

<script>
export default {
  name: 'StudyDepartment',
  props: {
    departments: {
      type: Array,
      default: function () {
        return undefined
      }
    },
    study: {
      type: Object,
      required: true
    }
  }
}
</script>
