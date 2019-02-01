<template>
    <changelog-loggable :loggable="user" ></changelog-loggable>
</template>

<script>
import ChangelogLoggable from '../changelog/ChangelogLoggable'
export default {
  name: 'UserChangeLog',
  components: {
    'changelog-loggable': ChangelogLoggable
  },
  props: {
    user: {
      type: Object,
      required: true
    }
  }
}
</script>
