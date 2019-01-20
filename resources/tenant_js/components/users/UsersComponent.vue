<template>
    <span>
        <user-add :roles="roles" :user-types="userTypes" :users="users" @created="refresh" @googleUsercreated="refresh" @moodleUserCreated="refresh"></user-add>

        <users-list :users="users" :user-types="userTypes" :roles="roles"></users-list>

        <!--TODO-->
        <!--<users-dashboard></users-dashboard>-->
    </span>
</template>
<script>
import * as actions from '../../store/action-types'
import UserAdd from './UserAddComponent'
import UsersList from './UsersListComponent'
export default {
  name: 'Users',
  components: {
    'user-add': UserAdd,
    'users-list': UsersList
  },
  props: {
    users: {
      type: Array,
      required: true
    },
    userTypes: {
      type: Array,
      required: false
    },
    roles: {
      type: Array,
      required: false
    }
  },
  methods: {
    refresh () {
      this.$store.dispatch(actions.FETCH_USERS)
    }
  }
}
</script>
