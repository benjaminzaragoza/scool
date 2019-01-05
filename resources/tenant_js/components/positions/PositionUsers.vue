<template>
    <user-avatar-list :existing-users="position.users" @added="add" @deleted="remove" role="PositionsManager"></user-avatar-list>
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
  },
  methods: {
    add (user) {
      this.adding = true
      window.axios.post('/api/v1/positions/' + this.position.id + '/users/' + user, {}).then(() => {
        this.$emit('refresh')
        this.adding = false
        this.userAddDialog = false
        this.newUser = null
      }).catch(error => {
        this.$snackbar.showError(error)
        this.adding = false
      })
    },
    remove (user) {
      console.log('REMOVE USER TODO')
      this.removing = true
      const url = '/api/v1/positions/' + this.position.id + '/users/' + user.id
      window.axios.delete(url).then(() => {
        this.$emit('refresh')
        this.removing = false
        this.userRemoveDialog = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.removing = false
      })
    },
  }
}
</script>
