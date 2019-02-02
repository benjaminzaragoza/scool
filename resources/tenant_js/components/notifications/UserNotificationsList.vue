<template>
    <span>
        User notifications TODO
    </span>
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
