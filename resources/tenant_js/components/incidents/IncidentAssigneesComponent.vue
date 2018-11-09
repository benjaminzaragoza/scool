<template>
    <span>TODO ASSIGNEES</span>
</template>

<script>

export default {
  name: 'IncidentTags',
  data () {
    return {
      loading: false
    }
  },
  props: {
    incident: {
      type: Object,
      required: true
    }
  },
  methods: {
    async remove () {
      console.log('TODO')
    }
  }
}
</script>
