<template>
    <user-avatar-list :users="position.users"></user-avatar-list>
</template>

<script>
import UserAvatarList from '../users/UserAvatarList'

export default {
  name: 'PositionUsers',
  components: {
    'user-avatar-list': UserAvatarList
  },
  props: {
    position: {
      type: Object,
      required: true
    }
  }
}
</script>
