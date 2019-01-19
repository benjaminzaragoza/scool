<template>
    <span>
        <v-chip :small="small" close @input="remove(role)" v-for="role in roles" :key="role.id" label outline color="grey grey--text text--darken-2">{{ role.name }} ({{ role.guard_name}})</v-chip>
    </span>
</template>

<script>
export default {
  name: 'UserRolesList',
  data () {
    return {
      close: [],
      roles: this.user.roles
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    small: {
      type: Boolean,
      default: true
    }
  },
  methods: {
    async remove (role) {
      let res = await this.$confirm('Esteu segurs que voleu eliminar aquest rol?', { title: 'Esteu segurs?', buttonTrueText: 'Eliminar' })
      if (res) {
        this.removeRole(role)
      } else {
        role.close = true
      }
    },
    removeRole (role) {
      window.axios.delete('/api/v1/user/' + this.user.id + '/role/' + role.id).then(()=> {
        this.$snackbar.showMessage('Rol eliminat correctament')
        this.roles.splice(this.roles.indexOf(role), 1)
      }).catch(error => {
        this.$snackbar.showError(error)
      })
    }
  }
}
</script>
