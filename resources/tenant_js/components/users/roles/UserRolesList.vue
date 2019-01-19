<template>
    <span>
        <v-progress-circular
                v-if="loading"
                indeterminate
                color="primary"
        ></v-progress-circular>
        <v-chip :small="small" close @input="remove(role)" v-for="role in dataRoles" :key="role.id" label outline color="grey grey--text text--darken-2">{{ role.name }} ({{ role.guard_name}})</v-chip>
    </span>
</template>

<script>
export default {
  name: 'UserRolesList',
  data () {
    return {
      close: [],
      dataRoles: this.roles,
      loading: false
    }
  },
  props: {
    user: {
      type: Object,
      required: true
    },
    roles: {
      type: Array,
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
      this.loading = true
      window.axios.delete('/api/v1/user/' + this.user.id + '/role/' + role.id).then(() => {
        this.$snackbar.showMessage('Rol eliminat correctament')
        this.dataRoles.splice(this.dataRoles.indexOf(role), 1)
        this.loading = false
      }).catch(error => {
        this.$snackbar.showError(error)
        this.loading = false
      })
    }
  }
}
</script>
