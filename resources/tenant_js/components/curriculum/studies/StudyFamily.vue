<template>
    <span>{{study.family_name}}</span>
</template>

<script>
export default {
  name: 'StudyFamily',
  props: {
    studies: {
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
