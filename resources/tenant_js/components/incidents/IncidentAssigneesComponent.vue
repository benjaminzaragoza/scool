<template>
    <span>TODO ASSIGNEES</span>
</template>

<script>

export default {
  name: 'IncidentAssignees',
  data () {
    return {
      loading: false
    }
  },
  props: {
    assignees: {
      type: Array,
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
