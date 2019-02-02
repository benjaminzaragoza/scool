<template>
    <span>TODO notifications list: {{ notifications }}</span>
</template>

<script>
export default {
  name: 'UserNotificationsList',
  props: {
    notifications: {
      type: Array,
      required: true
    }
  }
}
</script>
