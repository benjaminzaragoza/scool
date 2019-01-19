<template>
    <span>
        <v-chip :small="small" v-for="role in user.roles" :key="role.id" label outline color="grey grey--text text--darken-2">{{ role.name }} ({{ role.guard_name}})</v-chip>
    </span>
</template>

<script>
export default {
  name: 'UserRolesList',
  props: {
    user: {
      type: Object,
      required: true
    },
    small: {
      type: Boolean,
      default: true
    }
  }
}
</script>
