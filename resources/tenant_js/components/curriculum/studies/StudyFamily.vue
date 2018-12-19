<template>
    <span>
        TODO
    </span>
</template>

<script>
export default {
  name: 'StudyFamily',
  data () {
    return {
      dialog: false
    }
  },
  props: {
    families: {
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
