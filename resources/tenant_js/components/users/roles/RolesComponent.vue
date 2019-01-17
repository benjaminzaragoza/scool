<template>
    <span>
        <!--<role-add :roles="roles"></role-add>-->
        <roles-list :roles="roles"></roles-list>
    </span>
</template>

<script>
import RolesList from './RolesList'
export default {
  name: 'Roles',
  components: {
    'roles-list': RolesList
  },
  props: {
    roles: {
      type: Array,
      required: true
    }
  }
}
</script>
